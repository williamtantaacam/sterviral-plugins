<?php

namespace WP_Shopify\Processing;

if (!defined('ABSPATH')) {
    exit();
}

use WP_Shopify\Utils;
use WP_Shopify\Utils\Server;

class Collections_Custom extends
    \WP_Shopify\Processing\Vendor_Background_Process
{
    protected $action = 'wps_background_processing_collections_custom';

    public $DB_Settings_Syncing;
    public $DB_Collections;
    public $CPT_Model;

    public function __construct(
        $DB_Settings_Syncing,
        $DB_Collections,
        $CPT_Model
    ) {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->DB_Collections = $DB_Collections;
        $this->CPT_Model = $CPT_Model;

        parent::__construct($DB_Settings_Syncing);
    }

    /*

	Entry point. Initial call before processing starts.

	*/
    public function process($items, $params = false)
    {
        if ($this->expired_from_server_issues($items, __METHOD__, __LINE__)) {
            return;
        }

        $this->dispatch_items($items);
    }

    /*

	Performs actions required for each item in the queue

	*/
    protected function task($collection)
    {
        // Stops background process if syncing stops
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            $this->complete();
            return false;
        }

        if ($this->time_exceeded() || $this->memory_exceeded()) {
            return $collection;
        }

        $post_id = $this->DB_Collections->get_post_id_by_collection_id(
            $collection->id
        );

        if (!empty($post_id)) {
            $post_id = $post_id[0];
        } else {
            $post_id = false;
        }

        // Custom post type work
        $new_post_id = $this->CPT_Model->insert_or_update_collection_post(
            $collection,
            $post_id
        );

        // Custom table work
        $result = $this->DB_Collections->insert_items_of_type(
            $this->DB_Collections->mod_before_change($collection)
        );

        do_action(
            'wpshopify_processing_posts_task',
            $new_post_id,
            'collection',
            $collection
        );

        // Save warnings if exist ...
        $this->DB_Settings_Syncing->maybe_save_warning_from_insert(
            $result,
            'Collection',
            $collection->title
        );

        if (is_wp_error($result)) {
            $this->DB_Settings_Syncing->save_notice_and_expire_sync($result);
            $this->complete();
            return false;
        }

        return false;
    }

    /*

	After an individual task item is removed from the queue

	*/
    protected function after_queue_item_removal($item)
    {
        $this->DB_Settings_Syncing->increment_current_amount(
            'custom_collections'
        );

        $this->DB_Settings_Syncing->increment_current_amount(
            'custom_collections'
        );
    }
}
