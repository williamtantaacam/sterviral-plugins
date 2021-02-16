<?php

namespace WP_Shopify\API\Items;

use WP_Shopify\Messages;
use WP_Shopify\Utils;
use WP_Shopify\Utils\Data as Utils_Data;

if (!defined('ABSPATH')) {
    exit();
}

class Orders extends \WP_Shopify\API
{
    public function __construct(
        $DB_Settings_General,
        $DB_Settings_Syncing,
        $Shopify_API,
        $Processing_Orders
    ) {
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->Shopify_API = $Shopify_API;
        $this->Processing_Orders = $Processing_Orders;
    }

}
