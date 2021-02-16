<?php

namespace WP_Shopify;

if (!defined('ABSPATH')) {
    exit();
}

class I18N
{
    /*

	Load the plugin text domain for translation.

	*/
    public function load_textdomain()
    {
        $load_plugin_textdomain = load_plugin_textdomain(
            'wpshopify',
            false,
            dirname(dirname(plugin_basename(__FILE__))) .
                '/' .
                WP_SHOPIFY_LANGUAGES_FOLDER
        );
    }

    /*

	init

	*/
    public function init()
    {
        $this->load_textdomain();
    }
}
