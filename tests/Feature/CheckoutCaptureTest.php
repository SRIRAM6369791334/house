<?php

namespace Tests\Feature;

use App\Models\CheckoutAttempt;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\ProductStock;
use App\Models\User;
use App\Services\OrderConfirmationMailService;
use App\Services\ShiprocketService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutCaptureTest extends TestCase
{
    private string $gatewayStatus = 'ACTIVE';

    private int $gatewayAmount = 200;

    private bool $gatewayStartFails = false;

    private User $customer;

    private array $checkoutData;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'cache.default' => 'array',
            'services.cashfree.app_id' => 'test-client',
            'services.cashfree.secret_key' => 'test-secret',
            'services.cashfree.currency' => 'INR',
            'services.cashfree.env' => 'sandbox',
        ]);
        DB::purge('sqlite');
        DB::unprepared(file_get_contents(base_path('tests/Fixtures/storefront.sql')));
        $migration = require database_path('migrations/2026_09_18_000002_create_checkout_attempts_table.php');
        $migration->up();
        Mail::spy();
        Http::preventStrayRequests();
        Http::fake(function ($request) {
            if ($request->method() === 'POST' && str_ends_with($request->url(), '/orders')) {
                if ($this->gatewayStartFails) {
                    return Http::response([], 500);
                }

                return Http::response([
                    'order_id' => $request['order_id'], 'cf_order_id' => '123', 'payment_session_id' => 'session-123',
                ]);
            }

            return Http::response([
                'order_id' => basename($request->url()), 'order_amount' => $this->gatewayAmount,
                'order_currency' => 'INR', 'order_status' => $this->gatewayStatus,
            ]);
        });
        $shipping = $this->mock(ShiprocketService::class);
        $shipping->shouldReceive('shippingQuote')->andReturn(['serviceable' => true, 'charge' => 0]);
        $shipping->shouldReceive('createOrder')->andReturn(['order_id' => 100, 'shipment_id' => 101]);

        $this->customer = User::query()->create([
            'name' => 'Buyer', 'email' => 'buyer@example.com', 'phone' => '9999999999',
            'password' => 'Password123!', 'billing_city' => 'Original City',
        ]);
        $product = Product::query()->create(['product_name' => 'Watch', 'product_regular_price' => 100]);
        $variant = $product->variants()->create(['offer_price' => 100, 'mrp_price' => 150, 'product_qty' => 5, 'product_gst' => 18]);
        ProductStock::query()->create(['productid' => $product->id, 'pro_ver_id' => $variant->id, 'availablestock' => 5, 'salestock' => 0]);
        $this->withSession(['cart' => [$product->id.'_'.$variant->id => ['quantity' => 2, 'variant_id' => $variant->id]]]);
        $this->checkoutData = [
            'billing_first_name' => 'Buyer', 'billing_email' => 'buyer@example.com', 'billing_phone' => '9999999999',
            'billing_address' => '1 Main Road', 'billing_city' => 'Chennai', 'billing_state' => 'Tamil Nadu',
            'billing_postcode' => '600001', 'same_as_billing' => 1, 'payment_method' => 'card',
        ];
    }

    private function start(): string
    {
        return $this->actingAs($this->customer)->postJson(route('checkout.place'), $this->checkoutData)
            ->assertOk()->assertSessionMissing('last_order')->json('order_number');
    }

    private function assertNoOrder(): void
    {
        foreach (['product_orders', 'product_order_items', 'product_order_user_addresses', 'product_slots'] as $table) {
            $this->assertDatabaseCount($table, 0);
        }
        $this->assertSame('Original City', $this->customer->fresh()->billing_city);
        $this->assertSame(5, ProductStock::query()->first()->availablestock);
        Mail::shouldNotHaveReceived('send');
    }

    public function test_clicking_pay_saves_only_an_encrypted_attempt_and_no_order_or_email(): void
    {
        $number = $this->start();
        $this->assertMatchesRegularExpression('/^KNP-ORD-\d{3}$/', $number);
        $attempt = CheckoutAttempt::query()->where('order_number', $number)->firstOrFail();
        $this->assertSame($this->customer->id, $attempt->user_id);
        $this->assertStringNotContainsString('buyer@example.com', $attempt->getRawOriginal('payload'));
        $this->assertSame('Chennai', $attempt->payload['data']['billing_city']);
        $this->assertNoOrder();
    }

    public function test_pending_or_failed_payment_does_not_create_order_or_send_email(): void
    {
        $number = $this->start();
        foreach (['ACTIVE', 'EXPIRED'] as $status) {
            $this->gatewayStatus = $status;
            $this->postJson(route('checkout.cashfree.verify'), ['order_number' => $number])
                ->assertStatus(202)->assertSessionHas('cart');
            $this->assertNoOrder();
        }
    }

    public function test_paid_verification_creates_one_order_then_sends_one_email(): void
    {
        $number = $this->start();
        $this->gatewayStatus = 'PAID';
        $this->postJson(route('checkout.cashfree.verify'), ['order_number' => $number])
            ->assertOk()->assertJsonPath('success', true)->assertSessionHas('last_order.payment_status', 'paid')
            ->assertSessionMissing('cart')->assertSessionMissing('pending_payment_order');
        $this->postJson(route('checkout.cashfree.verify'), ['order_number' => $number])->assertOk();
        $this->assertDatabaseCount('product_orders', 1);
        $this->assertDatabaseCount('product_order_items', 1);
        $this->assertDatabaseCount('product_order_user_addresses', 1);
        $this->assertDatabaseCount('product_slots', 1);
        $this->assertDatabaseHas('product_orders', ['order_number' => $number, 'payment_status' => 'paid']);
        $this->assertSame('Chennai', $this->customer->fresh()->billing_city);
        $this->assertSame(3, ProductStock::query()->first()->availablestock);
        Mail::shouldHaveReceived('send')->once();
        $this->assertNotNull(CheckoutAttempt::query()->first()->completed_order_id);
    }

    public function test_webhook_creates_order_without_browser_session_using_original_customer(): void
    {
        $number = $this->start();
        auth()->logout();
        session()->flush();
        $this->gatewayStatus = 'PAID';
        // Product changes after checkout must not change the amount or item snapshot already paid for.
        Product::query()->update(['product_name' => 'Renamed Watch', 'product_regular_price' => 300]);
        $body = json_encode(['type' => 'PAYMENT_SUCCESS_WEBHOOK', 'data' => [
            'order' => ['order_id' => $number], 'payment' => ['payment_status' => 'SUCCESS'],
        ]]);
        $timestamp = (string) now()->getTimestampMs();
        $headers = [
            'CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json',
            'HTTP_X_WEBHOOK_TIMESTAMP' => $timestamp,
            'HTTP_X_WEBHOOK_SIGNATURE' => base64_encode(hash_hmac('sha256', $timestamp.$body, 'test-secret', true)),
        ];
        $this->call('POST', route('checkout.cashfree.webhook'), [], [], [], $headers, $body)->assertOk();
        $this->call('POST', route('checkout.cashfree.webhook'), [], [], [], $headers, $body)->assertOk();
        $this->assertDatabaseCount('product_orders', 1);
        $this->assertDatabaseHas('product_orders', ['user_id' => $this->customer->id, 'payment_status' => 'paid', 'total_amount' => 200]);
        $this->assertDatabaseHas('product_order_items', ['product_name' => 'Watch', 'price' => 100]);
        Mail::shouldHaveReceived('send')->once();
    }

    public function test_mismatched_paid_amount_does_not_create_an_order(): void
    {
        $number = $this->start();
        $this->gatewayStatus = 'PAID';
        $this->gatewayAmount = 1;
        $this->postJson(route('checkout.cashfree.verify'), ['order_number' => $number])->assertStatus(503);
        $this->assertNoOrder();
    }

    public function test_gateway_start_failure_does_not_create_an_order(): void
    {
        $this->gatewayStartFails = true;
        $this->actingAs($this->customer)->postJson(route('checkout.place'), $this->checkoutData)->assertStatus(422);
        $this->assertNoOrder();
    }

    public function test_confirmation_email_service_refuses_unpaid_online_orders(): void
    {
        $order = new ProductOrder(['payment_method' => 'card', 'payment_status' => 'pending', 'billing_email' => 'buyer@example.com']);
        $this->assertFalse(app(OrderConfirmationMailService::class)->send($order));
        Mail::shouldNotHaveReceived('send');
    }

    public function test_reconciliation_recovers_a_paid_attempt_with_no_existing_order(): void
    {
        $number = $this->start();
        $this->gatewayStatus = 'PAID';
        $this->artisan('payments:reconcile', ['order_number' => $number])->expectsOutput($number.': paid')->assertSuccessful();
        $this->assertDatabaseHas('product_orders', ['order_number' => $number, 'payment_status' => 'paid']);
    }
}
