<?php
if (!defined('ABSPATH')) exit;

$target_post_id = !empty($post_id) ? intval($post_id) : get_the_ID();
$rs             = LGL_Forms::get_reserve_settings();
$mode           = LGL_Forms::get_current_reserve_mode($target_post_id);

if (!in_array($mode, ['form_only', 'auto_reserve'], true)) return;
?>
<div class="lgl-inline-form-wrapper lgl-reserve-inline">
    <div class="lgl-form-header">
        <h2 class="lgl-inline-form-title"><?php echo esc_html(LGL_Forms::parse_modal_string($rs['title'] ?? __('Reserve this Leisure Vehicle for free', 'lgl-shortcodes'), $target_post_id)); ?></h2>
    </div>
    <div class="lgl-form-body">
        <?php include LGL_SHORTCODES_PATH . 'templates/partials/lgl-form-reserve.php'; ?>
    </div>
</div>