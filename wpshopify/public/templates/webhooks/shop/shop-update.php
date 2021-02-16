<?php

$DB_Shop = WP_Shopify\Factories\DB\Shop_Factory::build();

$DB_Shop->update_items_of_type($data);
