<?php

namespace WP_Shopify\Factories\API\Admin\Variants;

defined('ABSPATH') ?: die();

use WP_Shopify\Factories;
use WP_Shopify\API\Admin;

class Variants_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Admin\Variants(
                Factories\API\Admin\Admin_Factory::build(),
                Factories\API\Admin\Variants\Queries_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
