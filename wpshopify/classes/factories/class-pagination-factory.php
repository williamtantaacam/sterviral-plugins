<?php

namespace WP_Shopify\Factories;

use WP_Shopify\Pagination;
use WP_Shopify\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Pagination_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Pagination(
				Factories\DB\Settings_General_Factory::build(),
				Factories\Templates_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
