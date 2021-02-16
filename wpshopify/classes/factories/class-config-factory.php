<?php

namespace WP_Shopify\Factories;

use WP_Shopify\Config;

if (!defined('ABSPATH')) {
	exit;
}

class Config_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			$Config = new Config();

			self::$instantiated = $Config;

		}

		return self::$instantiated;

	}

}
