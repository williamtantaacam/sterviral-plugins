<?php

defined('ABSPATH') ?: die();

get_header('wpshopify');

$Collections = WP_Shopify\Factories\Render\Collections\Collections_Factory::build();
$Settings = WP_Shopify\Factories\DB\Settings_General_Factory::build();

$is_showing_heading = $Settings->get_col_value(
    'collections_heading_toggle',
    'bool'
);
?>

<style>
   .wps-breadcrumbs {
      max-width: 1100px;
      margin: 0 auto;
   }

   .wps-breadcrumbs-name {
         text-transform: capitalize;
      }
</style>

<section class="wps-container">
   <?= do_action('wps_breadcrumbs') ?>

   <div class="wps-collections-all">
      
      <?php
      if ($is_showing_heading) { ?>
         <h1 class="wps-heading">

          <?= apply_filters(
              'wps_collections_all_title',
              $Settings->get_col_value('collections_heading', 'string')
          ) ?>

         </h1>
      <?php }

      $Collections->collections(
          apply_filters('wps_collections_all_args', [
              'link_target' => '_self',
              'link_to' => 'wordpress',
          ])
      );
      ?>

   </div>

</section>


<?php get_footer('wpshopify');
