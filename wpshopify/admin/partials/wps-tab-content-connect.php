<?php

use WP_Shopify\Utils; ?>
<!--

Tab Content: Connect

-->
<div class="tab-content" data-tab-content="tab-connect">


  <div class="wps-admin-section">
   <div id="wpshopify-connection-status"></div>

    <form method="post" name="cleanup_options" action="options.php" id="wps-connect" class="wps-admin-form">

      <div class="wps-form-group">

        <h4><?php _e('API Key', 'wpshopify'); ?></h4>

        <input required <?= $has_connection
            ? 'disabled'
            : '' ?> type="text" class="regular-text <?= $has_connection
     ? 'valid'
     : '' ?>" id="<?= WP_SHOPIFY_SETTINGS_GENERAL_OPTION_NAME ?>_api_key" name="api_key" value="<?php if (
    !empty($connection->api_key)
) {
    echo Utils::mask_value($connection->api_key);
} ?>" placeholder=""> <span class="wps-help-tip wps-help-tip-inline-no-position" title="<?php _e(
    'To generate an API key you must create a "Private App" within your Shopify account.',
    'wpshopify'
); ?>"></span><div class="wps-form-icon wps-animated"></div>

      </div>


      <div class="wps-form-group">

        <h4><?php _e('API Password', 'wpshopify'); ?></h4>

        <input required <?= $has_connection
            ? 'disabled'
            : '' ?> type="text" class="regular-text <?= $has_connection
     ? 'valid'
     : '' ?>" id="<?= WP_SHOPIFY_SETTINGS_GENERAL_OPTION_NAME ?>_api_password" name="api_password" autocomplete="off" value="<?php if (
    !empty($connection->api_password)
) {
    echo Utils::mask_value($connection->api_password);
} ?>" placeholder=""> <span class="wps-help-tip wps-help-tip-inline-no-position" title="<?php _e(
    'To generate an API Password you must create a "Private App" within your Shopify account.',
    'wpshopify'
); ?>"></span> <div class="wps-form-icon wps-animated"></div>

      </div>


      <div class="wps-form-group">

        <h4><?php _e('Shared Secret', 'wpshopify'); ?></h4>

        <input required <?= $has_connection
            ? 'disabled'
            : '' ?> type="text" class="regular-text <?= $has_connection
     ? 'valid'
     : '' ?>" id="<?= WP_SHOPIFY_SETTINGS_GENERAL_OPTION_NAME ?>_shared_secret" name="shared_secret" autocomplete="off" value="<?php if (
    !empty($connection->shared_secret)
) {
    echo Utils::mask_value($connection->shared_secret);
} ?>" placeholder=""> <span class="wps-help-tip wps-help-tip-inline-no-position" title="<?php _e(
    'To generate a Shared Secret you must create a "Private App" within your Shopify account. The Shared Secret is used to validate webhook requests and provide security for WP Shopify.',
    'wpshopify'
); ?>"></span> <div class="wps-form-icon wps-animated"></div>

      </div>

      <div class="wps-form-group">

        <h4><?php _e('Storefront Access Token', 'wpshopify'); ?></h4>

        <input required <?= $has_connection
            ? 'disabled'
            : '' ?> type="text" class="regular-text <?= $has_connection
     ? 'valid'
     : '' ?>" id="<?= WP_SHOPIFY_SETTINGS_GENERAL_OPTION_NAME ?>_storefront_access_token" name="storefront_access_token" value="<?php if (
    !empty($connection->storefront_access_token)
) {
    echo Utils::mask_value($connection->storefront_access_token);
} ?>" placeholder=""> <span class="wps-help-tip wps-help-tip-inline-no-position" title="<?php _e(
    'To generate a Storefront Access Token you must create a "Private App" within your Shopify account. The Storefront Access Token is used to create the front-end cart experience.',
    'wpshopify'
); ?>"></span><div class="wps-form-icon wps-animated"></div>

      </div>

      <div class="wps-form-group">

        <h4><?php _e('Shopify Domain', 'wpshopify'); ?></h4>
        <input required <?= $has_connection
            ? 'disabled'
            : '' ?> type="text" class="regular-text <?= $has_connection
     ? 'valid'
     : '' ?>" id="<?= WP_SHOPIFY_SETTINGS_GENERAL_OPTION_NAME ?>_domain" name="domain" value="<?php if (
    !empty($connection->domain)
) {
    echo $connection->domain;
} ?>" placeholder="<?php _e(
    'shop.myshopify.com',
    'wpshopify'
); ?>" id="domain"> <span class="wps-help-tip wps-help-tip-inline" title="<?php _e(
    'example: yourshop.myshopify.com',
    'wpshopify'
); ?>"></span>
        <div class="wps-form-icon wps-animated"></div>

      </div>

      <!-- Submit -->
      <div class="wps-button-group button-group-ajax">

        <?php if ($has_connection) { ?>
          <?php submit_button(
              __('Disconnect your Shopify store', 'wpshopify'),
              'primary large',
              'submitDisconnect',
              false,
              []
          ); ?>

        <?php } else { ?>
          <?php submit_button(
              __('Connect your Shopify store', 'wpshopify'),
              'primary large',
              'submitConnect',
              false,
              []
          ); ?>
        <?php } ?>

        <div class="spinner"></div>

      </div>

    </form>

  </div>

</div>
