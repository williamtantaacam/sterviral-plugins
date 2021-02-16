<?php

namespace WP_Shopify\API\Admin;

if (!defined('ABSPATH')) {
    exit();
}

// Convenience wrappers for consuming the Storefront API
class Variants
{
    public function __construct($Admin, $Admin_Variant_Queries)
    {
        $this->Admin = $Admin;
        $this->Admin_Variant_Queries = $Admin_Variant_Queries;
    }

    public function get_variants_inventory_tracked($ids)
    {
        return $this->Admin->graphql_api_request(
            $this->Admin_Variant_Queries->graph_query_variants_inventory_tracked(
                $ids
            )
        );
    }
}
