<?php

namespace Tests\Feature;

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RouteStructureTest extends TestCase
{
    public function test_storefront_routes_resolve_to_callable_controller_actions(): void
    {
        foreach (Route::getRoutes() as $route) {
            $action = $route->getActionName();
            if (! str_starts_with($action, 'App\\Http\\Controllers\\')) {
                continue;
            }

            [$controller, $method] = explode('@', $action);
            $this->assertTrue(is_callable([app($controller), $method]), $action);
            $route->prepareForSerialization();
        }

        $this->assertSame(CheckoutController::class.'@store', Route::getRoutes()->getByName('checkout.place')->getActionName());
        $this->assertSame(PaymentController::class.'@verify', Route::getRoutes()->getByName('checkout.cashfree.verify')->getActionName());
        $this->assertTrue(function_exists('house_product_price'));
        $this->assertSame(100, house_product_price((object) ['offer_price' => 100]));
    }

    public function test_cart_controller_retains_request_injection_after_namespace_change(): void
    {
        $route = Route::getRoutes()->match(Request::create('/cart/add/1'));
        $this->assertSame(CartController::class.'@add', $route->getActionName());
        $method = new \ReflectionMethod(CartController::class, 'add');
        $this->assertSame(Request::class, (string) $method->getParameters()[0]->getType());
    }

    public function test_account_and_review_routes_retain_authentication(): void
    {
        $this->assertContains('auth', Route::getRoutes()->match(Request::create('/account'))->gatherMiddleware());
        $this->assertContains('auth', Route::getRoutes()->getByName('product.review.store')->gatherMiddleware());
        $this->assertContains('auth', Route::getRoutes()->getByName('account.address.store')->gatherMiddleware());
    }
}
