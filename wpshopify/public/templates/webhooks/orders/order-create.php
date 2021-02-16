<?php

$DB_Orders = WP_Shopify\Factories\DB\Orders_Factory::build();

$DB_Orders->insert_items_of_type($data);
