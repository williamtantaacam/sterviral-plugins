<?php

namespace WP_Shopify\Factories\API\Items;

defined('ABSPATH') ?: die;

use WP_Shopify\API;
use WP_Shopify\Factories;

class Customers_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Items\Customers(
				Factories\DB\Settings_General_Factory::build(),
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\Shopify_API_Factory::build(),
            Factories\Processing\Customers_Factory::build(),
            Factories\DB\Customers_Factory::build(),
            Factories\DB\Settings_Connection_Factory::build(),
            Factories\API\Storefront\Customers\Customers_Factory::build(),
            Factories\API\Admin\Customers\Customers_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
