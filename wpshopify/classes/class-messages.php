<?php

namespace WP_Shopify;

if (!defined('ABSPATH')) {
    exit();
}

class Messages
{
    protected static $instance;

    public static $saving_native_cpt_data;
    public static $notice_allow_tracking;
    public static $app_uninstalled;

    public static $insecure_connection;
    public static $unable_to_cache_checkout;
    public static $missing_checkout_id;
    public static $delete_single_product_cache;
    public static $delete_single_product_images_cache;
    public static $delete_single_product_tags_cache;
    public static $delete_single_product_variants_cache;
    public static $delete_single_product_options_cache;
    public static $delete_product_prices;
    public static $delete_cached_settings;
    public static $delete_cached_admin_notices;
    public static $delete_cached_connection;
    public static $delete_cached_collection_queries;
    public static $delete_single_collection_cache;
    public static $delete_single_collections_cache;
    public static $delete_cached_products_queries;
    public static $delete_all_cache;
    public static $delete_cache_general;
    public static $delete_product_data_cache;
    public static $nonce_invalid;
    public static $connection_not_syncing;
    public static $connection_not_found;
    public static $connection_save_error;
    public static $connection_invalid_storefront_access_token;
    public static $connection_disconnect_invalid_access_token;
    public static $syncing_products_error;
    public static $syncing_variants_error;
    public static $syncing_options_error;
    public static $syncing_orders_error;
    public static $syncing_images_error;
    public static $syncing_customers_error;
    public static $delete_shop_error;
    public static $delete_connection_error;
    public static $delete_cpt_products_error;
    public static $delete_cpt_collections_error;
    public static $delete_product_images_error;
    public static $delete_product_inventory_error;
    public static $delete_collects_error;
    public static $insert_collects_error;
    public static $insert_collects_error_missing;
    public static $delete_product_tags_error;
    public static $delete_product_options_error;
    public static $delete_product_variants_error;
    public static $delete_products_error;
    public static $delete_custom_collections_error;
    public static $insert_custom_collections_error;
    public static $delete_smart_collections_error;
    public static $insert_smart_collections_error;
    public static $delete_orders_error;
    public static $delete_customers_error;
    public static $products_curency_format_not_found;
    public static $products_out_of_stock;
    public static $products_options_unavailable;
    public static $products_options_not_found;
    public static $webhooks_no_id_set;
    public static $webhooks_delete_error;
    public static $webhooks_sync_warning;
    public static $license_invalid_or_missing;
    public static $license_unable_to_delete;
    public static $smart_collections_not_found;
    public static $custom_collections_not_found;
    public static $orders_not_found;
    public static $customers_not_found;
    public static $products_not_found;
    public static $products_from_collection_not_found;
    public static $variants_not_found;
    public static $webhooks_not_found;
    public static $collects_not_found;
    public static $orders_insert_error;
    public static $shopify_api_400;
    public static $shopify_api_401;
    public static $shopify_api_402;
    public static $shopify_api_403;
    public static $shopify_api_404;
    public static $shopify_api_406;
    public static $shopify_api_422;
    public static $shopify_api_429;
    public static $shopify_api_500;
    public static $shopify_api_501;
    public static $shopify_api_503;
    public static $shopify_api_504;
    public static $shopify_api_generic;
    public static $invalid_server_connection;
    public static $syncing_status_update_failed;
    public static $missing_collects_for_page;
    public static $missing_media_for_page;
    public static $missing_products_for_page;
    public static $missing_product_ids;
    public static $missing_shop_for_page;
    public static $missing_orders_for_page;
    public static $missing_customers_for_page;
    public static $missing_collections_for_page;
    public static $missing_webhooks_for_page;

    public static $missing_smart_collections_for_page;
    public static $missing_custom_collections_for_page;

    public static $missing_shopify_domain;
    public static $max_allowed_packet;
    public static $max_post_body_size;
    public static $syncing_docs_check;
    public static $max_column_size_reached;

    public static $migration_table_creation_error;
    public static $migration_table_already_exists;
    public static $charset_not_found;
    public static $unable_to_convert_to_object;
    public static $unable_to_convert_to_array;
    public static $request_url_not_found;
    public static $api_invalid_endpoint;

