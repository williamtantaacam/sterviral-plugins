<?php

namespace WP_Shopify\API\Items;

use WP_Shopify\Messages;
use WP_Shopify\Utils;
use WP_Shopify\Utils\Data as Utils_Data;
use WP_Shopify\CPT;

if (!defined('ABSPATH')) {
    exit();
}

class Products extends \WP_Shopify\API
{
    public function __construct(
        $DB_Settings_General,
        $DB_Settings_Syncing,
        $DB_Tags,
        $DB_Products,
        $Shopify_API,
        $Processing_Products,
        $Processing_Variants,
        $Processing_Tags,
        $Processing_Options,
        $Processing_Images,
        $Admin_API_Variants
    ) {
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->DB_Tags = $DB_Tags;
        $this->DB_Products = $DB_Products;

        $this->Shopify_API = $Shopify_API;

        $this->Processing_Products = $Processing_Products;
        $this->Processing_Variants = $Processing_Variants;
        $this->Processing_Tags = $Processing_Tags;
        $this->Processing_Options = $Processing_Options;
        $this->Processing_Images = $Processing_Images;

        $this->Admin_API_Variants = $Admin_API_Variants;
    }

    /*

	Responsible for getting the total product count per an array of collection ids

	*/
    public function get_product_listings_count_by_collection_ids()
    {
        $products_count = [];
        $collections = $this->DB_Settings_General->get_sync_by_collections_ids();
        $errors = false;

        foreach ($collections as $collection) {
            $response = $this->Shopify_API->get_product_listings_count_by_collection_id(
                $collection['id']
            );

            if (is_wp_error($response)) {
                $errors = $response;
                break;
            }

            $response_body = $this->Shopify_API->sanitize_response(
                $response['body']
            );

            if (Utils::has($response_body, 'count')) {
                $products_count[] = $response_body->count;
            }
        }

        if ($errors) {
            return $errors;
        }

        return Utils::convert_array_to_object([
            'count' => array_sum($products_count),
        ]);
    }

    /*

	Get Products Count

	*/
    public function get_products_count($request)
    {
        /*

		If user is syncing by collections, then instead of getting the total
		number of products we need to get the total number of products
		assigned to all selected collections.

		*/
        if ($this->DB_Settings_General->is_syncing_by_collection()) {
            return $this->handle_response([
                'response' => $this->get_product_listings_count_by_collection_ids(),
                'access_prop' => 'count',
                'return_key' => 'products',
                'warning_message' => 'products_count_not_found',
            ]);
        }

        $response = $this->Shopify_API->get_product_listings_count();

        return $this->handle_response([
            'response' => $this->Shopify_API->pre_response_check($response),
            'access_prop' => 'count',
            'return_key' => 'products',
            'warning_message' => 'products_count_not_found',
        ]);
    }

    /*

	Responsible for getting an array of product ids from a single collection id

	Using Recursion

	*/
    public function get_product_ids_by_collection_id(
        $collection_id,
        $page_link = false,
        $limit,
        $combined_product_ids = []
    ) {
        $response = $this->Shopify_API->get_products_listing_product_ids_by_collection_id_per_page(
            $collection_id,
            $limit,
            $page_link
        );

        if (is_wp_error($response)) {
            return $response;
        }

        // No additional pages left
        if (!$response) {
            return $combined_product_ids;
        }

        $response_body = $this->Shopify_API->sanitize_response(
            $response['body']
        );

        $new_product_ids = $response_body->product_ids;

        // Save the result in memory
        $combined_product_ids = array_merge(
            $combined_product_ids,
            $new_product_ids
        );

        if (!$this->Shopify_API->has_pagination($response)) {
            return $combined_product_ids;
        }

        $page_link = $this->Shopify_API->get_pagination_link($response);

        return $this->get_product_ids_by_collection_id(
            $collection_id,
            $page_link,
            $limit,
            $combined_product_ids
        );
    }

