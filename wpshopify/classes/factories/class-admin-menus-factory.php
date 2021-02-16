<?php

namespace WP_Shopify\Factories;

use WP_Shopify\Admin_Menus;
use WP_Shopify\Factories;

if (!defined('ABSPATH')) {
    exit();
}

class Admin_Menus_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (!$plugin_settings) {
            $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();
        }

        if (is_null(self::$instantiated)) {
            self::$instantiated = new Admin_Menus(
                Factories\Render\Cart\Cart_Factory::build($plugin_settings)
            );
        }

        return self::$instantiated;
    }
}
