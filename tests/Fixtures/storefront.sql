-- SQLite fixtures for the existing storefront tables; no customer data.

-- Used only by ModelIntegrationTest with an in-memory database.

CREATE TABLE "blog" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "title" TEXT,
    "image" TEXT,
    "description" TEXT,
    "date" TEXT,
    "url_name" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT,
    "blog_category_id" INTEGER,
    "meta_title" TEXT,
    "meta_description" TEXT,
    "meta_key" TEXT
);

CREATE TABLE "bulkorders" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "name" TEXT,
    "email" TEXT,
    "subject" TEXT,
    "phone" TEXT,
    "message" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "categories" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "category_name" TEXT,
    "category_banner" TEXT,
    "category_image" TEXT,
    "category_icon" TEXT,
    "banner_title" TEXT,
    "banner_description" TEXT,
    "status" INTEGER,
    "is_gift" INTEGER,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "contacts" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "name" TEXT,
    "email" TEXT,
    "phone_number" TEXT,
    "subject" TEXT,
    "message" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "coupons" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "codename" TEXT,
    "mini_amt" INTEGER,
    "discounttype" INTEGER,
    "discount" INTEGER,
    "start_date" TEXT,
    "end_date" TEXT,
    "default_id" TEXT,
    "coupon_status" INTEGER,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "gift_categories" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "category_name" TEXT,
    "category_image" TEXT,
    "banner_title" TEXT,
    "banner_description" TEXT,
    "status" INTEGER,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "gift_combos" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "gift_category_id" INTEGER,
    "gift_subcategory_id" INTEGER,
    "combo_name" TEXT,
    "combo_image" TEXT,
    "combo_image_2" TEXT,
    "combo_image_3" TEXT,
    "variant_name" TEXT,
    "mrp_price" NUMERIC,
    "offer_price" NUMERIC,
    "additional_payment" NUMERIC,
    "created_at" TEXT,
    "updated_at" TEXT,
    "stock_quantity" INTEGER,
    "weight" TEXT,
    "unit_id" INTEGER,
    "product_value" TEXT,
    "fit" TEXT,
    "features" TEXT,
    "low_stock" INTEGER,
    "gst" INTEGER,
    "trending_collection" INTEGER,
    "popular_prod" INTEGER,
    "product_description" TEXT,
    "combo_details" TEXT,
    "shirt_color" TEXT,
    "shirt_size" TEXT,
    "perfume_ml" TEXT,
    "watch_model" TEXT,
    "thumbnail_images" TEXT
);

CREATE TABLE "home_promotions" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "badge_text" TEXT,
    "main_title" TEXT,
    "highlight_text" TEXT,
    "bg_image" TEXT,
    "link_url" TEXT,
    "sort_order" INTEGER,
    "status" INTEGER,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "home_sections" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "section_key" TEXT,
    "section_group" TEXT,
    "label" TEXT,
    "content" TEXT,
    "highlight_word" TEXT,
    "char_limit" INTEGER,
    "sort_order" INTEGER,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "page_meta_settings" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "page_name" TEXT,
    "page_url" TEXT,
    "meta_title" TEXT,
    "meta_keywords" TEXT,
    "meta_description" TEXT,
    "canonical_url" TEXT,
    "og_image" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "password_reset_tokens" (
    "email" TEXT PRIMARY KEY,
    "token" TEXT,
    "created_at" TEXT
);

