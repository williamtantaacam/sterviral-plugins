<?php

namespace WP_Shopify\API\Tools;

use WP_Shopify\Messages;
use WP_Shopify\Options;

if (!defined('ABSPATH')) {
    exit();
}

class Cache extends \WP_Shopify\API
{
    public function __construct($DB_Settings_Syncing)
    {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
    }

    public function delete_cache($request)
    {
        return $this->DB_Settings_Syncing->expire_sync();
    }

    public function toggle_cache_clear($request)
    {
        return Options::update('wp_shopify_cache_cleared', false);
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_tools_delete_cache()
    {
        return register_rest_route(WP_SHOPIFY_SHOPIFY_API_NAMESPACE, '/cache', [
            [
                'methods' => \WP_REST_Server::CREATABLE,
                'callback' => [$this, 'delete_cache'],
                'permission_callback' => [$this, 'pre_process'],
            ],
        ]);
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_tools_cache_toggle()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/cache/toggle',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'toggle_cache_clear'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [
            $this,
            'register_route_tools_delete_cache',
        ]);
        add_action('rest_api_init', [
            $this,
            'register_route_tools_cache_toggle',
        ]);
    }
}
