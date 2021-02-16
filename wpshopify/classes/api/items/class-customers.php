<?php

namespace WP_Shopify\API\Items;

use WP_Shopify\Messages;
use WP_Shopify\Utils;
use WP_Shopify\Utils\Data as Utils_Data;

if (!defined('ABSPATH')) {
    exit();
}

class Customers extends \WP_Shopify\API
{
    public function __construct(
        $DB_Settings_General,
        $DB_Settings_Syncing,
        $Shopify_API,
        $Processing_Customers,
        $DB_Customers,
        $DB_Settings_Connection,
        $Storefront_Customers,
        $Admin_Customers
    ) {
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->Shopify_API = $Shopify_API;
        $this->Processing_Customers = $Processing_Customers;
        $this->DB_Customers = $DB_Customers;
        $this->DB_Settings_Connection = $DB_Settings_Connection;
        $this->Storefront_Customers = $Storefront_Customers;
        $this->Admin_Customers = $Admin_Customers;
    }

}
