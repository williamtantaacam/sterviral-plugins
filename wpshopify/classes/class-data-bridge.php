<?php

namespace WP_Shopify;

use WP_Shopify\Options;
use WP_Shopify\Utils;
use WP_Shopify\Utils\Data;

if (!defined('ABSPATH')) {
    exit();
}

class Data_Bridge
{
    public $plugin_settings;
    public $Render_Products_Defaults;

    public function __construct($plugin_settings, $Render_Products_Defaults)
    {
        $this->plugin_settings = $plugin_settings;
        $this->Render_Products_Defaults = $Render_Products_Defaults;
    }

    public function replace_rest_protocol()
    {
        if (\is_ssl()) {
            return str_replace("http://", "https://", \get_rest_url());
        }

        return \get_rest_url();
    }

    public function get_has_connection($connection)
    {
        if (empty($connection)) {
            return false;
        }

        if (
            empty($connection['api_key']) ||
            empty($connection['api_password']) ||
            empty($connection['shared_secret']) ||
            empty($connection['storefront_access_token']) ||
            empty($connection['domain'])
        ) {
            return false;
        }

        return true;
    }

    public function get_download_ids()
    {
    }

    public function get_settings($is_admin)
    {
        global $post;

        return [
            'settings' => [
                'syncing' => [
                    'reconnectingWebhooks' => false,
                    'hasConnection' => $this->get_has_connection(
                        $this->plugin_settings['connection']
                    ),
                    'isSyncing' => false,
                    'manuallyCanceled' => false,
                    'isClearing' => false,
                    'isDisconnecting' => false,
                    'isConnecting' => false,
                ],
                'general' => Data::sanitize_settings(
                    $this->plugin_settings['general']
                ),
                'connection' => [
                    'storefront' => [
                        'domain' =>
                            $this->plugin_settings['connection']['domain'],
                        'storefrontAccessToken' =>
                            $this->plugin_settings['connection'][
                                'storefront_access_token'
                            ],
                    ],
                ],
            ],
            'notices' => $this->plugin_settings['notices'],
            'api' => [
                'namespace' => WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
                'restUrl' => $this->replace_rest_protocol(),
                'nonce' => \wp_create_nonce('wp_rest'),
            ],
            'misc' => [
                'postID' => $post ? $post->ID : false,
                'isMobile' => \wp_is_mobile(),
                'pluginsDirURL' => \plugin_dir_url(dirname(__FILE__)),
                'pluginsDistURL' => plugin_dir_url(dirname(__FILE__)) . 'dist/',
                'adminURL' => \get_admin_url(),
                'siteUrl' => \site_url(),
                'latestVersion' => WP_SHOPIFY_NEW_PLUGIN_VERSION,
                'isPro' => false,
                'timers' => [
                    'syncing' => false,
                ],
                'locale' => \get_locale()
            ],
        ];
    }

    public function stringify_settings($settings, $is_admin)
    {
        $settings_encoded_string = wp_json_encode(
            Utils::convert_underscore_to_camel_array($settings)
        );

        if ($is_admin) {
            $js_string = "const wpshopify = " . $settings_encoded_string . ";";
        } else {
            $js_string =
                "function deepFreeze(object) {let propNames = Object.getOwnPropertyNames(object);for (let name of propNames) {let value = object[name];object[name] = value && typeof value === 'object' ? deepFreeze(value) : value;}return Object.freeze(object);}const wpshopify = " .
                $settings_encoded_string .
                ";deepFreeze(wpshopify);";
        }

        return $js_string;
    }

    public function add_settings_script($script_dep, $is_admin)
    {
        wp_add_inline_script(
            $script_dep,
            $this->stringify_settings(
                $this->get_settings($is_admin),
                $is_admin
            ),
            'before'
        );
    }
}
