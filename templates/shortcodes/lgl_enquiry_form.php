<?php
if (!defined('ABSPATH')) exit;

$target_post_id = !empty($post_id) ? intval($post_id) : get_the_ID();
$enq            = LGL_Forms::get_enquiry_settings();
?>
<div class="lgl-inline-form-wrapper lgl-enquiry-inline">
    <div class="lgl-form-header">
        <h2 class="lgl-inline-form-title"><?php echo esc_html(LGL_Forms::parse_modal_string($enq['title'] ?? __('Make an Enquiry', 'lgl-shortcodes'), $target_post_id)); ?></h2>
    </div>
    <div class="lgl-form-body">
        <?php include LGL_SHORTCODES_PATH . 'templates/partials/lgl-form-enquiry.php'; ?>
    </div>
</div>