<?php

namespace WP_Shopify\Factories\Processing;

use WP_Shopify\Processing;
use WP_Shopify\Factories;

if (!defined('ABSPATH')) {
    exit();
}

class Collections_Smart_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Processing\Collections_Smart(
                Factories\DB\Settings_Syncing_Factory::build(),
                Factories\DB\Collections_Factory::build(),
                Factories\CPT_Model_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
