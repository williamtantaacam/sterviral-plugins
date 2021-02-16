<?php

namespace WP_Shopify\Vendor\DeepCopy\Matcher\Doctrine;

use WP_Shopify\Vendor\DeepCopy\Matcher\Matcher;
use WP_Shopify\Vendor\Doctrine\Common\Persistence\Proxy;

/**
 * @final
 */
class DoctrineProxyMatcher implements Matcher
{
    /**
     * Matches a Doctrine Proxy class.
     *
     * {@inheritdoc}
     */
    public function matches($object, $property)
    {
        return $object instanceof Proxy;
    }
}