    /*

	New messages

	*/
    public static $smart_collections_count_not_found;
    public static $custom_collections_count_not_found;
    public static $shop_count_not_found;
    public static $products_count_not_found;
    public static $collects_count_not_found;
    public static $orders_count_not_found;
    public static $customers_count_not_found;
    public static $failed_to_set_post_id_custom_table;
    public static $failed_to_set_lookup_key_post_meta_table;
    public static $max_memory_exceeded;
    public static $wp_cron_disabled;
    public static $failed_to_find_batch;

    public static function get_instance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function get($message_name)
    {
        $Messages = self::get_instance();

        return $Messages::${$message_name};
    }

    public static function message_exist($prop)
    {
        return property_exists(__CLASS__, $prop);
    }

    public static function trace($params)
    {
        return '<p>This occured while calling: ' .
            $params['call_method'] .
            ' on line ' .
            $params['call_line'] .
            '</p> ' .
            self::get('syncing_docs_check');
    }

    public static function get_message_aux($params)
    {
        if (\array_key_exists('message_aux', $params)) {
            $message_aux = $params['message_aux'];
        } else {
            $message_aux = '';
        }

        return $message_aux;
    }

    public static function error($params)
    {
        $method_set = \array_key_exists('call_method', $params);
        $line_set = \array_key_exists('call_line', $params);
        $message_set = \array_key_exists('message_lookup', $params);

        if ($message_set && (!$method_set && !$line_set)) {
            return $params['message_lookup'];
        }

        $message_aux = self::get_message_aux($params);

        if (!self::message_exist($params['message_lookup'])) {
            return $params['message_lookup'] .
                $message_aux .
                self::trace($params);
        }

        return self::get($params['message_lookup']) .
            $message_aux .
            self::trace($params);
    }

