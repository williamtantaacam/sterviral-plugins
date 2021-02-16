<?php

namespace WP_Shopify\API\Admin\Customers;

if (!defined('ABSPATH')) {
	exit;
}

class Queries {

   public function graph_query_customer_by_email($query) {

      return [
         "query" => 'query($query: String!) {
            customers(first: 1, query:$query) {
               edges {
                  node {
                     displayName
                     email
                     firstName
                  }
               }
            }
         }',
         "variables" => [
            'query' => $query
         ]
      ];
   }

}