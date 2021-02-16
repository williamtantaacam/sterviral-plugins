<?php

defined('ABSPATH') ?: die();

get_header('wpshopify');

$Products = WP_Shopify\Factories\Render\Products\Products_Factory::build();
$Settings = WP_Shopify\Factories\DB\Settings_General_Factory::build();

$paged_var = get_query_var('paged');
$paged = $paged_var ? $paged_var : 1;
$post_id = $Settings->get_col_value('page_products', 'int');
$num_posts = $Settings->get_col_value('num_posts', 'int');

$is_showing_heading = $Settings->get_col_value(
    'products_heading_toggle',
    'bool'
);

$description_toggle = $Settings->get_col_value(
    'products_plp_descriptions_toggle',
    'bool'
);

if (!$description_toggle) {
    $products_args = [
        'excludes' => ['description'],
    ];
} else {
    $products_args = [
        'excludes' => [],
    ];
}

$posts_ids = get_posts([
    'post_type' => 'wps_products',
    'posts_per_page' => -1,
    'fields' => 'ids',
]);

$products_args['post_id'] = array_slice($posts_ids, -250, 250, true);
$products_args['connective'] = 'OR';
$products_args['page_size'] = $num_posts;
$products_args['available_for_sale'] = true;
?>

<style>
   .wps-breadcrumbs {
      max-width: 1100px;
      margin: 0 auto;
   }

   .wps-breadcrumbs-name {
         text-transform: capitalize;
      }

   .wps-products-wrapper {
      display: flex;
      padding: 2em 0;
   }

   .wps-pagination {
      text-align: center;
      padding: 2em 0;
      font-size: 20px;      
   }

   .wps-products-content {
      flex: 1;
   }

   .wps-products-sidebar {
      width: 30%;
   }

   .wps-heading {
      text-align: center;
      margin-bottom: 1em;
   }
</style>

<section class="wps-products-wrapper wps-container">

   <div class="wps-products-content">
      
   <?php if ($is_showing_heading) { ?>

      <header class="wps-products-header">
         <h1 class="wps-heading">
            <?= apply_filters(
                'wps_products_all_title',
                $Settings->get_col_value('products_heading', 'string')
            ) ?>
         </h1>
      </header>

   <?php } ?>
      
      <?= do_action('wps_breadcrumbs') ?>

         <div class="wps-products-all">
            <?php $Products->products(
                apply_filters('wps_products_all_args', $products_args)
            ); ?>
         </div>

         <?php if (
             apply_filters('wps_products_all_show_post_content', true)
         ) { ?>
         <section class="wps-page-content">
            <?= do_shortcode(
                apply_filters(
                    'wps_products_all_content',
                    get_post_field('post_content', $post_id)
                )
            ) ?>
         </section>
      <?php } ?>

   </div>

   <?php if (apply_filters('wps_products_show_sidebar', false)) { ?>
      <div class="wps-sidebar wps-products-sidebar">
         <?= get_sidebar('wpshopify') ?>
      </div>
   <?php } ?>

</section>

<?php get_footer('wpshopify');
