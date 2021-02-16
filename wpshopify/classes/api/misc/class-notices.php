<?php

namespace WP_Shopify\API\Misc;

use WP_Shopify\Options;
use WP_Shopify\Messages;
use WP_Shopify\Transients;
use WP_Shopify\Utils\Data as Data;

if (!defined('ABSPATH')) {
    exit();
}

class Notices extends \WP_Shopify\API
{
    public $plugin_settings;
    public $DB_Settings_General;
    public $Backend;
    public $DB_Settings_Syncing;

    public function __construct(
        $plugin_settings,
        $DB_Settings_General,
        $Backend,
        $DB_Settings_Syncing
    ) {
        $this->plugin_settings = $plugin_settings;
        $this->DB_Settings_General = $DB_Settings_General;
        $this->Backend = $Backend;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
    }

    public function delete_notices($request)
    {
        return $this->handle_response([
            'response' => $this->DB_Settings_General->set_app_uninstalled(0),
        ]);
    }

    /*

	Notice: Error

	*/
    public function error(
        $message,
        $dismiss_name = false,
        $cache_type = 'transient'
    ) {
        $this->notice('error', $message, $dismiss_name, $cache_type);
    }

    /*

	Notice: Warning

	*/
    public function warning_notice(
        $message,
        $dismiss_name = false,
        $cache_type = 'transient'
    ) {
        $this->notice('warning', $message, $dismiss_name, $cache_type);
    }

    /*

	Notice: Success

	*/
    public function success_notice(
        $message,
        $dismiss_name = false,
        $cache_type = 'transient'
    ) {
        $this->notice('success', $message, $dismiss_name, $cache_type);
    }

    /*

	Notice: Info

	*/
    public function info_notice(
        $message,
        $dismiss_name = false,
        $cache_type = 'transient'
    ) {
        $this->notice('info', $message, $dismiss_name, $cache_type);
    }

    public function show_notice_markup(
        $type,
        $dismiss_name,
        $message,
        $cache_type
    ) {
        ?>
      <div class="notice wps-notice notice-<?php
      echo $type;

      if ($dismiss_name) {
          echo ' is-dismissible" data-dismiss-name="' . $dismiss_name;
      }
      ?>" data-dismiss-type="<?= $cache_type ?>">

         <p><?= $message ?></p>

      </div>
      <?php
    }

    /*

	Notice

	*/
    private function notice(
        $type,
        $message,
        $dismiss_name = false,
        $cache_type = 'transient'
    ) {
        if ($cache_type === 'transient') {
            $already_dismissed = Transients::get(
                "wps_admin_dismissed_{$dismiss_name}"
            );
        } else {
            $already_dismissed = Options::get(
                "wps_admin_dismissed_{$dismiss_name}"
            );
        }

        if ($already_dismissed) {
            return;
        }

        $this->show_notice_markup($type, $dismiss_name, $message, $cache_type);
    }

    /*

	Show admin notices

	*/
    public function show_cpt_data_erase_notice()
    {
        if (
            $this->Backend->is_admin_posts_page($this->Backend->get_screen_id())
        ) {
            $this->warning_notice(
                Messages::get('saving_native_cpt_data'),
                'notice_warning_post_data_eraser',
                'option'
            );
        }
    }

    public function show_tracking_notice()
    {
        if ($this->Backend->is_plugin_specific_pages()) {
            $this->warning_notice(
                Messages::get('notice_allow_tracking'),
                'notice_allow_tracking',
                'option'
            );
        }
    }

    /*

	Dismiss notices

	*/
    public function dismiss_notice($request)
    {
        $dismiss_name = sanitize_text_field(
            $request->get_param('dismiss_name')
        );
        $dismiss_type = sanitize_text_field(
            $request->get_param('dismiss_type')
        );
        $dismiss_value = sanitize_text_field(
            $request->get_param('dismiss_value')
        );

        if (!$dismiss_name) {
            return $this->handle_response([
                'response' => false,
            ]);
        }

        if (!$dismiss_type) {
            $dismiss_type = 'transient';
        }

        if ($dismiss_type === 'transient') {
            $notice_dismissed = Transients::set(
                "wps_admin_dismissed_{$dismiss_name}",
                true,
                0
            );
        } elseif ($dismiss_type === 'option') {
            $notice_dismissed = Options::update(
                "wps_admin_dismissed_{$dismiss_name}",
                true
            );
        }

        if ($dismiss_value) {
            if ($dismiss_name === 'notice_allow_tracking') {
                $this->DB_Settings_General->update_column_single(
                    ['allow_tracking' => Data::to_bool_int($dismiss_value)],
                    ['id' => 1]
                );
            }
        }

        return $this->handle_response([
            'response' => $notice_dismissed,
        ]);
    }

    /*

	Show admin notices

	*/
    public function show_admin_notices()
    {
        $this->show_cpt_data_erase_notice();
        //   $this->show_tracking_notice();
    }

    /*

	Register route: collections_heading

	*/
    public function register_route_notices()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/notices',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'delete_notices'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    /*

	Register route: collections_heading

	*/
    public function register_route_notices_dismiss()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/notices/dismiss',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'dismiss_notice'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_notices']);
        add_action('rest_api_init', [$this, 'register_route_notices_dismiss']);
        add_action('admin_notices', [$this, 'show_admin_notices']);
    }
}
