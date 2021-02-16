<?php

namespace WP_Shopify\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class Sorting
{
    /*

	Generic function to sort by a specific key / value

	*/
    public static function sort_by($items, $type)
    {
        usort($items, [__CLASS__, 'sort_by_' . $type]);

        return $items;
    }

    public static function sort_by_type($a, $b, $type)
    {
        $a_value = (int) $a->$type;
        $b_value = (int) $b->$type;

        if ($a_value == $b_value) {
            return 0;
        }

        return $a_value < $b_value ? -1 : 1;
    }

    public static function reverse($items)
    {
        return array_reverse($items);
    }
}
