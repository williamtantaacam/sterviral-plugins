<?php

namespace WP_Shopify;

if (!defined('ABSPATH')) {
    exit();
}

class Config
{
    public $plugin_dir_path;
    public $plugin_url;
    public $plugin_name;
    public $plugin_name_full;
    public $plugin_name_full_pro;
    public $plugin_name_full_encoded;
    public $plugin_version;
    public $plugin_author;
    public $plugin_nonce_action_backend;
    public $plugin_nonce_action_frontend;
    public $checkout_base_url;
    public $plugin_env;
    public $languages_folder;
    public $relative_template_dir;
    public $products_post_type_slug;
    public $orders_post_type_slug;
    public $collections_post_type_slug;
    public $shopify_rate_limit;
    public $plugin_default_currency;
    public $plugin_default_currency_symbol;
    public $cart_cache_expiration;
    public $settings_connection_option_name;
    public $settings_general_option_name;
    public $settings_license_option_name;
    public $plugin_table_name_images;
    public $plugin_table_name_variants;
    public $plugin_table_name_tags;
    public $plugin_table_name_shop;
    public $plugin_table_name_settings_license;
    public $plugin_table_name_settings_general;
    public $plugin_table_name_settings_connection;
    public $plugin_table_name_settings_syncing;
    public $plugin_table_name_products;
    public $plugin_table_name_orders;
    public $plugin_table_name_options;
    public $plugin_table_name_customers;
    public $plugin_table_name_collects;
    public $plugin_table_name_collections_custom;
    public $plugin_table_name_collections_smart;
    public $plugin_table_name_wp_posts;
    public $plugin_table_name_wp_postmeta;
    public $plugin_table_name_wp_term_relationships;
    public $plugin_table_name_wp_options;
    public $fallback_image_alt_text;
    public $total_webhooks_count;
    public $plugin_products_lookup_key;
    public $plugin_collections_lookup_key;
    public $plugin_default_cart_counter_color;
    public $plugin_default_cart_counter_fixed_color;
    public $plugin_default_cart_fixed_background_color;
    public $plugin_default_cart_icon_color;
    public $plugin_default_inline_cart_counter_text_color;
    public $plugin_default_cart_icon_fixed_color;
    public $plugin_default_variant_color;
    public $plugin_default_add_to_cart_color;
    public $plugin_default_products_heading;
    public $plugin_default_collections_heading;
    public $plugin_default_related_products_heading;
    public $plugin_default_products_images_sizing_width;
    public $plugin_default_products_images_sizing_height;
    public $plugin_default_products_images_sizing_crop;
    public $plugin_default_products_images_sizing_scale;

    public $plugin_default_products_thumbnail_images_sizing_width;
    public $plugin_default_products_thumbnail_images_sizing_height;
    public $plugin_default_products_thumbnail_images_sizing_crop;
    public $plugin_default_products_thumbnail_images_sizing_scale;

    public $plugin_default_products_images_show_zoom;

