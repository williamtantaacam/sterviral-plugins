<?php

namespace WP_Shopify\API\Syncing;

use WP_Shopify\Messages;

if (!defined('ABSPATH')) {
    exit();
}

class Status extends \WP_Shopify\API
{
    public $DB_Settings_Syncing;

    public function __construct($DB_Settings_Syncing)
    {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
    }

    /*

	Update setting: add_to_cart_color

	*/
    public function get_syncing_status($request)
    {
        return [
            'is_syncing' => $this->DB_Settings_Syncing->is_syncing(),
            'syncing_totals' => $this->DB_Settings_Syncing->syncing_totals(),
            'syncing_current_amounts' => $this->DB_Settings_Syncing->syncing_current_amounts(),
            'has_fatal_errors' => $this->DB_Settings_Syncing->has_fatal_errors(),
            'recently_syncd_media_ref' => $this->DB_Settings_Syncing->get_recently_syncd_media_ref(),
        ];
    }

    public function get_syncing_status_webhooks($request)
    {
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            return true;
        }

        return $this->handle_response(
            $this->DB_Settings_Syncing->get_col_value(
                'finished_webhooks_deletions',
                'bool'
            )
        );
    }

    public function get_syncing_status_removal($request)
    {
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            return true;
        }

        return $this->handle_response(
            $this->DB_Settings_Syncing->get_col_value(
                'finished_data_deletions',
                'bool'
            )
        );
    }

    public function get_syncing_status_media($request)
    {
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            return true;
        }

        return $this->handle_response(
            $this->DB_Settings_Syncing->get_col_value('finished_media', 'bool')
        );
    }

    // Fires once the syncing process stops
    public function get_syncing_notices($request)
    {
        return $this->handle_response(
            $this->DB_Settings_Syncing->syncing_notices()
        );
    }

    // Fires once the syncing process stops
    public function delete_syncing_notices($request)
    {
        return $this->handle_response(
            $this->DB_Settings_Syncing->reset_syncing_notices()
        );
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_syncing_status()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/syncing/status',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_syncing_status'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_syncing_status_webhooks()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/syncing/status/webhooks',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_syncing_status_webhooks'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_syncing_status_removal()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/syncing/status/removal',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_syncing_status_removal'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_syncing_status_media()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/syncing/status/media',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_syncing_status_media'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_syncing_stop()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/syncing/stop',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'expire_sync'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_syncing_notices()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/syncing/notices',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_syncing_notices'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'delete_syncing_notices'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Stops syncing

	*/
    public function expire_sync()
    {

        $result = $this->handle_response([
            'response' => $this->DB_Settings_Syncing->expire_sync(),
        ]);

        do_action('wpshopify_processing_completed');
        update_option('wpshopify_should_flush_rewrite_rules', true);

        return $result;
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_syncing_status']);

        add_action('rest_api_init', [
            $this,
            'register_route_syncing_status_webhooks',
        ]);
        add_action('rest_api_init', [
            $this,
            'register_route_syncing_status_removal',
        ]);
        add_action('rest_api_init', [
            $this,
            'register_route_syncing_status_media',
        ]);
        add_action('rest_api_init', [$this, 'register_route_syncing_stop']);
        add_action('rest_api_init', [$this, 'register_route_syncing_notices']);
    }
}
