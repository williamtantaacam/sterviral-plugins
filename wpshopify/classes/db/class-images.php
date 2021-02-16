<?php

namespace WP_Shopify\DB;

use WP_Shopify\Utils;
use WP_Shopify\Utils\URLs;

if (!defined('ABSPATH')) {
    exit();
}

class Images extends \WP_Shopify\DB
{
    public $table_name_suffix;
    public $table_name;
    public $version;
    public $primary_key;
    public $lookup_key;
    public $cache_group;
    public $type;

    public $default_image_id;
    public $default_product_id;
    public $default_post_id;
    public $default_variant_ids;
    public $default_src;
    public $default_alt;
    public $default_position;
    public $default_created_at;
    public $default_updated_at;
    public $default_collection_id;

    public function __construct()
    {
        // Table info
        $this->table_name_suffix = WP_SHOPIFY_TABLE_NAME_IMAGES;
        $this->table_name = $this->get_table_name();
        $this->version = '1.0';
        $this->primary_key = 'id';
        $this->lookup_key = 'image_id';
        $this->cache_group = 'wps_db_images';
        $this->type = 'image';

        // Defaults
        $this->default_image_id = 0;
        $this->default_product_id = 0;
        $this->default_collection_id = 0;
        $this->default_post_id = 0;
        $this->default_variant_ids = '';
        $this->default_src = '';
        $this->default_alt = '';
        $this->default_position = 0;
        $this->default_created_at = date_i18n('Y-m-d H:i:s');
        $this->default_updated_at = date_i18n('Y-m-d H:i:s');
    }

    /*

	Table column name / formats

	Important: Used to determine when new columns are added

	*/
    public function get_columns()
    {
        return [
            'id' => '%d',
            'image_id' => '%d',
            'product_id' => '%d',
            'collection_id' => '%d',
            'post_id' => '%d',
            'variant_ids' => '%s',
            'src' => '%s',
            'alt' => '%s',
            'position' => '%d',
            'created_at' => '%s',
            'updated_at' => '%s'
        ];
    }

    /*

   Columns that should remain integers during casting.
   We need to check against this when retrieving data since MYSQL 
   converts all cols to strings upon retrieval. 

   */
    public function cols_that_should_remain_ints()
    {
        return [
            'id',
            'image_id',
            'product_id',
            'collection_id',
            'post_id',
            'position'
        ];
    }

    /*

	Table default values

	*/
    public function get_column_defaults()
    {
        return [
            'image_id' => $this->default_image_id,
            'product_id' => $this->default_product_id,
            'collection_id' => $this->default_collection_id,
            'post_id' => $this->default_post_id,
            'variant_ids' => $this->default_variant_ids,
            'src' => $this->default_src,
            'alt' => $this->default_alt,
            'position' => $this->default_position,
            'created_at' => $this->default_created_at,
            'updated_at' => $this->default_updated_at
        ];
    }

    /*

	The modify options used for inserting / updating / deleting

	*/
    public function modify_options(
        $shopify_item,
        $item_lookup_key = WP_SHOPIFY_PRODUCTS_LOOKUP_KEY
    ) {
        return [
            'item' => $shopify_item,
            'item_lookup_key' => $item_lookup_key,
            'item_lookup_value' => $shopify_item->id,
            'prop_to_access' => 'images',
            'change_type' => 'image'
        ];
    }

    /*

	Mod before change

	*/
    public function mod_before_change($image)
    {
        $image_copy = $this->copy($image);
        $image_copy = $this->maybe_rename_to_lookup_key($image_copy);

        return $image_copy;
    }

    /*

	Inserts a single option

	*/
    public function insert_image($image)
    {
        return $this->insert($image);
    }

    /*

	Updates a single image

	*/
    public function update_image($image)
    {
        return $this->update(
            $this->lookup_key,
            $this->get_lookup_value($image),
            $image
        );
    }

