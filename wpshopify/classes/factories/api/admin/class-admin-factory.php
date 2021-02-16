<?php

namespace WP_Shopify\Factories\API\Admin;

defined('ABSPATH') ?: die;

use WP_Shopify\Factories;
use WP_Shopify\API;

class Admin_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Admin(
            Factories\DB\Settings_Connection_Factory::build()
			);

		}

		return self::$instantiated;

	}

}