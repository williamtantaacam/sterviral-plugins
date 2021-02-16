<?php

namespace WP_Shopify\Factories;

use WP_Shopify\Factories;
use WP_Shopify\HTTP;

if (!defined('ABSPATH')) {
	exit;
}

class HTTP_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new HTTP(
				Factories\DB\Settings_Connection_Factory::Build()
			);

		}

		return self::$instantiated;

	}

}
