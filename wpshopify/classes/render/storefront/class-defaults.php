<?php

namespace WP_Shopify\Render\Storefront;

defined('ABSPATH') ?: exit();

use WP_Shopify\Utils\Data;

class Defaults
{
    public $Render_Attributes;
    public $Products_Defaults;

    public function __construct($Render_Attributes, $Products_Defaults)
    {
        $this->Render_Attributes = $Render_Attributes;
        $this->Products_Defaults = $Products_Defaults;
    }

    public function storefront_query_attributes($attrs)
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
                10
            ),
        ];
    }

    public function storefront_settings_attributes($attrs)
    {
        return [
            'show_tags' => $this->Render_Attributes->attr(
                $attrs,
                'show_tags',
                true
            ),
            'show_vendors' => $this->Render_Attributes->attr(
                $attrs,
                'show_vendors',
                true
            ),
            'show_types' => $this->Render_Attributes->attr(
                $attrs,
                'show_types',
                true
            ),
            'show_selections' => $this->Render_Attributes->attr(
                $attrs,
                'show_selections',
                true
            ),
            'show_sorting' => $this->Render_Attributes->attr(
                $attrs,
                'show_sorting',
                true
            ),
            'show_pagination' => $this->Render_Attributes->attr(
                $attrs,
                'show_pagination',
                true
            ),
            'show_options_heading' => $this->Render_Attributes->attr(
                $attrs,
                'show_options_heading',
                true
            ),
        ];
    }

    public function storefront_component_attributes($attrs)
    {
        return [
            'render_from_server' => $this->Render_Attributes->attr(
                $attrs,
                'render_from_server',
                false
            ),
            'dropzone_payload' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_payload',
                false
            ),
            'dropzone_options' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_options',
                false
            ),
            'dropzone_selections' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_selections',
                false
            ),
            'dropzone_sorting' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_sorting',
                true
            ),
            'dropzone_heading' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_heading',
                false
            ),
            'dropzone_pagination' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_pagination',
                false
            ),
            'dropzone_page_size' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_page_size',
                true
            ),
            'dropzone_load_more' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_load_more',
                true
            ),
            'dropzone_loader' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_loader',
                false
            ),
            'dropzone_notices' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_notices',
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
                true
            ),
            'pagination_load_more' => $this->Render_Attributes->attr(
                $attrs,
                'pagination_load_more',
                true
            ),
            'no_results_text' => $this->Render_Attributes->attr(
                $attrs,
                'no_results_text',
                'No results found'
            ),
            'excludes' => $this->Render_Attributes->attr($attrs, 'excludes', [
                'description',
            ]),
            'items_per_row' => $this->Render_Attributes->attr(
                $attrs,
                'items_per_row',
                3
            ),
            'limit' => $this->Render_Attributes->attr($attrs, 'limit', false),
            'skip_initial_render' => $this->Render_Attributes->attr(
                $attrs,
                'skip_initial_render',
                false
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
            'data_type' => $this->Render_Attributes->attr(
                $attrs,
                'data_type',
                'products'
            ),
            'hide_wrapper' => $this->Render_Attributes->attr(
                $attrs,
                'hide_wrapper',
                false
            ),
            'connective' => $this->Render_Attributes->attr(
                $attrs,
                'connective',
                'AND'
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
            'title' => $this->Render_Attributes->attr($attrs, 'title', false),
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
            'filter_params' => [
                'available_for_sale' => $this->Render_Attributes->attr(
                    $attrs,
                    'available_for_sale',
                    false
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
                'title' => $this->Render_Attributes->attr(
                    $attrs,
                    'title',
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
                'vendor' => $this->Render_Attributes->attr(
                    $attrs,
                    'vendor',
                    false
                ),
            ],
            'component_type' => $this->Render_Attributes->attr(
                $attrs,
                'component_type',
                'storefront'
            ),
        ];
    }

    public function all_storefront_attributes($attrs)
    {
        return array_merge(
            $this->Products_Defaults->products_settings_attributes($attrs),
            $this->Products_Defaults->products_component_attributes($attrs),
            $this->storefront_query_attributes($attrs),
            $this->storefront_settings_attributes($attrs),
            $this->storefront_component_attributes($attrs)
        );
    }

    public function storefront($attrs)
    {
        return $this->Render_Attributes->combine_products_attributes(
            $this->all_storefront_attributes($attrs)
        );
    }
}
