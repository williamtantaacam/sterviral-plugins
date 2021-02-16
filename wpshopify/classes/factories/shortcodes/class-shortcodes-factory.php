<?php

namespace WP_Shopify\Factories\Shortcodes;

defined('ABSPATH') ?: exit();

use WP_Shopify\Factories;
use WP_Shopify\Shortcodes;

class Shortcodes_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (empty($plugin_settings)) {
            $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();
        }

        if (is_null(self::$instantiated)) {
            self::$instantiated = new Shortcodes(
                Factories\Render\Products\Products_Factory::build(
                    $plugin_settings
                ),
                Factories\Render\Cart\Cart_Factory::build($plugin_settings),

                Factories\Render\Search\Search_Factory::build($plugin_settings),
                Factories\Render\Storefront\Storefront_Factory::build(
                    $plugin_settings
                ),
                Factories\Render\Collections\Collections_Factory::build(
                    $plugin_settings
                )
            );
        }

        return self::$instantiated;
    }
}
