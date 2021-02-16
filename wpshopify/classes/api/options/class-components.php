<?php

namespace WP_Shopify\API\Options;

use WP_Shopify\Utils;

defined('ABSPATH') ?: exit();

class Components extends \WP_Shopify\API
{
    public function __construct($Template_Loader)
    {
        $this->Template_Loader = $Template_Loader;
    }

    public function get_components_template($request)
    {
        $template_name = $request->get_param('data');
        $template_name = pathinfo($template_name)['filename'];

        $template_file_path = 'custom/' . $template_name;
      
        ob_start();
        $this->Template_Loader
            ->set_template_data([])
            ->get_template_part($template_file_path);
        $output = ob_get_clean();

        if (empty($output)) {
            return $this->handle_response(
                Utils::wp_error(
                    'Template file can\'t be found or is empty. Please make sure you\'ve placed your template inside wps-templates/custom'
                )
            );
        }

        return $this->handle_response([
            'response' => Utils::sanitize_html_template(
                Utils::strip_tags_content($output, '<style>', true)
            ),
        ]);
    }

    public function register_route_components_template()
    {
        return register_rest_route(
            WP_SHOPIFY_SHOPIFY_API_NAMESPACE,
            '/components/template',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_components_template'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [
            $this,
            'register_route_components_template',
        ]);
    }
}
