<?php

$DB_Shop             = WP_Shopify\Factories\DB\Shop_Factory::build();
$DB_Settings_General = WP_Shopify\Factories\DB\Settings_General_Factory::build();

$DB_Shop->update_items_of_type($data);
$DB_Settings_General->set_app_uninstalled(1);