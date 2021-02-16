<?php

namespace WP_Shopify\API\Admin\Variants;

if (!defined('ABSPATH')) {
    exit();
}

class Queries
{
    public function graph_query_variants_inventory_tracked($ids)
    {
        return [
            "query" => 'query($ids: [ID!]!) {
               nodes(ids: $ids) {
                  ... on ProductVariant {
                     id
                     inventoryPolicy
                     inventoryItem {
                        tracked
                        inventoryLevels(first: 5) {
                           edges {
                              node {
                                 available
                              }
                           }
                        }
                     }
                     
                  }
               }
            }',
            "variables" => [
                'ids' => $ids,
            ],
        ];
    }
}
