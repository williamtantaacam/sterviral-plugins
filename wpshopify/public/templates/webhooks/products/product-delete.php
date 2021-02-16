<?php

use WP_Shopify\Utils\Data as Utils_Data;

$DB_Products = WP_Shopify\Factories\DB\Products_Factory::build();
$DB_Variants = WP_Shopify\Factories\DB\Variants_Factory::build();
$DB_Options = WP_Shopify\Factories\DB\Options_Factory::build();
$DB_Images = WP_Shopify\Factories\DB\Images_Factory::build();
$DB_Collects = WP_Shopify\Factories\DB\Collects_Factory::build();
$DB_Tags = WP_Shopify\Factories\DB\Tags_Factory::build();
$DB_Posts = WP_Shopify\Factories\DB\Posts_Factory::build();
$DB_Settings_General = WP_Shopify\Factories\DB\Settings_General_Factory::build();

$sync_by_collections = maybe_unserialize($DB_Settings_General->get_col_value('sync_by_collections'));
$product_id = $data->product_listing->product_id;

if (!empty($sync_by_collections)) {

   $collection_ids = Utils_Data::map_array_prop($sync_by_collections, 'id');

   // If updated a product not apart of selected collections, exit
   if (!$DB_Products->is_product_in_selected_collections($product_id, $collection_ids)) {
      error_log('WP Shopify Warning: You have sync by collections enabled but your recently deleted product, "' . $product_id .'", does not belong to any collections. Therefore, it was not deleted in WordPress.');
      exit;
   }

}

$post_id = $DB_Products->get_post_id_from_product_id($product_id);

$DB_Products->delete_products_from_product_id($product_id);
$DB_Variants->delete_variants_from_product_id($product_id);
$DB_Options->delete_options_from_product_id($product_id);
$DB_Images->delete_images_from_product_id($product_id);
$DB_Tags->delete_tags_from_product_id($product_id);
$DB_Posts->delete_posts_by_ids($post_id);

WP_Shopify\Transients::delete_cached_product_single();
WP_Shopify\Transients::delete_cached_product_queries();
