<?php

namespace WP_Shopify;

use WP_Shopify\Utils;
use WP_Shopify\Utils\Data as Utils_Data;
use WP_Shopify\Transients;

if (!defined('ABSPATH')) {
    exit();
}

class CPT
{
    public $DB_Settings_General;
    public $plugin_settings;

    public function __construct($DB_Settings_General, $plugin_settings)
    {
        $this->DB_Settings_General = $DB_Settings_General;
        $this->plugin_settings = $plugin_settings;
    }

    public function find_post_type_slug($type)
    {
        $enable_default_pages =
            $this->plugin_settings['general']['enable_default_pages'];

        if (!$enable_default_pages) {
            return $type;
        }

        $url = $this->plugin_settings['general']['url_' . $type];
        $slug = basename(parse_url($url, PHP_URL_PATH));

        if (!$url || !$slug) {
            return $type;
        }

        return $slug;
    }

    /*

	Adds the post ID if one exists. Used for building the product / collections model

	*/
    public static function set_post_id_if_exists($model, $existing_post_id)
    {
        if (!empty($existing_post_id)) {
            $model['ID'] = $existing_post_id;
        }

        return $model;
    }

    /*

	Grabs the current author ID

	*/
    public static function return_author_id()
    {
        if (get_current_user_id() === 0) {
            $author_id = 1;
        } else {
            $author_id = get_current_user_id();
        }

        return intval($author_id);
    }

    /*

	Responsible for assigning a post_id to a post

	*/
    public static function set_post_id($post, $post_id)
    {
        $post->post_id = $post_id;

        return $post;
    }

    public static function add_props($item, $props)
    {
        foreach ($props as $key => $value) {
            $item->{$key} = $value;
        }

        return $item;
    }

    public static function add_props_to_items($items, $props)
    {
        return array_map(function ($item) use ($props) {
            return self::add_props($item, $props);
        }, $items);
    }

    public function post_type_products()
    {
        if (post_type_exists(WP_SHOPIFY_PRODUCTS_POST_TYPE_SLUG)) {
            return;
        }

        $slug = $this->find_post_type_slug('products');

        $rewrite_rules = [
            'slug' => $slug,
            'with_front' => false,
            'feeds' => true,
        ];

        $publicly_queryable = true;
        $exclude_from_search = false;

        $labels = [
            'name' => 'Products',
            'singular_name' => 'Product',
            'menu_name' => 'Products',
            'new_item' => 'Add New Product',
            'edit_item' => 'Edit Product',
            'not_found' => 'No Products found',
            'not_found_in_trash' => 'No Products found in trash',
        ];

        $args = [
            'label' => 'Products',
            'description' => 'Custom Post Type for Products',
            'labels' => $labels,
            'supports' => [
                'title',
                'page-attributes',
                'editor',
                'custom-fields',
                'comments',
                'thumbnail',
            ],
            'taxonomies' => ['category'],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'menu_position' => 100,
            'menu_icon' => 'dashicons-megaphone',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => $exclude_from_search,
            'publicly_queryable' => $publicly_queryable,
            'capability_type' => 'post',
            'rewrite' => $rewrite_rules,
            'capabilities' => [
                'create_posts' => false,
            ],
            'map_meta_cap' => true,
        ];

        register_post_type(
            WP_SHOPIFY_PRODUCTS_POST_TYPE_SLUG,
            apply_filters('wps_register_products_args', $args)
        );
    }

    public function post_type_collections()
    {
        if (post_type_exists(WP_SHOPIFY_COLLECTIONS_POST_TYPE_SLUG)) {
            return;
        }

        $slug = $this->find_post_type_slug('collections');

        $rewrite_rules = [
            'slug' => $slug,
            'with_front' => false,
            'feeds' => true,
        ];
        $publicly_queryable = true;
        $exclude_from_search = false;

        $labels = [
            'name' => 'Collections',
            'singular_name' => 'Collection',
            'menu_name' => 'Collections',
            'parent_item_colon' => 'Parent Item:',
            'new_item' => 'Add New Collection',
            'edit_item' => 'Edit Collection',
            'not_found' => 'No Collections found',
            'not_found_in_trash' => 'No Collections found in trash',
        ];

        $args = [
            'label' => 'Collections',
            'description' => 'Custom Post Type for Collections',
            'labels' => $labels,
            'supports' => [
                'title',
                'page-attributes',
                'editor',
                'custom-fields',
                'comments',
                'thumbnail',
            ],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'menu_position' => 100,
            'menu_icon' => 'dashicons-megaphone',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => $exclude_from_search,
            'publicly_queryable' => $publicly_queryable,
            'capability_type' => 'post',
            'rewrite' => $rewrite_rules,
            'capabilities' => [
                'create_posts' => false,
            ],
            'map_meta_cap' => true,
        ];

        register_post_type(
            WP_SHOPIFY_COLLECTIONS_POST_TYPE_SLUG,
            apply_filters('wps_register_collections_args', $args)
        );
    }

    function maybe_flush() {

      if ($this->plugin_settings['misc']['should_flush_rewrite_rules']) {
         flush_rewrite_rules();
         update_option('wpshopify_should_flush_rewrite_rules', false);
      }

    }

    public function init()
    {
        $this->post_type_products();
        $this->post_type_collections();
        $this->maybe_flush();
    }
}
