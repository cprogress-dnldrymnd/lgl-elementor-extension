<?php

/**
 * Breadcrumbs and Back to Results Template
 * Shortcode: [lgl_breadcrumbs]
 * Dynamically includes Make and Model filters and provides links to clear them.
 */

if (!defined('ABSPATH')) {
    exit;
}

$options = get_option('lgl_settings', array());
$current_id = get_queried_object_id();
$home_url = home_url();

// Determine the CSS class based on the shortcode attribute. Defaults to 'dark'.
$style_class = (isset($style) && $style === 'light') ? 'lgl-breadcrumbs-light' : 'lgl-breadcrumbs-dark';

echo '<div class="lgl-breadcrumbs-wrapper ' . esc_attr($style_class) . '">';
echo '<div class="lgl-breadcrumbs">';
echo '<a href="' . esc_url($home_url) . '">Home</a> <span class="lgl-separator">|</span> ';

// 1. Determine post type and context
$is_single = is_singular(array('caravan', 'motorhome', 'campervan'));
$post_type = '';

if ($is_single) {
    $post_type = get_post_type();
} else {
    // Map current page ID to LGL Settings to determine active vehicle type
    if ($current_id == ($options['caravan_page'] ?? 0)) $post_type = 'caravan';
    elseif ($current_id == ($options['motorhome_page'] ?? 0)) $post_type = 'motorhome';
    elseif ($current_id == ($options['campervan_page'] ?? 0)) $post_type = 'campervan';
    
    // Fallback for native post type archives
    elseif (is_post_type_archive(array('caravan', 'motorhome', 'campervan'))) {
        $post_type = get_query_var('post_type');
    }
}

// 2. Render LGL Specific Breadcrumbs
if (!empty($post_type)) {
    
    // Helper function to strictly cast page IDs and ensure valid base URLs
    $get_archive_url = function ($setting_key, $cpt_slug) use ($options, $home_url) {
        if (!empty($options[$setting_key])) {
            $permalink = get_permalink((int)$options[$setting_key]);
            if ($permalink) return $permalink;
        }
        $link = get_post_type_archive_link($cpt_slug);
        return $link ? $link : rtrim($home_url, '/') . '/' . $cpt_slug . '/';
    };

    $archive_url = trailingslashit($get_archive_url($post_type . '_page', $post_type));
    $archive_label = ucfirst($post_type) . 's';

    // Extract Make and Model Slugs/Names
    $make_slug = ''; $model_slug = ''; $make_name = ''; $model_name = '';

    if ($is_single) {
        // Retrieve terms directly from the post
        $terms = wp_get_post_terms(get_the_ID(), 'listing-make-model');
        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                if ($term->parent == 0) {
                    $make_slug = $term->slug; $make_name = $term->name;
                } else {
                    $model_slug = $term->slug; $model_name = $term->name;
                }
            }
            // Self-heal: If model is found but make is missing, trace the parent
            if (empty($make_slug) && !empty($model_slug)) {
                foreach ($terms as $term) {
                    if ($term->slug === $model_slug && $term->parent > 0) {
                        $parent_term = get_term($term->parent, 'listing-make-model');
                        if (!is_wp_error($parent_term)) {
                            $make_slug = $parent_term->slug; $make_name = $parent_term->name;
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

    // --- Build the Trail ---

    // A. Vehicle Type Node (e.g., Motorhomes)
    if ($make_slug || $is_single) {
        echo '<a href="' . esc_url($archive_url) . '" class="lgl-br-archive">' . esc_html($archive_label) . '</a> <span class="lgl-separator">|</span> ';
    } else {
        echo '<span class="lgl-current-page">' . esc_html($archive_label) . '</span>';
    }

    // B. Make Node
    if ($make_slug && $make_name) {
        if ($model_slug || $is_single) {
            echo '<a href="' . esc_url($archive_url . $make_slug . '/') . '">' . esc_html($make_name) . '</a> <span class="lgl-separator">|</span> ';
        } else {
            echo '<span class="lgl-current-page">' . esc_html($make_name) . '</span>';
        }
    }

    // C. Model Node
    if ($model_slug && $model_name) {
        if ($is_single) {
            echo '<a href="' . esc_url($archive_url . $make_slug . '/' . $model_slug . '/') . '">' . esc_html($model_name) . '</a> <span class="lgl-separator">|</span> ';
        } else {
            echo '<span class="lgl-current-page">' . esc_html($model_name) . '</span>';
        }
    }

    // D. Single Vehicle Title Node
    if ($is_single) {
        echo '<span class="lgl-current-page">' . esc_html(get_the_title()) . '</span>';
    }

    echo '</div>'; // End breadcrumbs left side

    // Output Back to Results Button (Right side, Single Only)
    if ($is_single) {
        echo '<div class="lgl-br-back lgl-back-to-results-wrapper" style="display: none;">';
        echo '<a href="' . esc_url($archive_url) . '" class="lgl-back-to-results" style="text-decoration: none;">&laquo; Back to Results</a>';
        echo '</div>';
    }

} else {
    // 3. Standard Custom Pages View (Non-LGL)
    $page_title = get_the_title($current_id);
    echo '<span class="lgl-current-page">' . esc_html($page_title) . '</span>';
    echo '</div>'; // End breadcrumbs left side
}

echo '</div>'; // End lgl-breadcrumbs-wrapper