<?php

use WP_Shopify\Options;
use WP_Shopify\Utils;

$Root = WP_Shopify\Factories\Render\Root_Factory::build();

$encoded_options = $Root->encode_component_data($data->data);
$component_id = $Root->generate_component_id($encoded_options);

$Root->render_root_component([
    'component_type' => $data->type,
    'component_id' => $component_id,
    'component_options' => $encoded_options,
]);

?>