    /*

	Deletes a single image

	*/
    public function delete_image($image)
    {
        return $this->delete_rows(
            $this->lookup_key,
            $this->get_lookup_value($image)
        );
    }

    /*

	Delete images from product ID

	*/
    public function delete_images_from_product_id($product_id)
    {
        return $this->delete_rows(WP_SHOPIFY_PRODUCTS_LOOKUP_KEY, $product_id);
    }

    /*

	Gets all images associated with a given product, by product id

	*/
    public function get_images_from_product_id($product_id)
    {
        return $this->get_rows(WP_SHOPIFY_PRODUCTS_LOOKUP_KEY, $product_id);
    }

    /*

	Gets variants from an image

	*/
    public static function get_variants_from_image($image)
    {
        if (is_array($image)) {
            $image = Utils::convert_array_to_object($image);
        }

        if (Utils::has($image, 'variant_ids')) {
            $variantIDs = maybe_unserialize($image->variant_ids);

            if (!empty($variantIDs)) {
                $variantIDs = implode(', ', $variantIDs);
            } else {
                $variantIDs = '';
            }
        } else {
            $variantIDs = '';
        }

        return $variantIDs;
    }

    /*

	TODO: Rethink ... redundant
	Currently used within imgs.templates/components/single/imgs.php

	*/
    public static function get_image_details_from_image($image, $product)
    {
        $result = new \stdClass();

        if (empty($image->alt)) {
            $alt = $product->details->title;
        } else {
            $alt = $image->alt;
        }

        if (empty($image->src)) {
            $src = WP_SHOPIFY_PLUGIN_URL . 'public/imgs/placeholder.png';
        } else {
            $src = $image->src;
        }

        $result->src = $src;
        $result->alt = $alt;

        return $result;
    }

    /*

	Gets Image details (alt and src) by product object
	Param: $product Object

	*/
    public static function get_image_details_from_product($product)
    {
        $data = new \stdClass();

        // If an object is passed ...
        if (is_object($product)) {
            if (empty($product->feat_image)) {
                $alt = $product->title;

                if (empty($product->image)) {
                    $src =
                        WP_SHOPIFY_PLUGIN_URL . 'public/imgs/placeholder.png';
                } else {
                    $src = $product->image;
                }
            } else {
                $src = $product->feat_image[0]->src;

                if (empty($product->feat_image[0]->alt)) {
                    $alt = $product->title;
                } else {
                    $alt = $product->feat_image[0]->alt;
                }
            }

            $data->src = $src;
            $data->alt = $alt;
        } else {
            $data->src = '';
            $data->alt = '';
        }

        return $data;
    }

    /*

	Checks whether image is placeholder or not

	*/
    public static function has_placeholder($image)
    {
        if (Utils::str_contains($image, 'public/imgs/placeholder.png')) {
            return true;
        }

        return false;
    }

    /*

	Gets Image details (alt and src) by product object
	Param: $product Object

	*/
    public static function get_image_details_from_collection($collection)
    {
        $data = new \stdClass();

        if (empty($collection->image)) {
            $src = WP_SHOPIFY_PLUGIN_URL . 'public/imgs/placeholder.png';
        } else {
            $src = $collection->image;
        }

        if (isset($collection->title)) {
            $alt = $collection->title;
        } else {
            $alt = '';
        }

        $data->src = $src;
        $data->alt = $alt;

        return $data;
    }

    /*

	Get Single Product Images
	Without: Images, variants

	*/
    public function get_images_from_post_id($postID = null)
    {
        global $wpdb;

        if ($postID === null) {
            $postID = get_the_ID();
        }

        $query =
            "SELECT images.* FROM " .
            $wpdb->prefix .
            WP_SHOPIFY_TABLE_NAME_PRODUCTS .
            " AS products INNER JOIN " .
            $this->table_name .
            " AS images ON images.product_id = products.product_id WHERE products.post_id = %d";

        return $wpdb->get_results($wpdb->prepare($query, $postID));
    }

