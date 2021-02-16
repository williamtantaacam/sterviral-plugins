<?php

namespace WP_Shopify\Factories\API\Tools;

defined('ABSPATH') ?: die();

use WP_Shopify\API;
use WP_Shopify\Factories;

class Clear_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new API\Tools\Clear(
                Factories\Processing\Database_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
