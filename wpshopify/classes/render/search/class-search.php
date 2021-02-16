<?php

namespace WP_Shopify\Render;

if (!defined('ABSPATH')) {
    exit();
}

/*

Render: Search

*/
class Search
{
    public $Templates;
    public $Defaults_Search;

    public function __construct($Templates, $Defaults_Search)
    {
        $this->Templates = $Templates;
        $this->Defaults_Search = $Defaults_Search;
    }

    /*

	Search: Search

	*/
    public function search($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/search/search',
            'type' => 'search',
            'defaults' => 'search',
            'data' => $this->Templates->merge_user_component_data(
                $data,
                'search',
                $this->Defaults_Search
            ),
            'skip_required_data' => true,
        ]);
    }
}