    /*

	Position is a string so we need a more relaxed
	equality check

	*/
    public function get_featured_image_by_position($image)
    {
        return $image->position == 1;
    }

    /*

	Get feat image by id

	*/
    public function get_feat_image_by_post_id($post_id)
    {
        return array_values(
            array_filter($this->get_images_from_post_id($post_id), [
                $this,
                "get_featured_image_by_position"
            ])
        );
    }

    /*

	Responsible for getting image extension from image URL

	*/
    public function get_image_extension_from_url($image_url)
    {
        return URLs::get_extension($image_url);
    }

    /*

	Responsible for splitting image into parts

	*/
    public function split_from_extension($image_url, $extension)
    {
        $to_explode = '.' . $extension;

        return explode($to_explode, $image_url);
    }

    /*

	Responsible for building width and height filter

	*/
    public function build_width_height_filter($width = 0, $height = 0)
    {
        if (!is_int($width) || $width < 0) {
            $width = 0;
        }

        if (!is_int($height) || $height < 0) {
            $height = 0;
        }

        if (empty($width) && empty($height)) {
            return '';
        }

        // Both set
        if ($width !== 0 && $height !== 0) {
            return '_' . $width . 'x' . $height;
        }

        // Only width set
        if ($height === 0 && $width !== 0) {
            return '_' . $width . 'x';
        }

        // Only height set
        if ($width === 0 && $height !== 0) {
            return '_x' . $height;
        }

        return '';
    }

    /*

	Responsible for taking an $image_url and returning its parts.

	*/
    public function split_image_url($image_url)
    {
        $extension = $this->get_image_extension_from_url($image_url);

        if (empty($extension)) {
            return false;
        }

        $image_parts = $this->split_from_extension($image_url, $extension);

        return [
            'extension' => $extension,
            'before_extension' => $image_parts[0],
            'after_extension' => $image_parts[1]
        ];
    }

    /*

	Responsible for taking a $crop value and returning the URL string version.

	Empty string is no $crop set

	*/
    public function build_crop_filter($crop = '')
    {
        if (empty($crop) || !is_string($crop) || $crop === 'none') {
            return '';
        }

        return '_crop_' . $crop;
    }

    /*

	Responsible for taking a $scale value and returning the URL string version.

	Empty string is no $scale set

	*/
    public function build_scale_filter($scale = 0)
    {
        if (empty($scale) || !is_int($scale) || $scale <= 1 || $scale > 3) {
            return '';
        }

        return '@' . $scale . 'x';
    }

    /*

	Responsible for adding width and height filter to image URL

	*/
    public function add_custom_size_to_image_url($width, $height, $image_url)
    {
        $split_parts = $this->split_image_url($image_url);

        if ($split_parts === false) {
            return $image_url;
        }

        return $split_parts['before_extension'] .
            $this->build_width_height_filter($width, $height) .
            '.' .
            $split_parts['extension'] .
            $split_parts['after_extension'];
    }

    public function auto_width_and_height($settings)
    {
        if (isset($settings['width']) && isset($settings['width'])) {
            return $settings['width'] === 0 && $settings['height'] === 0;
        }

        return true;
    }

    /*

	Responsible for adding crop filter to image URL

	*/
    public function add_custom_crop_to_image_url($settings, $image_url)
    {
        $split_parts = $this->split_image_url($image_url);

        if (
            $split_parts === false ||
            !isset($settings['crop']) ||
            $this->auto_width_and_height($settings)
        ) {
            return $image_url;
        }

        return $split_parts['before_extension'] .
            $this->build_crop_filter($settings['crop']) .
            '.' .
            $split_parts['extension'] .
            $split_parts['after_extension'];
    }

