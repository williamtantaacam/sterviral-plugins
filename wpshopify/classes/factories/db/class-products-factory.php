<?php

namespace WP_Shopify\Factories\DB;

use WP_Shopify\DB;

if (!defined('ABSPATH')) {
	exit;
}

class Products_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new DB\Products();

		}

		return self::$instantiated;

	}

}
