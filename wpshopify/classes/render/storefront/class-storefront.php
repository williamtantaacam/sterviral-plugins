<?php

namespace WP_Shopify\Render;

defined('ABSPATH') ?: exit();

/*

Render: Storefront

*/
class Storefront
{
    public $Templates;
    public $Defaults_Storefront;

    public function __construct($Templates, $Defaults_Storefront)
    {
        $this->Templates = $Templates;
        $this->Defaults_Storefront = $Defaults_Storefront;
    }

    /*

	Storefront: Storefront

	*/
    public function storefront($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/storefront/storefront',
            'type' => 'storefront',
            'defaults' => 'storefront',
            'data' => $this->Templates->merge_user_component_data(
                $data,
                'storefront',
                $this->Defaults_Storefront
            ),
            'skip_required_data' => true,
        ]);
    }
}
