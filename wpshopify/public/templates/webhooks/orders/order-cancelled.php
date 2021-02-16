<?php

$DB_Orders = WP_Shopify\Factories\DB\Orders_Factory::build();

$DB_Orders->update_items_of_type($data);