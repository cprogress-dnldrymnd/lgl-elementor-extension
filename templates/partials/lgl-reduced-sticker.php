<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Reduced sticker overlay.
 * Renders the per-vehicle "Reduced" sticker image over the top corner of the
 * vehicle photo when the toggle is enabled and an image has been chosen in the
 * Vehicle Form Settings meta box. Expects $post_id in scope (falls back to the
 * loop's current post).
 */

$lgl_sticker_pid = isset($post_id) ? $post_id : get_the_ID();
$lgl_sticker_on  = get_post_meta($lgl_sticker_pid, '_lgl_reduced_sticker', true);
$lgl_sticker_id  = get_post_meta($lgl_sticker_pid, '_lgl_reduced_sticker_id', true);

if ($lgl_sticker_on === '1' && $lgl_sticker_id) {
    $lgl_sticker_url = wp_get_attachment_image_url($lgl_sticker_id, 'medium', false);
    if ($lgl_sticker_url) {
        echo '<img class="lgl-reduced-sticker" src="' . esc_url($lgl_sticker_url) . '" alt="' . esc_attr__('Reduced', 'lgl-shortcodes') . '" loading="lazy" />';
    }
}