    /*

	Responsible for getting an array of product ids from a list of collection ids

	*/
    public function get_product_ids_by_collection_ids()
    {
      
        $collections = maybe_unserialize(
            $this->DB_Settings_General->sync_by_collections()
        );
     
        $all_product_ids = [];

        $limit = $this->DB_Settings_General->get_items_per_request();

        foreach ($collections as $collection) {

            $collection_product_ids = $this->get_product_ids_by_collection_id(
                $collection['id'],
                false,
                $limit
            );

            if (is_wp_error($collection_product_ids)) {
                return $collection_product_ids;
            }

            $all_product_ids = array_merge(
                $all_product_ids,
                $collection_product_ids
            );
        }

      /*

      This check is vital. We're checking whether products are assigned to multiple collections or not. If so, we need to update the total count to remove the duplicates otherwise the syncing will never finish.

      */
        $current_totals =
            $this->DB_Settings_Syncing->get_syncing_totals_products() / 6;
        $difference_count = $this->has_duplicates_product_ids(
            $all_product_ids,
            $current_totals
        );

        if ($difference_count) {
            $this->update_total_count_with_duplicates($difference_count);
        }

        return array_unique($all_product_ids);

    }

    public function update_total_count_with_duplicates($new_total_count_to_set)
    {
        return $this->DB_Settings_Syncing->update_column_single(
            ['syncing_totals_products' => $new_total_count_to_set * 6],
            ['id' => 1]
        );
    }

    public function has_duplicates_product_ids(
        $all_product_ids = [],
        $current_totals = 0
    ) {
        if (empty($all_product_ids) || !is_int($current_totals)) {
            return false;
        }

        $num_of_unique_ids = count(array_count_values($all_product_ids));
        $num_of_all_ids = count($all_product_ids);

        if ($num_of_all_ids > $num_of_unique_ids) {
            $difference = $num_of_all_ids - $num_of_unique_ids;

            $new_total_count_to_set = $current_totals - $difference;

            // New totals should never be negative
            if ($new_total_count_to_set < 0) {
                return false;
            }

            return $new_total_count_to_set;
        }

        return false;
    }

    /*

	Getting published product ids

	Using Recursion

	*/
    public function get_product_ids(
        $page_link = false,
        $limit,
        $combined_product_ids = []
    ) {

        if (!$this->DB_Settings_Syncing->is_syncing()) {
            return $combined_product_ids;
        }

        $response = $this->Shopify_API->get_products_listing_product_ids_per_page(
            $page_link,
            $limit
        );

        if (is_wp_error($response)) {
            return $response;
        }

        // No additional pages left
        if (!$response) {
            return $combined_product_ids;
        }

        $response_body = $this->Shopify_API->sanitize_response(
            $response['body']
        );

        $new_product_ids = $response_body->product_ids;
        $new_product_ids_count = count($new_product_ids);

        // Save the result in memory
        $combined_product_ids = array_merge(
            $combined_product_ids,
            $new_product_ids
        );

        if (!$this->Shopify_API->has_pagination($response)) {
            return $combined_product_ids;
        }

        $page_link = $this->Shopify_API->get_pagination_link($response);

        if (empty($page_link)) {
            return $combined_product_ids;
        }        

        return $this->get_product_ids(
            $page_link,
            $limit,
            $combined_product_ids
        );
    }

    public function get_published_product_ids()
    {
        $limit = $this->DB_Settings_General->get_items_per_request();

        // If syncing by collections ...
        if ($this->DB_Settings_General->is_syncing_by_collection()) {
            $response = $this->get_product_ids_by_collection_ids();

        } else {
            $response = $this->get_product_ids(false, $limit);
        }

        // The product ids contained in $response should be unique!
        $this->DB_Settings_Syncing->set_published_product_ids($response);

        return $this->handle_response([
            'response' => $response,
            'warning_message' => 'missing_product_ids',
        ]);
    }

    /*

	Gets published product ids as a URL param string

	*/
    public function get_published_product_ids_as_param($current_page)
    {
        $product_ids = $this->DB_Settings_Syncing->get_published_product_ids();
        $limit = $this->DB_Settings_General->get_items_per_request();

        return $this->Shopify_API->create_param_ids(
            $product_ids,
            $limit,
            $current_page
        );
    }

