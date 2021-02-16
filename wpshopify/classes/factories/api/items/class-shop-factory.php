<?php

namespace WP_Shopify\Factories\API\Items;

defined('ABSPATH') ?: die();

use WP_Shopify\API;
use WP_Shopify\Factories;

class Shop_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new API\Items\Shop(
                Factories\Shopify_API_Factory::build(),
                Factories\Processing\Shop_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
