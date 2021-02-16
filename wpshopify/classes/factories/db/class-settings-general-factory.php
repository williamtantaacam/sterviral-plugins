<?php

namespace WP_Shopify\Factories\DB;

if (!defined('ABSPATH')) {
	exit;
}

use WP_Shopify\DB;

class Settings_General_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new DB\Settings_General();

		}

		return self::$instantiated;

	}

}
