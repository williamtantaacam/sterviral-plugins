<?php

namespace WP_Shopify\Render;

use WP_Shopify\Utils\Data as Utils_Data;

if (!defined('ABSPATH')) {
    exit();
}

class Templates
{
    public $Template_Loader;
    public $Products_Defaults;

    public function __construct($Template_Loader, $Products_Defaults)
    {
        $this->Template_Loader = $Template_Loader;
        $this->Products_Defaults = $Products_Defaults;
    }

    public function merge_user_component_data(
        $user_data = [],
        $component_type,
        $component_class
    ) {
      if (!$user_data) {
         $user_data = [];
      }

      $default_vals = $component_class->$component_type($user_data);

      $default_vals_formatted = Utils_Data::standardize_layout_data(array_replace_recursive($default_vals, $user_data));

      $query = $this->Products_Defaults->create_product_query($default_vals_formatted);

      $default_vals_formatted['query'] = $query;         

      return $default_vals_formatted;
    }

    public function params_client_render($params)
    {
        return [
            'data' => $params,
            'path' => 'components/wrapper/wrapper',
            'name' => 'client',
        ];
    }

    public function set_and_get_template($params)
    {
        return $this->Template_Loader
            ->set_template_data($params['data'])
            ->get_template_part($params['path'], $params['name']);
    }

    public function load($params)
    {
        return $this->set_and_get_template(
            $this->params_client_render($params)
        );
    }
}
