<?php

namespace WP_Shopify\Factories\API\Storefront\Customers;

defined('ABSPATH') ?: die;

use WP_Shopify\API;

class Queries_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Storefront\Customers\Queries();

		}

		return self::$instantiated;

	}

}