    /*

	Responsible for adding scale filter to image URL

	*/
    public function add_custom_scale_to_image_url($scale, $image_url)
    {
        $split_parts = $this->split_image_url($image_url);

        if ($split_parts === false || $scale <= 1 || $scale > 3) {
            return $image_url;
        }

        return $split_parts['before_extension'] .
            $this->build_scale_filter($scale) .
            '.' .
            $split_parts['extension'] .
            $split_parts['after_extension'];
    }

    /*

	$settings is an array with this structure:

	[
		'src'			=> 'https://cdn.shopify.com/s/files/1/1405/0664.jpg',
		'width'		=> 300
		'height'	=> 0
		'crop'		=> 'none'
		'scale'		=> 0
	]

	TODO: Just pass the $settings instead

	*/
    public function add_custom_sizing_to_image_url($settings)
    {
        $src = $settings['src'];
        $width = $settings['width'];
        $height = $settings['height'];
        $crop = $settings['crop'];
        $scale = $settings['scale'];

        // Returns a modified image URL
        return $this->add_custom_scale_to_image_url(
            $scale,
            $this->add_custom_crop_to_image_url(
                $settings,
                $this->add_custom_size_to_image_url($width, $height, $src)
            )
        );
    }

    public function query_all_images()
    {
        return 'SELECT images.src, images.post_id, images.alt FROM ' .
            $this->table_name .
            ' as images WHERE images.position = 1';
    }

    public function get_all_images()
    {
        global $wpdb;

        return $wpdb->get_results($this->query_all_images());
    }

    public function delete_media()
    {
        $media_ids = $this->get_all_plugin_attachments();

        if ($media_ids && !empty($media_ids)) {
            $results = [];

            foreach ($media_ids as $media_id) {
                $results[] = wp_delete_attachment($media_id->ID, true);
            }

            return $results;
        } else {
            return false;
        }
    }

    public function get_all_plugin_attachments()
    {
        $args = [
            'posts_per_page' => -1,
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'no_found_rows' => true,
            'meta_query' => [
                [
                    'key' => '_wp_attachment_metadata',
                    'value' => 'wpshopify',
                    'compare' => 'LIKE'
                ]
            ]
        ];

        $images = new \WP_Query($args);

        return $images->posts;
    }

    public function query_get_all_plugin_attachments_meta()
    {
        global $wpdb;

        $posts_table = $wpdb->prefix . 'posts';
        $post_meta_table = $wpdb->prefix . 'postmeta';

        return "SELECT " .
            $post_meta_table .
            ".* FROM " .
            $posts_table .
            " INNER JOIN " .
            $post_meta_table .
            " ON ( " .
            $posts_table .
            ".ID = " .
            $post_meta_table .
            ".post_id ) WHERE 1=1 AND (( " .
            $post_meta_table .
            ".meta_key = '_wp_attachment_metadata' AND " .
            $post_meta_table .
            ".meta_value LIKE '%wpshopify%' )) AND " .
            $posts_table .
            ".post_type = 'attachment' AND ((" .
            $posts_table .
            ".post_status = 'inherit')) GROUP BY " .
            $posts_table .
            ".ID";
    }

    public function get_all_plugin_attachments_meta()
    {
        global $wpdb;

        return $wpdb->get_results(
            $this->query_get_all_plugin_attachments_meta()
        );
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
			image_id bigint(100) unsigned NOT NULL DEFAULT '{$this->default_image_id}',
			product_id bigint(100) DEFAULT '{$this->default_product_id}',
         collection_id bigint(100) DEFAULT '{$this->default_collection_id}',
         post_id bigint(100) DEFAULT '{$this->default_post_id}',
			variant_ids longtext DEFAULT '{$this->default_variant_ids}',
			src longtext DEFAULT '{$this->default_src}',
			alt longtext DEFAULT '{$this->default_alt}',
			position int(20) DEFAULT '{$this->default_position}',
			created_at datetime DEFAULT '{$this->default_created_at}',
			updated_at datetime DEFAULT '{$this->default_updated_at}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";
    }
}
