<?php

namespace WP_Shopify;

use WP_Shopify\Utils;
use WP_Shopify\Utils\Data as Utils_Data;

if (!defined('ABSPATH')) {
    exit();
}

class Shopify_API extends \WP_Shopify\HTTP
{
    public function __construct($DB_Settings_Connection)
    {
        parent::__construct($DB_Settings_Connection);
    }

    public function base_uri()
    {
        return '/admin/api/' . apply_filters('wps_admin_rest_api_version', WP_SHOPIFY_ADMIN_REST_API_VERSION);
    }

    public function endpoint_products()
    {
        return $this->base_uri() . "/products.json";
    }

    public function endpoint_product_listings_count()
    {
        return $this->base_uri() . "/product_listings/count.json"; // works on smart collections
    }

    public function endpoint_product_listings_product_ids()
    {
        return $this->base_uri() . "/product_listings/product_ids.json";
    }

    public function endpoint_webhooks()
    {
        return $this->base_uri() . "/webhooks.json";
    }

    public function endpoint_webhooks_single($webhook_id)
    {
        return $this->base_uri() . "/webhooks/" . $webhook_id . ".json";
    }

    public function endpoint_shop()
    {
        return $this->base_uri() . "/shop.json";
    }

    public function endpoint_storefront_access_tokens()
    {
        return $this->base_uri() . "/storefront_access_tokens.json";
    }

    public function endpoint_orders()
    {
        return $this->base_uri() . "/orders.json";
    }

    public function endpoint_orders_count()
    {
        return $this->base_uri() . "/orders/count.json";
    }

    public function endpoint_customers()
    {
        return $this->base_uri() . "/customers.json";
    }

    public function endpoint_customers_count()
    {
        return $this->base_uri() . "/customers/count.json";
    }

    public function endpoint_collects()
    {
        return $this->base_uri() . "/collects.json";
    }

    public function endpoint_collects_count()
    {
        return $this->base_uri() . "/collects/count.json";
    }

    public function endpoint_smart_collections()
    {
        return $this->base_uri() . "/smart_collections.json";
    }

    public function endpoint_smart_collections_count()
    {
        return $this->base_uri() . "/smart_collections/count.json";
    }

    public function endpoint_custom_collections()
    {
        return $this->base_uri() . "/custom_collections.json";
    }

    public function endpoint_custom_collections_count()
    {
        return $this->base_uri() . "/custom_collections/count.json";
    }

    public function endpoint_products_from_collection($collection_id)
    {
        return $this->base_uri() .
            "/collections/" .
            $collection_id .
            "/products.json";
    }

    /*

	Params

	*/
    public function param_limit($limit)
    {
        return "limit=" . $limit;
    }

    public function param_page($page)
    {
        return "page=" . $page;
    }

    public function param_product_id($product_id)
    {
        return "product_id=" . $product_id;
    }

    public function param_collection_id($collection_id)
    {
        return "collection_id=" . $collection_id;
    }

    public function param_status($status)
    {
        return "status=" . $status;
    }

    public function param_ids($ids)
    {
        return "ids=" . $ids;
    }

    /*

	Gets products per page

	*/
    public function get_products_per_page($product_ids, $limit)
    {
        return $this->get(
            $this->endpoint_products(),
            '?' .
                $this->param_ids($product_ids) .
                '&' .
                $this->param_limit($limit)
        );
    }

    /*

	Gets products per page

	*/
    public function get_collects_per_page($limit)
    {
        return $this->get(
            $this->endpoint_collects(),
            '?' . $this->param_limit($limit)
        );
    }

    public function page_link_has_previous($page_link)
    {
        return Utils::str_contains($page_link, 'rel="previous"');
    }

    public function page_link_has_next($page_link)
    {
        return Utils::str_contains($page_link, 'rel="previous"');
    }

    public function split_page_link_by_previous($string)
    {
        return \explode('rel="previous"', $string)[1];
    }

    public function split_page_link_by_next($string)
    {
        return \explode('rel="next"', $string)[0];
    }

