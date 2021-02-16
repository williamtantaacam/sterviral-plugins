<?php

use WP_Shopify\Utils;

$DB_Settings_General = WP_Shopify\Factories\DB\Settings_General_Factory::build();

$post_type = get_post_type();

// If it is a custom post type display name and link
if ($post_type != 'post') {
    $post_type_object = get_post_type_object($post_type);

    if ($post_type === 'wps_collections') {
        $post_id = $DB_Settings_General->get_col_value(
            'page_collections',
            'int'
        );
        $page_link = get_page_link($post_id);
    } elseif ($post_type === 'wps_products') {
        $post_id = $DB_Settings_General->get_col_value('page_products', 'int');
        $page_link = get_page_link($post_id);
    } else {
        $page_link = '/';
    }
}

$separator = __(
    apply_filters('wps_breadcrumbs_separator', '&gt;'),
    'wpshopify'
);
$breadcrums_id = __(
    apply_filters('wps_breadcrumbs_id', 'wps-breadcrumbs'),
    'wpshopify'
);
$breadcrums_class = __(
    apply_filters('wps_breadcrumbs_inner_class', 'wps-breadcrumbs-inner'),
    'wpshopify'
);
$home_title = __(
    apply_filters('wps_breadcrumbs_home_text', 'Home'),
    'wpshopify'
);

// If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
$custom_taxonomy = '';

// Get the query & post information
global $post, $wp_query;

