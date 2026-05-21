<?php
if (!defined('ABSPATH')) exit;

// Inherits: $target_post_id, $fin, $fin_mode

$dur_raw   = $fin['term_options'] ?? "24\n36\n48\n60";
$durations = array_values(array_filter(array_map('trim', explode("\n", $dur_raw))));
$def_term  = trim($fin['default_term'] ?? '60');
$def_dep   = (float) ($fin['default_deposit'] ?? 500);
$min_dep   = (float) ($fin['min_deposit'] ?? 100);
?>
<?php if ('custom' === $fin_mode && ! empty($fin['custom_code'])) : ?>
    <div class="lgl-fc-custom-container">
        <?php echo do_shortcode($fin['custom_code']); ?>
    </div>
<?php elseif ('afo' === $fin_mode) :
    $cash_price   = (float) get_post_meta($target_post_id, 'price', true);
    $afo_referrer = $fin['afo_referrer'] ?? 'tony-giles-caravans';
    $afo_deposit  = $fin['afo_deposit'] ?? 1000;
    $afo_url = sprintf(
        'https://www.autofinanceonline.co.uk/third-party-calculator-large/?default-amount=%d&default-length=10&deposit=%d&referrer=%s',
        $cash_price,
        $afo_deposit,
        urlencode($afo_referrer)
    );
?>
    <div class="lgl-fc-afo-container" style="width: 100%; height: 100%; min-height: 650px;">
        <iframe id="iframe" width="100%" height="100%" src="<?php echo esc_url($afo_url); ?>" frameborder="0" style="min-height: 650px;"></iframe>
    </div>
<?php else : ?>
    <div class="lgl-fc-inputs">
        <div class="lgl-fc-field">
            <label for="lgl-fc-deposit"><?php _e('Deposit', 'lgl-shortcodes'); ?> <span class="lgl-form-req">(Required)</span></label>
            <div class="lgl-fc-input-wrap">
                <span class="lgl-fc-prefix">£</span>
                <input type="number" id="lgl-fc-deposit" value="<?php echo esc_attr($def_dep); ?>" min="<?php echo esc_attr($min_dep); ?>" step="10" class="lgl-fc-input">
            </div>
        </div>
        <div class="lgl-fc-field">
            <label for="lgl-fc-duration"><?php _e('Duration', 'lgl-shortcodes'); ?></label>
            <select id="lgl-fc-duration" class="lgl-fc-input">
                <?php foreach ($durations as $d) : ?>
                    <option value="<?php echo esc_attr($d); ?>" <?php selected($def_term, $d); ?>><?php echo esc_html($d); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <button type="button" class="lgl-btn lgl-btn-primary lgl-fc-calc-btn" id="lgl-fc-calc-btn">
        <?php _e('CALCULATE', 'lgl-shortcodes'); ?>
    </button>

    <div class="lgl-fc-outputs" id="lgl-fc-outputs">
        <div class="lgl-fc-output-grid">
            <div class="lgl-fc-output-item"><span class="lgl-fc-out-label"><?php _e('Cash Price', 'lgl-shortcodes'); ?></span><span class="lgl-fc-out-val" id="lgl-fc-cash-price">—</span></div>
            <div class="lgl-fc-output-item"><span class="lgl-fc-out-label"><?php _e('Deposit', 'lgl-shortcodes'); ?></span><span class="lgl-fc-out-val" id="lgl-fc-deposit-out">—</span></div>
            <div class="lgl-fc-output-item"><span class="lgl-fc-out-label"><?php _e('Total Amount of Credit', 'lgl-shortcodes'); ?></span><span class="lgl-fc-out-val" id="lgl-fc-credit">—</span></div>
            <div class="lgl-fc-output-item"><span class="lgl-fc-out-label"><?php _e('Agreement Duration', 'lgl-shortcodes'); ?></span><span class="lgl-fc-out-val" id="lgl-fc-dur-out">—</span></div>
            <div class="lgl-fc-output-item"><span class="lgl-fc-out-label"><?php _e('Monthly Repayments of', 'lgl-shortcodes'); ?></span><span class="lgl-fc-out-val" id="lgl-fc-monthly">—</span></div>
            <div class="lgl-fc-output-item"><span class="lgl-fc-out-label"><?php _e('Total Amount Repayable', 'lgl-shortcodes'); ?></span><span class="lgl-fc-out-val" id="lgl-fc-total">—</span></div>
            <div class="lgl-fc-output-item"><span class="lgl-fc-out-label"><?php _e('Purchase Fee', 'lgl-shortcodes'); ?></span><span class="lgl-fc-out-val" id="lgl-fc-fee">—</span></div>
            <div class="lgl-fc-output-item"><span class="lgl-fc-out-label"><?php _e('Interest Rate', 'lgl-shortcodes'); ?></span><span class="lgl-fc-out-val" id="lgl-fc-rate">—</span></div>
            <div class="lgl-fc-output-item"><span class="lgl-fc-out-label"><?php _e('Representative APR', 'lgl-shortcodes'); ?></span><span class="lgl-fc-out-val" id="lgl-fc-apr">—</span></div>
            <div class="lgl-fc-output-item lgl-fc-output-full"><span class="lgl-fc-out-label"><?php _e('Monthly Payment*', 'lgl-shortcodes'); ?></span><span class="lgl-fc-out-val lgl-fc-out-big" id="lgl-fc-payment">—</span></div>
        </div>
    </div>

    <p class="lgl-fc-disclaimer"><?php echo esc_html(LGL_Forms::parse_modal_string($fin['disclaimer_text'] ?? '', $target_post_id)); ?></p>
<?php endif; ?>