<?php

/*

Plugin Name:         WP Shopify
Plugin URI:          https://wpshop.io
Description:         Sell and build custom Shopify experiences on WordPress.
Version:             3.5.7
Author:              WP Shopify
Author URI:          https://wpshop.io
License:             GPL-2.0+
License URI:         https://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:         wpshopify
Domain Path:         /languages
Requires at least:   5.4
Requires PHP:        5.6

*/

global $wpshopify;

require_once ABSPATH . 'wp-admin/includes/plugin.php';

// If this file is called directly, abort.
defined('WPINC') ?: die();

// If this file is called directly, abort.
defined('ABSPATH') ?: die();


/*

Used for both free / pro versions

*/
if (!defined('WP_SHOPIFY_BASENAME')) {
    define('WP_SHOPIFY_BASENAME', plugin_basename(__FILE__));
}

if (!defined('WP_SHOPIFY_ROOT_FILE_PATH')) {
    define('WP_SHOPIFY_ROOT_FILE_PATH', __FILE__);
}

if (!defined('WP_SHOPIFY_PLUGIN_DIR')) {
    define('WP_SHOPIFY_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

use WP_Shopify\Bootstrap;
use WP_Shopify\Utils;

if (!\function_exists('wpshopify_bootstrap')) {
    function wpshopify_bootstrap()
    {
        // initialize
        if (!isset($wpshopify)) {
            $wpshopify = new Bootstrap();
            $wpshopify->initialize();
        }

        // return
        return $wpshopify;
    }
}

wpshopify_bootstrap();

/*

Adds hooks which run on both plugin activation and deactivation.
The actions here are added during Activator->init() and Deactivator-init().

*/
register_activation_hook(__FILE__, function ($network_wide) {
    do_action('wps_on_plugin_activate', $network_wide);
});

register_deactivation_hook(__FILE__, function () {
    do_action('wps_on_plugin_deactivate');
});
