<?php

namespace WP_Shopify;

use WP_Shopify\Utils;
use WP_Shopify\Options;
use WP_Shopify\Utils\Data;
use WP_Shopify\Utils\Server;

if (!defined('ABSPATH')) {
    exit();
}

class Frontend
{
    public $plugin_settings;
    public $Data_Bridge;

    /*

	Initialize the class and set its properties.

	*/
    public function __construct($plugin_settings, $Data_Bridge)
    {
        $this->plugin_settings = $plugin_settings;
        $this->Data_Bridge = $Data_Bridge;
    }

    /*

	Public styles

	*/
    public function public_styles()
    {
        if (!is_admin()) {
            wp_enqueue_style(
                'wpshopify-styles-frontend-all',
                WP_SHOPIFY_PLUGIN_URL . 'dist/public.min.css',
                [],
                filemtime(WP_SHOPIFY_PLUGIN_DIR_PATH . 'dist/public.min.css'),
                'all'
            );
        }
    }

    /*

	Public scripts

	*/
    public function public_scripts()
    {
        if (!is_admin()) {
            global $post;

            $runtime_url = WP_SHOPIFY_PLUGIN_URL . 'dist/runtime.49e1a3.min.js';
            $vendors_admin_url =
                WP_SHOPIFY_PLUGIN_URL . 'dist/vendors-public.49e1a3.min.js';
            $main_url = WP_SHOPIFY_PLUGIN_URL . 'dist/public.49e1a3.min.js';

            wp_enqueue_script('wpshopify-runtime', $runtime_url, []);

            wp_enqueue_script(
                'wpshopify-vendors-public',
                $vendors_admin_url,
                []
            );

            wp_enqueue_script('wpshopify-public', $main_url, [
                'wp-hooks',
                'wp-element',
                'wp-components',
                'wp-i18n',
                'wpshopify-runtime',
                'wpshopify-vendors-public',
            ]);

            if (empty($this->plugin_settings['shop'])) {
                $currency = 'USD';
            } else {
                $currency = $this->plugin_settings['shop']['currency'];
            }

            // Global plugin JS settings
            $this->Data_Bridge->add_settings_script('wpshopify-public', false);

            wp_set_script_translations(
                'wpshopify-public',
                'wpshopify',
                WP_SHOPIFY_PLUGIN_DIR . WP_SHOPIFY_LANGUAGES_FOLDER
            );
        }
    }

    public function css_body_class($classes)
    {
        $classes[] = 'wpshopify';

        return $classes;
    }

    public function add_script_attributes($tag, $handle)
    {
        if (
            'wpshopify-runtime' !== $handle &&
            'wpshopify-vendors-public' !== $handle &&
            'wpshopify-public' !== $handle
        ) {
            return $tag;
        }

        return str_replace(' src', ' defer src', $tag);
    }

    public function init()
    {
        if (\is_admin()) {
            return;
        }

        add_action('wp_enqueue_scripts', [$this, 'public_styles']);
        add_action('wp_enqueue_scripts', [$this, 'public_scripts']);

        add_filter(
            'script_loader_tag',
            [$this, 'add_script_attributes'],
            10,
            2
        );

        add_filter('body_class', [$this, 'css_body_class']);
    }
}
