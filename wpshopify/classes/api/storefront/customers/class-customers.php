<?php

namespace WP_Shopify\API\Storefront;

if (!defined('ABSPATH')) {
	exit;
}

/*

Convenience wrappers for consuming the Storefront API

*/
class Customers {

   public function __construct($Storefront, $Storefront_Queries) {
      $this->Storefront = $Storefront;
		$this->Storefront_Queries = $Storefront_Queries;
   }
   
   public function create_customer($email, $password) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->graph_query_customer_create($email, $password), 'customerCreate');
   }

   public function get_customer($customer_access_token) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->graph_query_customer($customer_access_token), 'customer');
   }

   public function create_customer_access_token($email, $password) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->graph_query_customer_access_token_create($email, $password), 'customerAccessTokenCreate');
   }

   public function reset_customer_password($customer_id, $reset_token, $password) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->graph_query_customer_reset($customer_id, $reset_token, $password), 'customerReset');
   }

   public function recover_password($email) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->graph_query_customer_recover_password($email), 'customerRecover');
   }

   public function update_customer_address($customer_access_token, $address_id, $new_address) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->graph_query_customer_address_update($customer_access_token, $address_id, $new_address), 'customerAddressUpdate');
   }

   public function update_customer_default_address($customer_access_token, $address_id) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->graph_query_customer_address_set_default($customer_access_token, $address_id), 'customerDefaultAddressUpdate');
   }

   public function add_customer_address($customer_access_token, $address) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->graph_query_customer_address_add($customer_access_token, $address), 'customerAddressCreate');
   }

   public function delete_customer_address($customer_access_token, $address_id) {
      return $this->Storefront->graphql_api_request($this->Storefront_Queries->graph_query_customer_address_delete($customer_access_token, $address_id), 'customerAddressDelete');
   }

}
