<?php
if (!defined('ABSPATH')) {
    exit;
}

$target_post_id = !empty($post_id) ? intval($post_id) : get_the_ID();
$rs   = LGL_Forms::get_reserve_settings();
$mode = LGL_Forms::get_current_reserve_mode($target_post_id);

// Prevent rendering if the vehicle is strictly set to 'no_reserve'
if (!in_array($mode, ['form_only', 'auto_reserve'], true)) {
    return;
}
?>
<div class="lgl-inline-form-wrapper lgl-reserve-inline">
    <div class="lgl-form-header">
        <h2 class="lgl-inline-form-title"><?php echo esc_html(LGL_Forms::parse_modal_string($rs['title'] ?? __('Reserve this Leisure Vehicle for free', 'lgl-shortcodes'), $target_post_id)); ?></h2>
    </div>
    <div class="lgl-form-body">
        <form id="lgl-reserve-form" class="lgl-modal-form lgl-form-inline-instance" novalidate>
            <input type="hidden" name="action" value="lgl_submit_reserve">
            <input type="hidden" name="lgl_forms_nonce" value="<?php echo esc_attr(wp_create_nonce('lgl_forms_nonce')); ?>">
            <input type="hidden" name="product_id" value="<?php echo esc_attr($target_post_id); ?>">
            
            <div class="lgl-form-grid">
                <?php foreach (($rs['fields'] ?? []) as $field) : ?>
                    <?php echo LGL_Forms::render_form_field($field); ?>
                <?php endforeach; ?>
            </div>
            
            <div class="lgl-form-msg" style="display:none"></div>
            
            <button type="submit" class="lgl-btn lgl-btn-outline lgl-form-submit-btn">
                <span class="lgl-submit-txt"><?php echo esc_html($rs['submit_text'] ?? __('RESERVE YOUR LEISURE VEHICLE', 'lgl-shortcodes')); ?></span>
                <span class="lgl-submit-spin" style="display:none"></span>
            </button>
        </form>
    </div>
</div>