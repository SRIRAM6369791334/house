<?php

namespace Tests\Feature;

use App\Services\CheckoutOrderService;
use App\Services\OrderConfirmationMailService;
use App\Services\ShiprocketService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class PaymentConfirmationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'services.cashfree.app_id' => 'test-client',
            'services.cashfree.secret_key' => 'test-secret',
            'services.cashfree.currency' => 'INR',
            'services.cashfree.env' => 'sandbox',
        ]);
        DB::purge('sqlite');
        Http::preventStrayRequests();

        Schema::create('product_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->integer('user_id')->default(10);
            $table->string('payment_method')->default('card');
            $table->string('payment_status')->default('pending');
            $table->string('status')->default('Order Placed');
            $table->decimal('total_amount', 10, 2)->default(100);
            $table->decimal('subtotal', 10, 2)->default(100);
            $table->decimal('shipping_charge', 10, 2)->default(0);
            $table->decimal('coupon_discount', 10, 2)->default(0);
            $table->string('shiprocket_order_id')->nullable();
            $table->string('shiprocket_shipment_id')->nullable();
            $table->string('shiprocket_status')->nullable();
            $table->timestamp('stock_transferred_at')->nullable();
            $table->timestamps();
        });
        Schema::create('product_order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('product_id');
            $table->integer('product_variant_id')->nullable();
            $table->string('product_name');
            $table->integer('quantity');
        });
        Schema::create('productstocks', function (Blueprint $table) {
            $table->id();
            $table->integer('productid');
            $table->integer('pro_ver_id')->nullable();
            $table->integer('availablestock');
            $table->integer('salestock');
            $table->timestamps();
        });

        DB::table('product_orders')->insert(['id' => 1, 'order_number' => 'ORDER-1']);
        DB::table('product_order_items')->insert([
            'order_id' => 1, 'product_id' => 1, 'product_name' => 'Watch', 'quantity' => 2,
        ]);
        DB::table('productstocks')->insert([
            'productid' => 1, 'availablestock' => 5, 'salestock' => 0,
        ]);

        $orders = Mockery::mock(CheckoutOrderService::class)->makePartial();
        $orders->shouldReceive('shiprocketPayload')->andReturn(['order_id' => 'ORDER-1']);
        $this->app->instance(CheckoutOrderService::class, $orders);
        $this->mock(OrderConfirmationMailService::class)->shouldReceive('send')->andReturn(true);
    }

    private function gateway(string $status = 'PAID', array $overrides = []): void
    {
        Http::fake(['*/orders/ORDER-1' => Http::response(array_merge([
            'order_id' => 'ORDER-1', 'order_status' => $status,
            'order_amount' => 100, 'order_currency' => 'INR',
        ], $overrides))]);
    }

    private function shipment(): void
    {
        $this->mock(ShiprocketService::class)->shouldReceive('createOrder')->once()->andReturn([
            'order_id' => 'SHIP-1', 'shipment_id' => 'SHIPMENT-1', 'status' => 'created',
        ]);
    }

    private function verify()
    {
        return $this->withSession(['last_order' => ['number' => 'ORDER-1'], 'cart' => ['1' => 2]])
            ->postJson(route('checkout.cashfree.verify'), ['order_number' => 'ORDER-1']);
    }

    public function test_successful_payment_is_saved_and_stock_and_shipping_are_processed_once(): void
    {
        $this->gateway();
        $this->shipment();
        $this->verify()->assertOk()->assertJsonPath('success', true)
            ->assertSessionMissing('cart')->assertSessionHas('last_order.payment_status', 'paid');
        $this->verify()->assertOk();
        $this->assertDatabaseHas('product_orders', ['payment_status' => 'paid', 'status' => 'Order Placed']);
        $this->assertDatabaseHas('productstocks', ['availablestock' => 3, 'salestock' => 2]);
        Http::assertSentCount(1);
    }

    public function test_pending_payment_does_not_clear_cart_or_deduct_stock(): void
    {
        $this->gateway('ACTIVE');
        $this->mock(ShiprocketService::class)->shouldNotReceive('createOrder');
        $this->verify()->assertStatus(202)->assertJsonPath('pending', true)->assertSessionHas('cart');
        $this->assertDatabaseHas('product_orders', ['payment_status' => 'pending']);
        $this->assertDatabaseHas('productstocks', ['availablestock' => 5]);
    }

    public function test_stock_shortage_does_not_roll_back_payment_and_can_be_retried(): void
    {
        $this->gateway();
        $this->shipment();
        DB::table('productstocks')->update(['availablestock' => 1]);
        $this->verify()->assertOk()->assertJsonPath('success', true);
        $this->assertDatabaseHas('product_orders', ['payment_status' => 'paid', 'stock_transferred_at' => null]);
        $this->assertDatabaseHas('productstocks', ['availablestock' => 1, 'salestock' => 0]);
        DB::table('productstocks')->update(['availablestock' => 5]);
        $this->verify()->assertOk();
        $this->assertDatabaseHas('productstocks', ['availablestock' => 3, 'salestock' => 2]);
    }

    public function test_shipping_failure_preserves_payment_and_does_not_repeat_stock_deduction(): void
    {
        $this->gateway();
        $this->mock(ShiprocketService::class)->shouldReceive('createOrder')->twice()
            ->andThrow(new \RuntimeException('Shipping unavailable'));
        $this->verify()->assertOk();
        $this->verify()->assertOk();
        $this->assertDatabaseHas('product_orders', ['payment_status' => 'paid']);
        $this->assertDatabaseHas('productstocks', ['availablestock' => 3, 'salestock' => 2]);
    }

    public function test_gateway_amount_currency_and_order_must_match(): void
    {
        $this->mock(ShiprocketService::class)->shouldNotReceive('createOrder');
        foreach ([['order_amount' => 1], ['order_currency' => 'USD'], ['order_id' => 'ANOTHER']] as $overrides) {
            $this->gateway('PAID', $overrides);
            $this->verify()->assertStatus(503)->assertSessionHas('cart');
            $this->assertDatabaseHas('product_orders', ['payment_status' => 'pending']);
        }
    }

    public function test_api_failure_preserves_pending_order_and_cart(): void
    {
        Http::fake(['*' => Http::response([], 500)]);
        $this->mock(ShiprocketService::class)->shouldNotReceive('createOrder');
        $this->verify()->assertStatus(503)->assertSessionHas('cart');
        $this->assertDatabaseHas('product_orders', ['payment_status' => 'pending']);
    }

    public function test_other_visitors_cannot_verify_an_order(): void
    {
        $this->postJson(route('checkout.cashfree.verify'), ['order_number' => 'ORDER-1'])->assertNotFound();
        Http::assertNothingSent();
    }

    private function webhook(string $signature = '', string $status = 'SUCCESS')
    {
        $body = json_encode([
            'type' => 'PAYMENT_SUCCESS_WEBHOOK',
            'data' => ['order' => ['order_id' => 'ORDER-1'], 'payment' => ['payment_status' => $status]],
        ]);
        $timestamp = '1750000000000';
        $signature = $signature ?: base64_encode(hash_hmac('sha256', $timestamp.$body, 'test-secret', true));

        return $this->call('POST', route('checkout.cashfree.webhook'), [], [], [], [
            'CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json',
            'HTTP_X_WEBHOOK_TIMESTAMP' => $timestamp, 'HTTP_X_WEBHOOK_SIGNATURE' => $signature,
        ], $body);
    }

    public function test_signed_webhook_confirms_without_browser_and_duplicates_are_safe(): void
    {
        $this->gateway();
        $this->shipment();
        $this->webhook()->assertOk();
        $this->webhook()->assertOk();
        $this->verify()->assertOk();
        $this->assertDatabaseHas('product_orders', ['payment_status' => 'paid']);
        $this->assertDatabaseHas('productstocks', ['availablestock' => 3, 'salestock' => 2]);
        Http::assertSentCount(1);
    }

    public function test_forged_and_non_success_webhooks_cannot_mark_paid(): void
    {
        $this->webhook('invalid')->assertUnauthorized();
        $this->webhook('', 'FAILED')->assertOk();
        $this->assertDatabaseHas('product_orders', ['payment_status' => 'pending']);
        Http::assertNothingSent();
    }

    public function test_webhook_requests_retry_when_gateway_is_still_pending(): void
    {
        $this->gateway('ACTIVE');
        $this->webhook()->assertStatus(503);
        $this->assertDatabaseHas('product_orders', ['payment_status' => 'pending']);
    }

    public function test_return_without_session_still_records_payment(): void
    {
        $this->gateway();
        $this->shipment();
        $this->get(route('checkout.cashfree.return', ['order_id' => 'ORDER-1']))->assertRedirect('login');
        $this->assertDatabaseHas('product_orders', ['payment_status' => 'paid']);
    }

    public function test_reconciliation_recovers_a_missed_callback(): void
    {
        $this->gateway();
        $this->shipment();
        $this->artisan('payments:reconcile', ['order_number' => 'ORDER-1'])
            ->expectsOutput('ORDER-1: paid')->assertSuccessful();
        $this->assertDatabaseHas('product_orders', ['payment_status' => 'paid']);
    }

    public function test_cod_is_never_confirmed_as_an_online_payment(): void
    {
        DB::table('product_orders')->update(['payment_method' => 'cod', 'payment_status' => 'unpaid']);
        $this->verify()->assertStatus(503);
        Http::assertNothingSent();
        $this->assertDatabaseHas('product_orders', ['payment_status' => 'unpaid']);
    }

    public function test_stock_failure_rolls_back_all_items_but_preserves_paid_status(): void
    {
        $this->gateway();
        $this->mock(ShiprocketService::class)->shouldNotReceive('createOrder');
        DB::table('product_order_items')->insert([
            'order_id' => 1, 'product_id' => 2, 'product_name' => 'Sold out item', 'quantity' => 1,
        ]);
        $this->verify()->assertOk();
        $this->assertDatabaseHas('product_orders', ['payment_status' => 'paid', 'stock_transferred_at' => null]);
        $this->assertDatabaseHas('productstocks', ['productid' => 1, 'availablestock' => 5, 'salestock' => 0]);
    }

    public function test_stock_marker_migration_preserves_legacy_allocations(): void
    {
        Schema::table('product_orders', fn (Blueprint $table) => $table->dropColumn('stock_transferred_at'));
        DB::table('product_orders')->insert([
            ['order_number' => 'OLD-PAID', 'payment_status' => 'paid', 'payment_method' => 'card'],
            ['order_number' => 'OLD-COD', 'payment_status' => 'unpaid', 'payment_method' => 'cod'],
        ]);
        $migration = require database_path('migrations/2026_09_18_000001_add_stock_transferred_at_to_product_orders.php');
        $migration->up();
        $this->assertNull(DB::table('product_orders')->where('order_number', 'ORDER-1')->value('stock_transferred_at'));
        $this->assertNotNull(DB::table('product_orders')->where('order_number', 'OLD-PAID')->value('stock_transferred_at'));
        $this->assertNotNull(DB::table('product_orders')->where('order_number', 'OLD-COD')->value('stock_transferred_at'));
    }

    public function test_cashfree_payload_includes_return_order_id_and_webhook_url(): void
    {
        config(['services.cashfree.return_url' => 'https://example.com/checkout/cashfree/return']);
        $order = (object) [
            'order_number' => 'ORDER-1', 'total_amount' => 100, 'user_id' => 10,
            'billing_name' => 'Test Customer', 'billing_email' => 'test@example.com', 'billing_phone' => '9999999999',
        ];
        $payload = app(CheckoutOrderService::class)->cashfreePayload($order);
        $this->assertSame('https://example.com/checkout/cashfree/return?order_id={order_id}', $payload['order_meta']['return_url']);
        $this->assertSame(route('checkout.cashfree.webhook'), $payload['order_meta']['notify_url']);
    }
}
