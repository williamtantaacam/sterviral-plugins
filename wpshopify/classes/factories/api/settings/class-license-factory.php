<?php

namespace WP_Shopify\Factories\API\Settings;

defined('ABSPATH') ?: die();

use WP_Shopify\API;
use WP_Shopify\Factories;

class License_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new API\Settings\License(
                Factories\DB\Settings_License_Factory::build(),
                Factories\HTTP_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
