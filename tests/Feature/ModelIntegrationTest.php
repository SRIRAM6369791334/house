<?php

namespace Tests\Feature;

use App\Http\Controllers\AccountController;
use App\Models\Category;
use App\Models\GiftCombo;
use App\Models\PasswordResetToken;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\ProductStock;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use App\Models\UserAddress;
use App\Services\CheckoutOrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ModelIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'cache.default' => 'array',
            'mail.default' => 'array',
        ]);
        DB::purge('sqlite');
        DB::unprepared(file_get_contents(base_path('tests/Fixtures/storefront.sql')));
        $migration = require database_path('migrations/2026_09_18_000002_create_checkout_attempts_table.php');
        $migration->up();
        Http::preventStrayRequests();
    }

    private function product(): Product
    {
        $category = Category::query()->create(['category_name' => 'Watches']);
        $product = Product::query()->create([
            'category_id' => $category->id, 'product_name' => 'Watch', 'slug' => 'test-watch',
            'product_regular_price' => 150, 'product_mrp_price' => 200,
        ]);
        $product->variants()->create([
            'offer_price' => 100, 'mrp_price' => 200, 'product_qty' => 5,
            'product_gst' => 18, 'value' => 'Standard',
        ]);

        return $product;
    }

    private function customer(): User
    {
        return User::query()->create([
            'name' => 'Test Customer', 'email' => 'customer@example.com',
            'phone' => '9999999999', 'password' => 'Password123!',
        ]);
    }

    public function test_storefront_scope_returns_models_and_aggregates_variants(): void
    {
        $product = $this->product();
        $product->variants()->create(['offer_price' => 120, 'mrp_price' => 220, 'product_qty' => 3, 'product_gst' => 18]);
        Product::query()->create(['product_name' => 'Deleted', 'deleted_at' => now()]);
        $rows = Product::query()->forStorefront()->get();

        $this->assertCount(1, $rows);
        $this->assertInstanceOf(Product::class, $rows->first());
        $this->assertEquals(100, $rows->first()->offer_price);
        $this->assertEquals(8, $rows->first()->stock_qty);
        $this->assertCount(2, $rows->first()->variants);
        $this->assertSame('Watches', $rows->first()->category->category_name);
    }

    public function test_cart_helper_keeps_variant_price_quantity_and_model_identity(): void
    {
        $product = $this->product();
        $variant = $product->variants()->first();
        $this->withSession(['cart' => [$product->id.'_'.$variant->id => ['quantity' => 2, 'variant_id' => $variant->id]]]);
        $cart = house_products_from_session('cart');

        $this->assertInstanceOf(Product::class, $cart->first());
        $this->assertSame(2, $cart->first()->cart_qty);
        $this->assertSame($variant->id, $cart->first()->cart_variant_id);
        $this->assertEquals(100, house_product_price($cart->first()));
    }

    public function test_account_eager_loads_order_items_and_addresses_by_order_number(): void
    {
        $user = $this->customer();
        $product = $this->product();
        $order = $user->orders()->create(['order_number' => 'MODEL-ORDER', 'payment_status' => 'paid']);
        $order->items()->create(['product_id' => $product->id, 'product_name' => 'Watch', 'quantity' => 1]);
        $order->shippingAddresses()->create([
            'user_id' => $user->id, 'firstname' => 'Test', 'address_line_one' => '1 Main Road',
            'address_type_name' => 'Shipping',
        ]);
        $this->actingAs($user);
        $view = app(AccountController::class)->index();
        $loaded = $view->getData()['orders']->first();

        $this->assertTrue($loaded->relationLoaded('items'));
        $this->assertTrue($loaded->items->first()->relationLoaded('product'));
        $this->assertSame('test-watch', $loaded->items->first()->slug);
        $this->assertSame('MODEL-ORDER', $loaded->shipping_address_record->order_id);
        $this->assertSame($order->id, $loaded->shipping_address_record->order->id);
        $this->assertTrue($user->addresses()->first()->is_default);
    }

    public function test_address_actions_preserve_ownership_and_default_selection(): void
    {
        $user = $this->customer();
        $this->actingAs($user)->post(route('account.address.store'), [
            'address_first_name' => 'Test', 'address_phone_number' => '9999999999',
            'address_line_one' => '1 Main Road', 'city' => 'Chennai', 'state' => 'Tamil Nadu',
            'pincode' => '600001', 'address_type_name' => 'Shipping',
        ])->assertRedirect('account#address');
        $first = $user->addresses()->first();
        $this->assertTrue($first->is_default);
        $second = $user->addresses()->create(['address_type_name' => 'Shipping', 'is_default' => false]);
        $foreign = UserAddress::query()->create(['user_id' => $user->id + 1, 'is_default' => true]);

        $this->post(route('account.address.default', $second->id))->assertRedirect();
        $this->assertFalse($first->fresh()->is_default);
        $this->assertTrue($second->fresh()->is_default);
        $this->post(route('account.address.delete', $foreign->id))->assertNotFound();
        $this->post(route('account.address.delete', $second->id))->assertRedirect();
        $this->assertTrue($first->fresh()->is_default);
    }

    private function checkout(string $method): ProductOrder
    {
        $product = $this->product();
        $variant = $product->variants()->first();
        ProductStock::query()->create([
            'productid' => $product->id, 'pro_ver_id' => $variant->id, 'availablestock' => 5, 'salestock' => 0,
        ]);
        $product->offer_price = 100;
        $product->product_gst = 18;
        $product->cart_qty = 2;
        $product->cart_variant_id = $variant->id;

        return app(CheckoutOrderService::class)->create([
            'billing_first_name' => 'Guest', 'billing_email' => 'guest@example.com',
            'billing_phone' => '9888888888', 'billing_address' => '1 Main Road',
            'billing_city' => 'Chennai', 'billing_state' => 'Tamil Nadu', 'billing_postcode' => '600001',
            'same_as_billing' => true, 'payment_method' => $method,
        ], collect([$product]), null, 200, 0, 0, 200);
    }

    public function test_online_order_cannot_be_created_without_verified_payment_attempt(): void
    {
        $this->expectException(\LogicException::class);
        $this->checkout('card');
    }

    public function test_cod_checkout_uses_model_writes_and_records_stock_allocation(): void
    {
        $order = $this->checkout('cod');
        $this->assertSame('KNP-ORD-001', $order->order_number);
        $this->assertSame('unpaid', $order->payment_status);
        $this->assertNotNull($order->stock_transferred_at);
        $this->assertSame(3, ProductStock::query()->first()->availablestock);
        $this->assertSame(3, ProductVariant::query()->first()->product_qty);
    }

    public function test_delivered_product_review_is_created_through_its_model(): void
    {
        $user = $this->customer();
        $product = $this->product();
        $order = $user->orders()->create(['order_number' => 'DELIVERED-1', 'status' => 'delivered']);
        $order->items()->create(['product_id' => $product->id, 'quantity' => 1]);
        $this->actingAs($user)->post(route('product.review.store'), [
            'product_id' => $product->id, 'ratings' => 5, 'review' => 'Good watch.',
        ])->assertRedirect()->assertSessionHas('review_success');
        $review = $product->reviews()->first();
        $this->assertInstanceOf(Review::class, $review);
        $this->assertSame(5, $review->ratings);
        $this->assertEquals($user->id, $review->user_id);
        $this->assertSame(0, $review->status);
    }

    public function test_reset_token_model_supports_string_primary_key_and_otp_verification(): void
    {
        $user = $this->customer();
        PasswordResetToken::query()->updateOrCreate(['email' => $user->email], [
            'token' => Hash::make('123456'), 'created_at' => now(),
        ]);
        $this->withSession(['password_reset_email' => $user->email])
            ->post(route('password.otp.verify'), ['otp' => '123456'])->assertRedirect(route('password.reset'));
        $this->assertNull(PasswordResetToken::query()->find($user->email));
    }

    public function test_storefront_views_accept_eloquent_models(): void
    {
        $this->withoutExceptionHandling();
        $this->product();
        $combo = GiftCombo::query()->create(['combo_name' => 'Test Box', 'offer_price' => 100, 'mrp_price' => 150]);
        foreach (['/', '/shop', '/single-product?slug=test-watch', '/collections', '/combos', '/combos/'.$combo->id,
            '/blog', '/checkout', '/cart', '/wishlist', '/about', '/contact', '/bulk-order'] as $url) {
            $this->get($url)->assertOk();
        }
    }
}
