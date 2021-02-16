<?php

namespace WP_Shopify\Render\Cart;

use WP_Shopify\Utils\Data;

if (!defined('ABSPATH')) {
    exit();
}

class Defaults
{
    public $plugin_settings;
    public $Render_Attributes;

    public function __construct($plugin_settings, $Render_Attributes)
    {
        $this->plugin_settings = $plugin_settings;
        $this->Render_Attributes = $Render_Attributes;
    }

    public function all_cart_icon_attributes($attrs)
    {
        return [
            'render_from_server' => $this->Render_Attributes->attr(
                $attrs,
                'render_from_server',
                false
            ),
            'icon' => $this->Render_Attributes->attr($attrs, 'icon', false),
            'type' => $this->Render_Attributes->attr($attrs, 'type', 'inline'),
            'inline_background_color' => $this->Render_Attributes->attr(
                $attrs,
                'inline_background_color',
                $this->plugin_settings['general']['cart_counter_color']
            ),
            'inline_cart_counter_text_color' => $this->Render_Attributes->attr(
                $attrs,
                'inline_cart_counter_text_color',
                $this->plugin_settings['general'][
                    'inline_cart_counter_text_color'
                ]
            ),
            'inline_icon_color' => $this->Render_Attributes->attr(
                $attrs,
                'cart_icon_color',
                $this->plugin_settings['general']['cart_icon_color']
            ),
            'show_counter' => $this->Render_Attributes->attr(
                $attrs,
                'show_counter',
                true
            ),
            'data_type' => $this->Render_Attributes->attr(
                $attrs,
                'data_type',
                false
            ),
            'hide_wrapper' => $this->Render_Attributes->attr(
                $attrs,
                'hide_wrapper',
                false
            ),
            'fixed_background_color' => $this->Render_Attributes->attr(
                $attrs,
                'cart_fixed_background_color',
                $this->plugin_settings['general']['cart_fixed_background_color']
            ),
            'fixed_counter_color' => $this->Render_Attributes->attr(
                $attrs,
                'cart_counter_fixed_color',
                $this->plugin_settings['general']['cart_counter_fixed_color']
            ),
            'fixed_icon_color' => $this->Render_Attributes->attr(
                $attrs,
                'cart_icon_fixed_color',
                $this->plugin_settings['general']['cart_icon_fixed_color']
            ),
        ];
    }

    public function cart_icon($attrs)
    {
        return $this->all_cart_icon_attributes($attrs);
    }
}
