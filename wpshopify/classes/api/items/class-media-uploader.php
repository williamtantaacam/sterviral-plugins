<?php

namespace WP_Shopify\API\Items;

if (!defined('ABSPATH')) {
    exit();
}

use WP_Shopify\Messages;
use WP_Shopify\Utils;
use WP_Shopify\Utils\Data as Utils_Data;

class Media_Uploader extends \WP_Shopify\API
{
    public $Processing_Media_Uploader;
    public $Shopify_API;
    public $DB_Settings_Syncing;
    public $DB_Images;

    public function __construct(
        $Processing_Media_Uploader,
        $Shopify_API,
        $DB_Settings_Syncing,
        $DB_Images
    ) {
        $this->Processing_Media_Uploader = $Processing_Media_Uploader;
        $this->Shopify_API = $Shopify_API;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->DB_Images = $DB_Images;
    }

    public function maybe_increment_media_difference($images)
    {
        $gross_totals = $this->DB_Settings_Syncing->get_syncing_totals_media();
        $real_totals = count($images);

        if ($gross_totals > $real_totals) {
            $difference = $gross_totals - $real_totals;
            $this->DB_Settings_Syncing->increment_current_amount(
                'media',
                $difference
            );
        }
    }

    public function media_upload($request)
    {
        $images = $this->DB_Images->get_all_images();

        $this->maybe_increment_media_difference($images);

        $this->handle_response([
            'response' => $images,
            'process_fns' => [$this->Processing_Media_Uploader],
        ]);
    }

    public function media_counts($request)
    {
        return $this->handle_response([
            'response' => [
                'media' => $this->DB_Settings_Syncing->get_syncing_totals_media(),
            ],
        ]);
    }

    /*

	Register route: collections_heading

	*/
    public function register_route_media_upload()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/media/upload',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'media_upload'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: collections_heading

	*/
    public function register_route_media_counts()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/media/counts',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'media_counts'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_media_upload']);
        add_action('rest_api_init', [$this, 'register_route_media_counts']);
    }
}
