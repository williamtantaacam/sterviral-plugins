<?php

namespace WP_Shopify\Render\Collections;

if (!defined('ABSPATH')) {
    exit();
}

use WP_Shopify\Utils\Data;

class Defaults
{
    public $Render_Attributes;
    public $Products_Defaults;
    public $plugin_settings;

    public function __construct(
        $Render_Attributes,
        $Products_Defaults,
        $plugin_settings
    ) {
        $this->Render_Attributes = $Render_Attributes;
        $this->Products_Defaults = $Products_Defaults;
        $this->plugin_settings = $plugin_settings;
    }

    public function create_collections_query($all_attrs)
    {
        $filter_params = $this->Render_Attributes->get_collections_filter_params_from_shortcode(
            Data::format_shortcode_attrs($all_attrs)
        );

        if (!isset($all_attrs['connective'])) {
            if (empty($all_attrs)) {
                $all_attrs = [];
            }

            $all_attrs['connective'] = 'AND';
        }

        return $this->Render_Attributes->build_query(
            $filter_params,
            $all_attrs
        );
    }

    public function collections_query_attributes($attrs)
    {
        return [
            'query' => $this->create_collections_query($attrs),
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

    public function collections_settings_attributes($attrs)
    {
        return [
            'single' => $this->Render_Attributes->attr($attrs, 'single', false),
            'collection_type' => $this->Render_Attributes->attr(
                $attrs,
                'collection_type',
                false
            ),
            'images_sizing_toggle' => $this->Render_Attributes->attr(
                $attrs,
                'images_sizing_toggle',
                $this->plugin_settings['general'][
                    'collections_images_sizing_toggle'
                ]
            ),
            'images_sizing_toggle' => $this->Render_Attributes->attr(
                $attrs,
                'images_sizing_width',
                $this->plugin_settings['general'][
                    'collections_images_sizing_width'
                ]
            ),
            'images_sizing_toggle' => $this->Render_Attributes->attr(
                $attrs,
                'images_sizing_height',
                $this->plugin_settings['general'][
                    'collections_images_sizing_height'
                ]
            ),
            'images_sizing_toggle' => $this->Render_Attributes->attr(
                $attrs,
                'images_sizing_crop',
                $this->plugin_settings['general'][
                    'collections_images_sizing_crop'
                ]
            ),
            'images_sizing_toggle' => $this->Render_Attributes->attr(
                $attrs,
                'images_sizing_scale',
                $this->plugin_settings['general'][
                    'collections_images_sizing_scale'
                ]
            ),
        ];
    }

    public function collections_component_attributes($attrs)
    {
        return [
            'collection_id' => $this->Render_Attributes->attr(
                $attrs,
                'collection_id',
                false
            ),
            'post_id' => $this->Render_Attributes->attr(
                $attrs,
                'post_id',
                false
            ),
            'connective' => $this->Render_Attributes->attr(
                $attrs,
                'connective',
                'AND'
            ),
            'title' => $this->Render_Attributes->attr($attrs, 'title', false),
            'render_from_server' => $this->Render_Attributes->attr(
                $attrs,
                'render_from_server',
                false
            ),
            'updated_at' => $this->Render_Attributes->attr(
                $attrs,
                'updated_at',
                false
            ),
            'collection' => $this->Render_Attributes->attr(
                $attrs,
                'collection',
                false
            ),
            'items_per_row' => $this->Render_Attributes->attr(
                $attrs,
                'items_per_row',
                3
            ),
            'limit' => $this->Render_Attributes->attr($attrs, 'limit', false),
            'post_meta' => $this->Render_Attributes->attr(
                $attrs,
                'post_meta',
                false
            ),
            'excludes' => $this->Render_Attributes->attr($attrs, 'excludes', [
                'products',
            ]),
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
            'skip_initial_render' => $this->Render_Attributes->attr(
                $attrs,
                'skip_initial_render',
                false
            ),
            'dropzone_collection_title' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_collection_title',
                false
            ),
            'dropzone_collection_image' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_collection_image',
                false
            ),
            'dropzone_collection_description' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_collection_description',
                false
            ),
            'dropzone_collection_products' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_collection_products',
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
                'collections'
            ),
            'is_singular' => is_singular(WP_SHOPIFY_COLLECTIONS_POST_TYPE_SLUG),
            'hide_wrapper' => $this->Render_Attributes->attr(
                $attrs,
                'hide_wrapper',
                false
            ),
            'link_to' => $this->Render_Attributes->attr(
                $attrs,
                'link_to',
                'wordpress'
            ),
            'link_target' => $this->Render_Attributes->attr(
                $attrs,
                'link_target',
                '_self'
            ),
            'no_results_text' => $this->Render_Attributes->attr(
                $attrs,
                'no_results_text',
                'No collections left to show'
            ),
            'component_type' => $this->Render_Attributes->attr(
                $attrs,
                'component_type',
                'collection'
            ),
        ];
    }

    public function all_collections_attributes($attrs)
    {
        $default_products_query = [
            'sort_by' => 'collection_default',
            'reverse' => false,
            'page_size' => 10,
            'query' => '',
        ];

        if (empty($attrs['products'])) {
            $attrs_prods = $default_products_query;
        } else {
            $attrs_prods = array_merge(
                $default_products_query,
                $attrs['products']
            );
        }

        $final_attrs = array_merge(
            [
                'products' => $this->Products_Defaults->all_products_attributes(
                    $attrs_prods
                ),
            ],
            $this->collections_query_attributes($attrs),
            $this->collections_settings_attributes($attrs),
            $this->collections_component_attributes($attrs)
        );

        return $final_attrs;
    }

    public function normalize_collection_single_data($all_atts)
    {
        if (isset($all_atts['single'])) {
            $single = Data::attr_to_boolean($all_atts['single']);
        } else {
            $single = false;
        }

        if (is_array($all_atts)) {
            $all_atts['single'] = $single;

            if (isset($all_atts['items_per_row'])) {
                if ($single) {
                    $all_atts['items_per_row'] = 1;
                } else {
                    $all_atts['items_per_row'] = Data::attr_to_integer(
                        $all_atts['items_per_row']
                    );
                }
            }
        }

        return $all_atts;
    }

    public function collections($attrs = [])
    {
        $combine_products_attributes = $this->Render_Attributes->combine_products_attributes(
            $attrs
        );

        $all_collections_attrs = $this->all_collections_attributes(
            $combine_products_attributes
        );

        $all_atts = $this->Render_Attributes->combine_products_attributes(
            $all_collections_attrs
        );

        $normalized = $this->normalize_collection_single_data($all_atts);

        return $normalized;
    }
}
