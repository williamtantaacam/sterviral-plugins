<?php

namespace WP_Shopify\API\Admin;

if (!defined('ABSPATH')) {
	exit;
}

/*

Convenience wrappers for consuming the Storefront API

*/
class Customers {

   public function __construct($Admin, $Admin_Queries) {
      $this->Admin = $Admin;
		$this->Admin_Queries = $Admin_Queries;
   }
   
   public function get_customer_by_email($email) {
      return $this->Admin->graphql_api_request($this->Admin_Queries->graph_query_customer_by_email($email), 'customerReset');
   }

}
