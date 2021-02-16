<?php

namespace WP_Shopify;

use WP_Shopify\Utils;
use WP_Shopify\CPT;

if (!defined('ABSPATH')) {
    exit();
}

class CPT_Model
{
    /*

	Returns a collections post model with collection id assigned as meta_value

	*/
    public function set_collection_model_defaults($collection)
    {
        $collection = Utils::convert_array_to_object($collection);

        return [
            'post_title' => property_exists($collection, 'title')
                ? $collection->title
                : '',
            'post_status' => 'publish',
            'post_author' => CPT::return_author_id(),
            'post_type' => WP_SHOPIFY_COLLECTIONS_POST_TYPE_SLUG,
            'post_name' => property_exists($collection, 'handle')
                ? sanitize_title($collection->handle)
                : '',
            'meta_input' => [
                'collection_id' => property_exists($collection, 'id')
                    ? $collection->id
                    : '',
            ],
        ];
    }

    /*

	Returns a model used to either add or update a product CPT

	*/
    public function set_product_model_defaults($product)
    {
        $product = Utils::convert_array_to_object($product);

        return [
            'post_title' => property_exists($product, 'title')
                ? $product->title
                : '',
            'post_content' =>
                property_exists($product, 'body_html') &&
                $product->body_html !== null
                    ? $product->body_html
                    : '',
            'post_status' => 'publish',
            'post_type' => WP_SHOPIFY_PRODUCTS_POST_TYPE_SLUG,
            'post_name' => property_exists($product, 'handle')
                ? sanitize_title($product->handle)
                : '',
            'meta_input' => [
                'product_id' => property_exists($product, 'id')
                    ? $product->id
                    : '',
            ],
        ];
    }

    /*

	Wrapper function used during a collection post update

	*/
    public function build_collections_model_for_update($collection, $post_id)
    {
        $collection_model = $this->set_collection_model_defaults($collection);
        $collection_model = CPT::set_post_id_if_exists(
            $collection_model,
            $post_id
        );

        return $collection_model;
    }

    /*

   Checks for existing post categories and assigns them to the new product model before 
   updating / inserting.

	*/
    public function set_existing_product_categories($post_id, $product_model)
    {
        $categories = get_the_category($post_id);
        $cat_ids = [];

        if (empty($categories)) {
            return $product_model;
        }

        foreach ($categories as $category) {
            array_push($cat_ids, $category->cat_ID);
        }

        $product_model['post_category'] = $cat_ids;

        return $product_model;
    }

    /*

   Checks for existing post excerpts and assigns them to the new product model before 
   updating / inserting.

	*/
    public function set_existing_product_excerpt($post_id, $product_model)
    {
        if (!has_excerpt($post_id)) {
            return $product_model;
        }

        $product_model['post_excerpt'] = get_the_excerpt($post_id);

        return $product_model;
    }

    public function set_existing_custom_fields($post_id, $product_model)
    {
        $custom_fields = get_post_custom($post_id);

        if (empty($custom_fields)) {
            return $product_model;
        }

        foreach ($custom_fields as $cf_name => $cf_val) {
            $product_model['meta_input'][$cf_name] = $cf_val[0];
        }

        return $product_model;
    }

    /*

	Wrapper function used during a product post update

	*/
    public function build_products_model_for_update($product, $post_id = false)
    {

        $product_model = $this->set_product_model_defaults($product);

        $product_model = CPT::set_post_id_if_exists($product_model, $post_id);

        $product_model = $this->set_existing_product_categories(
            $post_id,
            $product_model
        );

        $product_model = $this->set_existing_product_excerpt(
            $post_id,
            $product_model
        );

        $product_model = $this->set_existing_custom_fields(
            $post_id,
            $product_model
        );

        return $product_model;
    }

    public function insert_or_update_product_post($product, $post_id = false)
    { 

        $model = $this->build_products_model_for_update($product, $post_id);

        $post_result = wp_insert_post($model, true);

        return $post_result;
        
    }

    public function insert_or_update_collection_post(
        $collection,
        $post_id = false
    ) {
        $model = $this->build_collections_model_for_update(
            $collection,
            $post_id
        );
        return wp_insert_post($model, true);
    }
}