    public function __construct()
    {
        $this->plugin_dir_path = plugin_dir_path(__DIR__);
        $this->plugin_url = plugin_dir_url(__DIR__);
        $this->plugin_name = 'wps';
        $this->plugin_name_full = 'WP Shopify';
        $this->plugin_name_full_pro = 'WP Shopify Pro';
        $this->plugin_name_full_encoded = urlencode($this->plugin_name_full);

        $this->plugin_version = '3.5.7';
        $this->compatibility_plugin_version = '1.0.4';
        $this->plugin_author = 'WP Shopify';

        $this->plugin_nonce_action_backend = 'wp-shopify-backend';
        $this->plugin_nonce_action_frontend = 'wp-shopify-frontend';

        $this->checkout_base_url = 'https://checkout.shopify.com';
        $this->plugin_env = 'https://wpshop.io';
        $this->languages_folder = 'languages';
        $this->relative_template_dir = 'public/templates';
        $this->products_post_type_slug = 'wps_products';
        $this->collections_post_type_slug = 'wps_collections';
        $this->orders_post_type_slug = 'wps_orders';
        $this->shopify_rate_limit = '39/40';
        $this->plugin_default_currency = 'USD';
        $this->plugin_default_currency_symbol = '$';
        $this->cart_cache_expiration = 259200; // Checkout is cached for three days
        $this->fallback_image_alt_text = 'Shop Product';
        $this->total_webhooks_count = 27;
        $this->shopify_domain_suffix = '.myshopify.com';
        $this->shopify_header_verify_webhooks = 'HTTP_X_SHOPIFY_HMAC_SHA256';
        $this->shopify_header_api_call_limit =
            'HTTP_X_SHOPIFY_SHOP_API_CALL_LIMIT';
        $this->max_items_per_request = 250;
        $this->shopify_payload_key = 'id';
        $this->max_ids_per_request = 250;
        // WP Shopify API
        $this->api_slug = 'wpshopify';
        $this->api_version = 'v1';
        $this->api_namespace = $this->api_slug . '/' . $this->api_version;

        // Settings. TODO: Remove?
        $this->settings_connection_option_name =
            $this->plugin_name . '_settings_connection';
        $this->settings_general_option_name =
            $this->plugin_name . '_settings_general';
        $this->settings_license_option_name =
            $this->plugin_name . '_settings_license';

        $this->plugin_table_name_images = 'wps_images';
        $this->plugin_table_name_variants = 'wps_variants';
        $this->plugin_table_name_tags = 'wps_tags';
        $this->plugin_table_name_shop = 'wps_shop';
        $this->plugin_table_name_settings_license = 'wps_settings_license';
        $this->plugin_table_name_settings_general = 'wps_settings_general';
        $this->plugin_table_name_settings_connection =
            'wps_settings_connection';
        $this->plugin_table_name_settings_syncing = 'wps_settings_syncing';
        $this->plugin_table_name_products = 'wps_products';
        $this->plugin_table_name_orders = 'wps_orders';
        $this->plugin_table_name_options = 'wps_options';
        $this->plugin_table_name_customers = 'wps_customers';
        $this->plugin_table_name_collects = 'wps_collects';
        $this->plugin_table_name_collections_custom = 'wps_collections_custom';
        $this->plugin_table_name_collections_smart = 'wps_collections_smart';

        $this->plugin_table_name_wp_posts = 'posts';
        $this->plugin_table_name_wp_postmeta = 'postmeta';
        $this->plugin_table_name_wp_term_relationships = 'term_relationships';
        $this->plugin_table_name_wp_options = 'options';

        $this->plugin_table_migration_suffix = '_migrate';
        $this->plugin_table_migration_suffix_tests = '_migrate_tests';

        $this->plugin_products_lookup_key = 'product_id';
        $this->plugin_collections_lookup_key = 'collection_id';
        $this->plugin_cart_default_terms_content =
            'I agree with the terms and conditions.';
        $this->plugin_default_add_to_cart_text = 'Add to cart';
        $this->plugin_default_add_to_cart_color = '#415aff';
        $this->plugin_default_variant_color = '#000000';
        $this->plugin_default_cart_counter_color = '#6ae06a';
        $this->plugin_default_cart_counter_fixed_color = '#FFF';
        $this->plugin_default_cart_icon_color = '#000';
        $this->plugin_default_inline_cart_counter_text_color = '#000';
        $this->plugin_default_cart_icon_fixed_color = '#FFF';
        $this->plugin_default_products_heading = 'Products';
        $this->plugin_default_collections_heading = 'Collections';
        $this->plugin_default_related_products_heading = 'Related Products';
        $this->default_enable_custom_checkout_domain = 0;
        $this->default_products_compare_at = 0;
        $this->default_products_show_price_range = 1;

        $this->plugin_default_cart_fixed_background_color = '#000000';
        $this->plugin_default_products_images_show_zoom = 0;

        $this->plugin_default_products_images_sizing_width = 400;
        $this->plugin_default_products_images_sizing_height = 400;
        $this->plugin_default_products_images_sizing_crop = 'center';
        $this->plugin_default_products_images_sizing_scale = 0;

        $this->plugin_default_products_thumbnail_images_sizing_width = 70;
        $this->plugin_default_products_thumbnail_images_sizing_height = 70;
        $this->plugin_default_products_thumbnail_images_sizing_crop = 'center';
        $this->plugin_default_products_thumbnail_images_sizing_scale = 0;

        $this->plugin_default_collections_images_sizing_width = 400;
        $this->plugin_default_collections_images_sizing_height = 400;
        $this->plugin_default_collections_images_sizing_crop = 'center';
        $this->plugin_default_collections_images_sizing_scale = 0;

        $this->plugin_default_related_products_images_sizing_width = 0;
        $this->plugin_default_related_products_images_sizing_height = 0;
        $this->plugin_default_related_products_images_sizing_crop = 'center';
        $this->plugin_default_related_products_images_sizing_scale = 0;

        $this->plugin_default_cart_conditional_fixed_tab_loading = 'all';

        $this->plugin_default_checkout_button_target = '_self';
        $this->plugin_default_products_link_target = '_self';
        $this->plugin_default_products_link_to = 'none';

        $this->plugin_default_enable_automatic_syncing = 0;
        $this->plugin_default_sync_by_webhooks =
            "product_listings/add, product_listings/update, product_listings/remove";

        $this->plugin_default_allow_tracking = 0;
        $this->plugin_default_sync_media = 0;

        $this->plugin_default_free_basename = 'wpshopify/wp-shopify.php';
        $this->plugin_default_pro_basename = 'wp-shopify-pro/wp-shopify.php';
        $this->plugin_default_admin_rest_api_version = '2021-01';

        $this->plugin_default_page_products = '';
        $this->plugin_default_page_collections = '';
        $this->plugin_default_variant_style = 'dropdown';
        $this->plugin_download_id = 35;

        if (!defined('WP_SHOPIFY_DOWNLOAD_NAME')) {
            define('WP_SHOPIFY_DOWNLOAD_NAME', $this->plugin_name_full);
        }

        if (!defined('WP_SHOPIFY_DOWNLOAD_ID')) {
            define('WP_SHOPIFY_DOWNLOAD_ID', $this->plugin_download_id);
        }

        if (!defined('WP_SHOPIFY_DEFAULT_PAGE_PRODUCTS')) {
            define(
                'WP_SHOPIFY_DEFAULT_PAGE_PRODUCTS',
                $this->plugin_default_page_products
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_VARIANT_STYLE')) {
            define(
                'WP_SHOPIFY_DEFAULT_VARIANT_STYLE',
                $this->plugin_default_variant_style
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_PAGE_COLLECTIONS')) {
            define(
                'WP_SHOPIFY_DEFAULT_PAGE_COLLECTIONS',
                $this->plugin_default_page_collections
            );
        }

        if (!defined('WP_SHOPIFY_ADMIN_REST_API_VERSION')) {
            define(
                'WP_SHOPIFY_ADMIN_REST_API_VERSION',
                $this->plugin_default_admin_rest_api_version
            );
        }

        if (!defined('WP_SHOPIFY_FREE_BASENAME')) {
            define(
                'WP_SHOPIFY_FREE_BASENAME',
                $this->plugin_default_free_basename
            );
        }

        if (!defined('WP_SHOPIFY_PRO_BASENAME')) {
            define(
                'WP_SHOPIFY_PRO_BASENAME',
                $this->plugin_default_pro_basename
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_SYNC_MEDIA')) {
            define(
                'WP_SHOPIFY_DEFAULT_SYNC_MEDIA',
                $this->plugin_default_sync_media
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_ALLOW_TRACKING')) {
            define(
                'WP_SHOPIFY_DEFAULT_ALLOW_TRACKING',
                $this->plugin_default_allow_tracking
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_ENABLE_AUTOMATIC_SYNCING')) {
            define(
                'WP_SHOPIFY_DEFAULT_ENABLE_AUTOMATIC_SYNCING',
                $this->plugin_default_enable_automatic_syncing
            );
        }

        if (!defined('WP_SHOPIFY_SHOPIFY_HEADER_VERIFY_WEBHOOKS')) {
            define(
                'WP_SHOPIFY_SHOPIFY_HEADER_VERIFY_WEBHOOKS',
                $this->shopify_header_verify_webhooks
            );
        }

        if (!defined('WP_SHOPIFY_SHOPIFY_HEADER_API_CALL_LIMIT')) {
            define(
                'WP_SHOPIFY_SHOPIFY_HEADER_API_CALL_LIMIT',
                $this->shopify_header_api_call_limit
            );
        }

        if (!defined('WP_SHOPIFY_SETTINGS_CONNECTION_OPTION_NAME')) {
            define(
                'WP_SHOPIFY_SETTINGS_CONNECTION_OPTION_NAME',
                $this->settings_connection_option_name
            );
        }

        if (!defined('WP_SHOPIFY_SETTINGS_GENERAL_OPTION_NAME')) {
            define(
                'WP_SHOPIFY_SETTINGS_GENERAL_OPTION_NAME',
                $this->settings_general_option_name
            );
        }

        if (!defined('WP_SHOPIFY_SETTINGS_LICENSE_OPTION_NAME')) {
            define(
                'WP_SHOPIFY_SETTINGS_LICENSE_OPTION_NAME',
                $this->settings_license_option_name
            );
        }

        if (!defined('WP_SHOPIFY_PLUGIN_NAME')) {
            define('WP_SHOPIFY_PLUGIN_NAME', $this->plugin_name);
        }

        if (!defined('WP_SHOPIFY_PLUGIN_NAME_FULL')) {
            define('WP_SHOPIFY_PLUGIN_NAME_FULL', $this->plugin_name_full);
        }

        if (!defined('WP_SHOPIFY_PLUGIN_NAME_FULL_PRO')) {
            define(
                'WP_SHOPIFY_PLUGIN_NAME_FULL_PRO',
                $this->plugin_name_full_pro
            );
        }

        if (!defined('WP_SHOPIFY_PLUGIN_NAME_ENCODED')) {
            define(
                'WP_SHOPIFY_PLUGIN_NAME_ENCODED',
                $this->plugin_name_full_encoded
            );
        }

        if (!defined('WP_SHOPIFY_NEW_PLUGIN_VERSION')) {
            define('WP_SHOPIFY_NEW_PLUGIN_VERSION', $this->plugin_version);
        }

        if (!defined('WP_SHOPIFY_COMPATIBILITY_PLUGIN_VERSION')) {
            define(
                'WP_SHOPIFY_COMPATIBILITY_PLUGIN_VERSION',
                $this->compatibility_plugin_version
            );
        }

        if (!defined('WP_SHOPIFY_NEW_PLUGIN_AUTHOR')) {
            define('WP_SHOPIFY_NEW_PLUGIN_AUTHOR', $this->plugin_author);
        }

        if (!defined('WP_SHOPIFY_PLUGIN_URL')) {
            define('WP_SHOPIFY_PLUGIN_URL', $this->plugin_url);
        }

        if (!defined('WP_SHOPIFY_PLUGIN_DIR_PATH')) {
            define('WP_SHOPIFY_PLUGIN_DIR_PATH', $this->plugin_dir_path);
        }

        if (!defined('WP_SHOPIFY_CART_CACHE_EXPIRATION')) {
            define(
                'WP_SHOPIFY_CART_CACHE_EXPIRATION',
                $this->cart_cache_expiration
            );
        }

        if (!defined('WP_SHOPIFY_PLUGIN_ENV')) {
            define('WP_SHOPIFY_PLUGIN_ENV', $this->plugin_env);
        }

        if (!defined('WP_SHOPIFY_RELATIVE_TEMPLATE_DIR')) {
            define(
                'WP_SHOPIFY_RELATIVE_TEMPLATE_DIR',
                $this->relative_template_dir
            );
        }

        if (!defined('WP_SHOPIFY_CHECKOUT_BASE_URL')) {
            define('WP_SHOPIFY_CHECKOUT_BASE_URL', $this->checkout_base_url);
        }

        if (!defined('WP_SHOPIFY_SHOPIFY_RATE_LIMIT')) {
            define('WP_SHOPIFY_SHOPIFY_RATE_LIMIT', $this->shopify_rate_limit);
        }

        if (!defined('WP_SHOPIFY_LANGUAGES_FOLDER')) {
            define('WP_SHOPIFY_LANGUAGES_FOLDER', $this->languages_folder);
        }

        if (!defined('WP_SHOPIFY_PRODUCTS_POST_TYPE_SLUG')) {
            define(
                'WP_SHOPIFY_PRODUCTS_POST_TYPE_SLUG',
                $this->products_post_type_slug
            );
        }

        if (!defined('WP_SHOPIFY_ORDERS_POST_TYPE_SLUG')) {
            define(
                'WP_SHOPIFY_ORDERS_POST_TYPE_SLUG',
                $this->orders_post_type_slug
            );
        }

        if (!defined('WP_SHOPIFY_COLLECTIONS_POST_TYPE_SLUG')) {
            define(
                'WP_SHOPIFY_COLLECTIONS_POST_TYPE_SLUG',
                $this->collections_post_type_slug
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_IMAGES')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_IMAGES',
                $this->plugin_table_name_images
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_VARIANTS')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_VARIANTS',
                $this->plugin_table_name_variants
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_TAGS')) {
            define('WP_SHOPIFY_TABLE_NAME_TAGS', $this->plugin_table_name_tags);
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_SHOP')) {
            define('WP_SHOPIFY_TABLE_NAME_SHOP', $this->plugin_table_name_shop);
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_SETTINGS_LICENSE')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_SETTINGS_LICENSE',
                $this->plugin_table_name_settings_license
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_SETTINGS_GENERAL')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_SETTINGS_GENERAL',
                $this->plugin_table_name_settings_general
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_SETTINGS_CONNECTION')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_SETTINGS_CONNECTION',
                $this->plugin_table_name_settings_connection
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_SETTINGS_SYNCING')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_SETTINGS_SYNCING',
                $this->plugin_table_name_settings_syncing
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_PRODUCTS')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_PRODUCTS',
                $this->plugin_table_name_products
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_ORDERS')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_ORDERS',
                $this->plugin_table_name_orders
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_OPTIONS')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_OPTIONS',
                $this->plugin_table_name_options
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_CUSTOMERS')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_CUSTOMERS',
                $this->plugin_table_name_customers
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_COLLECTS')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_COLLECTS',
                $this->plugin_table_name_collects
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_COLLECTIONS_SMART')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_COLLECTIONS_SMART',
                $this->plugin_table_name_collections_smart
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_COLLECTIONS_CUSTOM')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_COLLECTIONS_CUSTOM',
                $this->plugin_table_name_collections_custom
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_WP_POSTS')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_WP_POSTS',
                $this->plugin_table_name_wp_posts
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_WP_POSTMETA')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_WP_POSTMETA',
                $this->plugin_table_name_wp_postmeta
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_WP_TERM_RELATIONSHIPS')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_WP_TERM_RELATIONSHIPS',
                $this->plugin_table_name_wp_term_relationships
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_NAME_WP_OPTIONS')) {
            define(
                'WP_SHOPIFY_TABLE_NAME_WP_OPTIONS',
                $this->plugin_table_name_wp_options
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_CURRENCY')) {
            define(
                'WP_SHOPIFY_DEFAULT_CURRENCY',
                $this->plugin_default_currency
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_CURRENCY_SYMBOL')) {
            define(
                'WP_SHOPIFY_DEFAULT_CURRENCY_SYMBOL',
                $this->plugin_default_currency_symbol
            );
        }

        if (!defined('WP_SHOPIFY_BACKEND_NONCE_ACTION')) {
            define(
                'WP_SHOPIFY_BACKEND_NONCE_ACTION',
                $this->plugin_nonce_action_backend
            );
        }

        if (!defined('WP_SHOPIFY_FRONTEND_NONCE_ACTION')) {
            define(
                'WP_SHOPIFY_FRONTEND_NONCE_ACTION',
                $this->plugin_nonce_action_frontend
            );
        }

        if (!defined('WP_SHOPIFY_FALLBACK_IMAGE_ALT_TEXT')) {
            define(
                'WP_SHOPIFY_FALLBACK_IMAGE_ALT_TEXT',
                $this->fallback_image_alt_text
            );
        }

        if (!defined('WP_SHOPIFY_TOTAL_WEBHOOKS_COUNT')) {
            define(
                'WP_SHOPIFY_TOTAL_WEBHOOKS_COUNT',
                $this->total_webhooks_count
            );
        }

        if (!defined('WP_SHOPIFY_SHOPIFY_DOMAIN_SUFFIX')) {
            define(
                'WP_SHOPIFY_SHOPIFY_DOMAIN_SUFFIX',
                $this->shopify_domain_suffix
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_MIGRATION_SUFFIX')) {
            define(
                'WP_SHOPIFY_TABLE_MIGRATION_SUFFIX',
                $this->plugin_table_migration_suffix
            );
        }

        if (!defined('WP_SHOPIFY_TABLE_MIGRATION_SUFFIX_TESTS')) {
            define(
                'WP_SHOPIFY_TABLE_MIGRATION_SUFFIX_TESTS',
                $this->plugin_table_migration_suffix_tests
            );
        }

        if (!defined('WP_SHOPIFY_MAX_ITEMS_PER_REQUEST')) {
            define(
                'WP_SHOPIFY_MAX_ITEMS_PER_REQUEST',
                $this->max_items_per_request
            );
        }

        if (!defined('WP_SHOPIFY_MAX_IDS_PER_REQUEST')) {
            define(
                'WP_SHOPIFY_MAX_IDS_PER_REQUEST',
                $this->max_ids_per_request
            );
        }

        if (!defined('WP_SHOPIFY_SHOPIFY_PAYLOAD_KEY')) {
            define(
                'WP_SHOPIFY_SHOPIFY_PAYLOAD_KEY',
                $this->shopify_payload_key
            );
        }

        if (!defined('WP_SHOPIFY_PRODUCTS_LOOKUP_KEY')) {
            define(
                'WP_SHOPIFY_PRODUCTS_LOOKUP_KEY',
                $this->plugin_products_lookup_key
            );
        }

        if (!defined('WP_SHOPIFY_COLLECTIONS_LOOKUP_KEY')) {
            define(
                'WP_SHOPIFY_COLLECTIONS_LOOKUP_KEY',
                $this->plugin_collections_lookup_key
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_CART_TERMS_CONTENT')) {
            define(
                'WP_SHOPIFY_DEFAULT_CART_TERMS_CONTENT',
                $this->plugin_cart_default_terms_content
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_ADD_TO_CART_TEXT')) {
            define(
                'WP_SHOPIFY_DEFAULT_ADD_TO_CART_TEXT',
                $this->plugin_default_add_to_cart_text
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_ADD_TO_CART_COLOR')) {
            define(
                'WP_SHOPIFY_DEFAULT_ADD_TO_CART_COLOR',
                $this->plugin_default_add_to_cart_color
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_VARIANT_COLOR')) {
            define(
                'WP_SHOPIFY_DEFAULT_VARIANT_COLOR',
                $this->plugin_default_variant_color
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_CART_COUNTER_COLOR')) {
            define(
                'WP_SHOPIFY_DEFAULT_CART_COUNTER_COLOR',
                $this->plugin_default_cart_counter_color
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_CART_COUNTER_FIXED_COLOR')) {
            define(
                'WP_SHOPIFY_DEFAULT_CART_COUNTER_FIXED_COLOR',
                $this->plugin_default_cart_counter_fixed_color
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_CART_FIXED_BACKGROUND_COLOR')) {
            define(
                'WP_SHOPIFY_DEFAULT_CART_FIXED_BACKGROUND_COLOR',
                $this->plugin_default_cart_fixed_background_color
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_CART_ICON_COLOR')) {
            define(
                'WP_SHOPIFY_DEFAULT_CART_ICON_COLOR',
                $this->plugin_default_cart_icon_color
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_INLINE_CART_COUNTER_TEXT_COLOR')) {
            define(
                'WP_SHOPIFY_DEFAULT_INLINE_CART_COUNTER_TEXT_COLOR',
                $this->plugin_default_inline_cart_counter_text_color
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_CART_ICON_FIXED_COLOR')) {
            define(
                'WP_SHOPIFY_DEFAULT_CART_ICON_FIXED_COLOR',
                $this->plugin_default_cart_icon_fixed_color
            );
        }

        if (!defined('WP_SHOPIFY_SHOPIFY_API_SLUG')) {
            define('WP_SHOPIFY_SHOPIFY_API_SLUG', $this->api_slug);
        }

        if (!defined('WP_SHOPIFY_SHOPIFY_API_VERSION')) {
            define('WP_SHOPIFY_SHOPIFY_API_VERSION', $this->api_version);
        }

        if (!defined('WP_SHOPIFY_SHOPIFY_API_NAMESPACE')) {
            define('WP_SHOPIFY_SHOPIFY_API_NAMESPACE', $this->api_namespace);
        }

        if (!defined('WP_SHOPIFY_DEFAULT_PRODUCTS_HEADING')) {
            define(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_HEADING',
                $this->plugin_default_products_heading
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_COLLECTIONS_HEADING')) {
            define(
                'WP_SHOPIFY_DEFAULT_COLLECTIONS_HEADING',
                $this->plugin_default_collections_heading
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_RELATED_PRODUCTS_HEADING')) {
            define(
                'WP_SHOPIFY_DEFAULT_RELATED_PRODUCTS_HEADING',
                $this->plugin_default_related_products_heading
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_PRODUCTS_IMAGES_SIZING_WIDTH')) {
            define(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_IMAGES_SIZING_WIDTH',
                $this->plugin_default_products_images_sizing_width
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_PRODUCTS_IMAGES_SIZING_HEIGHT')) {
            define(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_IMAGES_SIZING_HEIGHT',
                $this->plugin_default_products_images_sizing_height
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_PRODUCTS_IMAGES_SIZING_CROP')) {
            define(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_IMAGES_SIZING_CROP',
                $this->plugin_default_products_images_sizing_crop
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_PRODUCTS_IMAGES_SIZING_SCALE')) {
            define(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_IMAGES_SIZING_SCALE',
                $this->plugin_default_products_images_sizing_scale
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_PRODUCTS_IMAGES_SHOW_ZOOM')) {
            define(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_IMAGES_SHOW_ZOOM',
                $this->plugin_default_products_images_show_zoom
            );
        }

        if (
            !defined(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_WIDTH'
            )
        ) {
            define(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_WIDTH',
                $this->plugin_default_products_thumbnail_images_sizing_width
            );
        }

        if (
            !defined(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_HEIGHT'
            )
        ) {
            define(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_HEIGHT',
                $this->plugin_default_products_thumbnail_images_sizing_height
            );
        }

        if (
            !defined('WP_SHOPIFY_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_CROP')
        ) {
            define(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_CROP',
                $this->plugin_default_products_thumbnail_images_sizing_crop
            );
        }

        if (
            !defined(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_SCALE'
            )
        ) {
            define(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_THUMBNAIL_IMAGES_SIZING_SCALE',
                $this->plugin_default_products_thumbnail_images_sizing_scale
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_COLLECTIONS_IMAGES_SIZING_WIDTH')) {
            define(
                'WP_SHOPIFY_DEFAULT_COLLECTIONS_IMAGES_SIZING_WIDTH',
                $this->plugin_default_collections_images_sizing_width
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_COLLECTIONS_IMAGES_SIZING_HEIGHT')) {
            define(
                'WP_SHOPIFY_DEFAULT_COLLECTIONS_IMAGES_SIZING_HEIGHT',
                $this->plugin_default_collections_images_sizing_height
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_COLLECTIONS_IMAGES_SIZING_CROP')) {
            define(
                'WP_SHOPIFY_DEFAULT_COLLECTIONS_IMAGES_SIZING_CROP',
                $this->plugin_default_collections_images_sizing_crop
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_COLLECTIONS_IMAGES_SIZING_SCALE')) {
            define(
                'WP_SHOPIFY_DEFAULT_COLLECTIONS_IMAGES_SIZING_SCALE',
                $this->plugin_default_collections_images_sizing_scale
            );
        }

        if (
            !defined('WP_SHOPIFY_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_WIDTH')
        ) {
            define(
                'WP_SHOPIFY_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_WIDTH',
                $this->plugin_default_related_products_images_sizing_width
            );
        }

        if (
            !defined('WP_SHOPIFY_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_HEIGHT')
        ) {
            define(
                'WP_SHOPIFY_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_HEIGHT',
                $this->plugin_default_related_products_images_sizing_height
            );
        }

        if (
            !defined('WP_SHOPIFY_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_CROP')
        ) {
            define(
                'WP_SHOPIFY_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_CROP',
                $this->plugin_default_related_products_images_sizing_crop
            );
        }

        if (
            !defined('WP_SHOPIFY_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_SCALE')
        ) {
            define(
                'WP_SHOPIFY_DEFAULT_RELATED_PRODUCTS_IMAGES_SIZING_SCALE',
                $this->plugin_default_related_products_images_sizing_scale
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_CART_CONDITIONAL_FIXED_TAB_LOADING')) {
            define(
                'WP_SHOPIFY_DEFAULT_CART_CONDITIONAL_FIXED_TAB_LOADING',
                $this->plugin_default_cart_conditional_fixed_tab_loading
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_ENABLE_CUSTOM_CHECKOUT_DOMAIN')) {
            define(
                'WP_SHOPIFY_DEFAULT_ENABLE_CUSTOM_CHECKOUT_DOMAIN',
                $this->default_enable_custom_checkout_domain
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_PRODUCTS_COMPARE_AT')) {
            define(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_COMPARE_AT',
                $this->default_products_compare_at
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_PRODUCTS_SHOW_PRICE_RANGE')) {
            define(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_SHOW_PRICE_RANGE',
                $this->default_products_show_price_range
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_CHECKOUT_BUTTON_TARGET')) {
            define(
                'WP_SHOPIFY_DEFAULT_CHECKOUT_BUTTON_TARGET',
                $this->plugin_default_checkout_button_target
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_PRODUCTS_LINK_TARGET')) {
            define(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_LINK_TARGET',
                $this->plugin_default_products_link_target
            );
        }

        if (!defined('WP_SHOPIFY_DEFAULT_PRODUCTS_LINK_TO')) {
            define(
                'WP_SHOPIFY_DEFAULT_PRODUCTS_LINK_TO',
                $this->plugin_default_products_link_to
            );
        }
    }
}
