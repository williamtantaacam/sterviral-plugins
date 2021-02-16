<?php

namespace WP_Shopify\Factories\Render\Cart;

if (!defined('ABSPATH')) {
    exit();
}

use WP_Shopify\Render\Cart;
use WP_Shopify\Factories;

class Cart_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Cart(
                Factories\Render\Templates_Factory::build(),
                Factories\Render\Cart\Defaults_Factory::build($plugin_settings)
            );
        }

        return self::$instantiated;
    }
}
