<?php

namespace WP_Shopify\Factories;

defined('ABSPATH') ?: die();

use WP_Shopify\API;
use WP_Shopify\Factories;

class API_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new API(
                Factories\DB\Settings_Syncing_Factory::build()
            );
        }
        return self::$instantiated;
    }
}
