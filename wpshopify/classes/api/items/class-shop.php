<?php

namespace WP_Shopify\API\Items;

use WP_Shopify\Messages;
use WP_Shopify\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class Shop extends \WP_Shopify\API
{
    public function __construct($Shopify_API, $Processing_Shop)
    {
        $this->Shopify_API = $Shopify_API;
        $this->Processing_Shop = $Processing_Shop;
    }

    /*

	Get Smart Collections Count

	Nonce checks are handled automatically by WordPress

	*/
    public function get_shop_count($request)
    {
        return [
            'shop' => 1,
        ];
    }

    /*

	Get smart collections

	Nonce checks are handled automatically by WordPress

	*/
    public function get_shop($request)
    {
        $response = $this->Shopify_API->get_shop();

        return $this->handle_response([
            'response' => $this->Shopify_API->pre_response_check($response),
            'access_prop' => 'shop',
            'return_key' => 'shop',
            'warning_message' => 'shop_count_not_found',
            'process_fns' => [$this->Processing_Shop],
        ]);
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_shop_count()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/shop/count',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_shop_count'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_shop()
    {
        return register_rest_route(WP_SHOPIFY_SHOPIFY_API_NAMESPACE, '/shop', [
            [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [$this, 'get_shop'],
                'permission_callback' => [$this, 'pre_process'],
            ],
        ]);
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_shop_count']);
        add_action('rest_api_init', [$this, 'register_route_shop']);
    }
}
