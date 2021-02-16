<?php

$DB_Collects       = WP_Shopify\Factories\DB\Collects_Factory::build();
$DB_Collections    = WP_Shopify\Factories\DB\Collections_Factory::build();
$DB_Posts          = WP_Shopify\Factories\DB\Posts_Factory::build();

$post_result        = $DB_Posts->delete_posts_by_ids( $DB_Collections->get_post_id_from_collection($data) );
$collects_result    = $DB_Collects->delete_collects_from_collection_id($data->id);
$data_result        = $DB_Collections->delete_collection_from_collection_id($data->id);

WP_Shopify\Transients::delete_cached_collection_queries();
WP_Shopify\Transients::delete_cached_single_collections();