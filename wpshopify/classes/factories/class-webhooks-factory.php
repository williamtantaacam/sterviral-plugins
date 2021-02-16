<?php

namespace WP_Shopify\Factories;

use WP_Shopify\Webhooks;
use WP_Shopify\Factories;

if (!defined('ABSPATH')) {
    exit();
}

class Webhooks_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Webhooks(
                $plugin_settings,
                Factories\Template_Loader_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
