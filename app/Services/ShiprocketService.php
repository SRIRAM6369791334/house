<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ShiprocketService
{
    private function baseUrl(): string
    {
        return rtrim(config('services.shiprocket.base_url'), '/');
    }

    public function createOrder(array $payload): array
    {
        $this->ensureConfigured();

        $response = $this->client()->post($this->baseUrl() . '/orders/create/adhoc', $payload);

        if (! $response->successful()) {
            throw new RuntimeException('Shiprocket order failed: ' . $response->body());
        }

        return $response->json();
    }

    public function isPincodeServiceable(string $deliveryPincode, float $weight = 0.5, bool $cod = false): bool
    {
        return $this->shippingQuote($deliveryPincode, $weight, $cod)['serviceable'];
    }

    public function shippingQuote(string $deliveryPincode, float $weight = 0.5, bool $cod = false, float $declaredValue = 0): array
    {
        $this->ensureConfigured();

        $pickupPincode = trim((string) config('services.shiprocket.pickup_pincode'));
        $deliveryPincode = preg_replace('/\D+/', '', $deliveryPincode);

        if ($pickupPincode === '') {
            throw new RuntimeException('Shiprocket pickup pincode is not configured.');
        }

        if (! preg_match('/^\d{6}$/', $deliveryPincode)) {
            throw new RuntimeException('Delivery pincode must be 6 digits.');
        }

        $response = $this->client()->get($this->baseUrl() . '/courier/serviceability', [
            'pickup_postcode' => $pickupPincode,
            'delivery_postcode' => $deliveryPincode,
            'weight' => max(0.1, $weight),
            'cod' => $cod ? 1 : 0,
            'declared_value' => max(1, round($declaredValue, 2)),
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Shiprocket pincode check failed: ' . $response->body());
        }

        $couriers = collect($response->json('data.available_courier_companies', []))
            ->filter(fn ($courier) => is_array($courier))
            ->sortBy(fn ($courier) => $this->courierCharge($courier))
            ->values();

        if ($couriers->isEmpty()) {
            return [
                'serviceable' => false,
                'charge' => 0.0,
                'courier' => null,
                'etd' => null,
            ];
        }

        $courier = $couriers->first();

        return [
            'serviceable' => true,
            'charge' => round($this->courierCharge($courier), 2),
            'courier' => $courier['courier_name'] ?? $courier['name'] ?? null,
            'etd' => $courier['etd'] ?? $courier['estimated_delivery_days'] ?? null,
        ];
    }

    private function courierCharge(array $courier): float
    {
        foreach (['rate', 'freight_charge', 'shipping_amount', 'total_charge'] as $key) {
            if (isset($courier[$key]) && is_numeric($courier[$key])) {
                return (float) $courier[$key];
            }
        }

        $freight = isset($courier['freight_charge']) && is_numeric($courier['freight_charge'])
            ? (float) $courier['freight_charge']
            : 0.0;
        $cod = isset($courier['cod_charges']) && is_numeric($courier['cod_charges'])
            ? (float) $courier['cod_charges']
            : 0.0;

        return $freight + $cod;
    }

    private function client()
    {
        return Http::withToken($this->token())->acceptJson()->asJson()->timeout(20);
    }

    private function token(): string
    {
        return Cache::remember('shiprocket_token_' . md5((string) config('services.shiprocket.email')), 8 * 60 * 60, function () {
            $response = Http::acceptJson()->timeout(20)->post($this->baseUrl() . '/auth/login', [
                'email' => trim((string) config('services.shiprocket.email')),
                'password' => trim((string) config('services.shiprocket.password')),
            ]);

            if (! $response->successful() || ! $response->json('token')) {
                throw new RuntimeException('Shiprocket login failed: ' . $response->body());
            }

            return $response->json('token');
        });
    }

    private function ensureConfigured(): void
    {
        if (! trim((string) config('services.shiprocket.email')) || ! trim((string) config('services.shiprocket.password'))) {
            throw new RuntimeException('Shiprocket credentials are not configured.');
        }

        if (! trim((string) config('services.shiprocket.pickup_location'))) {
            throw new RuntimeException('Shiprocket pickup location is not configured.');
        }
    }
}