    /*

	Gets products by page

	*/
    public function get_products_per_page($current_page)
    {
        $product_ids = $this->get_published_product_ids_as_param($current_page);

        $limit = $this->DB_Settings_General->get_items_per_request();

        $response = $this->Shopify_API->get_products_per_page(
            $product_ids,
            $limit
        );

        return $this->Shopify_API->pre_response_check($response);
    }

    public function get_all_products_tags()
    {
        return [
            'tags' => $this->DB_Tags->get_unique_tags(),
        ];
    }

    public function get_all_products_vendors()
    {
        return [
            'vendors' => $this->DB_Products->get_unique_vendors(),
        ];
    }

    public function get_all_products_types()
    {
        return [
            'types' => $this->DB_Products->get_unique_types(),
        ];
    }

    public function get_variant_inventory_management($data)
    {
        $variant_ids = $data['ids'];

        if (empty($data) || empty($data['ids'])) {
            return $this->handle_response();
        }

        $response = $this->Admin_API_Variants->get_variants_inventory_tracked(
            $data['ids']
        );

        if (is_wp_error($response)) {
            return $this->handle_response([
                'response' => Utils::wp_error($response->get_error_message()),
            ]);
        }

        if (empty($response) || !property_exists($response, 'nodes')) {
            return [];
        }

        return array_filter($response->nodes);
    }

    public function meta_info()
    {
        return [
            'post_type' => WP_SHOPIFY_PRODUCTS_POST_TYPE_SLUG,
            'increment_name' => 'products',
        ];
    }

    public function get_process_fns()
    {
        return [
            $this->Processing_Products,
            $this->Processing_Variants,
            $this->Processing_Tags,
            $this->Processing_Options,
            $this->Processing_Images,
        ];
    }

    /*

	Get Bulk Products

	Runs for each "page" of the Shopify API

	Doesn't save error to DB -- returns to client instead

	*/
    public function get_products($request)
    {
        $page = $request->get_param('page');

        if (!is_integer($page)) {
            return $this->handle_response([
                'response' => Utils::wp_error([
                    'message_lookup' => 'Page is not of type integer',
                    'call_method' => __METHOD__,
                    'call_line' => __LINE__,
                ]),
            ]);
        }

        $response = $this->get_products_per_page($page);

        if (is_wp_error($response)) {
            return $this->handle_response([
                'response' => Utils::wp_error([
                    'message_lookup' => $response->get_error_message(),
                    'call_method' => __METHOD__,
                    'call_line' => __LINE__,
                ]),
            ]);
        }

        $response->products = CPT::add_props_to_items(
            $response->products,
            $this->meta_info()
        );

        return $this->handle_response([
            'response' => $response,
            'access_prop' => 'products',
            'return_key' => 'products',
            'warning_message' => 'missing_products_for_page',
            'meta' => $this->meta_info(),
            'process_fns' => $this->get_process_fns(),
        ]);
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_products_ids()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/products/ids',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_published_product_ids'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_products_count()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/products/count',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_products_count'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_products()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/products',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_products'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_products_all_tags()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/products/tags',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_all_products_tags'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

   Register route: cart_icon_color

   */
    public function register_route_products_all_vendors()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/products/vendors',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_all_products_vendors'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

   Register route: cart_icon_color

   */
    public function register_route_products_all_types()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/products/types',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_all_products_types'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

   Register route: cart_icon_color

   */
    public function register_route_get_variant_inventory_management()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/products/variants/inventory_management',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_variant_inventory_management'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_products']);
        add_action('rest_api_init', [$this, 'register_route_products_ids']);
        add_action('rest_api_init', [$this, 'register_route_products_count']);
        add_action('rest_api_init', [
            $this,
            'register_route_products_all_tags',
        ]);
        add_action('rest_api_init', [
            $this,
            'register_route_products_all_vendors',
        ]);
        add_action('rest_api_init', [
            $this,
            'register_route_products_all_types',
        ]);

        add_action('rest_api_init', [
            $this,
            'register_route_get_variant_inventory_management',
        ]);
    }
}
