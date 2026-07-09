<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Reduced sticker overlay.
 * Renders the global "Reduced" sticker image (set in LGL Settings → General) over
 * the top corner of the vehicle photo when the per-vehicle toggle is enabled.
 * Expects $post_id in scope (falls back to the loop's current post).
 */

$lgl_sticker_pid = isset($post_id) ? $post_id : get_the_ID();
$lgl_sticker_on  = get_post_meta($lgl_sticker_pid, '_lgl_reduced_sticker', true);

if ($lgl_sticker_on === '1') {
    $lgl_settings    = get_option('lgl_settings', array());
    $lgl_sticker_url = isset($lgl_settings['reduced_sticker_image']) ? $lgl_settings['reduced_sticker_image'] : '';
    if ($lgl_sticker_url) {
        echo '<img class="lgl-reduced-sticker" src="' . esc_url($lgl_sticker_url) . '" alt="' . esc_attr__('Reduced', 'lgl-shortcodes') . '" loading="lazy" />';
    }
}
