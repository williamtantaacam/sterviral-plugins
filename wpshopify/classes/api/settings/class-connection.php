<?php

namespace WP_Shopify\API\Settings;

if (!defined('ABSPATH')) {
    exit();
}

use WP_Shopify\Messages;
use WP_Shopify\Utils;
use WP_Shopify\Utils\Data;

class Connection extends \WP_Shopify\API
{
    public $DB_Settings_Connection;
    public $DB_Settings_General;
    public $DB_Settings_Syncing;
    public $Shopify_API;

    public function __construct(
        $DB_Settings_Connection,
        $DB_Settings_General,
        $DB_Settings_Syncing,
        $Shopify_API
    ) {
        $this->DB_Settings_Connection = $DB_Settings_Connection;
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->Shopify_API = $Shopify_API;
    }

    public function only_valid_storefront_access_tokens(
        $access_token,
        $user_entered_token
    ) {
        return $access_token->access_token === $user_entered_token;
    }

    public function valid_storefront_access_tokens(
        $storefront_access_tokens,
        $user_entered_token
    ) {
        return array_filter($storefront_access_tokens, function (
            $access_token
        ) use ($user_entered_token) {
            return $this->only_valid_storefront_access_tokens(
                $access_token,
                $user_entered_token
            );
        });
    }

    /*

	Mask a connection

	*/
    public function mask_connection($request)
    {
        return $this->handle_response([
            'response' => $this->DB_Settings_Connection->mask_connection(),
        ]);
    }

    /*

	Deletes a connection

	*/
    public function delete_connection($request)
    {
        return $this->handle_response([
            'response_multi' => [
                $this->DB_Settings_Connection->truncate(),
                $this->DB_Settings_General->reset_sync_by_collections(),
            ],
        ]);
    }

    /*

	Insert connection data

	Called from

	*/
    public function set_connection($request)
    {
        $connection = $request->get_param('connection');

        $nonce_verified = wp_verify_nonce(
            $request->get_param('nonce'),
            'wp_rest'
        );

        if (!$nonce_verified) {
            return $this->handle_response([
                'response' => Utils::wp_error(
                    'Whoops, looks like you don\'t have permission to update this.'
                ),
            ]);
        }

        $clean_connection = $this->Shopify_API->sanitize_response($connection);

        // Remove any existing connection first
        $this->DB_Settings_Connection->truncate();

        return $this->return_success([
            'response' => $this->DB_Settings_Connection->maybe_insert_connection(
                $clean_connection
            ),
        ]);
    }

    /*

	Checks either the web server connection or the Shopify connection

	*/
    public function check_connection($request)
    {
        $nonce = sanitize_text_field($request->get_param('nonce'));
        $connection_type = sanitize_text_field($request->get_param('type'));

        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return $this->return_error('Invalid nonce', __METHOD__, __LINE__);
        }

        if (!$connection_type) {
            // Defaults to checking for an open / valid webserver connection
            return $this->return_success([
                'response' => $this->check_server_connection(),
                'is_syncing' => $request->get_param('is_syncing'),
            ]);
        }

        if ($connection_type === 'shopify') {
            $creds = $request->get_param('creds');

            if (empty($creds)) {
                return $this->return_error(
                    'Invalid Shopify API Keys',
                    __METHOD__,
                    __LINE__
                );
            }

            $creds = Data::sanitize_text_fields($creds);

            $get_tokens = $this->Shopify_API->get_storefront_access_tokens();

            if (empty($get_tokens)) {
                return $this->return_error(
                    'No valid Shopify storefront access tokens found. Please double check that you\'ve entered the correct API keys and domain.',
                    __METHOD__,
                    __LINE__
                );
            }

            $access_tokens_resp = $this->Shopify_API->pre_response_check($get_tokens);

            if (is_wp_error($access_tokens_resp)) {
                return $access_tokens_resp;
            }

            $response = $this->return_success([
                'response' => $access_tokens_resp,
                'is_syncing' => $request->get_param('is_syncing'),
            ]);

            if ($this->is_handle_response_error($response)) {
                return $response;
            }

            $valid_tokens = $this->valid_storefront_access_tokens(
                $response->storefront_access_tokens,
                $creds['storefront_access_token']
            );

            if (empty($valid_tokens)) {
                return $this->handle_response([
                    'response' => Utils::wp_error(
                        'Oops, it looks like the Storefront Access Token you provided is wrong. Try to copy / paste the key again.'
                    ),
                ]);
            }

            $body_shop = wp_remote_retrieve_body(
                $this->Shopify_API->get_shop()
            );

            if (is_wp_error($body_shop) || empty($body_shop)) {
                $response_to_client = ['name' => $creds['domain']];
            } else {
                $response_to_client = $body_shop->shop;
            }

            return $this->return_success([
                'response' => $response_to_client,
            ]);
        }
    }

    /*

	Checks for a valid (open) connection to the web server based on a URL. Useful to check
	whether the syncing will even work before starting ...

	WP Shopify addon

	@param string $url
	@return boolean

	*/
    public function check_server_connection()
    {
        $url = $_SERVER['HTTP_REFERER'];

        $url_parts = @parse_url($url);

        if (!$url_parts) {
            return false;
        }
        if (!isset($url_parts['host'])) {
            return false;
        } //can't process relative URLs
        if (!isset($url_parts['path'])) {
            $url_parts['path'] = '/';
        }

        $socket = fsockopen(
            $url_parts['host'],
            isset($url_parts['port']) ? (int) $url_parts['port'] : 80,
            $errno,
            $errstr,
            30
        );

        if (!$socket) {
            return Utils::wp_error([
                'message_lookup' => 'invalid_server_connection',
                'call_method' => __METHOD__,
                'call_line' => __LINE__,
            ]);
        } else {
            return true;
        }
    }

    /*

	Register route: collections_heading

	*/
    public function register_route_connection()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/connection',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'set_connection'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: collections_heading

	*/
    public function register_route_connection_delete()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/connection/delete',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'delete_connection'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: collections_heading

	*/
    public function register_route_connection_check()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/connection/check',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'check_connection'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: collections_heading

	*/
    public function register_route_connection_mask()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/connection/mask',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'mask_connection'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_connection_mask']);
        add_action('rest_api_init', [$this, 'register_route_connection']);
        add_action('rest_api_init', [$this, 'register_route_connection_check']);
        add_action('rest_api_init', [
            $this,
            'register_route_connection_delete',
        ]);
    }
}
