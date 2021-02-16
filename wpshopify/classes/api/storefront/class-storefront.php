<?php

namespace WP_Shopify\API;

use WP_Shopify\Utils;

if (!defined('ABSPATH')) {
	exit;
}


class Storefront {

	public function __construct($DB_Settings_Connection) {
		$this->DB_Settings_Connection = $DB_Settings_Connection;
	}

   public function graphql_api_endpoint() {
      return 'https://' . $this->DB_Settings_Connection->get_domain() . '/api/graphql';
   }

   public function graphql_api_headers() {

      return [
         'X-Shopify-Storefront-Access-Token' => $this->DB_Settings_Connection->get_storefront_access_token(),
         'Content-type' => 'application/json'
      ];
   }

   public function post($endpoint, $options) {
      return wp_remote_post($endpoint, $options);      
   }

   public function graph_post_options($query) {
      return [
         'headers' => $this->graphql_api_headers(),
         'body' => json_encode($query)
      ];
   }


   /*
   
   Params: 
      $query: The raw graphql query
      $type_key: The object property on the returned error. For example: c. Allows for dynamically fetching API errors.

   Returns: WP_Error or data from API
   
   */
   public function graphql_api_request($query, $type_key = false) {
      return $this->return_proper_response(
         $this->post(
            $this->graphql_api_endpoint(), 
            $this->graph_post_options($query)
         ),
         $type_key
      );
   }

   public function return_proper_response($response, $type_key = false) {

      $response_code       = wp_remote_retrieve_response_code($response);
      $response_message    = wp_remote_retrieve_response_message($response);
      $body                = json_decode(wp_remote_retrieve_body($response));

      if ( 200 != $response_code && !empty( $response_message ) ) {

         return Utils::wp_error($response_message);

      } elseif ( 200 != $response_code ) {

         return Utils::wp_error('Unknown error occurred while calling Shopify');

      } elseif (property_exists($body, 'errors')) {

         return Utils::wp_error($body->errors[0]->message);
      
      } elseif (property_exists($body, 'data') && property_exists($body->data, $type_key) && !empty($body->data->{$type_key}->customerUserErrors)) {

         return Utils::wp_error($body->data->{$type_key}->customerUserErrors[0]->message);

      } else {
         return json_decode(wp_remote_retrieve_body($response))->data;
      }
      
   }

}
