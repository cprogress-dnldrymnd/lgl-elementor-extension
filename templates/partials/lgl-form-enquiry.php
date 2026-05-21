<?php
if (!defined('ABSPATH')) exit;
// Inherits: $target_post_id, $enq
?>
<form id="lgl-enquiry-form" class="lgl-modal-form lgl-form-instance" novalidate>
    <input type="hidden" name="action" value="lgl_submit_enquiry">
    <input type="hidden" name="lgl_forms_nonce" value="<?php echo esc_attr(wp_create_nonce('lgl_forms_nonce')); ?>">
    <input type="hidden" name="product_id" value="<?php echo esc_attr($target_post_id); ?>">
    <div class="lgl-form-grid">
        <?php foreach (($enq['fields'] ?? []) as $field) : ?>
            <?php echo LGL_Forms::render_form_field($field); ?>
        <?php endforeach; ?>
    </div>
    <div class="lgl-form-msg" style="display:none"></div>
    <button type="submit" class="lgl-btn lgl-btn-accent lgl-form-submit-btn">
        <span class="lgl-submit-txt"><?php echo esc_html($enq['submit_text'] ?? __('SUBMIT ENQUIRY', 'lgl-shortcodes')); ?></span>
        <span class="lgl-submit-spin" style="display:none"></span>
    </button>
</form>