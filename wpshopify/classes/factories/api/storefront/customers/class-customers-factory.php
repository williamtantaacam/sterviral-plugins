<?php

namespace WP_Shopify\Factories\API\Storefront\Customers;

defined('ABSPATH') ?: die;

use WP_Shopify\Factories;
use WP_Shopify\API\Storefront;

class Customers_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Storefront\Customers(
            Factories\API\Storefront\Storefront_Factory::build(),
            Factories\API\Storefront\Customers\Queries_Factory::build()
			);

		}

		return self::$instantiated;

	}

}