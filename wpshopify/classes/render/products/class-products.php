<?php

namespace WP_Shopify\Render;

if (!defined('ABSPATH')) {
    exit();
}

class Products
{
    public $Templates;
    public $Defaults_Products;

    public function __construct($Templates, $Defaults_Products)
    {
        $this->Templates = $Templates;
        $this->Defaults_Products = $Defaults_Products;
    }

    public function buy_button($data = [])
    {
        return $this->add_to_cart($data);
    }

    public function add_to_cart($data = [])
    {

        return $this->Templates->load([
            'path' => 'components/products/buy-button/buy',
            'name' => 'button',
            'type' => 'products',
            'defaults' => 'add_to_cart',
            'data' => $this->Templates->merge_user_component_data(
                $data,
                'product_add_to_cart',
                $this->Defaults_Products
            ),
        ]);
    }

    /*

    Products: Title

     */
    public function title($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/products/title/title',
            'type' => 'products',
            'defaults' => 'title',
            'data' => $this->Templates->merge_user_component_data(
                $data,
                'product_title',
                $this->Defaults_Products
            ),
        ]);
    }

    /*

    Products: Description

     */
    public function description($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/products/description/description',
            'type' => 'products',
            'defaults' => 'description',
            'data' => $this->Templates->merge_user_component_data(
                $data,
                'product_description',
                $this->Defaults_Products
            ),
        ]);
    }

    public function pricing($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/products/pricing/pricing',
            'type' => 'products',
            'defaults' => 'pricing',
            'data' => $this->Templates->merge_user_component_data(
                $data,
                'product_pricing',
                $this->Defaults_Products
            ),
        ]);
    }

    public function gallery($data = [])
    {
        return $this->Templates->load([
            'path' => 'components/products/gallery/gallery',
            'type' => 'products',
            'defaults' => 'gallery',
            'data' => $this->Templates->merge_user_component_data(
                $data,
                'product_gallery',
                $this->Defaults_Products
            ),
        ]);
    }

    public function products($data = [])
    {
        $data = $this->Templates->merge_user_component_data(
            $data,
            'products',
            $this->Defaults_Products
        );

        return $this->Templates->load([
            'path' => 'components/products/products-all',
            'type' => 'products',
            'defaults' => 'products',
            'cache_key' => 'wp_shopify_shortcode_wps_products_',
            'data' => $data,
        ]);
    }
}
