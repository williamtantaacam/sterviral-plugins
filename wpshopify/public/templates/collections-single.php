<?php

defined('ABSPATH') ?: die();

get_header('wpshopify');

global $post;

$Collections = WP_Shopify\Factories\Render\Collections\Collections_Factory::build();

$Collections->collections(
    apply_filters('wps_collections_single_args', [
        'title' => $post->post_title,
        'single' => true,
        'dropzone_collection_image' => '#collection_image',
        'dropzone_collection_title' => '#collection_title',
        'dropzone_collection_description' => '#collection_description',
        'dropzone_collection_products' => '#collection_products',
        'hide_wrapper' => true,
        'excludes' => false,
        'products_excludes' => ['description'],
        'products_infinite_scroll' => true,
    ])
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
      
   .wps-collection-single-content {
      max-width: 1100px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
   }

   #collection_products {
      margin-top: 2em;
   }

   #collection_image .wps-component-collection-image {
      margin-bottom: 0;
   }

</style>

<section class="wps-collections-wrapper wps-container">
   <?= do_action('wps_breadcrumbs') ?>

   <div class="wps-collection-single row">
      
      <div class="wps-collection-single-content col">
      
         <div id="collection_image"></div>
         <div id="collection_title"></div>
         <div id="collection_description"></div>
         <div id="collection_products"></div>
      </div>

   </div>
</section>

<?php get_footer('wpshopify');
