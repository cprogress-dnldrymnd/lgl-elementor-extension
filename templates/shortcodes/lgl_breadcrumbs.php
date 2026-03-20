<?php
/**
 * Template for rendering the LGL Breadcrumbs shortcode.
 * Dynamically includes Make and Model filters and provides links to clear them.
 * * Available variables:
 * @var string $style Accepts 'dark' or 'light'.
 */

if (!defined('ABSPATH')) {
    exit;
}

$options = get_option('lgl_settings', array());
$style_class = (isset($style) && $style === 'light') ? 'lgl-breadcrumbs-light' : 'lgl-breadcrumbs-dark';

// 1. Determine the current context (Single Post vs. Archive/Search Page)
$is_single = is_singular(array('caravan', 'motorhome', 'campervan'));
$post_type = '';

if ($is_single) {
    $post_type = get_post_type();
} else {
    // Map current page ID to LGL Settings to determine active vehicle type
    $current_page_id = get_queried_object_id();
    if ($current_page_id == ($options['caravan_page'] ?? 0)) $post_type = 'caravan';
    elseif ($current_page_id == ($options['motorhome_page'] ?? 0)) $post_type = 'motorhome';
    elseif ($current_page_id == ($options['campervan_page'] ?? 0)) $post_type = 'campervan';
    
    // Fallback for native post type archives
    if (empty($post_type) && is_post_type_archive(array('caravan', 'motorhome', 'campervan'))) {
        $post_type = get_query_var('post_type');
    }
}

// Abort rendering if we aren't on a recognized LGL page
if (empty($post_type)) {
    return;
}

// 2. Establish Base URLs and Labels
$page_key = $post_type . '_page';
$base_url = !empty($options[$page_key]) ? get_permalink($options[$page_key]) : get_post_type_archive_link($post_type);
$base_url = rtrim($base_url, '/') . '/'; // Enforce trailing slash
$vehicle_label = ucfirst($post_type) . 's';

// 3. Extract Make and Model Slugs/Names
$make_slug  = '';
$model_slug = '';
$make_name  = '';
$model_name = '';

if ($is_single) {
    // Retrieve terms directly from the post if on a single vehicle page
    $terms = wp_get_post_terms(get_the_ID(), 'listing-make-model');
    if (!is_wp_error($terms) && !empty($terms)) {
        foreach ($terms as $term) {
            if ($term->parent == 0) {
                $make_slug = $term->slug;
                $make_name = $term->name;
            } else {
                $model_slug = $term->slug;
                $model_name = $term->name;
            }
        }
        // Self-heal: If model is found but make is missing, trace the parent
        if (empty($make_slug) && !empty($model_slug)) {
            foreach ($terms as $term) {
                if ($term->slug === $model_slug && $term->parent > 0) {
                    $parent_term = get_term($term->parent, 'listing-make-model');
                    if (!is_wp_error($parent_term)) {
                        $make_slug = $parent_term->slug;
                        $make_name = $parent_term->name;
                    }
                }
            }
        }
    }
} else {
    // Read directly from the URL query vars on search/archive pages
    $make_slug  = get_query_var('listing_make') ? sanitize_text_field(get_query_var('listing_make')) : '';
    $model_slug = get_query_var('listing_model') ? sanitize_text_field(get_query_var('listing_model')) : '';
    
    if ($make_slug) {
        $make_term = get_term_by('slug', $make_slug, 'listing-make-model');
        if ($make_term) $make_name = $make_term->name;
    }
    if ($model_slug) {
        $model_term = get_term_by('slug', $model_slug, 'listing-make-model');
        if ($model_term) $model_name = $model_term->name;
    }
}

// 4. Render the Breadcrumbs DOM
echo '<nav class="lgl-breadcrumbs ' . esc_attr($style_class) . '" aria-label="Breadcrumb">';
echo '<ol>';

// Home Node
echo '<li><a href="' . esc_url(home_url()) . '">Home</a></li>';

// Vehicle Type Node (e.g., Motorhomes)
if ($make_slug || $is_single) {
    // If a Make exists (or we're on a single page), make this clickable to return to the unfiltered base URL
    echo '<li><a href="' . esc_url($base_url) . '" class="lgl-br-archive">' . esc_html($vehicle_label) . '</a></li>';
} else {
    // If no deeper filters exist, this is the current active page
    echo '<li aria-current="page">' . esc_html($vehicle_label) . '</li>';
}

// Make Node
if ($make_slug && $make_name) {
    if ($model_slug || $is_single) {
        // If a Model exists (or single page), make this clickable to return to the Make-only URL
        $make_url = $base_url . $make_slug . '/';
        echo '<li><a href="' . esc_url($make_url) . '">' . esc_html($make_name) . '</a></li>';
    } else {
        // If Model does not exist, Make is the current active filter
        echo '<li aria-current="page">' . esc_html($make_name) . '</li>';
    }
}

// Model Node
if ($model_slug && $model_name) {
    if ($is_single) {
        // If on a single page, make this clickable to return to the Make+Model archive
        $model_url = $base_url . $make_slug . '/' . $model_slug . '/';
        echo '<li><a href="' . esc_url($model_url) . '">' . esc_html($model_name) . '</a></li>';
    } else {
        // If on the archive, Model is the current active filter
        echo '<li aria-current="page">' . esc_html($model_name) . '</li>';
    }
}

// Single Vehicle Title Node
if ($is_single) {
    echo '<li aria-current="page">' . esc_html(get_the_title()) . '</li>';
}

echo '</ol>';
echo '</nav>';