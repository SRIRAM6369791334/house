<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class CashfreeService
{
    private function baseUrl(): string
    {
        return config('services.cashfree.env') === 'production'
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';
    }

    public function createOrder(array $payload): array
    {
        ini_set('serialize_precision', '-1');

        if (isset($payload['order_amount'])) {
            $payload['order_amount'] = (float) number_format((float) $payload['order_amount'], 2, '.', '');
        }

        $this->ensureConfigured();

        $response = $this->client()->post($this->baseUrl().'/orders', $payload);

        if (! $response->successful()) {
            if ($response->status() === 409 && $response->json('code') === 'order_already_exists') {
                throw new CashfreeOrderAlreadyExistsException('Cashfree order ID is already in use.');
            }

            Log::error('Cashfree create order API failed', [
                'status' => $response->status(),
                'body' => $response->json() ?: $response->body(),
                'payload' => [
                    'order_id' => $payload['order_id'] ?? null,
                    'order_amount' => $payload['order_amount'] ?? null,
                    'order_currency' => $payload['order_currency'] ?? null,
                    'customer_id' => $payload['customer_details']['customer_id'] ?? null,
                    'customer_phone_length' => strlen((string) ($payload['customer_details']['customer_phone'] ?? '')),
                    'return_url_host' => parse_url($payload['order_meta']['return_url'] ?? '', PHP_URL_HOST),
                ],
            ]);

            $message = $response->json('message') ?: $response->body();

            throw new RuntimeException('Cashfree order failed: '.$message);
        }

        $data = $response->json();

        if (empty($data['payment_session_id'])) {
            throw new RuntimeException('Cashfree did not return a payment session id.');
        }

        return $data;
    }

    public function getOrder(string $orderId): array
    {
        $this->ensureConfigured();

        $response = $this->client()->get($this->baseUrl().'/orders/'.rawurlencode($orderId));

        if (! $response->successful()) {
            throw new RuntimeException('Cashfree order check failed: '.$response->body());
        }

        return $response->json();
    }

    public function mode(): string
    {
        return config('services.cashfree.env') === 'production' ? 'production' : 'sandbox';
    }

    public function validWebhook(string $body, string $timestamp, string $signature): bool
    {
        $secret = trim((string) config('services.cashfree.secret_key'));
        if ($secret === '' || $timestamp === '' || $signature === '') {
            return false;
        }

        $expected = base64_encode(hash_hmac('sha256', $timestamp.$body, $secret, true));

        return hash_equals($expected, $signature);
    }

    private function client()
    {
        return Http::acceptJson()
            ->asJson()
            ->timeout(20)
            ->withHeaders([
                'x-client-id' => trim((string) config('services.cashfree.app_id')),
                'x-client-secret' => trim((string) config('services.cashfree.secret_key')),
                'x-api-version' => trim((string) config('services.cashfree.api_version')),
            ]);
    }

    private function ensureConfigured(): void
    {
        if (! trim((string) config('services.cashfree.app_id')) || ! trim((string) config('services.cashfree.secret_key'))) {
            throw new RuntimeException('Cashfree credentials are not configured.');
        }
    }
}