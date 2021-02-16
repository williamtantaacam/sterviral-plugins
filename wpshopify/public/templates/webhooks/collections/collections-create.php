<?php

use WP_Shopify\Transients;

$CPT_Model            = WP_Shopify\Factories\CPT_Model_Factory::build();
$DB_Collections       = WP_Shopify\Factories\DB\Collections_Factory::build();
$DB_Collects          = WP_Shopify\Factories\DB\Collects_Factory::build();
$API_Items_Collects   = WP_Shopify\Factories\API\Items\Collects_Factory::build();


$post_id               = $CPT_Model->insert_or_update_collection_post( $data );
$collects_result       = $DB_Collects->modify_from_shopify(
                           $DB_Collects->modify_options($API_Items_Collects->get_collects_from_collection($data), WP_SHOPIFY_COLLECTIONS_LOOKUP_KEY)
                        );

$collection_result     = $DB_Collections->insert_items_of_type( $DB_Collections->mod_before_change($data, $post_id) );

Transients::delete_cached_collection_queries();
Transients::delete_cached_single_collections();