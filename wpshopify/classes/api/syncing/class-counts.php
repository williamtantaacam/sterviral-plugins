<?php

namespace WP_Shopify\API\Syncing;

use WP_Shopify\Messages;
use WP_Shopify\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class Counts extends \WP_Shopify\API
{
    public $DB_Settings_Syncing;

    public function __construct($DB_Settings_Syncing)
    {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
    }

    public function get_media_totals($syncing_totals)
    {
        $media_total = array_map(function ($type) {
            if (isset($type['products'])) {
                return $type['products'];
            }

            if (isset($type['smart_collections'])) {
                return $type['smart_collections'];
            }

            if (isset($type['custom_collections'])) {
                return $type['custom_collections'];
            }
        }, $syncing_totals);

        return array_sum(array_filter($media_total));
    }

    public function set_syncing_counts($request)
    {
        $syncing_totals = $request->get_param('counts');
        $syncing_exclusions = $request->get_param('exclusions');

        if (!is_array($syncing_totals) || empty($syncing_totals)) {
            return $this->return_error(
                'Couldn\'t find any Shopify data to sync!',
                __METHOD__,
                __LINE__
            );
        }

        if (!is_array($syncing_exclusions) || empty($syncing_exclusions)) {
            $syncing_exclusions = [];
        }

        // Sets the "total" feat images to sync
        $this->DB_Settings_Syncing->update_column_single(
            [
                'syncing_totals_media' => $this->get_media_totals(
                    $syncing_totals
                ),
            ],
            ['id' => 1]
        );

        return $this->return_success([
            'response' => $this->DB_Settings_Syncing->set_syncing_totals(
                Utils::flatten_array($syncing_totals),
                Utils::flatten_array($syncing_exclusions)
            ),
        ]);
    }

    public function set_syncing_count($request)
    {
        $step_name = sanitize_text_field($request->get_param('stepName'));

        if (empty($step_name)) {
            return $this->return_error(
                'Syncing step name not set during count incrementing',
                __METHOD__,
                __LINE__
            );
        }

        $grand_total_col_name = 'syncing_totals_' . $step_name;
        $current_total_col_name = 'syncing_current_amounts_' . $step_name;

        $grand_total = $this->DB_Settings_Syncing->get_col_value(
            $grand_total_col_name,
            'int'
        );
        $current_total = $this->DB_Settings_Syncing->get_col_value(
            $current_total_col_name,
            'int'
        );

        if ($current_total !== $grand_total) {

            $this->DB_Settings_Syncing->save_warning(
                Messages::get('missing_' . $step_name . '_for_page')
            );

            $update_result = $this->DB_Settings_Syncing->update_column_single(
                [$current_total_col_name => $grand_total],
                ['id' => 1]
            );

            if (is_wp_error($update_result)) {
                return $this->return_error(
                    $update_result->get_error_message(),
                    __METHOD__,
                    __LINE__
                );
            } else {
                return $this->return_success([
                    'response' => $grand_total,
                ]);
            }
        }

        return $this->return_success([
            'response' => $grand_total,
        ]);
    }

    public function get_syncing_grand_totals()
    {
        return $this->DB_Settings_Syncing->syncing_totals();
    }

    public function get_syncing_current_totals()
    {
        return $this->DB_Settings_Syncing->syncing_current_amounts();
    }

    public function filter_totals_by_excludes($sync_totals, $excludes)
    {
        if (empty($excludes)) {
            return $sync_totals;
        }

        foreach ($excludes as $exclude) {
            foreach ($sync_totals as &$syncTotal) {
                if (isset($syncTotal[$exclude])) {
                    unset($syncTotal[$exclude]);
                }
            }
        }

        return $sync_totals;
    }

    /*

	Progress: Filter session variables by includes

	*/
    public function filter_totals_by_includes($sync_totals, $includes)
    {
        if (empty($includes)) {
            return $sync_totals;
        }

        $allowed = [];

        foreach ($includes as $include) {
            $allowed[$include] = 0;
        }

        $sync_totals['wps_syncing_current_amounts'] = $allowed;
        $sync_totals['wps_syncing_totals'] = array_intersect_key(
            $sync_totals['wps_syncing_totals'],
            $allowed
        );

        return $sync_totals;
    }

    /*

	Progress: Session creation

	$includes and $excludes are arrays containing the data types like this:

	Array (
		[0] => smart_collections
		[1] => custom_collections
		[2] => customers
		[3] => orders
	)

	*/
    public function get_syncing_total_counts($request)
    {
        $this->DB_Settings_Syncing->toggle_syncing(1);

        $sync_totals = [];
        $includes = $request->get_param('includes')
            ? $request->get_param('includes')
            : [];
        $excludes = $request->get_param('excludes')
            ? $request->get_param('excludes')
            : [];

        // TODO: Need to handle errors here
        $sync_totals['wps_syncing_totals'] = $this->get_syncing_grand_totals();
        $sync_totals[
            'wps_syncing_current_amounts'
        ] = $this->get_syncing_current_totals();

        $sync_totals = $this->filter_totals_by_includes(
            $sync_totals,
            $includes
        );

        // Removes any sync type excluded by the user
        $sync_totals = $this->filter_totals_by_excludes(
            $sync_totals,
            $excludes
        );

        if (empty($sync_totals)) {
            return $this->return_error(
                'Sync totals appear to be empty!',
                __METHOD__,
                __LINE__
            );
        }

        return $this->return_success([
            'response' => $sync_totals,
        ]);
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_syncing_set_counts()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/syncing/counts',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'set_syncing_counts'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
                [
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => [$this, 'get_syncing_total_counts'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: cart_icon_color

	*/
    public function register_route_syncing_set_single_count()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/syncing/count',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'set_syncing_count'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [
            $this,
            'register_route_syncing_set_counts',
        ]);
        add_action('rest_api_init', [
            $this,
            'register_route_syncing_set_single_count',
        ]);
    }
}
