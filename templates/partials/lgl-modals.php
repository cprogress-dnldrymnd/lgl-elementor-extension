<?php
/**
 * LGL Modals Partial
 * Renders the Finance Calculator, Enquiry, and Reserve modals.
 * Included via LGL_Forms::render_modals() → wp_footer hook on single vehicle pages.
 */
if (! defined('ABSPATH')) exit;

$fin            = LGL_Forms::get_finance_settings();
$enq            = LGL_Forms::get_enquiry_settings();
$rs             = LGL_Forms::get_reserve_settings();
$target_post_id = get_the_ID();
$mode           = LGL_Forms::get_current_reserve_mode($target_post_id);
$reserved       = LGL_Forms::is_reserved($target_post_id);
$fin_mode       = $fin['mode'] ?? 'native';
?>

<div class="lgl-modal-overlay" id="lgl-modal-overlay"></div>

<?php if ('off' !== $fin_mode) : ?>
    <div class="lgl-modal fin-mode-<?= esc_attr($fin_mode) ?>" id="lgl-modal-finance" role="dialog" aria-modal="true" aria-labelledby="lgl-fc-title">
        <div class="lgl-modal-inner">
            <div class="lgl-modal-header">
                <div>
                    <h2 id="lgl-fc-title"><?php echo esc_html(LGL_Forms::parse_modal_string($fin['title'] ?? __('Finance Calculator', 'lgl-shortcodes'), $target_post_id)); ?></h2>
                    <?php if (! empty($fin['subtitle'])) : ?>
                        <p class="lgl-modal-subtitle"><?php echo esc_html(LGL_Forms::parse_modal_string($fin['subtitle'], $target_post_id)); ?></p>
                    <?php endif; ?>
                </div>
                <button class="lgl-modal-close-btn" aria-label="<?php esc_attr_e('Close', 'lgl-shortcodes'); ?>">&#x2715;</button>
            </div>
            <div class="lgl-modal-body">
                <?php include LGL_SHORTCODES_PATH . 'templates/partials/lgl-form-finance.php'; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="lgl-modal" id="lgl-modal-enquiry" role="dialog" aria-modal="true" aria-labelledby="lgl-enq-title">
    <div class="lgl-modal-inner">
        <div class="lgl-modal-header">
            <h2 id="lgl-enq-title"><?php echo esc_html(LGL_Forms::parse_modal_string($enq['title'] ?? __('Make an Enquiry', 'lgl-shortcodes'), $target_post_id)); ?></h2>
            <button class="lgl-modal-close-btn" aria-label="<?php esc_attr_e('Close', 'lgl-shortcodes'); ?>">&#x2715;</button>
        </div>
        <div class="lgl-modal-body">
            <?php include LGL_SHORTCODES_PATH . 'templates/partials/lgl-form-enquiry.php'; ?>
        </div>
    </div>
</div>

<?php if (in_array($mode, ['form_only', 'auto_reserve'], true)) : ?>
    <div class="lgl-modal" id="lgl-modal-reserve" role="dialog" aria-modal="true" aria-labelledby="lgl-res-title">
        <div class="lgl-modal-inner">
            <div class="lgl-modal-header">
                <h2 id="lgl-res-title"><?php echo esc_html(LGL_Forms::parse_modal_string($rs['title'] ?? __('Reserve this Leisure Vehicle for free', 'lgl-shortcodes'), $target_post_id)); ?></h2>
                <button class="lgl-modal-close-btn" aria-label="<?php esc_attr_e('Close', 'lgl-shortcodes'); ?>">&#x2715;</button>
            </div>
            <div class="lgl-modal-body">
                <?php include LGL_SHORTCODES_PATH . 'templates/partials/lgl-form-reserve.php'; ?>
            </div>
        </div>
    </div>
<?php endif; ?>