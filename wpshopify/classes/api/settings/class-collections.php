<?php

namespace WP_Shopify\API\Settings;

if (!defined('ABSPATH')) {
    exit();
}

class Collections extends \WP_Shopify\API
{
    public $DB_Settings_General;

    public function __construct($DB_Settings_General, $DB_Settings_Syncing)
    {
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
    }

    public function get_setting_selected_collections($request)
    {
        $collections = maybe_unserialize(
            $this->DB_Settings_General->sync_by_collections()
        );

        return $this->handle_response([
            'response' => $collections,
        ]);
    }

      public function starts_with($haystack, $needle) {
         $length = strlen( $needle );
         return substr( $haystack, 0, $length ) === $needle;
      }

      public function ends_with($haystack, $needle) {
         
         $length = strlen( $needle );
         
         if (!$length) {
            return true;
         }

         return substr( $haystack, -$length ) === $needle;

      }

      public function format_page_slug($slug) {
         if (!$this->starts_with($slug, '/')) {
            $slug = '/' . $slug;
         }

         if (!$this->ends_with($slug, '/')) {
            $slug = $slug . '/';
         }

         return $slug;
      }

    public function get_setting_available_pages($request)
    {
        $pages = \get_pages();

        $pages_updated = array_map(function ($page) {

            return [
                'ID' => $page->ID,
                'post_title' => $page->post_title,
                'guid' => $this->format_page_slug($page->post_name),
            ];
        }, $pages);

        return $this->handle_response([
            'response' => $pages_updated,
        ]);
    }

    /*

	Register route: collections_heading

	*/
    public function register_route_selected_collections()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/settings/selected_collections',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_setting_selected_collections'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_available_pages()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/settings/available_pages',
            [
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_setting_available_pages'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [
            $this,
            'register_route_selected_collections',
        ]);
        add_action('rest_api_init', [$this, 'register_route_available_pages']);
    }
}
