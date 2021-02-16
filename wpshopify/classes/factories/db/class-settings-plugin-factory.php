<?php

namespace WP_Shopify\Factories\DB;

if (!defined('ABSPATH')) {
    exit();
}

use WP_Shopify\DB;
use WP_Shopify\Options;
use WP_Shopify\Messages;

class Settings_Plugin_Factory
{
    protected static $settings = [];

    public static function query_data($class)
    {
        return $class->table_exists($class->table_name)
            ? $class->get_all_rows()
            : false;
    }

    public static function sanitize($data, $class)
    {
        $col_definitions = $class->get_columns();
        $data_array = (array) $data;

        $remain_ints = $class->cols_that_should_remain_ints();
        $new_data = [];

        foreach ($data_array as $key => $val) {
            // DB Col was probably deleted in new plugin version
            if (!isset($col_definitions[$key])) {
                $new_data[$key] = false;
            } else {
                if ($col_definitions[$key] === '%d') {
                    if (!in_array($key, $remain_ints)) {
                        $val = (bool) $val;
                    } else {
                        $val = (int) $val;
                    }
                }

                if ($col_definitions[$key] === '%f') {
                    $val = (float) $val;
                }

                $new_data[$key] = $val;
            }
        }

        return $new_data;
    }

    public static function get_results($class_result, $class)
    {
        if (empty($class_result)) {
            if ($class->table_name_suffix === 'wps_settings_license') {
                return [];
            } else {
                return $class->get_column_defaults();
            }
        }

        // Returns all rows if license table
        if ($class->table_name_suffix === 'wps_settings_license') {
            return array_map(function ($license) use ($class) {
                return self::sanitize($license, $class);
            }, $class_result);
        } else {
            return $class_result[0];
        }
    }

    public static function get_allow_usage_tracking($general_settings)
    {
        //   if ($general_settings->default_allow_tracking) {
        //       return [];
        //   }

        //   if (!Options::get("wps_admin_dismissed_notice_allow_tracking")) {
        //       return [
        //           'id' => \uniqid(),
        //           'status' => 'warning',
        //           'content' => Messages::get('notice_allow_tracking'),
        //           'isDismissable' => true,
        //           'dismissName' => 'notice_allow_tracking',
        //           'dismissType' => 'option',
        //           'dismissValue' => true,
        //       ];
        //   }

        return [];
    }

    public static function get_admin_notices($general_settings)
    {
        $ssdo = array_filter(
            array_merge([], self::get_allow_usage_tracking($general_settings))
        );

        if (empty($ssdo)) {
            return [];
        }

        return $ssdo;
    }

    public static function build($plugin_settings = false)
    {
        if (empty(self::$settings)) {
            $general = new DB\Settings_General();
            $connection = new DB\Settings_Connection();
            $license = new DB\Settings_License();
            $syncing = new DB\Settings_Syncing();
            $shop = new DB\Shop();

            self::$settings['general'] = self::sanitize(
                self::get_results(self::query_data($general), $general),
                $general
            );

            self::$settings['connection'] = self::sanitize(
                self::get_results(self::query_data($connection), $connection),
                $connection
            );

            self::$settings['license'] = self::get_results(
                self::query_data($license),
                $license
            );

            self::$settings['syncing'] = self::sanitize(
                self::get_results(self::query_data($syncing), $syncing),
                $syncing
            );

            self::$settings['shop'] = self::sanitize(
                self::get_results(self::query_data($shop), $shop),
                $shop
            );

            self::$settings['notices'] = self::get_admin_notices($general);

            self::$settings['misc'] = [
               'should_flush_rewrite_rules' => get_option('wpshopify_should_flush_rewrite_rules')
            ];
        }

        return self::$settings;
    }
}
