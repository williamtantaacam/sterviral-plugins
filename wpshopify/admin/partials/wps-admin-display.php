<?php

use WP_Shopify\Factories;

$DB_Settings_Connection = Factories\DB\Settings_Connection_Factory::build();
$DB_Settings_License = Factories\DB\Settings_License_Factory::build();
$connection = $DB_Settings_Connection->get();
$license = $DB_Settings_License->get();
$has_connection = $DB_Settings_Connection->has_connection();
?>

<div class="wrap wps-admin-wrap">

   <div id="wpshopify-app-settings"></div>
   
   <?php require plugin_dir_path(__FILE__) . 'wps-admin-notices.php'; ?>

   <section id="wpshopify-admin-content"></section>

   <?php
   require plugin_dir_path(__FILE__) . 'wps-tab-content-connect.php';
   require plugin_dir_path(__FILE__) . 'wps-tab-content-tools.php';

   require plugin_dir_path(__FILE__) . 'wps-tab-content-license.php';
   require plugin_dir_path(__FILE__) . 'wps-tab-content-help.php';
   require plugin_dir_path(__FILE__) . 'wps-tab-content-extensions.php';
   ?>

   <section id="wpshopify-admin-footer"></section>

</div>