<?php

namespace WP_Shopify\DB;

use WP_Shopify\Utils;
use WP_Shopify\Utils\Data as Utils_Data;
use WP_Shopify\Transients;

if (!defined('ABSPATH')) {
    exit();
}

class Settings_Connection extends \WP_Shopify\DB
{
    public $table_name_suffix;
    public $table_name;
    public $version;
    public $primary_key;
    public $lookup_key;
    public $cache_group;
    public $type;

    public $default_api_key;
    public $default_api_password;
    public $default_shared_secret;
    public $default_storefront_access_token;
    public $default_access_token;
    public $default_domain;
    public $default_nonce;

    public function __construct()
    {
        $this->table_name_suffix = WP_SHOPIFY_TABLE_NAME_SETTINGS_CONNECTION;
        $this->table_name = $this->get_table_name();
        $this->version = '1.0';
        $this->primary_key = 'id';
        $this->lookup_key = 'id';
        $this->cache_group = 'wps_db_connection';
        $this->type = 'settings_connection';

        $this->default_api_key = '';
        $this->default_api_password = '';
        $this->default_shared_secret = '';
        $this->default_storefront_access_token = '';
        $this->default_access_token = '';
        $this->default_domain = '';
        $this->default_nonce = '';
    }

    public function get_columns()
    {
        return [
            'id' => '%d',
            'api_key' => '%s',
            'api_password' => '%s',
            'shared_secret' => '%s',
            'storefront_access_token' => '%s',
            'access_token' => '%s',
            'domain' => '%s',
            'nonce' => '%s',
        ];
    }

    /*

  Columns that should remain integers during casting.
  We need to check against this when retrieving data since MYSQL 
  converts all cols to strings upon retrieval. 

  */
    public function cols_that_should_remain_ints()
    {
        return ['id'];
    }

    public function get_column_defaults()
    {
        return [
            'api_key' => $this->default_api_key,
            'api_password' => $this->default_api_password,
            'shared_secret' => $this->default_shared_secret,
            'storefront_access_token' => $this->default_storefront_access_token,
            'access_token' => $this->default_access_token,
            'domain' => $this->default_domain,
            'nonce' => $this->default_nonce,
        ];
    }

    /*

	Insert connection data

	*/
    public function maybe_insert_connection($connectionData)
    {
        if (isset($connectionData['domain']) && $connectionData['domain']) {
            if ($this->get_row_by('domain', $connectionData['domain'])) {
                $row_id = $this->get_column_by(
                    'id',
                    'domain',
                    $connectionData['domain']
                );
                $results = $this->update(
                    $this->lookup_key,
                    $row_id,
                    $connectionData
                );
            } else {
                $results = $this->insert($connectionData);
            }
        } else {
            return Utils::wp_error([
                'message_lookup' =>
                    'Please make sure you\'ve entered your Shopify domain.',
                'call_method' => __METHOD__,
                'call_line' => __LINE__,
            ]);
        }

        return $results;
    }

    public function update_connection($connection_data)
    {
        return $this->update($this->lookup_key, 1, $connection_data);
    }

    /*

	Predicate function
	Checks whether an active connection to Shopify exists

	*/
    public function has_connection()
    {
        $access_token = $this->get_column_single('access_token');

        if (
            Utils::array_not_empty($access_token) &&
            isset($access_token[0]->access_token)
        ) {
            return true;
        } else {
            return false;
        }
    }

    /*

	Get the Shopify shared secret. Used to verify Webhooks.

	*/
    public function shared_secret()
    {
        $setting = $this->get_column_single('shared_secret');

        if (
            Utils::array_not_empty($setting) &&
            isset($setting[0]->shared_secret)
        ) {
            return $setting[0]->shared_secret;
        } else {
            return false;
        }
    }

    /*

	Responsible for building the Basic Auth header value used during requests

	*/
    public function build_auth_token($api_key, $api_password)
    {
        return base64_encode($api_key . ':' . $api_password);
    }

    /*

	Builds an auth token used for Shopify API requests

	wp_shopify_auth_token Transient cache is cleared after each sync

	*/
    public function get_auth_token()
    {
        $connection = $this->get();

        // Need to check if $connect is empty. If so, die with connection empy message

        if (
            Utils::has($connection, 'api_key') &&
            Utils::has($connection, 'api_password')
        ) {
            $built_auth_token = $this->build_auth_token(
                $connection->api_key,
                $connection->api_password
            );

            return $built_auth_token;
        }

        return false;
    }

    /*

	Get the current myshopify domain

	*/
    public function get_domain()
    {
        $domain = $this->get_column_single('domain');

        if (Utils::array_not_empty($domain) && isset($domain[0]->domain)) {
            return $domain[0]->domain;
        } else {
            return false;
        }
    }

    public function get_storefront_access_token()
    {
        $storefront_access_token = $this->get_column_single(
            'storefront_access_token'
        );

        if (empty($storefront_access_token)) {
            return false;
        }

        return $storefront_access_token[0]->storefront_access_token;
    }

    public function get_admin_api_password()
    {
        $api_password = $this->get_column_single('api_password');

        if (empty($api_password)) {
            return false;
        }

        return Utils_Data::coerce($api_password[0]->api_password, 'string');
    }

    public function maybe_migrate_legacy()
    {
        $connection = $this->get();

        // Nothing to update ... just return
        if (empty($connection)) {
            return false;
        }

        // We know we're not dealing with a legacy upgrade ... just return
        if (
            empty($connection->password) &&
            empty($connection->js_access_token)
        ) {
            return false;
        }

        // We know we already updated ... just return
        if (
            $connection->password === $connection->api_password &&
            $connection->js_access_token ===
                $connection->storefront_access_token
        ) {
            return false;
        }

        // Need to upgrade. Here we're setting the old values to the new values.
        $connection->api_password = $connection->password;
        $connection->storefront_access_token = $connection->js_access_token;

        $results_migrating_connection = $this->update_connection($connection);

        return $results_migrating_connection;
    }

    public function mask_connection()
    {
        $connection = (array) $this->get();

        return array_map(function ($value) {
            $is_domain = strpos($value, 'myshopify.com');

            if ($value && !$is_domain) {
                return Utils::mask_value($value);
            }
            return $value;
        }, $connection);
    }

    /*

	Creates a table query string

	*/
    public function create_table_query($table_name = false)
    {
        if (!$table_name) {
            $table_name = $this->table_name;
        }

        $collate = $this->collate();

        return "CREATE TABLE $table_name (
			id bigint(100) unsigned NOT NULL AUTO_INCREMENT,
			api_key varchar(100) DEFAULT '{$this->default_api_key}',
			api_password varchar(100) DEFAULT '{$this->default_api_password}',
			shared_secret varchar(100) DEFAULT '{$this->default_shared_secret}',
			storefront_access_token varchar(100) DEFAULT '{$this->default_storefront_access_token}',
			access_token varchar(100) DEFAULT '{$this->default_access_token}',
			domain varchar(100) DEFAULT '{$this->default_domain}',
			nonce varchar(100) DEFAULT '{$this->default_nonce}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";
    }
}
