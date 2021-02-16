<?php

use WP_Shopify\Options; ?>

<!--

Tab Content: Tools

-->
<div class="tab-content" data-tab-content="tab-misc">

  <div class="wps-admin-section">

    <h3><?php _e('Migrate WP Shopify Database Tables', 'wpshopify'); ?> </h3>

    <p><?php _e(
        'If you just upgraded from a version below 1.2.2, then you need to perform a simple database upgrade. Please make sure that you\'ve made a backup of your database before proceeding. Data loss could occur!',
        'wpshopify'
    ); ?></p>

    <div class="wps-button-group button-group-ajax" id="wps-button-wrapper-migrate">

      <?php
      $attributes = [
          'id' => 'wps-button-migrate',
      ];

      if (Options::get('wp_shopify_migration_needed') != true) {
          $attributes['disabled'] = true;
      }
      ?>

      <?php submit_button(
          __('Upgrade Database Tables', 'wpshopify'),
          'primary large',
          'submitSettings',
          false,
          $attributes
      ); ?>

      <div class="spinner"></div>

    </div>

  </div>


</div>
