<?php

namespace WP_Shopify\Factories\API\Processing;

defined('ABSPATH') ?: die;

use WP_Shopify\Factories;
use WP_Shopify\API;


class Webhooks_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Processing\Webhooks(
            Factories\Processing\Webhooks_Factory::build(),
            Factories\DB\Settings_Syncing_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
