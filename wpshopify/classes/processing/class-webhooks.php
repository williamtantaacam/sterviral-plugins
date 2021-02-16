<?php

namespace WP_Shopify\Processing;

if (!defined('ABSPATH')) {
    exit();
}

use WP_Shopify\Utils;
use WP_Shopify\Utils\Server;

class Webhooks extends \WP_Shopify\Processing\Vendor_Background_Process
{
    protected $action = 'wps_background_processing_webhooks';

    protected $DB_Settings_Syncing;
    protected $Webhooks;
    protected $Shopify_API;

    public function __construct($DB_Settings_Syncing, $Webhooks, $Shopify_API)
    {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->Webhooks = $Webhooks;
        $this->Shopify_API = $Shopify_API;

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
    protected function task($topic)
    {
        // Stops background process if syncing stops
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            $this->complete();
            return false;
        }

        if ($this->time_exceeded() || $this->memory_exceeded()) {
            return $topic;
        }

        $topic_ok = maybe_unserialize($topic);

        // Actual work
        $response = $this->Shopify_API->register_webhook(
            $this->Webhooks->get_webhook_body_from_topic($topic)
        );

        if (is_wp_error($response)) {
            if ($this->Webhooks->is_invalid_topic_error($response)) {
                $this->DB_Settings_Syncing->save_warning(
                    'Unable to register webhook of topic: ' . $topic
                );
                return false;
            }

            $this->DB_Settings_Syncing->save_notice_and_expire_sync($response);
            $this->complete();
            return false;
        }

        return false;
    }

    /*

	After an individual task item is removed from the queue

	*/
    protected function after_queue_item_removal($topic)
    {
        $this->DB_Settings_Syncing->increment_current_amount('webhooks');
    }
}