    public function sanitize_next_page_link_from_header($page_link)
    {
        if ($this->page_link_has_previous($page_link)) {
            if ($this->page_link_has_next($page_link)) {
                $prev_split = $this->split_page_link_by_previous($page_link);

                return Utils::get_string_between($prev_split, '<', '>');
            } else {
                return false; // No additional pages
            }
        }

        $next_split = $this->split_page_link_by_next($page_link);

        return Utils::get_string_between($next_split, '<', '>');
    }

    /*

	Gets products listings per page

	*/
    public function get_products_listing_product_ids_per_page(
        $page_link = false,
        $limit = 250
    ) {
        return $this->with_pagination($page_link, function () use ($limit) {
            return $this->get(
                $this->endpoint_product_listings_product_ids(),
                '?' . $this->param_limit($limit)
            );
        });
    }



    /*

	Gets smart collections per page

	*/
    public function get_smart_collections_per_page($page_link = false, $limit = 250)
    {
         return $this->with_pagination($page_link, function () use ($limit) {
            return $this->get(
                  $this->endpoint_smart_collections(),
                  '?' . $this->param_limit($limit)
            );
         });
    }

    /*

	Gets custom collections per page

	*/
    public function get_custom_collections_per_page($page_link = false, $limit = 250)
    {
        return $this->with_pagination($page_link, function () use ($limit) {
            return $this->get(
                  $this->endpoint_custom_collections(),
                  '?' . $this->param_limit($limit)
            );
         });
    }    

    public function has_pagination($response)
    {
        if (\wp_remote_retrieve_header($response, 'link')) {
            return true;
        }

        return false;
    }

    public function get_pagination_link($response)
    {
        return \wp_remote_retrieve_header($response, 'link');
    }

    /*

	Gets products listings per page

	*/
    public function get_products_listing_product_ids_by_collection_id_per_page(
        $collection_id,
        $limit = 250,
        $page_link = false
    ) {
        return $this->with_pagination($page_link, function () use (
            $collection_id,
            $limit
        ) {
            return $this->get(
                $this->endpoint_product_listings_product_ids(),
                '?' .
                    $this->param_collection_id($collection_id) .
                    '&' .
                    $this->param_limit($limit)
            );
        });
    }

    /*

	Gets products from collection id per page

	*/
    public function get_products_from_collection_per_page(
        $collection_id,
        $limit
    ) {
        return $this->get(
            $this->endpoint_products(),
            '?' .
                $this->param_collection_id($collection_id) .
                '&' .
                $this->param_limit($limit)
        );
    }

    /*

	Gets collects from collection id per page

	*/
    public function get_collects_from_collection_per_page(
        $collection_id,
        $limit
    ) {
        return $this->get(
            $this->endpoint_collects(),
            '?' .
                $this->param_collection_id($collection_id) .
                '&' .
                $this->param_limit($limit)
        );
    }

    /*

	Gets products listing count

	*/
    public function get_product_listings_count()
    {
        return $this->get($this->endpoint_product_listings_count());
    }

    /*

	Gets products listing count by collection id

	*/
    public function get_product_listings_count_by_collection_id($collection_id)
    {
        return $this->get(
            $this->endpoint_product_listings_count(),
            '?' . $this->param_collection_id($collection_id)
        );
    }

    /*

	Gets webhooks

	*/
    public function get_webhooks()
    {
        return $this->get($this->endpoint_webhooks());
    }

    /*

	Registers a single webhook

	*/
    public function register_webhook($webhook_body)
    {
        return $this->post($this->endpoint_webhooks(), $webhook_body);
    }

    /*

	Deletes a single webhook by webhook id

	*/
    public function delete_webhook($webhook_id)
    {
        return $this->delete($this->endpoint_webhooks_single($webhook_id));
    }

    /*

	Gets shop

	*/
    public function get_shop()
    {
        return $this->get($this->endpoint_shop());
    }

    /*

	Gets storefront access tokens

	*/
    public function get_storefront_access_tokens()
    {
        return $this->get($this->endpoint_storefront_access_tokens());
    }

    /*

	Gets orders per page

	*/
    public function get_orders_per_page($limit, $current_page, $status)
    {
        return $this->get(
            $this->endpoint_orders(),
            '?' .
                $this->param_limit($limit) .
                '&' .
                $this->param_status($status)
        );
    }

    /*

	Gets orders count

	*/
    public function get_orders_count($status)
    {
        return $this->get(
            $this->endpoint_orders_count(),
            '?' . $this->param_status($status)
        );
    }

