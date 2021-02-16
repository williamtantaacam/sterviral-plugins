<?php

namespace WP_Shopify\Factories\Processing;

use WP_Shopify\Processing;
use WP_Shopify\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Images_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = $Async_Processing_Images = new Processing\Images(
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\DB\Images_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