    public function __construct()
    {
        self::$saving_native_cpt_data = __(
            'WP Shopify Warning: Any custom changes made to the post title or post content could potentially be erased as a result of resyncing. Consider making changes to these fields within Shopify instead. Custom fields added either natively by WordPress or through plugins like ACF will NOT be erased upon re-sync.',
            'wpshopify'
        );

        self::$notice_allow_tracking = __(
            'Share how you\'re using WP Shopify by allowing <a href="https://docs.wpshop.io/#/guides/share-data?utm_medium=plugin&utm_source=notice&utm_campaign=optin" target="_blank">usage tracking</a> and help us make the plugin even better!<br><br> <a href="#!" class="wps-notice-link" id="wps-dismiss-tracking" style="margin-right:10px;">No thanks</a> <a id="wps-allow-tracking" data-dismiss-value="true" href="#!" class="wps-notice-link">Yes, allow usage tracking</a>',
            'wpshopify'
        );

        self::$app_uninstalled = __(
            'WP Shopify Warning: It looks like your Shopify private app has been deleted! WP Shopify won\'t continue to work until you create a new one. Disconnect your current store from the Connect tab to clear the old connection and then enter your new credentials.',
            'wpshopify'
        );

        /*

		Client-side Messages

		*/
        self::$insecure_connection = __(
            'Sorry, a secure connection could not be established with the store. Please try clearing your browser cache and reloading the page.',
            'wpshopify'
        );

        /*

		Cache

		*/
        self::$unable_to_cache_checkout = __(
            'WP Shopify Warning: Unable to cache checkout.',
            'wpshopify'
        );
        self::$missing_checkout_id = __(
            'WP Shopify Warning: Can\'t find checkout id to cache.',
            'wpshopify'
        );
        self::$delete_single_product_cache = __(
            'WP Shopify Warning: Unable to delete single product cache.',
            'wpshopify'
        );
        self::$delete_single_product_images_cache = __(
            'WP Shopify Warning: Unable to delete single product images cache.',
            'wpshopify'
        );
        self::$delete_single_product_tags_cache = __(
            'WP Shopify Warning: Unable to delete single product tags cache.',
            'wpshopify'
        );
        self::$delete_single_product_variants_cache = __(
            'WP Shopify Warning: Unable to delete single product variants cache.',
            'wpshopify'
        );
        self::$delete_single_product_options_cache = __(
            'WP Shopify Warning: Unable to delete single product options cache.',
            'wpshopify'
        );
        self::$delete_product_prices = __(
            'WP Shopify Warning: Unable to delete cached product prices.',
            'wpshopify'
        );
        self::$delete_cached_settings = __(
            'WP Shopify Warning: Unable to delete cached settings.',
            'wpshopify'
        );
        self::$delete_cached_admin_notices = __(
            'WP Shopify Warning: Unable to delete cached admin notices.',
            'wpshopify'
        );
        self::$delete_cached_connection = __(
            'WP Shopify Warning: Unable to delete cached connection.',
            'wpshopify'
        );
        self::$delete_cached_collection_queries = __(
            'WP Shopify Warning: Unable to delete cached collection queries.',
            'wpshopify'
        );
        self::$delete_single_collection_cache = __(
            'WP Shopify Warning: Unable to delete single cached collection.',
            'wpshopify'
        );
        self::$delete_single_collections_cache = __(
            'WP Shopify Warning: Unable to delete all cached single collections.',
            'wpshopify'
        );
        self::$delete_cached_products_queries = __(
            'WP Shopify Warning: Unable to delete cached product queries.',
            'wpshopify'
        );
        self::$delete_all_cache = __(
            'WP Shopify Warning: Unable to delete all cache, please try again.',
            'wpshopify'
        );
        self::$delete_cache_general = __(
            'WP Shopify Warning: Unable to delete general plugin cache, please try again.',
            'wpshopify'
        );
        self::$delete_product_data_cache = __(
            'WP Shopify Warning: Unable to delete single product data cache. Make sure to manually clear via WP Shopify - Tools.',
            'wpshopify'
        );
        self::$nonce_invalid = __(
            '<b>Error:</b> Your request has been rejected for security reasons. Please clear your browser cache and try again.',
            'wpshopify'
        );
        self::$connection_not_syncing = __(
            '<b>Error:</b> Syncing canceled early. Please refresh the page.',
            'wpshopify'
        );
        self::$connection_not_found = __(
            '<b>Error:</b> No connection details found. Please try reconnecting your Shopify store.',
            'wpshopify'
        );
        self::$connection_save_error = __(
            '<b>Error:</b> Unable to save Shopify connection details. Please refresh your browser and try again.',
            'wpshopify'
        );
        self::$connection_invalid_storefront_access_token = __(
            'Error: Invalid storefront access token. Double check your credentials and try again.',
            'wpshopify'
        );
        self::$connection_disconnect_invalid_access_token = __(
            '<b>Error:</b> Unable to disconnect Shopify store. Missing or invalid access token.',
            'wpshopify'
        );
        self::$syncing_products_error = __(
            '<b>Error:</b> Syncing canceled early at insert_products().',
            'wpshopify'
        );
        self::$syncing_variants_error = __(
            '<b>Error:</b> Syncing canceled early at insert_variants().',
            'wpshopify'
        );
        self::$syncing_options_error = __(
            '<b>Error:</b> Syncing canceled early at insert_options().',
            'wpshopify'
        );
        self::$syncing_orders_error = __(
            '<b>Error:</b> Syncing canceled early at insert_orders().',
            'wpshopify'
        );
        self::$syncing_images_error = __(
            '<b>Error:</b> Syncing canceled early at insert_images().',
            'wpshopify'
        );
        self::$syncing_customers_error = __(
            '<b>Error:</b> Syncing canceled early at insert_customers().',
            'wpshopify'
        );

        self::$delete_shop_error = __(
            '<b>Warning:</b> Unable to delete shop data.',
            'wpshopify'
        );
        self::$delete_connection_error = __(
            '<b>Warning:</b> Unable to delete connection settings.',
            'wpshopify'
        );
        self::$delete_cpt_products_error = __(
            '<b>Warning:</b> Some products custom post types could not be deleted. Please try again.',
            'wpshopify'
        );
        self::$delete_cpt_collections_error = __(
            '<b>Warning:</b> Some collections custom post types could not be deleted. Please try again.',
            'wpshopify'
        );
        self::$delete_product_images_error = __(
            '<b>Warning:</b> Unable to delete product images.',
            'wpshopify'
        );
        self::$delete_product_inventory_error = __(
            '<b>Warning:</b> Unable to delete product inventory.',
            'wpshopify'
        );
        self::$delete_collects_error = __(
            '<b>Warning:</b> Unable to delete collects.',
            'wpshopify'
        );
        self::$insert_collects_error = __(
            '<b>Warning:</b> Unable to insert certain collects.',
            'wpshopify'
        );
        self::$insert_collects_error_missing = __(
            '<b>Warning:</b> Unable to insert certain collects, none found.',
            'wpshopify'
        );
        self::$delete_product_tags_error = __(
            '<b>Warning:</b> Unable to delete product tags.',
            'wpshopify'
        );
        self::$delete_product_options_error = __(
            '<b>Warning:</b> Unable to delete product options.',
            'wpshopify'
        );
        self::$delete_product_variants_error = __(
            '<b>Warning:</b> Unable to delete product variants.',
            'wpshopify'
        );
        self::$delete_products_error = __(
            '<b>Warning:</b> Unable to delete products.',
            'wpshopify'
        );
        self::$delete_custom_collections_error = __(
            '<b>Warning:</b> Unable to delete custom collections.',
            'wpshopify'
        );
        self::$insert_custom_collections_error = __(
            '<b>Warning:</b> Unable to insert certain custom collections.',
            'wpshopify'
        );
        self::$delete_smart_collections_error = __(
            '<b>Warning:</b> Unable to delete smart collections.',
            'wpshopify'
        );
        self::$insert_smart_collections_error = __(
            '<b>Warning:</b> Unable to insert certain smart collections.',
            'wpshopify'
        );
        self::$delete_orders_error = __(
            '<b>Warning:</b> Unable to delete orders.',
            'wpshopify'
        );
        self::$delete_customers_error = __(
            '<b>Warning:</b> Unable to delete customers.',
            'wpshopify'
        );
        self::$products_curency_format_not_found = __(
            '<b>Error:</b> Currency format not found. Please try again.',
            'wpshopify'
        );
        self::$products_out_of_stock = __(
            'Sorry, this product variant is out of stock. Please choose another combination.',
            'wpshopify'
        );
        self::$products_options_unavailable = __(
            '<b>Error:</b> Selected option(s) aren\'t available. Please select a different combination.',
            'wpshopify'
        );
        self::$products_options_not_found = __(
            '<b>Error:</b> Unable to find selected options. Please try again.',
            'wpshopify'
        );
        self::$webhooks_no_id_set = __(
            '<b>Error:</b> No webhook ID set. Please try reconnecting WordPress to your Shopify site.',
            'wpshopify'
        );
        self::$webhooks_delete_error = __(
            '<b>Error:</b> Unable to remove webhook',
            'wpshopify'
        );
        self::$webhooks_sync_warning = __(
            '<b>Warning:</b> Unable to sync webhook: ',
            'wpshopify'
        );
        self::$license_invalid_or_missing = __(
            '<b>Error:</b> This license key is either missing or invalid. Please verify your key by logging into your account at wpshop.io.',
            'wpshopify'
        );
        self::$license_unable_to_delete = __(
            '<b>Error:</b> Unable to delete license key. Please refresh your browser and try again.',
            'wpshopify'
        );
        self::$smart_collections_not_found = __(
            '<b>Warning:</b> Unable to sync smart collections, none found.',
            'wpshopify'
        );

        self::$custom_collections_not_found = __(
            '<b>Warning:</b> Unable to sync custom collections, none found.',
            'wpshopify'
        );
        self::$orders_not_found = __(
            '<b>Warning:</b> Unable to sync orders, none found.',
            'wpshopify'
        );
        self::$customers_not_found = __(
            '<b>Warning:</b> Unable to sync customers, none found.',
            'wpshopify'
        );
        self::$products_not_found = __(
            '<b>Warning:</b> Unable to sync products, none found.',
            'wpshopify'
        );
        self::$products_from_collection_not_found = __(
            '<b>Warning:</b> Unable to find products attached to any collections.',
            'wpshopify'
        );
        self::$variants_not_found = __(
            '<b>Warning:</b> Unable to sync variants, none found.',
            'wpshopify'
        );
        self::$webhooks_not_found = __(
            '<b>Warning:</b> Unable to sync webhooks, none found.',
            'wpshopify'
        );
        self::$collects_not_found = __(
            '<b>Warning:</b> Unable to sync collects, none found.',
            'wpshopify'
        );
        self::$orders_insert_error = __(
            '<b>Warning:</b> Unable to sync 1 or more orders.',
            'wpshopify'
        );

        /*

		Shopify API Errors

		*/
        self::$shopify_api_400 = __(
            '<b>400 Error:</b> The request was not understood by the server. ',
            'wpshopify'
        );
        self::$shopify_api_401 = __(
            '<b>401 Error:</b> The necessary authentication credentials are not present in the request or are incorrect. ',
            'wpshopify'
        );
        self::$shopify_api_402 = __(
            '<b>402 Error:</b> The requested shop is currently frozen. ',
            'wpshopify'
        );
        self::$shopify_api_403 = __(
            '<b>403 Error:</b> The server is refusing to respond to the request. This is generally because you have not requested the appropriate scope for this action. ',
            'wpshopify'
        );
        self::$shopify_api_404 = __(
            '<b>404 Error:</b> The requested resource was not found. ',
            'wpshopify'
        );
        self::$shopify_api_406 = __(
            '<b>406 Error:</b> The requested resource contained the wrong HTTP method or an invalid URL. ',
            'wpshopify'
        );
        self::$shopify_api_422 = __(
            '<b>422 Error:</b> The request body was well-formed but contains semantical errors. ',
            'wpshopify'
        );

        self::$api_invalid_endpoint = __(
            '<p class="wps-syncing-error-message"><b>400 Error:</b> The request endpoint was mal-formed.</p>',
            'wpshopify'
        );

        self::$syncing_docs_check = __(
            '<p class="wps-syncing-docs-check">🔮 Please check our documentation for <a href="https://docs.wpshop.io/#/getting-started/common-issues?id=syncing-issues?utm_medium=plugin&utm_source=notice&utm_campaign=help" target="_blank">possible solutions to this specific error.</a></p>',
            'wpshopify'
        );

        self::$max_post_body_size = __(
            '<p class="wps-syncing-error-message"><b>413 Error:</b> The Shopify data is too large for your server to handle.</p>',
            'wpshopify'
        );

        self::$shopify_api_429 = __(
            '<b>429 Error:</b> The request was not accepted because the application has exceeded the rate limit. See the API Call Limit documentation for a breakdown of Shopify\'s rate-limiting mechanism.',
            'wpshopify'
        );
        self::$shopify_api_500 = __(
            '<b>500 Error:</b> An internal error occurred at Shopify. ',
            'wpshopify'
        );
        self::$shopify_api_501 = __(
            '<b>501 Error:</b> The requested endpoint is not available on that particular shop. ',
            'wpshopify'
        );
        self::$shopify_api_503 = __(
            '<b>503 Error:</b> The server is currently unavailable. Check the Shopify <a href="https://status.shopify.com/" target="_blank">status page</a> for reported service outages. ',
            'wpshopify'
        );
        self::$shopify_api_504 = __(
            '<b>504 Error:</b> The request could not complete in time. ',
            'wpshopify'
        );
        self::$shopify_api_generic = __(
            '<b>Error:</b> An unknown Shopify API response was received during syncing. Please try disconnecting and reconnecting your store. ',
            'wpshopify'
        );
        self::$invalid_server_connection = __(
            '<b>521 Error:</b> Unable to establish an active connection with the web server. ',
            'wpshopify'
        );
        self::$syncing_status_update_failed = __(
            'Failed to update sync status during the syncing process. Please clear the plugin transient cache and try again. ',
            'wpshopify'
        );

        /*

		Missing data warnings during page batch requests

		*/
        self::$missing_collects_for_page = __(
            '<b>Warning:</b> Some collects were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resync.',
            'wpshopify'
        );

        self::$missing_media_for_page = __(
            '<b>Warning:</b> Some media files were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resync.',
            'wpshopify'
        );

        self::$missing_products_for_page = __(
            '<b>Warning:</b> Some products were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resync.',
            'wpshopify'
        );

        self::$missing_product_ids = __(
            '<b>Warning:</b> Some product ids were possibly missing during the syncing process. If you notice any absent content, try clearing the plugin cache and resync.',
            'wpshopify'
        );

        self::$missing_shop_for_page = __(
            '<b>Warning:</b> Some general shop data was possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resync.',
            'wpshopify'
        );
        self::$missing_orders_for_page = __(
            '<b>Warning:</b> Some orders were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resync.',
            'wpshopify'
        );
        self::$missing_customers_for_page = __(
            '<b>Warning:</b> Some customers were possibly missed during the syncing process. If you notice any absent content, try clearing the plugin cache and resync.',
            'wpshopify'
        );
        self::$missing_smart_collections_for_page = __(
            '<b>Warning:</b> Some smart collections were possibly missed during the syncing process. Please double check that all your collections are showing correctly.',
            'wpshopify'
        );

        self::$missing_custom_collections_for_page = __(
            '<b>Warning:</b> Some collections were possibly missed during the syncing process. Please double check that all your collections are showing correctly.',
            'wpshopify'
        );

        self::$missing_webhooks_for_page = __(
            '<b>Warning:</b> Some webhooks were possibly missed during the syncing process. If you notice any content not syncing automatically, try using the "Reconnect Automatic Post Syncing" tool.',
            'wpshopify'
        );

        /*

		Syncing related

		*/
        self::$missing_shopify_domain = __(
            'Please make sure you\'ve entered your Shopify domain.',
            'wpshopify'
        );

        /*

		Server-related errors

		*/
        self::$max_allowed_packet = __(
            '<b>Database Error:</b> The data you\'re trying to sync is too large for the database to handle. Try adjusting the "Items per request" option within the plugin settings.',
            'wpshopify'
        );

        self::$max_memory_exceeded = __(
            '<p class="wps-syncing-error-message"><b>Server Error:</b> The maximum amount of server memory was exceeded.</p>',
            'wpshopify'
        );

        self::$migration_table_creation_error = __(
            '<p class="wps-syncing-error-message"><b>Database Error:</b> Unable to create migration table.</p>',
            'wpshopify'
        );

        self::$migration_table_already_exists = __(
            '<p class="wps-syncing-error-message"><b>Database Error:</b> Unable to create migration table as it already exists.</p>',
            'wpshopify'
        );

        self::$charset_not_found = __(
            '<p class="wps-syncing-error-message"><b>Database Error:</b> Unable to find charset for table:</p>',
            'wpshopify'
        );

        self::$unable_to_convert_to_object = __(
            '<p class="wps-syncing-error-message"><b>Type Error:</b> Unabled to convert data type to Object.</p>',
            'wpshopify'
        );

        self::$unable_to_convert_to_array = __(
            '<p class="wps-syncing-error-message"><b>Type Error:</b> Unabled to convert data type to Array.</p>',
            'wpshopify'
        );

        self::$request_url_not_found = __(
            '<p class="wps-syncing-error-message"><b>HTTP Error:</b> Request URL not found.</p>',
            'wpshopify'
        );

        /*

		New Messages

		*/
        self::$smart_collections_count_not_found = __(
            '<b>Warning:</b> No Smart Collections were found during sync.',
            'wpshopify'
        );

        self::$custom_collections_count_not_found = __(
            '<b>Warning:</b> No Custom Collections were found during sync.',
            'wpshopify'
        );

        self::$shop_count_not_found = __(
            '<b>Warning:</b> No Shop data was found during sync.',
            'wpshopify'
        );

        self::$products_count_not_found = __(
            '<b>Warning:</b> No Products were found during sync.',
            'wpshopify'
        );

        self::$collects_count_not_found = __(
            '<b>Warning:</b> No Collects were found during sync.',
            'wpshopify'
        );

        self::$orders_count_not_found = __(
            '<b>Warning:</b> No Orders were found during sync.',
            'wpshopify'
        );

        self::$customers_count_not_found = __(
            '<b>Warning:</b> No Customers were found during sync.',
            'wpshopify'
        );

        self::$failed_to_set_post_id_custom_table = __(
            '<b>Warning:</b> Failed to assign Post ID  ',
            'wpshopify'
        );

        self::$failed_to_set_lookup_key_post_meta_table = __(
            '<b>Warning:</b> Failed to assign Shopify ID ',
            'wpshopify'
        );

        self::$wp_cron_disabled = __(
            '<b>Error:</b> The WordPress Cron is disabled.',
            'wpshopify'
        );

        self::$failed_to_find_batch = __(
            '<b>Error:</b> Failed to save batch during processing. ',
            'wpshopify'
        );
    }
}
