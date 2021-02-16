<?php

namespace WP_Shopify\API\Items;

use WP_Shopify\Messages;
use WP_Shopify\Utils;
use WP_Shopify\Utils\Data as Utils_Data;

if (!defined('ABSPATH')) {
    exit();
}

class Collects extends \WP_Shopify\API
{
    public function __construct(
        $DB_Settings_General,
        $DB_Settings_Syncing,
        $Shopify_API,
        $Processing_Collects
    ) {
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->Shopify_API = $Shopify_API;
        $this->Processing_Collects = $Processing_Collects;
    }

    /*

	Responsible for getting an array of API endpoints for given collection ids

	*/
    public function get_collects_count_by_collection_ids()
    {
        $collects_count = [];
        $collections = $this->DB_Settings_General->get_sync_by_collections_ids();
        $errors = [];

        foreach ($collections as $collection) {
            if ($collection['smart']) {
                continue;
            }

            $response = $this->Shopify_API->get_collects_count_by_collection_id(
                $collection['id']
            );

            if (is_wp_error($response)) {
                $errors[] = $this->handle_response(['response' => $response]);
            } else {
                $response_body = $this->Shopify_API->sanitize_response(
                    $response['body']
                );

                if (Utils::has($response_body, 'count')) {
                    $collects_count[] = $response_body->count;
                }
            }
        }

        // if ($errors) {
        //    return $errors;
        // }

        return Utils::convert_array_to_object([
            'count' => array_sum($collects_count),
        ]);
    }

    /*

	Get Collections Count

	*/
    public function get_collects_count($request)
    {
        // User is syncing by collection
        if ($this->DB_Settings_General->is_syncing_by_collection()) {
            return $this->handle_response([
                'response' => $this->get_collects_count_by_collection_ids(),
                'access_prop' => 'count',
                'return_key' => 'collects',
                'warning_message' => 'collects_count_not_found',
            ]);
        }

        $response = $this->Shopify_API->get_collects_count();

        return $this->handle_response([
            'response' => $this->Shopify_API->pre_response_check($response),
            'access_prop' => 'count',
            'return_key' => 'collects',
            'warning_message' => 'collects_count_not_found',
        ]);
    }

    public function normalize_collects_response($response)
    {
        if (is_array($response)) {
            return $response;
        }

        if (is_object($response) && property_exists($response, 'collects')) {
            return $response->collects;
        }
    }

    public function get_collects_from_collections()
    {
        $collects = [];
        $collections = maybe_unserialize(
            $this->DB_Settings_General->sync_by_collections()
        );

        $errors = [];
        $limit = $this->DB_Settings_General->get_items_per_request();

        foreach ($collections as $collection) {
            if ($collection['smart']) {
                continue;
            }

            $response = $this->Shopify_API->get_collects_from_collection_per_page(
                $collection['id'],
                $limit
            );

            if (is_wp_error($response)) {
                $errors[] = $response;
            } else {
                $response_body = $this->Shopify_API->sanitize_response(
                    $response['body']
                );
                $result = $this->normalize_collects_response($response_body);

                $collects = array_merge($collects, $result);
            }
        }

        return Utils::convert_array_to_object([
            'collects' => $collects,
        ]);
    }

    public function get_collects_per_page()
    {
        $limit = $this->DB_Settings_General->get_items_per_request();
        $response = $this->Shopify_API->get_collects_per_page($limit);

        return $this->Shopify_API->pre_response_check($response);
    }

    /*

	Get Collects

	Runs for each "page" of the Shopify API

	*/
    public function get_collects($request)
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

        $page = sanitize_text_field($page);

        // Check if user is syncing from collections -- returns proper products
        if ($this->DB_Settings_General->is_syncing_by_collection()) {
            $response = $this->get_collects_from_collections();
        } else {
            $response = $this->get_collects_per_page();
        }

        return $this->handle_response([
            'response' => $response,
            'access_prop' => 'collects',
            'return_key' => 'collects',
            'warning_message' => 'missing_collects_for_page',
            'process_fns' => [$this->Processing_Collects],
        ]);
    }

    /*

	Responsible for adding collects to $data

	*/
    public function add_collects_to_item($item, $collects)
    {
        $item->collects = $collects;

        return $item;
    }

    public function find_collects_to_add($collects)
    {
        if (is_wp_error($collects) || Utils::object_is_empty($collects)) {
            $collects_to_add = [];
        } else {
            $collects_to_add = $collects->collects;
        }

        return $collects_to_add;
    }

    /*

	Get a list of collects by product ID

	*/
    public function get_collects_from_product($item = null)
    {
        $response = $this->Shopify_API->get_custom_collects_by_product_id($item->id);

        if (is_wp_error($response)) {
            return $response;
        }

        $response_body = $this->Shopify_API->sanitize_response(
            $response['body']
        );

        return $this->add_collects_to_item(
            $item,
            $this->find_collects_to_add($response_body)
        );
    }

    /*

	Get a list of collects by collection ID

	*/
    public function get_collects_from_collection($item)
    {
        $response = $this->Shopify_API->get_collects_from_collection_id(
            $item->id
        );

        if (\is_wp_error($response)) {
            return $response;
        }

        $collects = $this->Shopify_API->sanitize_response($response['body']);

        return $this->add_collects_to_item(
            $item,
            $this->find_collects_to_add($collects)
        );
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_collects_count()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/collects/count',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_collects_count'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_collects()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/collects',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_collects'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_collects_count']);
        add_action('rest_api_init', [$this, 'register_route_collects']);
    }
}
