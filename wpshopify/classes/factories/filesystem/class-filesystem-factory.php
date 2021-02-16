<?php

namespace WP_Shopify\Factories\Filesystem;

defined('ABSPATH') ?: exit();

use WP_Shopify\Filesystem;

class Filesystem_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Filesystem();
        }

        return self::$instantiated;
    }
}
