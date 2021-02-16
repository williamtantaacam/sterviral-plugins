<?php

defined('ABSPATH') ?: die();

get_header('wpshopify');

global $post;

$Products = WP_Shopify\Factories\Render\Products\Products_Factory::build();

$Products->products(
    apply_filters('wps_products_single_args', [
        'dropzone_product_buy_button' => '#product_buy_button',
        'dropzone_product_title' => '#product_title',
        'dropzone_product_description' => '#product_description',
        'dropzone_product_pricing' => '#product_pricing',
        'dropzone_product_gallery' => '#product_gallery',
        'variant_style' => 'dropdown',
        'link_to' => 'none',
        'excludes' => false,
        'post_id' => $post->ID,
        'pagination' => false,
        'limit' => 1,
    ])
);
?>

<section class="wps-container">

   <style>

      .wps-breadcrumbs-name {
         text-transform: capitalize;
      }

      .single-wps_products .wps-breadcrumbs + .wps-product-single {
            margin-top: 0;
         }

      .single-wps_products .wps-product-single {
         margin-top: 1em;
         margin-bottom: 4em;
         display: flex;
      }

      .single-wps_products .wps-product-single-content,
      .single-wps_products .wps-product-single-gallery {
         width: 50%;
         max-width: 50%;
         flex: 0 0 50%;
      }

      .single-wps_products .wps-product-single-content {
         padding: 0em 2em 2em 2em;
         width: calc(50% - 4em);
         max-width: calc(50% - 4em);
         flex: 0 0 calc(50% - 4em);
      }

      .single-wps_products .wps-component-products-title .wps-products-title {
         margin-top: 0;
         font-size: 34px;
      }

      .single-wps_products .wps-container {
         padding: 0 1em;
         max-width: 1100px;
         margin: 0 auto;
      }

      @media (max-width: 600px) {

         .single-wps_products .wps-product-single {
            flex-direction: column;
         }

         .single-wps_products .wps-container {
            padding: 0 1em;
         }

         .single-wps_products .wps-product-single-content,
         .single-wps_products .wps-product-single-gallery {
            width: 100%;
            max-width: 100%;
            flex: 0 0 100%;
            padding: 0;
         }

         .single-wps_products .wps-product-single-content {
            width: calc(100% - 4em);
            max-width: calc(100% - 4em);
            flex: 0 0 calc(100% - 4em);
         }

         .single-wps_products .wps-product-single .wps-product-image-wrapper .wps-product-image {
            margin: 0 auto;
            display: block;      
         }

      }

   </style>

   <?= do_action('wps_breadcrumbs') ?>
      
   <div class="wps-product-single">

      <div class="wps-product-single-gallery">
         <div id="product_gallery"></div>
      </div>

      <div class="wps-product-single-content">
         
         <div id="product_title"></div>
         <div id="product_pricing"></div>
         <div id="product_description"></div>
         <div id="product_buy_button"></div>

      </div>

   </div>

</section>

<?php get_footer('wpshopify');
