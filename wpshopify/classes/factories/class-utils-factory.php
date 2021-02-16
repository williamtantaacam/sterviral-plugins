<?php

namespace WP_Shopify\Factories;

use WP_Shopify\Utils;

if (!defined('ABSPATH')) {
	exit;
}

class Utils_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			$Utils = new Utils();

			self::$instantiated = $Utils;

		}

		return self::$instantiated;

	}

}
