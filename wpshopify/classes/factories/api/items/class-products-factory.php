<?php

namespace WP_Shopify\Factories\API\Items;

defined('ABSPATH') ?: die();

use WP_Shopify\API;
use WP_Shopify\Factories;

class Products_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new API\Items\Products(
                Factories\DB\Settings_General_Factory::build(),
                Factories\DB\Settings_Syncing_Factory::build(),
                Factories\DB\Tags_Factory::build(),
                Factories\DB\Products_Factory::build(),
                Factories\Shopify_API_Factory::build(),
                Factories\Processing\Products_Factory::build(),
                Factories\Processing\Variants_Factory::build(),
                Factories\Processing\Tags_Factory::build(),
                Factories\Processing\Options_Factory::build(),
                Factories\Processing\Images_Factory::build(),
                Factories\API\Admin\Variants\Variants_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
