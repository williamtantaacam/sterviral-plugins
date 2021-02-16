<?php

namespace WP_Shopify\Render;

if (!defined('ABSPATH')) {
    exit();
}

/*

Render: Collections

*/
class Collections
{
    public $Templates;
    public $Defaults_Collections;

    public function __construct($Templates, $Defaults_Collections)
    {
        $this->Templates = $Templates;
        $this->Defaults_Collections = $Defaults_Collections;
    }

    /*

	Products: Gallery

	*/
    public function collections($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/collections/collections-all',
            'type' => 'collections',
            'defaults' => 'collections',
            'data' => $this->Templates->merge_user_component_data(
                $data,
                'collections',
                $this->Defaults_Collections
            ),
        ]);
    }
}
