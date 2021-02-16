<?php

namespace WP_Shopify\API\Storefront\Customers;

if (!defined('ABSPATH')) {
	exit;
}

class Queries {

   public function graph_query_customer_reset($customer_id, $reset_token, $password) {
      return [
         "query" => 'mutation customerReset($id: ID!, $input: CustomerResetInput!) {
            customerReset(id: $id, input: $input) {
               customer {
                  id
                  displayName
               }
               customerAccessToken {
                  accessToken
                  expiresAt
               }
               customerUserErrors {
                  code
                  field
                  message
               }
            }
         }',
         "variables" => [
            'id' => $customer_id,
            'input' => [
               'resetToken' => $reset_token,
               'password' => $password
            ]
         ]
      ];
   }

   public function graph_query_customer($customer_access_token) {
      return [
         "query" => 'query($customerAccessToken: String!) {
            customer(customerAccessToken: $customerAccessToken) {
               acceptsMarketing
               createdAt
               defaultAddress {
                  address1
               }
               displayName
               email
               firstName
               id
               lastIncompleteCheckout {
                  completedAt
               }
               lastName
               phone
               tags
               updatedAt
               orders(first: 10) {
                  edges {
                     node {
                        discountApplications(first:10) {
                           edges {
                              node {
                                 allocationMethod
                                 targetSelection
                                 targetType
                                 value
                              }
                           }
                        }
                        lineItems(first: 20) {
                           edges {
                              node {
                                 customAttributes {
                                    key
                                    value
                                 }
                                 discountAllocations {
                                    allocatedAmount {
                                       amount
                                       currencyCode
                                    }
                                    discountApplication {
                                       allocationMethod
                                       targetSelection
                                       targetType
                                       value
                                    }
                                 }
                                 quantity
                                 title
                                 variant {
                                    metafields(first: 10) {
                                       edges {
                                          node {
                                             description
                                             id
                                             key
                                             namespace
                                             parentResource
                                             value
                                             valueType
                                          }
                                       }
                                    }
                                    presentmentPrices(first: 10) {
                                       edges {
                                          node {
                                             compareAtPrice {
                                                amount
                                                currencyCode
                                             }
                                             price {
                                                amount
                                                currencyCode                                                
                                             }
                                          }
                                       }
                                    }
                                    availableForSale
                                    compareAtPriceV2 {
                                       amount
                                       currencyCode
                                    }
                                    id
                                    image {
                                       altText
                                       id
                                       originalSrc
                                       transformedSrc
                                    }
                                    priceV2 {
                                       amount
                                       currencyCode                                                
                                    }
                                    requiresShipping
                                    selectedOptions {
                                       name
                                       value
                                    }
                                    sku
                                    title
                                    weight
                                    weightUnit
                                 }
                              }
                           }
                        }
                        currencyCode
                        customerLocale
                        customerUrl
                        email
                        id
                        name
                        orderNumber
                        phone
                        processedAt
                        shippingAddress {
                           address1
                           address2
                           city
                           company
                           country
                           countryCodeV2
                           firstName
                           formatted
                           formattedArea
                           id
                           lastName
                           latitude
                           longitude
                           name
                           phone
                           province
                           provinceCode
                           zip   
                        }
                        shippingDiscountAllocations {
                           discountApplication {
                              allocationMethod
                              targetSelection
                              targetType
                              value
                           }
                        }
                        statusUrl
                        subtotalPriceV2 {
                           amount
                           currencyCode
                        }
                        successfulFulfillments {
                           trackingCompany
                        }
                        totalPriceV2 {
                           amount
                           currencyCode                           
                        }
                        totalRefundedV2 {
                           amount
                           currencyCode                           
                        }
                        totalShippingPriceV2 {
                           amount
                           currencyCode                           
                        }
                     }
                  }
               }
               addresses(first:10) {
                  edges {
                     node {
                        address1
                        address2
                        city
                        company
                        country
                        countryCodeV2
                        firstName
                        formatted
                        formattedArea
                        id
                        lastName
                        latitude
                        longitude
                        name
                        phone
                        province
                        provinceCode
                     }
                  }
               }
            }
         }',
         "variables" => [
            'customerAccessToken' => $customer_access_token
         ]
      ];
   }


   public function graph_query_customer_access_token_create($email, $password) {
      return [
         "query" => 'mutation customerAccessTokenCreate($input: CustomerAccessTokenCreateInput!) {
            customerAccessTokenCreate(input: $input) {
               customerUserErrors {
                  field
                  message
               }
               customerAccessToken {
                  accessToken
                  expiresAt
               }
            }
         }',
         "variables" => [
            'input' => [
               'email' => $email,
               'password' => $password
            ]
         ]
      ];
   }


   public function graph_query_customer_create($email, $password) {

      return [
         "query" => 'mutation customerCreate($input: CustomerCreateInput!) {
            customerCreate(input: $input) {
               userErrors {
                  field
                  message
               }
               customer {
                  id
               }
            }
         }',
         "variables" => [
            'input' => [
               'email' => $email,
               'password' => $password
            ]
         ]
      ];

   }


   public function graph_query_customer_reset_by_url($reset_url, $password) {
      return [
         "query" => 'mutation customerResetByUrl($resetUrl: URL!, $password: String!) {
            customerResetByUrl(resetUrl: $resetUrl, password: $password) {
               customer {
                  id
               }
               customerAccessToken {
                  accessToken
                  expiresAt
               }
               customerUserErrors {
                  code
                  field
                  message
               }
            }
         }',
         "variables" => [
            'resetUrl' => 'https://scripts.blog/account/reset/2068600717360',
            'password' => $password
         ]
         ];   
   }


   public function graph_query_customer_associate() {
      return [
         "query" => 'mutation checkoutCustomerAssociateV2($checkoutId: ID!, $customerAccessToken: String!) {
            checkoutCustomerAssociateV2(checkoutId: $checkoutId, customerAccessToken: $customerAccessToken) {
               checkout {
                  id
                  webUrl
               }
               checkoutUserErrors {
                  code
                  field
                  message
               }
               customer {
                  id
               }
            }
         }',
         "variables" => [
            'checkoutId' => 'Z2lkOi8vc2hvcGlmeS9DaGVja291dC82ZWVlYmFjZjY2YmZmYjUzMjAxNWZjODI2Y2RjZWZjMD9rZXk9MWQ3YzFjNGQxYjgzNTk1NWNhYzE1NDFiNDBhOTRjMGI=',
            'customerAccessToken' => '72b2c955e43fc2cf484d502d2038b389'
         ]
      ];      
   }

   
   public function graph_query_customer_recover_password($email) {
      return [
         "query" => 'mutation customerRecover($email: String!) {
            customerRecover(email: $email) {
               userErrors {
                  field
                  message
               }
            }
         }',
         "variables" => [
            'email' => $email,
         ]
      ];
   }


   public function graph_query_customer_address_update($customer_access_token, $address_id, $new_address) {
      return [
         "query" => 'mutation customerAddressUpdate($customerAccessToken: String!, $id: ID!, $address: MailingAddressInput!) {
            customerAddressUpdate(customerAccessToken: $customerAccessToken, id: $id, address: $address) {
               customerAddress {
                  id,
                  address1
                  address2
                  city
                  company
                  country
                  countryCodeV2
                  firstName
                  formatted
                  formattedArea
                  lastName
                  latitude
                  longitude
                  name
                  phone
                  province
                  provinceCode
                  zip
               }
               customerUserErrors {
                  code
                  field
                  message
               }
            }
         }',
         "variables" => [
            "customerAccessToken" => $customer_access_token,
            "id" => $address_id,
            "address" => $new_address
         ]
      ];
   }

   public function graph_query_customer_address_set_default($customer_access_token, $address_id) {
      return [
         "query" => 'mutation customerDefaultAddressUpdate($customerAccessToken: String!, $addressId: ID!) {
            customerDefaultAddressUpdate(customerAccessToken: $customerAccessToken, addressId: $addressId) {
               customer {
                  id
                  addresses(first:10) {
                     edges {
                        node {
                           address1
                           address2
                           city
                           company
                           country
                           countryCodeV2
                           firstName
                           formatted
                           formattedArea
                           id
                           lastName
                           latitude
                           longitude
                           name
                           phone
                           province
                           provinceCode
                        }
                     }
                  }
                  defaultAddress {
                     address1
                  }
               }
               customerUserErrors {
                  code
                  field
                  message
               }
            }
         }',
         "variables" => [
            "customerAccessToken" => $customer_access_token,
            "addressId" => $address_id
         ]
      ];
   }

   public function graph_query_customer_address_add($customer_access_token, $address) {
      return [
         "query" => 'mutation customerAddressCreate($customerAccessToken: String!, $address: MailingAddressInput!) {
            customerAddressCreate(customerAccessToken: $customerAccessToken, address: $address) {
               customerAddress {
                  address1
                  address2
                  city
                  company
                  country
                  countryCodeV2
                  firstName
                  formatted
                  formattedArea
                  id
                  lastName
                  latitude
                  longitude
                  name
                  phone
                  province
                  provinceCode
                  zip
               }
               customerUserErrors {
                  code
                  field
                  message
               }
            }
         }',
         "variables" => [
            "customerAccessToken" => $customer_access_token,
            "address" => $address
         ]
      ];
   }
   
   public function graph_query_customer_address_delete($customer_access_token, $address_id) {
      return [
         "query" => 'mutation customerAddressDelete($id: ID!, $customerAccessToken: String!) {
            customerAddressDelete(id: $id, customerAccessToken: $customerAccessToken) {
               customerUserErrors {
                  code
                  field
                  message
               }
               deletedCustomerAddressId
            }
         }',
         "variables" => [
            "customerAccessToken" => $customer_access_token,
            "id" => $address_id
         ]
      ];
   }


}
