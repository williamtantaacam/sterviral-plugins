<?php

namespace WP_Shopify\Factories\Render\Cart;

if (!defined('ABSPATH')) {
    exit();
}

use WP_Shopify\Render\Cart\Defaults;
use WP_Shopify\Factories;

class Defaults_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Defaults(
                $plugin_settings,
                Factories\Render\Attributes_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
