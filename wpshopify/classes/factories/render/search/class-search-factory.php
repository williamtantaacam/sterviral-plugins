<?php

namespace WP_Shopify\Factories\Render\Search;

if (!defined('ABSPATH')) {
    exit();
}

use WP_Shopify\Render\Search;
use WP_Shopify\Factories;

class Search_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Search(
                Factories\Render\Templates_Factory::build(),
                Factories\Render\Search\Defaults_Factory::build(
                    $plugin_settings
                )
            );
        }

        return self::$instantiated;
    }
}
