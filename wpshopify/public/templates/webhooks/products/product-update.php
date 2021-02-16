<?php

use WP_Shopify\Transients as Transients;
use WP_Shopify\Utils\Data as Utils_Data;

$CPT_Model = WP_Shopify\Factories\CPT_Model_Factory::build();
$DB_Products = WP_Shopify\Factories\DB\Products_Factory::build();
$DB_Settings_General = WP_Shopify\Factories\DB\Settings_General_Factory::build();
$DB_Tags = WP_Shopify\Factories\DB\Tags_Factory::build();
$DB_Variants = WP_Shopify\Factories\DB\Variants_Factory::build();
$DB_Options = WP_Shopify\Factories\DB\Options_Factory::build();
$DB_Images = WP_Shopify\Factories\DB\Images_Factory::build();
$DB_Collects = WP_Shopify\Factories\DB\Collects_Factory::build();
$API_Items_Collects = WP_Shopify\Factories\API\Items\Collects_Factory::build();

// Needed because of discrepancies between Shopify's API Product and ProductListing endpoints
$data = $data->product_listing;
$data = $DB_Products->switch_shopify_ids($data, 'product_id', 'id');

$sync_by_collections = maybe_unserialize($DB_Settings_General->get_col_value('sync_by_collections'));


if (!empty($sync_by_collections)) {

   $collection_ids = Utils_Data::map_array_prop($sync_by_collections, 'id');

   // If updated a product not apart of selected collections, exit
   if (!$DB_Products->is_product_in_selected_collections($data->id, $collection_ids)) {
      error_log('WP Shopify Warning: You have sync by collections enabled but your recently updated product, "' . $data->title .'", does not belong to any collections. Therefore, it was not updated in WordPress.');
      exit;
   }

}

$array_of_post_ids = $DB_Products->get_post_id_by_product_id($data->id);

if (empty($array_of_post_ids)) {
   $current_post_id = false;

} else {
   $current_post_id = $array_of_post_ids[0];
}

$post_id = $CPT_Model->insert_or_update_product_post($data, $current_post_id);

$variants_result = $DB_Variants->modify_from_shopify(
    $DB_Variants->modify_options(
        $DB_Variants->maybe_add_product_id_to_variants($data)
    )
);

$options_result = $DB_Options->modify_from_shopify(
    $DB_Options->modify_options($data)
);

$images_result = $DB_Images->modify_from_shopify(
    $DB_Images->modify_options($data)
);

$tags_result = $DB_Tags->modify_from_shopify(
    $DB_Tags->modify_options(
        $DB_Tags->add_tags_to_product(
            $DB_Tags->construct_tags_for_insert($data, $post_id),
            $data
        )
    )
);

if ($DB_Products->product_exists($data->id)) {
    $data_result = $DB_Products->update_items_of_type($data);

} else {
    $data_result = $DB_Products->insert_items_of_type(
        $DB_Products->mod_before_change($data, $post_id)
    );
}

Transients::delete_cached_single_product_by_id($post_id);
Transients::delete_cached_product_queries();
