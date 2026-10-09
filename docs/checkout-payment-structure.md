# Checkout and payment changes

`routes/web.php` now declares routes only. Controllers handle checkout, payments,
coupons, cart, wishlist, catalog, products, combos, account, addresses, media,
contact, bulk orders, blog, authentication pages and static pages. Existing URLs,
route names and middleware are preserved. Shared Blade-compatible `house_*`
helpers live in `app/Support/storefront.php` and load from `AppServiceProvider`,
including when routes are cached or commands run.

## Model layer

All controller data access now uses Eloquent models, with explicit mappings for
the existing table names (`ProductVariant` maps to `product_varient`, `ProductOrder`
to `product_orders`, `OrderAddress` to `product_order_user_addresses`, and so on).
The checkout/payment services, recovery command and shared Blade queries use the
same models. There are 28 new models plus the existing `User` model.

Models declare fillable fields, applicable casts and relationships. Product and
variant query scopes provide the storefront's price/stock aggregates. Account
orders eager-load items, products and shipping addresses; shipping addresses link
through `order_number`, while order items link through the numeric order ID.
Transactions use their model's connection.

There are no runtime `Schema` checks or table alterations in the application.
Controllers expect the existing database tables and columns to be present. The
old request-time addition of `reviews.combo_id` has been removed; that column was
confirmed present in the database definitions. Schema management belongs in
deployment migrations, including the payment stock-marker migration below.

`ModelIntegrationTest` uses a SQLite fixture containing table definitions only.
It covers model relationships, storefront aggregates, addresses, guest/COD
checkout, reviews, OTP tokens and rendering storefront pages with Eloquent data.

`PaymentConfirmationService` is shared by the browser return, modal verification,
signed webhook and reconciliation command. It checks Cashfree's order ID, amount,
currency and PAID status before recording payment. Payment is committed before
stock and shipping; fulfillment errors are logged as `Paid order fulfillment
requires attention`. Stock allocation has a persistent marker and shipment
creation checks existing IDs while holding an order lock. Duplicate confirmations
do not repeat successful stock or shipment work. A gateway timeout during shipment
creation can still require checking Shiprocket before retrying, because a remote
shipment may exist even if its response was lost.

The account page now displays payment status separately from delivery status.
An obsolete block referencing undefined `loginToggle` and `loginForm` variables
was removed; it previously stopped JavaScript before the Pay handler was attached.
The browser retries delayed confirmation and offers a confirmation retry instead
of starting another payment after an unresolved verification.

## Deployment

For online checkout, clicking Pay now stores only an encrypted snapshot in
`checkout_attempts`. It does not create a product order, order items, order
addresses or delivery slots, update the customer profile, or send confirmation
email. Cashfree must report `PAID` with the matching amount, currency and order ID
before the snapshot is converted atomically to a paid order. The original customer,
prices and quantities are retained even if the browser session disappears or the
catalog changes. Duplicate callbacks reuse the completed order. Passwords in the
snapshot are hashed before encryption. Keep the existing `APP_KEY` stable so
pending snapshots remain readable.

COD remains a separate pay-on-delivery flow and creates its order immediately.
Existing legacy pending orders can still be verified; they are not deleted.

Configured webhook endpoint: `https://houseofknp.com/checkout/cashfree/webhook`
(POST, payment-success event).

1. Deploy the controllers, services, provider, helpers, routes, middleware, views,
   command and migration together. Run the migration before accepting checkout:

   ```sh
   php artisan migrate --path=database/migrations/2026_09_18_000001_add_stock_transferred_at_to_product_orders.php --force
   php artisan migrate --path=database/migrations/2026_09_18_000002_create_checkout_attempts_table.php --force
   php artisan optimize:clear
   php artisan route:cache
   ```

   The migration adds `stock_transferred_at` and marks legacy paid/COD orders as
   already allocated, matching the previous checkout behavior. Do not rerun stock
   deductions for those orders.

2. Ensure `APP_URL` uses the public HTTPS storefront URL and Cashfree credentials
   match the intended environment. `CASHFREE_RETURN_URL`, if set, must target
   `/checkout/cashfree/return?order_id={order_id}` on that storefront.

3. Register the public URL `/checkout/cashfree/webhook` for payment-success events
   in the corresponding Cashfree dashboard. New orders also send this address as
   `order_meta.notify_url`. Only this endpoint is exempt from CSRF; it verifies
   Cashfree's signature over the raw request body and rechecks the order API.
   See [Cashfree webhook documentation](https://www.cashfree.com/docs/payments/online/webhooks/overview).

4. Recover an old pending order using the gateway's current state:

   ```sh
   php artisan payments:reconcile ORDER_NUMBER
   ```

   Without an order number, the command checks unfinished checkout attempts and
   legacy pending card/UPI orders.
   It can send confirmations and create shipments for verified payments. For a
   paid order whose stock or shipping failed, resolve the stock/shipping problem
   first, then run the command for that specific order. Never mark orders paid
   manually based solely on a customer's return URL.

5. Complete a Cashfree sandbox payment and check both browser verification and
   webhook delivery, account payment status, stock and Shiprocket. No live payment
   or production reconciliation was performed during local implementation.

## Local verification

`php artisan test --filter='PaymentConfirmationTest|RouteStructureTest'` exercises paid/pending orders,
gateway failures, mismatched payment details, stock shortage, shipping failure,
duplicate callbacks, authorization, signed/forged webhooks and reconciliation.
Tests use in-memory SQLite, fake Cashfree responses and mocked shipment/email
services; they do not contact payment or shipping systems.

`node --test tests/checkout-payment.test.cjs` executes the checkout JavaScript with
mocked browser/gateway interfaces to check initialization, delayed confirmation,
network failure and retries that do not open another payment. These are not live
browser or gateway tests.

Validation after the model refactor: 28 PHP tests (217 assertions), 3 JavaScript
tests and PHP syntax checks passed. The model tests render 13 storefront pages.
No `Schema` or `DB` facade usage remains in application code or views. The last
full existing PHP suite run also had two failures:
the homepage test has no `web_images` fixture/table, and the incorrect-OTP test
reaches a page whose header requires the missing `categories` table. The latter
also retains the pre-existing reset-page guard that checks the email session
rather than OTP verification. Those unrelated test/auth issues are not changed here.
