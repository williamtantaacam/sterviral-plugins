<?php

namespace WP_Shopify\Utils;

use WP_Shopify\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class Data
{
    /*

	Finds the size in bytes of a given piece of data / variable

	*/
    public static function size_in_bytes($data)
    {
        $serialized_data = serialize($data);

        if (function_exists('mb_strlen')) {
            $size = mb_strlen($serialized_data, '8bit');
        } else {
            $size = strlen($serialized_data);
        }

        return $size;
    }

    /*

	Convert to readable size format

	*/
    public static function to_readable_size($size)
    {
        $base = log($size) / log(1024);
        $suffix = ["", "KB", "MB", "GB", "TB"];
        $f_base = floor($base);

        return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
    }

    /*

	Converts an array to a comma seperated string without spaces

	*/
    public static function array_to_comma_string($maybe_array)
    {
        return Utils::remove_spaces_from_string(
            Utils::convert_to_comma_string($maybe_array)
        );
    }

    /*

	Retrieves an array value based on a provided index, minus one

	*/
    public static function current_index_value_less_one($items, $index)
    {
        $current_index_less_one = $index - 1;

        // Send current index if empty
        if (!isset($items[$current_index_less_one])) {
            return $index;
        }

        return $items[$current_index_less_one];
    }

    /*

	Chunks an array

	*/
    public static function chunk_data($data, $items_per_chunk)
    {
        return array_chunk($data, $items_per_chunk);
    }

    /*

	Used within a Reduce to count the total number of items

	*/
    public static function add($carry, $item)
    {
        $carry += $item;

        return $carry;
    }

    /*

	Adds ints from an array like:

	[1, 2, 3, 4]

	*/
    public static function add_totals($array_of_ints)
    {
        return array_reduce($array_of_ints, [__CLASS__, 'add']);
    }

    /*

	Only returns wp_errors

	*/
    public static function only_wp_errors($item)
    {
        return is_wp_error($item);
    }

    /*

	Only returns wp_errors

	*/
    public static function return_only_wp_errors($items)
    {
        return array_filter($items, [__CLASS__, 'only_wp_errors']);
    }

    /*

	Coerce into a given type

	*/
    public static function coerce($value, $type)
    {
        $new_value = $value;

        if (settype($new_value, $type)) {
            return $new_value;
        }

        return false;
    }

    public static function contains_comma($string)
    {
        return Utils::str_contains($string, ',');
    }

    public static function to_bool_int($value)
    {
        if ($value) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function sanitize_setting($setting, $serialize = false)
    {
        if (\is_string($setting)) {
            if ($setting === '1') {
                return true;
            }

            if ($setting === '0') {
                return false;
            }

            if (\is_numeric($setting)) {
                if (\is_float($setting)) {
                    return (float) $setting;
                }

                return (int) $setting;
            }

            if ($serialize) {
                return \maybe_serialize($setting);
            } else {
                return \maybe_unserialize($setting);
            }
        }

        return $setting;
    }

    public static function sanitize_settings($settings, $serialize = false)
    {
        return \array_map(function ($setting) use ($serialize) {
            return self::sanitize_setting($setting, $serialize);
        }, $settings);
    }

    public static function sanitize_text_fields($fields)
    {
        return \array_map(function ($field) {
            return sanitize_text_field($field);
        }, $fields);
    }

    public static function map_array_prop($items, $prop)
    {
        return array_map(function ($item) use ($prop) {
            return $item[$prop];
        }, $items);
    }

    public static function attr_to_boolean($attr_val)
    {
        if (
            $attr_val === 'true' ||
            $attr_val == 1 ||
            $attr_val == 'yes' ||
            $attr_val == 'on'
        ) {
            return true;
        }

        return false;
    }

    public static function attr_to_integer($attr_val)
    {
        return (int) $attr_val;
    }

    public static function to_type($value)
    {
        if (
            $value === 'true' ||
            $value === 'false' ||
            $value === 'yes' ||
            $value === 'no' ||
            $value === 'on'
        ) {
            return self::attr_to_boolean($value);
        }

        if (is_numeric($value)) {
            return self::attr_to_integer($value);
        }

        return $value;
    }

    public static function format_shortcode_attr(
        $arg_value,
        $arg_name,
        $keep_commas = false
    ) {
        // TODO: We might need to check more than title & query
        if (($arg_name === 'title' || $arg_name === 'query') && $keep_commas) {
            return $arg_value;
        }

        if (is_string($arg_value)) {
            if (self::contains_comma($arg_value)) {
                if (strpos($arg_value, 'data:image/svg') !== false) {
                    return trim($arg_value);
                }

                // TODO: We might need to check more than title & query
                if (
                    ($arg_name === 'title' || $arg_name === 'query') &&
                    $keep_commas
                ) {
                    return $arg_value;
                }

                return array_filter(
                    Utils::comma_list_to_array(self::to_type(trim($arg_value)))
                );
            } else {
                return self::to_type(trim($arg_value));
            }
        }

        return self::to_type($arg_value);
    }

    /*
    
    This evaluates to true mostly on the default products page. But could potentially evaluate as true by using the Render API. 

    If the firt value in the array of post ids is an integer, we can safely assume the rest will be too.
    
    Since we fetch all the post ids on the product details page, we need to add an upper bound to how many we "format" here so as to not run into performance issues.
    
    */
    public static function has_real_post_ids($name, $value)
    {

      if ($name === 'post_id' && is_array($value) && !empty($value)) {

         if (!isset($value[0])) {
            $reset_value = array_values($value);

            if (is_int($reset_value[0])) {
               return true;
            }

            return false;
            
         }

         if (is_int($value[0])) {
            return true;
         }

      } else {
         return false;
      }

    }

    public static function format_shortcode_attrs($shortcode_args)
    {
        if (
            isset($shortcode_args['keep_commas']) &&
            $shortcode_args['keep_commas'] === 'true'
        ) {
            $keep_commas = true;
        } else {
            $keep_commas = false;
        }

        if (empty($shortcode_args)) {
            return [];
        }

        foreach ($shortcode_args as $arg_name => $arg_value) {
            if (self::has_real_post_ids($arg_name, $arg_value)) {
                continue;
            }

            if (\is_array($shortcode_args[$arg_name])) {
                $shortcode_args[$arg_name] = self::format_shortcode_attrs(
                    $shortcode_args[$arg_name]
                );
            } else {
                $shortcode_args[$arg_name] = self::format_shortcode_attr(
                    $arg_value,
                    $arg_name,
                    $keep_commas
                );
            }
        }

        return $shortcode_args;
    }

    public static function standardize_layout_data($shortcode_args)
    {
        if (!isset($shortcode_args) || !$shortcode_args) {
            return [];
        }

        $after_result = self::format_shortcode_attrs($shortcode_args);

        return $after_result;
    }
}
