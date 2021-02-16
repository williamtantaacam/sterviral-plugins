<?php

namespace WP_Shopify\Render;

use WP_Shopify\Utils;
use WP_Shopify\Utils\Data as Utils_Data;

if (!defined('ABSPATH')) {
    exit();
}

class Attributes
{
    public function __construct($DB_Products)
    {
        $this->DB_Products = $DB_Products;
    }

    public function has_attr($attributes, $value)
    {
        return isset($attributes[$value]) && !empty($attributes[$value]);
    }

    public function add_boolean_to_query($key, $val)
    {
        if (is_bool($val)) {
            $bool_converted = $val ? 'true' : 'false';
        } else {
            $bool_converted = $val;
        }

        return $key . ':' . $bool_converted;
    }

    /*

    Defaults to a phrase query which surrounds each term in double quotes

     */
    public function add_string_to_query($key, $val)
    {
        if (substr($val, -1) === '*') {
            return $key . ':' . $val;
        } else {
            return $key . ':' . '"' . $val . '"';
        }
    }

    public function query_checks($key, $val, $query)
    {
        if (is_bool($val) || $val === 'true' || $val === 'false') {
            $query .= $this->add_boolean_to_query($key, $val);
        } else {
            $query .= $this->add_string_to_query($key, $val);
        }

        return $query;
    }

    public function add_nested_query($key, $values, $all_attrs, $keep_commas)
    {
        $query = '';

        if ($keep_commas) {
            $query_keep = $key . ': "';

            foreach ($all_attrs[$key] as $v) {
                $query_keep .= $v . ', ';
            }

            $query_keep = rtrim($query_keep, ", ");
            $query_keep = $query_keep . '"';

            return $query_keep;
        }

        foreach ($values as $val) {
            $query = $this->query_checks($key, $val, $query);

            if ($val !== end($values)) {
                $query .= ' ' . strtoupper($all_attrs['connective']) . ' ';
            }
        }

        return $query;
    }

    public function build_query($filter_params, $all_attrs)
    {
        if (
            isset($all_attrs['keep_commas']) &&
            $all_attrs['keep_commas'] === true
        ) {
            $keep_commas = true;
        } else {
            $keep_commas = false;
        }

        if (!array_filter($filter_params)) {
            return 'TITLE:*'; // Returns the default query instead
        }

        if (isset($filter_params['available_for_sale'])) {
            if ($filter_params['available_for_sale'] === 'unavailable') {
                $filter_params['available_for_sale'] = 'false';
            } elseif ($filter_params['available_for_sale'] === 'available') {
                $filter_params['available_for_sale'] = 'true';
            } elseif ($filter_params['available_for_sale'] === 'any') {
                $filter_params['available_for_sale'] = false;
            }
        }

        $query = '';
        $valid_filter_params = array_filter($filter_params);

        foreach ($valid_filter_params as $key => $value) {
            if (\is_array($value)) {
                $query .= $this->add_nested_query(
                    $key,
                    $value,
                    $all_attrs,
                    $keep_commas
                );
            } else {
                $query = $this->query_checks($key, $value, $query);
            }

            if ($value !== end($valid_filter_params)) {
                if ($key === 'available_for_sale') {
                    $query .= ' AND ';
                } else {
                    $query .= ' ' . strtoupper($all_attrs['connective']) . ' ';
                }
            }
        }

        // Fallback
        if ($query === '') {
            $query = '*';
        }

        return $query;
    }

    public function attr($attrs, $attr_name, $default, $override = false)
    {
        if (
            !\is_array($attrs) ||
            !\array_key_exists($attr_name, $attrs) ||
            empty($attrs)
        ) {
            return $default;
        }

        return $attrs[$attr_name];
    }

    public function gather_products_attrs($shortcode_atts)
    {
        $products_only_attrs = [];

        if (!empty($shortcode_atts)) {
            foreach ($shortcode_atts as $key => $value) {
                if (Utils::str_contains($key, 'products_')) {
                    $products_only_attrs[$key] = $value;
                }
            }
        }

        return $products_only_attrs;
    }

    public function add_products_attrs_to_data($all_atts, $products_only_attrs)
    {
        if (empty($products_only_attrs)) {
            return $all_atts;
        } else {
            foreach ($products_only_attrs as $key => $value) {
                $without_prefix = str_replace('products_', '', $key);
                $all_atts['products'][$without_prefix] = $value;
            }
        }

        return $all_atts;
    }

    public function combine_products_attributes($all_atts)
    {
        return $this->add_products_attrs_to_data(
            $all_atts,
            $this->gather_products_attrs($all_atts)
        );
    }

    public function maybe_get_product_ids($attrs)
    {
        if (isset($attrs['post_id']) && $attrs['post_id']) {
            if (!is_array($attrs['post_id'])) {
                $attrs['post_id'] = [$attrs['post_id']];
            }

            $product_ids = $this->DB_Products->get_product_ids_from_post_ids(
                $attrs['post_id']
            );

            // Lands here if the post_id is not set inside the wps_products table during the syncing process. Can happen if syncing thousands of products. Fallback to grabbing the product_id from the post title.
            if (empty($product_ids)) {
                $product_ids = $this->DB_Products->get_product_ids_from_titles([
                    get_the_title($attrs['post_id'][0]),
                ]);
            }
        } else {
            $product_ids = [];
        }

        if (isset($attrs['product_id']) && $attrs['product_id']) {
            if (!is_array($attrs['product_id'])) {
                $attrs['product_id'] = [$attrs['product_id']];
            }

            $product_ids = array_merge($attrs['product_id'], $product_ids);
        }

        return empty($product_ids) ? false : $product_ids;
    }

    public function get_products_filter_params_from_shortcode($attrs)
    {
        return [
            'available_for_sale' => isset($attrs['available_for_sale'])
                ? $attrs['available_for_sale']
                : 'any',
            'created_at' => isset($attrs['created_at'])
                ? $attrs['created_at']
                : false,
            'product_type' => isset($attrs['product_type'])
                ? $attrs['product_type']
                : false,
            'tag' => isset($attrs['tag']) ? $attrs['tag'] : false,
            'title' => isset($attrs['title']) ? $attrs['title'] : false,
            'updated_at' => isset($attrs['updated_at'])
                ? $attrs['updated_at']
                : false,
            'variants_price' => isset($attrs['variants_price'])
                ? $attrs['variants_price']
                : false,
            'vendor' => isset($attrs['vendor']) ? $attrs['vendor'] : false,
            'id' => $this->maybe_get_product_ids($attrs),
            'collection' => isset($attrs['collection'])
                ? $attrs['collection']
                : false,
        ];
    }

    public function get_collections_filter_params_from_shortcode($attrs)
    {
        return [
            'updated_at' => isset($attrs['updated_at'])
                ? $attrs['updated_at']
                : false,
            'title' => isset($attrs['title']) ? $attrs['title'] : false,
            'collection_type' => isset($attrs['collection_type'])
                ? $attrs['collection_type']
                : false,
        ];
    }
}
