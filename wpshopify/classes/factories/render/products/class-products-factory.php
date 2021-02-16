<?php

namespace WP_Shopify\Factories\Render\Products;

defined('ABSPATH') ?: die();

use WP_Shopify\Render\Products;
use WP_Shopify\Factories;

class Products_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (empty($plugin_settings)) {
            $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();
        }

        if (is_null(self::$instantiated)) {
            self::$instantiated = new Products(
                Factories\Render\Templates_Factory::build(),
                Factories\Render\Products\Defaults_Factory::build(
                    $plugin_settings
                )
            );
        }

        return self::$instantiated;
    }
}
