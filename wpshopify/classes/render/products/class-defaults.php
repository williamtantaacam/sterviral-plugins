<?php

namespace WP_Shopify\Render\Products;

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

    public function lowercase_filter_params($filter_params)
    {
        return array_map(function ($value) {
            if (is_array($value)) {
                return $this->lowercase_filter_params($value);
            }

            if (is_string($value)) {
                return strtolower($value);
            }

            return $value;
        }, $filter_params);
    }

    public function create_product_query($user_atts)
    {
        $filter_params = $this->Render_Attributes->get_products_filter_params_from_shortcode(
            $user_atts
        );

        if (!isset($user_atts['connective'])) {
            if (empty($user_atts)) {
                $user_atts = [];
            }

            $user_atts['connective'] = 'AND';
        }

        $final_query = $this->Render_Attributes->build_query(
            $this->lowercase_filter_params($filter_params),
            $user_atts
        );

        return $final_query;
    }

    public function product_add_to_cart($attrs = [])
    {
        return array_replace($this->all_products_attributes($attrs), [
            'excludes' => $this->Render_Attributes->attr($attrs, 'excludes', [
                'title',
                'pricing',
                'description',
                'images',
            ]),
            'component_type' => $this->Render_Attributes->attr(
                $attrs,
                'component_type',
                'products/buy-button'
            ),
            'is_single_component' => $this->Render_Attributes->attr(
                $attrs,
                'is_single_component',
                true
            ),
            'link_to' => $this->Render_Attributes->attr(
                $attrs,
                'link_to',
                'none'
            ),
        ]);
    }

    public function product_buy_button($attrs)
    {
        return $this->product_add_to_cart($attrs);
    }

    public function product_title($attrs)
    {
        return array_replace($this->all_products_attributes($attrs), [
            'excludes' => $this->Render_Attributes->attr($attrs, 'excludes', [
                'description',
                'buy-button',
                'images',
                'pricing',
            ]),
            'component_type' => $this->Render_Attributes->attr(
                $attrs,
                'component_type',
                'products/title'
            ),
            'is_single_component' => $this->Render_Attributes->attr(
                $attrs,
                'is_single_component',
                true
            ),
        ]);
    }

    public function product_description($attrs)
    {
        return array_replace($this->all_products_attributes($attrs), [
            'excludes' => $this->Render_Attributes->attr($attrs, 'excludes', [
                'title',
                'buy-button',
                'images',
                'pricing',
            ]),
            'component_type' => $this->Render_Attributes->attr(
                $attrs,
                'component_type',
                'products/description'
            ),
            'is_single_component' => $this->Render_Attributes->attr(
                $attrs,
                'is_single_component',
                true
            ),
        ]);
    }

    public function product_pricing($attrs)
    {
        return array_replace($this->all_products_attributes($attrs), [
            'excludes' => $this->Render_Attributes->attr($attrs, 'excludes', [
                'title',
                'buy-button',
                'images',
                'description',
            ]),
            'component_type' => $this->Render_Attributes->attr(
                $attrs,
                'component_type',
                'products/pricing'
            ),
            'is_single_component' => $this->Render_Attributes->attr(
                $attrs,
                'is_single_component',
                true
            ),
        ]);
    }

    public function product_gallery($attrs)
    {
        return array_replace($this->all_products_attributes($attrs), [
            'excludes' => $this->Render_Attributes->attr($attrs, 'excludes', [
                'title',
                'pricing',
                'description',
                'buy-button',
            ]),
            'component_type' => $this->Render_Attributes->attr(
                $attrs,
                'component_type',
                'products/images'
            ),
            'is_single_component' => $this->Render_Attributes->attr(
                $attrs,
                'is_single_component',
                true
            ),
        ]);
    }

    public function products($attrs)
    {
        return $this->all_products_attributes($attrs);
    }

    public function products_settings_attributes($attrs)
    {
        return [
            'add_to_cart_button_text' => $this->Render_Attributes->attr(
                $attrs,
                'add_to_cart_button_text',
                WP_SHOPIFY_DEFAULT_ADD_TO_CART_TEXT
            ),
            'add_to_cart_button_text_color' => $this->Render_Attributes->attr(
                $attrs,
                'add_to_cart_button_text_color',
                false
            ),
            'add_to_cart_button_color' => $this->Render_Attributes->attr(
                $attrs,
                'add_to_cart_button_color',
                $this->plugin_settings['general']['add_to_cart_color']
            ),
            'add_to_cart_button_font' => $this->Render_Attributes->attr(
                $attrs,
                'add_to_cart_button_font',
                false
            ), // TODO: Discouraged since 3.5.1. Need to remove in 4.0
            'add_to_cart_button_font_weight' => $this->Render_Attributes->attr(
                $attrs,
                'add_to_cart_button_font_weight',
                false
            ), // TODO: Discouraged since 3.5.1. Need to remove in 4.0
            'add_to_cart_button_font_size' => $this->Render_Attributes->attr(
                $attrs,
                'add_to_cart_button_font_size',
                false
            ), // TODO: Discouraged since 3.5.1. Need to remove in 4.0

            'add_to_cart_button_type_font_family' => $this->Render_Attributes->attr(
                $attrs,
                'add_to_cart_button_type_font_family',
                false
            ),
            'add_to_cart_button_type_font_size' => $this->Render_Attributes->attr(
                $attrs,
                'add_to_cart_button_type_font_size',
                false
            ),
            'add_to_cart_button_type_font_weight' => $this->Render_Attributes->attr(
                $attrs,
                'add_to_cart_button_type_font_weight',
                false
            ),
            'add_to_cart_button_type_text_transform' => $this->Render_Attributes->attr(
                $attrs,
                'add_to_cart_button_type_text_transform',
                false
            ),
            'add_to_cart_button_type_font_style' => $this->Render_Attributes->attr(
                $attrs,
                'add_to_cart_button_type_font_style',
                false
            ),
            'add_to_cart_button_type_text_decoration' => $this->Render_Attributes->attr(
                $attrs,
                'add_to_cart_button_type_text_decoration',
                false
            ),
            'add_to_cart_button_type_line_height' => $this->Render_Attributes->attr(
                $attrs,
                'add_to_cart_button_type_line_height',
                false
            ),
            'add_to_cart_button_type_letter_spacing' => $this->Render_Attributes->attr(
                $attrs,
                'add_to_cart_button_type_letter_spacing',
                false
            ),

            'variant_button_color' => $this->Render_Attributes->attr(
                $attrs,
                'variant_button_color',
                $this->plugin_settings['general']['variant_color']
            ),
            'variant_dropdown_text_color' => $this->Render_Attributes->attr(
                $attrs,
                'variant_dropdown_text_color',
                '#FFFFFF'
            ),

            'variant_dropdown_font' => $this->Render_Attributes->attr(
                $attrs,
                'variant_dropdown_font',
                false
            ), // TODO: Discouraged since 3.5.1. Need to remove in 4.0
            'variant_dropdown_font_weight' => $this->Render_Attributes->attr(
                $attrs,
                'variant_dropdown_font_weight',
                false
            ), // TODO: Discouraged since 3.5.1. Need to remove in 4.0
            'variant_dropdown_font_size' => $this->Render_Attributes->attr(
                $attrs,
                'variant_dropdown_font_size',
                false
            ), // TODO: Discouraged since 3.5.1. Need to remove in 4.0

            'variant_dropdown_type_font_family' => $this->Render_Attributes->attr(
                $attrs,
                'variant_dropdown_type_font_family',
                false
            ),
            'variant_dropdown_type_font_size' => $this->Render_Attributes->attr(
                $attrs,
                'variant_dropdown_type_font_size',
                false
            ),
            'variant_dropdown_type_font_weight' => $this->Render_Attributes->attr(
                $attrs,
                'variant_dropdown_type_font_weight',
                false
            ),
            'variant_dropdown_type_text_transform' => $this->Render_Attributes->attr(
                $attrs,
                'variant_dropdown_type_text_transform',
                false
            ),
            'variant_dropdown_type_font_style' => $this->Render_Attributes->attr(
                $attrs,
                'variant_dropdown_type_font_style',
                false
            ),
            'variant_dropdown_type_text_decoration' => $this->Render_Attributes->attr(
                $attrs,
                'variant_dropdown_type_text_decoration',
                false
            ),
            'variant_dropdown_type_line_height' => $this->Render_Attributes->attr(
                $attrs,
                'variant_dropdown_type_line_height',
                false
            ),
            'variant_dropdown_type_letter_spacing' => $this->Render_Attributes->attr(
                $attrs,
                'variant_dropdown_type_letter_spacing',
                false
            ),

            'variant_style' => $this->Render_Attributes->attr(
                $attrs,
                'variant_style',
                $this->plugin_settings['general']['variant_style']
            ),
            'hide_quantity' => $this->Render_Attributes->attr(
                $attrs,
                'hide_quantity',
                false
            ),

            'min_quantity' => $this->Render_Attributes->attr(
                $attrs,
                'min_quantity',
                1
            ),
            'max_quantity' => $this->Render_Attributes->attr(
                $attrs,
                'max_quantity',
                false
            ),
            'show_quantity_label' => $this->Render_Attributes->attr(
                $attrs,
                'show_quantity_label',
                'hello'
            ),
            'quantity_label_text' => $this->Render_Attributes->attr(
                $attrs,
                'quantity_label_text',
                'Quantity'
            ),
            'pricing_font' => $this->Render_Attributes->attr(
                $attrs,
                'pricing_font',
                false
            ), // TODO: Discouraged since 3.5.1. Need to remove in 4.0
            'pricing_font_weight' => $this->Render_Attributes->attr(
                $attrs,
                'pricing_font_weight',
                false
            ), // TODO: Discouraged since 3.5.1. Need to remove in 4.0

            'pricing_type_font_family' => $this->Render_Attributes->attr(
                $attrs,
                'pricing_type_font_family',
                false
            ),
            'pricing_type_font_size' => $this->Render_Attributes->attr(
                $attrs,
                'pricing_type_font_size',
                false
            ),
            'pricing_type_font_weight' => $this->Render_Attributes->attr(
                $attrs,
                'pricing_type_font_weight',
                false
            ),
            'pricing_type_text_transform' => $this->Render_Attributes->attr(
                $attrs,
                'pricing_type_text_transform',
                false
            ),
            'pricing_type_font_style' => $this->Render_Attributes->attr(
                $attrs,
                'pricing_type_font_style',
                false
            ),
            'pricing_type_text_decoration' => $this->Render_Attributes->attr(
                $attrs,
                'pricing_type_text_decoration',
                false
            ),
            'pricing_type_line_height' => $this->Render_Attributes->attr(
                $attrs,
                'pricing_type_line_height',
                false
            ),
            'pricing_type_letter_spacing' => $this->Render_Attributes->attr(
                $attrs,
                'pricing_type_letter_spacing',
                false
            ),

            'pricing_color' => $this->Render_Attributes->attr(
                $attrs,
                'pricing_color',
                false
            ),
            'show_price_range' => $this->Render_Attributes->attr(
                $attrs,
                'show_price_range',
                $this->plugin_settings['general']['products_show_price_range']
            ),
            'show_compare_at' => $this->Render_Attributes->attr(
                $attrs,
                'show_compare_at',
                $this->plugin_settings['general']['products_compare_at']
            ),
            'show_featured_only' => $this->Render_Attributes->attr(
                $attrs,
                'show_featured_only',
                false
            ),
            'show_zoom' => $this->Render_Attributes->attr(
                $attrs,
                'show_zoom',
                $this->plugin_settings['general']['products_images_show_zoom']
            ),
            'images_sizing_toggle' => $this->Render_Attributes->attr(
                $attrs,
                'images_sizing_toggle',
                $this->plugin_settings['general'][
                    'products_images_sizing_toggle'
                ]
            ),
            'images_sizing_width' => $this->Render_Attributes->attr(
                $attrs,
                'images_sizing_width',
                $this->plugin_settings['general'][
                    'products_images_sizing_width'
                ]
            ),
            'images_sizing_height' => $this->Render_Attributes->attr(
                $attrs,
                'images_sizing_height',
                $this->plugin_settings['general'][
                    'products_images_sizing_height'
                ]
            ),
            'images_sizing_crop' => $this->Render_Attributes->attr(
                $attrs,
                'images_sizing_crop',
                $this->plugin_settings['general']['products_images_sizing_crop']
            ),
            'images_sizing_scale' => $this->Render_Attributes->attr(
                $attrs,
                'images_sizing_scale',
                $this->plugin_settings['general'][
                    'products_images_sizing_scale'
                ]
            ),
            'images_align' => $this->Render_Attributes->attr(
                $attrs,
                'images_align',
                'left'
            ),
            'thumbnail_images_sizing_toggle' => $this->Render_Attributes->attr(
                $attrs,
                'thumbnail_images_sizing_toggle',
                $this->plugin_settings['general'][
                    'products_thumbnail_images_sizing_toggle'
                ]
            ),
            'thumbnail_images_sizing_width' => $this->Render_Attributes->attr(
                $attrs,
                'thumbnail_images_sizing_width',
                $this->plugin_settings['general'][
                    'products_thumbnail_images_sizing_width'
                ]
            ),
            'thumbnail_images_sizing_height' => $this->Render_Attributes->attr(
                $attrs,
                'thumbnail_images_sizing_height',
                $this->plugin_settings['general'][
                    'products_thumbnail_images_sizing_height'
                ]
            ),
            'thumbnail_images_sizing_crop' => $this->Render_Attributes->attr(
                $attrs,
                'thumbnail_images_sizing_crop',
                $this->plugin_settings['general'][
                    'products_thumbnail_images_sizing_crop'
                ]
            ),
            'thumbnail_images_sizing_scale' => $this->Render_Attributes->attr(
                $attrs,
                'thumbnail_images_sizing_scale',
                $this->plugin_settings['general'][
                    'products_thumbnail_images_sizing_scale'
                ]
            ),
        ];
    }

    public function products_query_attributes($attrs)
    {
        return [
            'query' => $this->Render_Attributes->attr($attrs, 'query', '*'),
            'sort_by' => $this->Render_Attributes->attr(
                $attrs,
                'sort_by',
                'TITLE'
            ),
            'reverse' => $this->Render_Attributes->attr(
                $attrs,
                'reverse',
                false
            ),
            'page_size' => $this->Render_Attributes->attr(
                $attrs,
                'page_size',
                $this->plugin_settings['general']['num_posts']
            ),
        ];
    }

    public function products_component_attributes($attrs)
    {
        return [
            'product' => $this->Render_Attributes->attr(
                $attrs,
                'product',
                false
            ),
            'product_id' => $this->Render_Attributes->attr(
                $attrs,
                'product_id',
                false
            ),
            'post_id' => $this->Render_Attributes->attr(
                $attrs,
                'post_id',
                false
            ),
            'available_for_sale' => $this->Render_Attributes->attr(
                $attrs,
                'available_for_sale',
                'any'
            ),
            'created_at' => $this->Render_Attributes->attr(
                $attrs,
                'created_at',
                false
            ),

            'product_type' => $this->Render_Attributes->attr(
                $attrs,
                'product_type',
                false
            ),

            'tag' => $this->Render_Attributes->attr($attrs, 'tag', false),

            'collection' => $this->Render_Attributes->attr(
                $attrs,
                'collection',
                false
            ),

            'title' => $this->Render_Attributes->attr($attrs, 'title', false),

            'title_size' => $this->Render_Attributes->attr(
                $attrs,
                'title_size',
                '22px'
            ), // TODO: Discouraged since 3.5.1. Need to remove in 4.0

            'title_color' => $this->Render_Attributes->attr(
                $attrs,
                'title_color',
                '#111'
            ),

            'title_font' => $this->Render_Attributes->attr(
                $attrs,
                'title_font',
                false
            ), // TODO: Discouraged since 3.5.1. Need to remove in 4.0

            'title_font_weight' => $this->Render_Attributes->attr(
                $attrs,
                'title_font_weight',
                false
            ), // TODO: Discouraged since 3.5.1. Need to remove in 4.0

            'title_type_font_family' => $this->Render_Attributes->attr(
                $attrs,
                'title_type_font_family',
                false
            ),
            'title_type_font_size' => $this->Render_Attributes->attr(
                $attrs,
                'title_type_font_size',
                false
            ),
            'title_type_font_weight' => $this->Render_Attributes->attr(
                $attrs,
                'title_type_font_weight',
                false
            ),
            'title_type_text_transform' => $this->Render_Attributes->attr(
                $attrs,
                'title_type_text_transform',
                false
            ),
            'title_type_font_style' => $this->Render_Attributes->attr(
                $attrs,
                'title_type_font_style',
                false
            ),
            'title_type_text_decoration' => $this->Render_Attributes->attr(
                $attrs,
                'title_type_text_decoration',
                false
            ),
            'title_type_line_height' => $this->Render_Attributes->attr(
                $attrs,
                'title_type_line_height',
                false
            ),
            'title_type_letter_spacing' => $this->Render_Attributes->attr(
                $attrs,
                'title_type_letter_spacing',
                false
            ),

            'description_length' => $this->Render_Attributes->attr(
                $attrs,
                'description_length',
                false
            ),
            'description_size' => $this->Render_Attributes->attr(
                $attrs,
                'description_size',
                '16px'
            ), // TODO: Discouraged since 3.5.1. Need to remove in 4.0
            'description_color' => $this->Render_Attributes->attr(
                $attrs,
                'description_color',
                '#111'
            ),

            'description_font' => $this->Render_Attributes->attr(
                $attrs,
                'description_font',
                false
            ), // TODO: Discouraged since 3.5.1. Need to remove in 4.0

            'description_font_weight' => $this->Render_Attributes->attr(
                $attrs,
                'description_font_weight',
                false
            ), // TODO: Discouraged since 3.5.1. Need to remove in 4.0

            'description_type_font_family' => $this->Render_Attributes->attr(
                $attrs,
                'description_type_font_family',
                false
            ),
            'description_type_font_size' => $this->Render_Attributes->attr(
                $attrs,
                'description_type_font_size',
                false
            ),
            'description_type_font_weight' => $this->Render_Attributes->attr(
                $attrs,
                'description_type_font_weight',
                false
            ),
            'description_type_text_transform' => $this->Render_Attributes->attr(
                $attrs,
                'description_type_text_transform',
                false
            ),
            'description_type_font_style' => $this->Render_Attributes->attr(
                $attrs,
                'description_type_font_style',
                false
            ),
            'description_type_text_decoration' => $this->Render_Attributes->attr(
                $attrs,
                'description_type_text_decoration',
                false
            ),
            'description_type_line_height' => $this->Render_Attributes->attr(
                $attrs,
                'description_type_line_height',
                false
            ),
            'description_type_letter_spacing' => $this->Render_Attributes->attr(
                $attrs,
                'description_type_letter_spacing',
                false
            ),

            'updated_at' => $this->Render_Attributes->attr(
                $attrs,
                'updated_at',
                false
            ),
            'variants_price' => $this->Render_Attributes->attr(
                $attrs,
                'variants_price',
                false
            ),
            'vendor' => $this->Render_Attributes->attr($attrs, 'vendor', false),
            'post_meta' => $this->Render_Attributes->attr(
                $attrs,
                'post_meta',
                false
            ),
            'connective' => $this->Render_Attributes->attr(
                $attrs,
                'connective',
                'AND'
            ),
            'render_from_server' => $this->Render_Attributes->attr(
                $attrs,
                'render_from_server',
                false
            ),
            'limit' => $this->Render_Attributes->attr($attrs, 'limit', false),
            'random' => $this->Render_Attributes->attr($attrs, 'random', false),
            'excludes' => $this->Render_Attributes->attr($attrs, 'excludes', [
                'description',
            ]),
            'items_per_row' => $this->Render_Attributes->attr(
                $attrs,
                'items_per_row',
                3
            ),
            'grid_column_gap' => $this->Render_Attributes->attr(
                $attrs,
                'grid_column_gap',
                '20px'
            ),
            'no_results_text' => $this->Render_Attributes->attr(
                $attrs,
                'no_results_text',
                'No products left to show'
            ),
            'align_height' => $this->Render_Attributes->attr(
                $attrs,
                'align_height',
                false
            ),
            'pagination' => $this->Render_Attributes->attr(
                $attrs,
                'pagination',
                true
            ),
            'pagination_page_size' => $this->Render_Attributes->attr(
                $attrs,
                'pagination_page_size',
                false
            ),
            'pagination_load_more' => $this->Render_Attributes->attr(
                $attrs,
                'pagination_load_more',
                true
            ),
            'dropzone_pagination' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_pagination',
                false
            ),
            'dropzone_page_size' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_page_size',
                false
            ),
            'dropzone_load_more' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_load_more',
                false
            ),
            'dropzone_product_buy_button' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_product_buy_button',
                false
            ),
            'dropzone_product_title' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_product_title',
                false
            ),
            'dropzone_product_description' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_product_description',
                false
            ),
            'dropzone_product_pricing' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_product_pricing',
                false
            ),
            'dropzone_product_gallery' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_product_gallery',
                false
            ),
            'skip_initial_render' => $this->Render_Attributes->attr(
                $attrs,
                'skip_initial_render',
                false
            ),
            'data_type' => $this->Render_Attributes->attr(
                $attrs,
                'data_type',
                'products'
            ),
            'infinite_scroll' => $this->Render_Attributes->attr(
                $attrs,
                'infinite_scroll',
                false
            ),
            'infinite_scroll_offset' => $this->Render_Attributes->attr(
                $attrs,
                'infinite_scroll_offset',
                -200
            ),
            'is_single_component' => $this->Render_Attributes->attr(
                $attrs,
                'is_single_component',
                false
            ),
            'is_singular' => $this->Render_Attributes->attr(
                $attrs,
                'is_singular',
                is_singular(WP_SHOPIFY_PRODUCTS_POST_TYPE_SLUG)
            ),
            'hide_wrapper' => $this->Render_Attributes->attr(
                $attrs,
                'hide_wrapper',
                false
            ),
            'link_to' => $this->Render_Attributes->attr(
                $attrs,
                'link_to',
                $this->plugin_settings['general']['products_link_to']
            ),
            'link_target' => $this->Render_Attributes->attr(
                $attrs,
                'link_target',
                $this->plugin_settings['general']['products_link_target']
            ),
            'link_with_buy_button' => $this->Render_Attributes->attr(
                $attrs,
                'link_with_buy_button',
                false
            ),
            'direct_checkout' => $this->Render_Attributes->attr(
                $attrs,
                'direct_checkout',
                false
            ),
            'html_template' => $this->Render_Attributes->attr(
                $attrs,
                'html_template',
                false
            ),
            'component_type' => $this->Render_Attributes->attr(
                $attrs,
                'component_type',
                'products'
            ),
            'full_width' => $this->Render_Attributes->attr(
                $attrs,
                'full_width',
                false
            ),
            'carousel' => $this->Render_Attributes->attr(
                $attrs,
                'carousel',
                false
            ),
            'carousel_dots' => $this->Render_Attributes->attr(
                $attrs,
                'carousel_dots',
                true
            ),
            'carousel_infinite' => $this->Render_Attributes->attr(
                $attrs,
                'carousel_infinite',
                true
            ),
            'carousel_speed' => $this->Render_Attributes->attr(
                $attrs,
                'carousel_speed',
                500
            ),
            'carousel_slides_to_show' => $this->Render_Attributes->attr(
                $attrs,
                'carousel_slides_to_show',
                4
            ),
            'carousel_slides_to_scroll' => $this->Render_Attributes->attr(
                $attrs,
                'carousel_slides_to_scroll',
                4
            ),
            'carousel_prev_arrow' => $this->Render_Attributes->attr(
                $attrs,
                'carousel_prev_arrow',
                "data:image/svg+xml,%3Csvg aria-hidden='true' focusable='false' data-prefix='far' data-icon='angle-left' class='svg-inline--fa fa-angle-left fa-w-6' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 192 512'%3E%3Cpath fill='currentColor' d='M4.2 247.5L151 99.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17L69.3 256l118.5 119.7c4.7 4.7 4.7 12.3 0 17L168 412.5c-4.7 4.7-12.3 4.7-17 0L4.2 264.5c-4.7-4.7-4.7-12.3 0-17z'%3E%3C/path%3E%3C/svg%3E"
            ),
            'carousel_next_arrow' => $this->Render_Attributes->attr(
                $attrs,
                'carousel_next_arrow',
                "data:image/svg+xml,%3Csvg aria-hidden='true' focusable='false' data-prefix='far' data-icon='angle-right' class='svg-inline--fa fa-angle-right fa-w-6' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 192 512'%3E%3Cpath fill='currentColor' d='M187.8 264.5L41 412.5c-4.7 4.7-12.3 4.7-17 0L4.2 392.7c-4.7-4.7-4.7-12.3 0-17L122.7 256 4.2 136.3c-4.7-4.7-4.7-12.3 0-17L24 99.5c4.7-4.7 12.3-4.7 17 0l146.8 148c4.7 4.7 4.7 12.3 0 17z'%3E%3C/path%3E%3C/svg%3E"
            ),
            'keep_commas' => false,
            'show_price_under_variant_button' => false
        ];
    }

    public function all_products_attributes($attrs = [])
    {
        return array_replace(
            $this->products_query_attributes($attrs),
            $this->products_settings_attributes($attrs),
            $this->products_component_attributes($attrs)
        );
    }
}
