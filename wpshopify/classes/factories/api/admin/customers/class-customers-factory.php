<?php

namespace WP_Shopify\Factories\API\Admin\Customers;

defined('ABSPATH') ?: die;

use WP_Shopify\Factories;
use WP_Shopify\API\Admin;

class Customers_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Admin\Customers(
            Factories\API\Admin\Admin_Factory::build(),
            Factories\API\Admin\Customers\Queries_Factory::build()
			);

		}

		return self::$instantiated;

	}

}