    /*

	Gets customers per page

	*/
    public function get_customers_per_page($limit, $status)
    {
        return $this->get(
            $this->endpoint_customers(),
            '?' .
                $this->param_limit($limit) .
                '&' .
                $this->param_status($status)
        );
    }

    /*

	Gets customers count

	*/
    public function get_customers_count()
    {
        return $this->get($this->endpoint_customers_count());
    }

    /*

	Gets collects count

	*/
    public function get_collects_count()
    {
        return $this->get($this->endpoint_collects_count());
    }

    /*

	Gets collects by product id

	*/
    public function get_custom_collects_by_product_id($product_id)
    {

        return $this->get(
            $this->endpoint_collects(),
            '?' . $this->param_product_id($product_id)
        );
    }

    /*

	Gets collects from collection id

	*/
    public function get_collects_from_collection_id($collection_id)
    {
        return $this->get(
            $this->endpoint_collects(),
            '?' . $this->param_collection_id($collection_id)
        );
    }

    /*

	Gets collects count from collection id

	*/
    public function get_collects_count_by_collection_id($collection_id)
    {
        return $this->get(
            $this->endpoint_collects_count(),
            '?' . $this->param_collection_id($collection_id)
        );
    }

    /*

	Gets smart collections count

	*/
    public function get_smart_collections_count()
    {
        return $this->get($this->endpoint_smart_collections_count());
    }

    



    /*

	Gets custom collections count

	*/
    public function get_custom_collections_count()
    {
        return $this->get($this->endpoint_custom_collections_count());
    }

    function with_pagination($page_link, $fn)
    {
        if ($page_link) {
            $page_link_usable = $this->sanitize_next_page_link_from_header(
                $page_link
            );

            if (!$page_link_usable) {
                return false;
            }

            return $this->get($page_link_usable);
        }

        return $fn();
    }

    /*

	Gets custom collections count

	*/
    public function get_products_from_collection(
        $collection_id,
        $limit,
        $page_link = false
    ) {
        return $this->with_pagination($page_link, function () use (
            $collection_id,
            $limit
        ) {
            return $this->get(
                $this->endpoint_products_from_collection($collection_id),
                '?' . $this->param_limit($limit)
            );
        });
    }

    /*

	Takes an array of ids such as ...

	[1,2,3,4,5,6,7,8,9,10]

	And turns it into a URL param like ...

	'1,2,3,4,5'
	'6,7,8,9,10'

	*/
    public function create_param_ids($ids, $items_per_chunk, $current_page)
    {
        return Utils_Data::array_to_comma_string(
            Utils_Data::current_index_value_less_one(
                Utils_Data::chunk_data($ids, $items_per_chunk),
                $current_page
            )
        );
    }

    /*

	Normalize the product API responses

	*/
    public function normalize_products_response($response)
    {
        if (is_array($response)) {
            return $response;
        }

        if (is_object($response) && property_exists($response, 'products')) {
            return $response->products;
        }

        if (
            is_object($response) &&
            property_exists($response, 'product_listings')
        ) {
            return $response->product_listings;
        }
    }

    // Kudos: https://subinsb.com/php-check-if-string-is-html/s
    public function is_html($maybe_html)
    {
        $maybe_html_copy = $maybe_html;

        return $maybe_html_copy != strip_tags($maybe_html_copy) ? true : false;
    }

    public function pre_response_check($response)
    {
        if (is_wp_error($response)) {
            return $response;
        } else {
            return $this->sanitize_response($response['body']);
        }
    }

    public function sanitize_response($items)
    {
        if (is_wp_error($items) || empty($items) || !$items) {
            return $items;
        }

        foreach ($items as $key => $value) {
            if (is_object($value) || is_array($value)) {
                $this->sanitize_response($value);
            } else {
                if (is_string($value)) {
                    if ($this->is_html($value)) {
                        $value = wp_kses_post($value);
                    } else {
                        $value = sanitize_text_field($value);
                    }

                    // Save the sanitized data back
                    if (is_array($items)) {
                        $items[$key] = $value;
                    } elseif (is_object($items)) {
                        $items->$key = $value;
                    }
                }
            }
        }

        return $items;
    }
}