CREATE TABLE "product_child_images" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "product_id" INTEGER,
    "variant_id" INTEGER,
    "product_child_image" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "product_order_items" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "order_id" INTEGER,
    "product_id" INTEGER,
    "product_variant_id" INTEGER,
    "product_name" TEXT,
    "variant_name" TEXT,
    "quantity" INTEGER,
    "price" NUMERIC,
    "gst_rate" NUMERIC,
    "gst_amount" NUMERIC,
    "total" NUMERIC,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "product_order_user_addresses" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "user_id" TEXT,
    "order_id" TEXT,
    "firstname" TEXT,
    "secondname" TEXT,
    "address_line_one" TEXT,
    "address_line_two" TEXT,
    "landmark" TEXT,
    "area_id" INTEGER,
    "city" TEXT,
    "state" TEXT,
    "pincode" INTEGER,
    "phonecode" TEXT,
    "address_phone_number" INTEGER,
    "address_type_id" INTEGER,
    "address_type_name" TEXT,
    "address_type_others_name" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "product_orders" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "user_id" INTEGER,
    "order_number" TEXT,
    "order_id" TEXT,
    "delivery_person_id" INTEGER,
    "delivery_person_name" TEXT,
    "delivery_person_phone" TEXT,
    "is_delivery_assigned" INTEGER,
    "billing_name" TEXT,
    "billing_email" TEXT,
    "billing_phone" TEXT,
    "billing_door_no" TEXT,
    "billing_street" TEXT,
    "billing_area" TEXT,
    "billing_city" TEXT,
    "billing_state" TEXT,
    "billing_pincode" TEXT,
    "shipping_name" TEXT,
    "shipping_email" TEXT,
    "shipping_phone" TEXT,
    "shipping_door_no" TEXT,
    "shipping_street" TEXT,
    "shipping_area" TEXT,
    "shipping_city" TEXT,
    "shipping_state" TEXT,
    "shipping_pincode" TEXT,
    "subtotal" NUMERIC,
    "gst_amount" NUMERIC,
    "shipping_charge" NUMERIC,
    "coupon_discount" NUMERIC,
    "total_amount" NUMERIC,
    "payment_status" TEXT,
    "payment_method" TEXT,
    "razorpay_order_id" TEXT,
    "razorpay_payment_id" TEXT,
    "shiprocket_order_id" INTEGER,
    "shiprocket_shipment_id" INTEGER,
    "awb_code" TEXT,
    "courier_name" TEXT,
    "pickup_scheduled" INTEGER,
    "manifest_url" TEXT,
    "label_url" TEXT,
    "invoice_url" TEXT,
    "shiprocket_status" TEXT,
    "status" TEXT,
    "cancellation_reason" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT,
    "stock_transferred_at" TEXT
);

CREATE TABLE "product_slots" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "delivery_date" TEXT,
    "order_id" TEXT,
    "product_id" INTEGER,
    "product_varient_id" INTEGER,
    "product_name" TEXT,
    "product_rate" TEXT,
    "gst_amt" TEXT,
    "gst_per" TEXT,
    "product_value" TEXT,
    "quantity" INTEGER,
    "product_total" TEXT,
    "delivery_status" INTEGER,
    "preorder" INTEGER,
    "dispatch_date" TEXT,
    "order_delivered_time" TEXT,
    "deliver_person_id" TEXT,
    "is_cancelled" INTEGER,
    "cancel_reason" TEXT,
    "approve_staus" INTEGER,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "product_varient" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "categoryid" INTEGER,
    "subcategoryid" TEXT,
    "product_id" INTEGER,
    "sku" TEXT,
    "barcode" TEXT,
    "varient" TEXT,
    "unit_id" INTEGER,
    "varient_img" TEXT,
    "varient_name" TEXT,
    "value" TEXT,
    "offer_price" INTEGER,
    "mrp_price" INTEGER,
    "product_qty" INTEGER,
    "low_stock" TEXT,
    "hot_deals" INTEGER,
    "Popular_products" INTEGER,
    "pre_order" INTEGER,
    "pre_note" TEXT,
    "product_gst" INTEGER,
    "product_hsn" TEXT,
    "weight" NUMERIC,
    "length" NUMERIC,
    "breadth" NUMERIC,
    "height" NUMERIC,
    "subcatename" TEXT,
    "size_value" INTEGER,
    "varient_details" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT,
    "flash_sale" INTEGER,
    "flash_sale_date" TEXT
);

CREATE TABLE "products" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "category_id" INTEGER,
    "brand_id" INTEGER,
    "subcategory_id" TEXT,
    "product_name" TEXT,
    "slug" TEXT,
    "product_quantity" INTEGER,
    "product_mrp_price" INTEGER,
    "product_regular_price" INTEGER,
    "product_description" TEXT,
    "product_image" TEXT,
    "product_image_2" TEXT,
    "product_specification" TEXT,
    "product_specfication" TEXT,
    "brand_name" TEXT,
    "brand_material" TEXT,
    "brand_type" TEXT,
    "approval_days" INTEGER,
    "is_gift" INTEGER,
    "created_at" TEXT,
    "updated_at" TEXT,
    "unit_value" TEXT,
    "product_value" TEXT,
    "cate_name" TEXT,
    "subcate_name" TEXT,
    "size_value" INTEGER,
    "deleted_at" TEXT,
    "meta_title" TEXT,
    "meta_description" TEXT,
    "meta_key" TEXT,
    "color" TEXT,
    "size" TEXT,
    "fit" TEXT,
    "features" TEXT,
    "product_details" TEXT
);

