<?php

namespace WP_Shopify\API\Settings;

use WP_Shopify\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class License extends \WP_Shopify\API
{
    public $DB_Settings_License;
    public $HTTP;

    public function __construct($DB_Settings_License, $HTTP)
    {
        $this->DB_Settings_License = $DB_Settings_License;
        $this->HTTP = $HTTP;
    }

    public function get_active_downloads_info()
    {
        $constants = get_defined_constants(true);

        $user_constants = array_filter(
            $constants['user'],
            function ($v, $k) {
                return strpos($k, 'WP_SHOPIFY') !== false &&
                    strpos($k, 'DOWNLOAD_NAME') !== false;
            },
            ARRAY_FILTER_USE_BOTH
        );

        $active_downloads = array_map(
            function ($k, $download_name) {
                $download_name_constant = strtoupper(
                    Utils::convert_hyphen_to_underscore($download_name)
                );

                if (
                    $download_name_constant === 'WP_SHOPIFY' ||
                    $download_name_constant === 'WP_SHOPIFY_PRO'
                ) {
                    $download_license_constant = 'WP_SHOPIFY_LICENSE_KEY_PRO';
                } else {
                    $download_license_constant =
                        'WP_SHOPIFY_LICENSE_KEY_' . $download_name_constant;
                }

                if (defined($download_license_constant)) {
                    $license_key = constant($download_license_constant);
                } else {
                    $maybe_license = $this->DB_Settings_License->get_row_by(
                        'item_name',
                        $download_name
                    );

                    if (empty($maybe_license)) {
                        $license = false;
                    } else {
                        $license = $maybe_license;
                    }
                }

                return [
                    'name' => $download_name,
                    'license' => $license,
                ];
            },
            array_keys($user_constants),
            $user_constants
        );

        return $active_downloads;
    }

    public function get_active_downloads()
    {
        return $this->handle_response([
            'response' => $this->get_active_downloads_info(),
        ]);
    }

    /*

	Get License Details

	*/
    public function get_license($request)
    {
        return $this->handle_response([
            'response' => $this->DB_Settings_License->get(),
        ]);
    }

    /*

	Set License Details

	*/
    public function set_license($request)
    {
        $license = $request->get_param('license');

        if (empty($license)) {
            return $this->handle_response(
                Utils::wp_error('No license info found to insert')
            );
        }

        $insert_result = $this->DB_Settings_License->insert_license(
            $license['license']
        );

        if (is_wp_error($insert_result)) {
            return $this->handle_response($insert_result);
        }

        if (!$insert_result) {
            return $this->handle_response(
                Utils::wp_error('Failed to save license key')
            );
        }

        return $this->handle_response([
            'response' => $insert_result,
        ]);
    }

    /*

	Delete License Details

	*/
    public function delete_license_locally($request)
    {
        $license_key = $request->get_param('key');

        if (empty($license_key)) {
            return $this->handle_response(
                Utils::wp_error('No license key found to remove')
            );
        }

        $deletion_result = $this->DB_Settings_License->delete_rows(
            'license_key',
            $license_key
        );

        if (is_wp_error($deletion_result)) {
            return $this->handle_response($deletion_result);
        }

        if (!$deletion_result) {
            return $this->handle_response(
                Utils::wp_error('Failed to remove license key')
            );
        }

        return $this->handle_response([
            'response' => $deletion_result,
        ]);
    }

    /*

	Register route: collections_heading

	*/
    public function register_route_license_active_downloads()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/license/downloads',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_active_downloads'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_license()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/license',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_license'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'set_license'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_license_delete_locally()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/license/delete_locally',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'delete_license_locally'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_license']);

        add_action('rest_api_init', [
            $this,
            'register_route_license_delete_locally',
        ]);

        add_action('rest_api_init', [
            $this,
            'register_route_license_active_downloads',
        ]);
    }
}
