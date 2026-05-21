<?php
if (!defined('ABSPATH')) exit;

$target_post_id = !empty($post_id) ? intval($post_id) : get_the_ID();
$fin            = LGL_Forms::get_finance_settings();
$fin_mode       = $fin['mode'] ?? 'native';

if ('off' === $fin_mode) return;
?>
<div class="lgl-inline-form-wrapper fin-mode-<?php echo esc_attr($fin_mode); ?>">
    <div class="lgl-form-header">
        <h2 class="lgl-inline-form-title"><?php echo esc_html(LGL_Forms::parse_modal_string($fin['title'] ?? __('Finance Calculator', 'lgl-shortcodes'), $target_post_id)); ?></h2>
        <?php if (!empty($fin['subtitle'])) : ?>
            <p class="lgl-form-subtitle"><?php echo esc_html(LGL_Forms::parse_modal_string($fin['subtitle'], $target_post_id)); ?></p>
        <?php endif; ?>
    </div>
    <div class="lgl-form-body">
        <?php include LGL_SHORTCODES_PATH . 'templates/partials/lgl-form-finance.php'; ?>
    </div>
</div>