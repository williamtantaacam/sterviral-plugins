<?php

namespace WP_Shopify\Processing;

if (!defined('ABSPATH')) {
    exit();
}

use WP_Shopify\Utils;
use WP_Shopify\Utils\Server;

class Products extends \WP_Shopify\Processing\Vendor_Background_Process
{
    protected $action = 'wps_background_processing_products';

    protected $DB_Settings_Syncing;
    protected $DB_Settings_General;
    protected $DB_Products;
    protected $compatible_charset;
    protected $CPT_Model;

    public function __construct(
        $DB_Settings_Syncing,
        $DB_Settings_General,
        $DB_Products,
        $CPT_Model
    ) {
        $this->DB = $DB_Settings_Syncing; // used only for readability
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Products = $DB_Products;
        $this->CPT_Model = $CPT_Model;
        $this->compatible_charsets = true;

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

        $items_filtered = Utils::filter_data_by(
            $this->DB_Products->copy($items),
            ['variants', 'options']
        );

        do_action('wpshopify_processing_products_batch', $items_filtered);

        $this->dispatch_items($items_filtered);
    }

    protected function get_post_by_product_id($product)
    {
    }

    /*

	Performs actions required for each item in the queue

	*/
    protected function task($product)
    {
        if ($this->time_exceeded() || $this->memory_exceeded()) {
            return $product;
        }

        // Stops background process if syncing stops
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            $this->complete();
            return false;
        }

        $post_id = $this->DB_Products->get_post_id_by_product_id($product->id);

        if (!empty($post_id)) {
            $post_id = $post_id[0];
        } else {
            $post_id = false;
        }

        // Custom post type work
        $new_post_id = $this->CPT_Model->insert_or_update_product_post(
            $product,
            $post_id
        );

        // Custom table work
        $result = $this->DB_Products->insert_items_of_type(
            $this->DB_Products->mod_before_change($product, $new_post_id)
        );

        do_action(
            'wpshopify_processing_posts_task',
            $new_post_id,
            'product',
            $product
        );

        // Save warnings if exist ...
        $this->DB_Settings_Syncing->maybe_save_warning_from_insert(
            $result,
            'Product',
            $product->title
        );

        if (is_wp_error($result)) {
            $this->DB_Settings_Syncing->save_notice_and_expire_sync($result);
            $this->complete();
            return false;
        }

        return false;
    }

    /*

	Used to ensure proper table encoding before inserting

	*/
    protected function before_queue_item_save($items)
    {
        return $this->DB->encode_data(json_encode($items));
    }

    /*

	Used to increment the syncing current amounts

	*/
    protected function after_queue_item_removal($product)
    {
        $this->DB_Settings_Syncing->increment_current_amount('products');
        $this->DB_Settings_Syncing->increment_current_amount('products');
    }
}
