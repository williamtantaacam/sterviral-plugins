<?php

namespace WP_Shopify\Factories\Render;

defined('ABSPATH') ?: die();

use WP_Shopify\Render\Root;
use WP_Shopify\Factories;

class Root_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Root(
                Factories\Template_Loader_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
