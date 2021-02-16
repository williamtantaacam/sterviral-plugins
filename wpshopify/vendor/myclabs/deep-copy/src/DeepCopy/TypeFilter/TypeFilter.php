<?php

namespace WP_Shopify\Vendor\DeepCopy\TypeFilter;

interface TypeFilter
{
    /**
     * Applies the filter to the object.
     *
     * @param mixed $element
     */
    public function apply($element);
}