CREATE TABLE "productstocks" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "productid" INTEGER,
    "category_id" INTEGER,
    "subcategory_id" TEXT,
    "pro_ver_id" INTEGER,
    "productname" TEXT,
    "overallstock" INTEGER,
    "availablestock" INTEGER,
    "salestock" INTEGER,
    "low_stocks" TEXT,
    "last_stockupdate_date" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "review_videos" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "prod_id" INTEGER,
    "name" TEXT,
    "rating" INTEGER,
    "video" TEXT,
    "status" INTEGER,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "reviews" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "user_id" TEXT,
    "name" TEXT,
    "email" TEXT,
    "prod_id" TEXT,
    "combo_id" INTEGER,
    "prod_var_id" TEXT,
    "review" TEXT,
    "status" INTEGER,
    "ratings" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "shipping_amount_settings" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "minimum_amount" NUMERIC,
    "shipping_amount" NUMERIC,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "sub_categories" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "subcategory_name" TEXT,
    "subcategory_image" TEXT,
    "category_name" TEXT,
    "category_display" TEXT,
    "status" TEXT,
    "is_gift" INTEGER,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "testimonials" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "para" TEXT,
    "image" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT,
    "firstname" TEXT
);

CREATE TABLE "units" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "unit_name" TEXT,
    "short_name" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "user_addresses" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "address_username" TEXT,
    "address_first_name" TEXT,
    "address_last_name" TEXT,
    "user_id" TEXT,
    "address_line_one" TEXT,
    "address_line_two" TEXT,
    "landmark" TEXT,
    "area_id" INTEGER,
    "area_name" TEXT,
    "city" TEXT,
    "city_id" INTEGER,
    "state_id" INTEGER,
    "pincode" INTEGER,
    "pincode_id" INTEGER,
    "district" TEXT,
    "state" TEXT,
    "phone_code" TEXT,
    "address_phone_number" INTEGER,
    "address_type_id" INTEGER,
    "address_type_name" TEXT,
    "is_default" INTEGER,
    "address_type_others_name" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT
);

CREATE TABLE "users" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "user_id" TEXT,
    "is_guest_user" INTEGER,
    "user_token" TEXT,
    "name" TEXT,
    "email" TEXT,
    "phone" TEXT,
    "phone_number" TEXT,
    "first_name" TEXT,
    "last_name" TEXT,
    "gender" INTEGER,
    "profile_image" TEXT,
    "user_default_address_id" INTEGER,
    "area_id" INTEGER,
    "address_type_id" INTEGER,
    "email_verified_at" TEXT,
    "password" TEXT,
    "enc_password" TEXT,
    "remember_token" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT,
    "from_app" INTEGER,
    "firebase_fcm_token" TEXT,
    "door_no" TEXT,
    "street" TEXT,
    "area" TEXT,
    "city" TEXT,
    "pincode" TEXT,
    "billing_name" TEXT,
    "billing_phone" TEXT,
    "billing_door_no" TEXT,
    "billing_street" TEXT,
    "billing_area" TEXT,
    "billing_city" TEXT,
    "billing_state" TEXT,
    "billing_pincode" TEXT,
    "shipping_name" TEXT,
    "shipping_phone" TEXT,
    "shipping_door_no" TEXT,
    "shipping_street" TEXT,
    "shipping_area" TEXT,
    "shipping_city" TEXT,
    "shipping_state" TEXT,
    "shipping_pincode" TEXT
);

CREATE TABLE "web_images" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "image" TEXT,
    "video" TEXT,
    "title" TEXT,
    "subtitle" TEXT,
    "content" TEXT,
    "created_at" TEXT,
    "updated_at" TEXT
);