// Do not display on the homepage
if (!is_front_page()) {
    // Build the breadcrums
    echo '<div class="wps-breadcrumbs ' .
        apply_filters('wps_breadcrumbs_class', '') .
        ' wps-row wps-contain"><ul id="' .
        $breadcrums_id .
        '" class="' .
        $breadcrums_class .
        '" itemscope itemtype="http://schema.org/BreadcrumbList">';

    // Home page
    echo '<li class="wps-breadcrumbs-item-home" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="wps-breadcrumbs-link wps-breadcrumbs-home" href="' .
        Utils::get_site_url() .
        '" title="' .
        $home_title .
        '" itemprop="item"><span class="wps-breadcrumbs-name" itemprop="name">' .
        $home_title .
        '</span><meta itemprop="position" content="1" /></a></li>';
    echo '<li class="wps-breadcrumbs-separator wps-breadcrumbs-separator-home"> ' .
        $separator .
        ' </li>';

    if (is_archive() && !is_tax() && !is_category() && !is_tag()) {
        // If post is a custom post type
        $post_type = get_post_type();
        $post_type_object = get_post_type_object($post_type);

        echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-archive" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-archive" itemprop="name">' .
            ucfirst($post_type_object->rewrite['slug']) .
            '</strong><meta itemprop="position" content="2" /></li>';
    } elseif (is_archive() && is_tax() && !is_category() && !is_tag()) {
        // If post is a custom post type
        $post_type = get_post_type();

        // If it is a custom post type display name and link
        if ($post_type != 'post') {
            $post_type_object = get_post_type_object($post_type);
            $post_type_archive = get_post_type_archive_link($post_type);

            echo '<li class="wps-breadcrumbs-item-cat wps-breadcrumbs-item-custom-post-type-' .
                $post_type .
                '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="wps-breadcrumbs-cat wps-breadcrumbs-custom-post-type-' .
                $post_type .
                '" href="' .
                $post_type_archive .
                '" title="' .
                $post_type_object->labels->name .
                '" itemprop="item"><span class="wps-breadcrumbs-name" itemprop="name">' .
                ucfirst($post_type_object->rewrite['slug']) .
                '</span><meta itemprop="position" content="2" /></a></li>';
            echo '<li class="wps-breadcrumbs-separator"> ' .
                $separator .
                ' </li>';
        }

        $custom_tax_name = get_queried_object()->name;
        echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-archive"><strong class="wps-breadcrumbs-current wps-breadcrumbs-archive" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">' .
            $custom_tax_name .
            '</strong><meta itemprop="position" content="3" /></li>';
    } elseif (is_single()) {
        // If post is a custom post type
        $post_type = get_post_type();

        // If it is a custom post type display name and link
        if ($post_type != 'post') {
            $post_type_object = get_post_type_object($post_type);

            echo '<li class="wps-breadcrumbs-item-cat wps-breadcrumbs-item-custom-post-type-' .
                $post_type .
                '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="wps-breadcrumbs-cat wps-breadcrumbs-custom-post-type-' .
                $post_type .
                '" href="' .
                $page_link .
                '" title="' .
                $post_type_object->labels->name .
                '" itemprop="item"><span class="wps-breadcrumbs-name" itemprop="name">' .
                ucfirst($post_type_object->rewrite['slug']) .
                '</span><meta itemprop="position" content="2" /></a></li>';
            echo '<li class="wps-breadcrumbs-separator"> ' .
                $separator .
                ' </li>';
        }

        echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-' .
            $post->ID .
            '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-' .
            $post->ID .
            '" title="' .
            get_the_title() .
            '" itemprop="name">' .
            get_the_title() .
            '</strong><meta itemprop="position" content="3" /></li>';
    } elseif (is_category()) {
        // Category page
        echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-cat" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-cat" itemprop="name">' .
            single_cat_title('', false) .
            '</strong><meta itemprop="position" content="2" /></li>';
    } elseif (is_page()) {
        // Standard page
        if ($post->post_parent) {
            // If child page, get parents
            $anc = get_post_ancestors($post->ID);

            // Get parents in the right order
            $anc = array_reverse($anc);

            // Parent page loop
            if (!isset($parents)) {
                $parents = null;
            }
            foreach ($anc as $ancestor) {
                $parents .=
                    '<li class="wps-breadcrumbs-item-parent wps-breadcrumbs-item-parent-' .
                    $ancestor .
                    '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="wps-breadcrumbs-parent wps-breadcrumbs-parent-' .
                    $ancestor .
                    '" href="' .
                    get_permalink($ancestor) .
                    '" title="' .
                    get_the_title($ancestor) .
                    '" itemprop="item"><span class="wps-breadcrumbs-name" itemprop="name">' .
                    get_the_title($ancestor) .
                    '</span><meta itemprop="position" content="2" /></a></li>';
                $parents .=
                    '<li class="wps-breadcrumbs-separator wps-breadcrumbs-separator-' .
                    $ancestor .
                    '"> ' .
                    $separator .
                    ' </li>';
            }

            // Display parent pages
            echo $parents;

            // Current page
            echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-' .
                $post->ID .
                '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong title="' .
                get_the_title() .
                '" itemprop="name"> ' .
                get_the_title() .
                '</strong><meta itemprop="position" content="3" /></li>';
        } else {
            // Just display current page if not parents
            echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-' .
                $post->ID .
                '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-' .
                $post->ID .
                '" itemprop="name"> ' .
                get_the_title() .
                '</strong><meta itemprop="position" content="2" /></li>';
        }
    } elseif (is_tag()) {
        // Tag page

        // Get tag information
        $term_id = get_query_var('id');
        $taxonomy = 'post_tag';
        $args = 'include=' . $term_id;
        $terms = get_terms($taxonomy, $args);
        $get_term_id = $terms[0]->term_id;
        $get_term_slug = $terms[0]->slug;
        $get_term_name = $terms[0]->name;

        // Display the tag name
        echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-tag-' .
            $get_term_id .
            ' wps-breadcrumbs-item-tag-' .
            $get_term_slug .
            '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-tag-' .
            $get_term_id .
            ' wps-breadcrumbs-tag-' .
            $get_term_slug .
            '" itemprop="name">' .
            $get_term_name .
            '</strong><meta itemprop="position" content="2" /></li>';
    } elseif (is_day()) {
        // Day archive

        // Year link
        echo '<li class="wps-breadcrumbs-item-year wps-breadcrumbs-item-year-' .
            get_the_time('Y') .
            '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="wps-breadcrumbs-year wps-breadcrumbs-year-' .
            get_the_time('Y') .
            '" href="' .
            get_year_link(get_the_time('Y')) .
            '" title="' .
            get_the_time('Y') .
            '" itemprop="item"><span class="wps-breadcrumbs-name" itemprop="name">' .
            get_the_time('Y') .
            ' Archives</span><meta itemprop="position" content="2" /></a></li>';
        echo '<li class="wps-breadcrumbs-separator wps-breadcrumbs-separator-' .
            get_the_time('Y') .
            '"> ' .
            $separator .
            ' </li>';

        // Month link
        echo '<li class="wps-breadcrumbs-item-month wps-breadcrumbs-item-month-' .
            get_the_time('m') .
            '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="wps-breadcrumbs-month wps-breadcrumbs-month-' .
            get_the_time('m') .
            '" href="' .
            get_month_link(get_the_time('Y'), get_the_time('m')) .
            '" title="' .
            get_the_time('M') .
            '" itemprop="item"><span class="wps-breadcrumbs-name" itemprop="name">' .
            get_the_time('M') .
            ' Archives</span><meta itemprop="position" content="2" /></a></li>';
        echo '<li class="wps-breadcrumbs-separator wps-breadcrumbs-separator-' .
            get_the_time('m') .
            '"> ' .
            $separator .
            ' </li>';

        // Day display
        echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-' .
            get_the_time('j') .
            '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-' .
            get_the_time('j') .
            '" itemprop="name"> ' .
            get_the_time('jS') .
            ' ' .
            get_the_time('M') .
            ' Archives</strong><meta itemprop="position" content="2" /></li>';
    } elseif (is_month()) {
        // Month Archive

        // Year link
        echo '<li class="wps-breadcrumbs-item-year wps-breadcrumbs-item-year-' .
            get_the_time('Y') .
            '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="wps-breadcrumbs-year wps-breadcrumbs-year-' .
            get_the_time('Y') .
            '" href="' .
            get_year_link(get_the_time('Y')) .
            '" title="' .
            get_the_time('Y') .
            '" itemprop="item"><span class="wps-breadcrumbs-name" itemprop="name">' .
            get_the_time('Y') .
            ' Archives</span><meta itemprop="position" content="2" /></a></li>';
        echo '<li class="wps-breadcrumbs-separator wps-breadcrumbs-separator-' .
            get_the_time('Y') .
            '"> ' .
            $separator .
            ' </li>';

        // Month display
        echo '<li class="wps-breadcrumbs-item-month wps-breadcrumbs-item-month-' .
            get_the_time('m') .
            '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-month wps-breadcrumbs-month-' .
            get_the_time('m') .
            '" title="' .
            get_the_time('M') .
            '" itemprop="name"><meta itemprop="position" content="2" />' .
            get_the_time('M') .
            ' Archives</strong></li>';
    } elseif (is_year()) {
        // Display year archive
        echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-current-' .
            get_the_time('Y') .
            '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-current-' .
            get_the_time('Y') .
            '" title="' .
            get_the_time('Y') .
            '" itemprop="name">' .
            get_the_time('Y') .
            ' Archives</strong><meta itemprop="position" content="2" /></li>';
    } elseif (get_query_var('paged')) {
        // Paginated archives
        echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-current-' .
            get_query_var('paged') .
            '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-current-' .
            get_query_var('paged') .
            '" title="Page ' .
            get_query_var('paged') .
            '" itemprop="name">' .
            __('Page') .
            ' ' .
            get_query_var('paged') .
            '</strong><meta itemprop="position" content="2" /></li>';
    } elseif (is_search()) {
        // Search results page
        echo '<li class="wps-breadcrumbs-item-current wps-breadcrumbs-item-current-' .
            get_search_query() .
            '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><strong class="wps-breadcrumbs-current wps-breadcrumbs-current-' .
            get_search_query() .
            '" title="Search results for: ' .
            get_search_query() .
            '" itemprop="name">Search results for: ' .
            get_search_query() .
            '</strong><meta itemprop="position" content="2" /></li>';
    } elseif (is_404()) {
        // 404 page
        echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">' .
            'Error 404' .
            '</li>';
    }

    echo '</ul></div>';

    echo '<style>.wps-breadcrumbs-inner {display: flex;padding: 0;margin: 0 0 1em 0;list-style: none;}.wps-breadcrumbs-separator {margin: 0 0.7em;}</style>';
}
