<?php

namespace WP_Shopify;

if (!defined('ABSPATH')) {
    exit();
}

use WP_Shopify\Factories;
use WP_Shopify\Utils;

class Bootstrap
{
    public function __construct()
    {
    }

    public function initialize()
    {
        $is_admin_page = is_admin();

        $results = [];

        Factories\Config_Factory::build();
        Factories\Activator_Factory::build()->init();

        if ($is_admin_page) {
            if (!is_plugin_active(WP_SHOPIFY_BASENAME)) {
                return;
            }
        }

        // Plugin settings available here. Activator responsible for creating tables.
        $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();

        // The init action fires after plugins_loaded
        add_action('init', function () use ($plugin_settings, $results) {
            $this->build_plugin($plugin_settings, $results);
        });
        
    }

    public function build_plugin($plugin_settings, $results)
    {

        $results['Deactivator'] = Factories\Deactivator_Factory::build(
            $plugin_settings
        );

        $results['Backend'] = Factories\Backend_Factory::build(
            $plugin_settings
        );

        $results['Admin_Menus'] = Factories\Admin_Menus_Factory::build(
            $plugin_settings
        );

        $results['Hooks'] = Factories\Hooks_Factory::build($plugin_settings);


        $results['Templates'] = Factories\Templates_Factory::build(
            $plugin_settings
        );

        $results['Shortcodes'] = Factories\Shortcodes\Shortcodes_Factory::build(
            $plugin_settings
        );

        $results['CPT'] = Factories\CPT_Factory::build($plugin_settings);

        $results['I18N'] = Factories\I18N_Factory::build($plugin_settings);
        $results[
            'API_Items_Products_Factory'
        ] = Factories\API\Items\Products_Factory::build();

        $results['Frontend'] = Factories\Frontend_Factory::build(
            $plugin_settings
        );

        $results[
            'API_Options_Components_Factory'
        ] = Factories\API\Options\Components_Factory::build();


        $results[
            'API_Settings_Collections_Factory'
        ] = Factories\API\Settings\Collections_Factory::build();

        $results[
            'API_Settings_License_Factory'
        ] = Factories\API\Settings\License_Factory::build();
        $results[
            'API_Settings_General_Factory'
        ] = Factories\API\Settings\General_Factory::build();
        $results[
            'API_Settings_Connection_Factory'
        ] = Factories\API\Settings\Connection_Factory::build();

        $results[
            'API_Status_Factory'
        ] = Factories\API\Syncing\Status_Factory::build();
        $results[
            'API_Indicator_Factory'
        ] = Factories\API\Syncing\Indicator_Factory::build();
        $results[
            'API_Counts_Factory'
        ] = Factories\API\Syncing\Counts_Factory::build();

        $results[
            'API_Items_Collections_Factory'
        ] = Factories\API\Items\Collections_Factory::build();
        $results[
            'API_Items_Shop_Factory'
        ] = Factories\API\Items\Shop_Factory::build();

        $results[
            'API_Items_Collects_Factory'
        ] = Factories\API\Items\Collects_Factory::build();

        $results[
            'API_Misc_Notices_Factory'
        ] = Factories\API\Misc\Notices_Factory::build();
        $results[
            'API_Tools_Cache_Factory'
        ] = Factories\API\Tools\Cache_Factory::build();
        $results[
            'API_Tools_Clear_Factory'
        ] = Factories\API\Tools\Clear_Factory::build();

        return $this->init_classes($results);
    }

    public function init_classes($classes)
    {
        $results = [];

        foreach ($classes as $class_name => $class) {
            if (method_exists($class, 'init')) {
                if ($class_name === 'Activator') {
                    $results[$class_name] = true;
                } else {
                    $results[$class_name] = $class->init();
                }
            }
        }

        return $results;
    }
}
