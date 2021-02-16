<!--

Tab Content: Tools

-->
<div class="tab-content" data-tab-content="tab-tools">

  <div class="wps-admin-section">

    <h3><?php _e('Sync Product & Collection Detail Pages', 'wpshopify'); ?></h3>
    <p><?php _e(
        'This tool will create native WordPress posts (as a custom post type) for your products and collections. If you\'re not planning to have product detail pages then you won\'t need to use this.',
        'wpshopify'
    ); ?></p>

    <div class="wps-button-group button-group-ajax <?= $has_connection
        ? 'wps-is-active'
        : 'wps-is-not-active' ?>">

      <?php
      if ($has_connection) {
          $props = [
              'id' => 'wps-button-sync',
              'data-wpshopify-tool' => 'Sync Products',
          ];
      } else {
          $props = [
              'disabled' => 'disabled',
              'id' => 'wps-button-sync',
          ];
      }

      submit_button(
          __('Sync Detail Pages', 'wpshopify'),
          'primary large',
          'submitSettings',
          false,
          $props
      );
      ?>

      <div class="spinner"></div>

    </div>

  </div>


  <div class="wps-admin-section">

    <h3><?php _e('Clear Cache', 'wpshopify'); ?></h3>
    <p><?php _e(
        'If you\'re noticing various changes not appearing, try clearing the WP Shopify transient cache here.',
        'wpshopify'
    ); ?></p>

    <div class="wps-button-group button-group-ajax wps-is-active">

      <?php
      $props = [
          'id' => 'wps-button-clear-cache',
          'data-wpshopify-tool' => __('Clear Cache', 'wpshopify'),
      ];

      submit_button(
          __('Clear WP Shopify Cache', 'wpshopify'),
          'primary large',
          'submitSettings',
          false,
          $props
      );
      ?>

      <div class="spinner"></div>

    </div>

  </div>


  <div class="wps-admin-section">

    <h3><?php _e('Remove all synced data', 'wpshopify'); ?></h3>
    <p><?php _e(
        'This will remove all WP Shopify data from WordPress. Nothing will be changed in Shopify. Useful for removing any lingering data without re-installing the plugin. (Note: this can take up to 60 seconds and will delete product and collection posts).',
        'wpshopify'
    ); ?></p>

    <div class="wps-button-group button-group-ajax wps-is-active">

      <?php
      $props = [
          'id' => 'wps-button-clear-all-data',
          'data-wpshopify-tool' => 'Remove all synced data',
      ];

      submit_button(
          __('Remove all synced data from WordPress', 'wpshopify'),
          'primary large',
          'submitSettings',
          false,
          $props
      );
      ?>

      <div class="spinner"></div>

    </div>

  </div>

  <?php
?>


  <?php
?>


</div>
