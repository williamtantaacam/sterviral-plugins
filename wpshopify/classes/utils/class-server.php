<?php

namespace WP_Shopify\Utils;

use WP_Shopify\Utils\Data;

if (!defined('ABSPATH')) {
    exit();
}

class Server
{
    public static function get_php_post_max_size_bytes()
    {
        return wp_max_upload_size();
    }

    public static function exceeds_max_post_body_size($data)
    {
        return Data::size_in_bytes($data) > self::get_php_post_max_size_bytes();
    }
}
