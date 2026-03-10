<?php
/**
 * LGL Finance Specialist Shortcode
 *
 * Adds a "Finance Specialist" tab to LGL Settings and registers
 * the [lgl_finance_specialist] shortcode.
 *
 * Usage:
 *   [lgl_finance_specialist]
 *   [lgl_finance_specialist post_type="motorhomes"]
 *   [lgl_finance_specialist post_type="caravans"]
 *   [lgl_finance_specialist post_type="campervans"]
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ──────────────────────────────────────────────
// 1.  SETTINGS: Register & save
// ──────────────────────────────────────────────

add_action( 'admin_init', 'lgl_finance_register_settings' );
function lgl_finance_register_settings() {

    // ── General ──────────────────────────────
    register_setting( 'lgl_finance_settings_group', 'lgl_finance_heading',     [ 'sanitize_callback' => 'sanitize_text_field',    'default' => 'Finance Specialist' ] );
    register_setting( 'lgl_finance_settings_group', 'lgl_finance_description', [ 'sanitize_callback' => 'wp_kses_post',            'default' => 'We can assist you in finding the most suitable vehicle based on your monthly budget. Use our slider below to adjust how much you would like to spend each month.' ] );
    register_setting( 'lgl_finance_settings_group', 'lgl_finance_footer_text', [ 'sanitize_callback' => 'sanitize_text_field',    'default' => 'Finance provided by Close Brothers Motor Finance' ] );

    // ── Slider ───────────────────────────────
    register_setting( 'lgl_finance_settings_group', 'lgl_finance_price_min',   [ 'sanitize_callback' => 'absint',                 'default' => 5000 ] );
    register_setting( 'lgl_finance_settings_group', 'lgl_finance_price_max',   [ 'sanitize_callback' => 'absint',                 'default' => 150000 ] );
    register_setting( 'lgl_finance_settings_group', 'lgl_finance_price_step',  [ 'sanitize_callback' => 'absint',                 'default' => 1000 ] );
    register_setting( 'lgl_finance_settings_group', 'lgl_finance_price_default', [ 'sanitize_callback' => 'absint',               'default' => 25000 ] );

    // ── Motorhomes ───────────────────────────
    register_setting( 'lgl_finance_settings_group', 'lgl_finance_btn1_label',  [ 'sanitize_callback' => 'sanitize_text_field',    'default' => 'Search Motorhomes' ] );
    register_setting( 'lgl_finance_settings_group', 'lgl_finance_btn1_url',    [ 'sanitize_callback' => 'esc_url_raw',            'default' => '/motorhomes/' ] );

    // ── Caravans ─────────────────────────────
    register_setting( 'lgl_finance_settings_group', 'lgl_finance_btn2_label',  [ 'sanitize_callback' => 'sanitize_text_field',    'default' => 'Search Caravans' ] );
    register_setting( 'lgl_finance_settings_group', 'lgl_finance_btn2_url',    [ 'sanitize_callback' => 'esc_url_raw',            'default' => '/caravans/' ] );

    // ── Campervans ───────────────────────────
    register_setting( 'lgl_finance_settings_group', 'lgl_finance_btn3_label',  [ 'sanitize_callback' => 'sanitize_text_field',    'default' => 'Search Campervans' ] );
    register_setting( 'lgl_finance_settings_group', 'lgl_finance_btn3_url',    [ 'sanitize_callback' => 'esc_url_raw',            'default' => '/campervans/' ] );
}

// ──────────────────────────────────────────────
// 2.  SETTINGS PAGE: Hook into LGL Settings tabs
// ──────────────────────────────────────────────

/**
 * Adds our tab to the LGL Settings tab array.
 * Adjust the filter name to match your theme/plugin's actual hook.
 */
add_filter( 'lgl_settings_tabs', 'lgl_finance_add_settings_tab' );
function lgl_finance_add_settings_tab( $tabs ) {
    $tabs['finance_specialist'] = __( 'Finance Specialist', 'lgl' );
    return $tabs;
}

/**
 * Renders our tab content inside the LGL Settings page.
 * Adjust the action name to match your theme/plugin's actual hook.
 */
add_action( 'lgl_settings_tab_content_finance_specialist', 'lgl_finance_render_settings_tab' );
function lgl_finance_render_settings_tab() {
    ?>
    <form method="post" action="options.php">
        <?php settings_fields( 'lgl_finance_settings_group' ); ?>

        <table class="form-table" role="presentation">

            <!-- ── GENERAL ─────────────────────────────────── -->
            <tr><th colspan="2"><h2 style="margin:0;padding:16px 0 4px;"><?php esc_html_e( 'General', 'lgl' ); ?></h2></th></tr>

            <tr>
                <th scope="row"><label for="lgl_finance_heading"><?php esc_html_e( 'Heading', 'lgl' ); ?></label></th>
                <td>
                    <input type="text" id="lgl_finance_heading" name="lgl_finance_heading"
                           value="<?php echo esc_attr( get_option( 'lgl_finance_heading', 'Finance Specialist' ) ); ?>"
                           class="regular-text">
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="lgl_finance_description"><?php esc_html_e( 'Description', 'lgl' ); ?></label></th>
                <td>
                    <textarea id="lgl_finance_description" name="lgl_finance_description"
                              rows="4" class="large-text"><?php echo esc_textarea( get_option( 'lgl_finance_description', 'We can assist you in finding the most suitable vehicle based on your monthly budget. Use our slider below to adjust how much you would like to spend each month.' ) ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Displayed below the heading. Basic HTML is allowed.', 'lgl' ); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="lgl_finance_footer_text"><?php esc_html_e( 'Footer Text', 'lgl' ); ?></label></th>
                <td>
                    <input type="text" id="lgl_finance_footer_text" name="lgl_finance_footer_text"
                           value="<?php echo esc_attr( get_option( 'lgl_finance_footer_text', 'Finance provided by Close Brothers Motor Finance' ) ); ?>"
                           class="regular-text">
                    <p class="description"><?php esc_html_e( 'Small text shown at the bottom of the widget.', 'lgl' ); ?></p>
                </td>
            </tr>

            <!-- ── PRICE SLIDER ─────────────────────────────── -->
            <tr><th colspan="2"><h2 style="margin:0;padding:16px 0 4px;"><?php esc_html_e( 'Price Slider', 'lgl' ); ?></h2></th></tr>

            <tr>
                <th scope="row"><label for="lgl_finance_price_min"><?php esc_html_e( 'Minimum Price (£)', 'lgl' ); ?></label></th>
                <td>
                    <input type="number" id="lgl_finance_price_min" name="lgl_finance_price_min"
                           value="<?php echo esc_attr( get_option( 'lgl_finance_price_min', 5000 ) ); ?>"
                           min="0" step="500" class="small-text"> <span>£</span>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="lgl_finance_price_max"><?php esc_html_e( 'Maximum Price (£)', 'lgl' ); ?></label></th>
                <td>
                    <input type="number" id="lgl_finance_price_max" name="lgl_finance_price_max"
                           value="<?php echo esc_attr( get_option( 'lgl_finance_price_max', 150000 ) ); ?>"
                           min="1000" step="500" class="small-text"> <span>£</span>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="lgl_finance_price_step"><?php esc_html_e( 'Step (£)', 'lgl' ); ?></label></th>
                <td>
                    <input type="number" id="lgl_finance_price_step" name="lgl_finance_price_step"
                           value="<?php echo esc_attr( get_option( 'lgl_finance_price_step', 1000 ) ); ?>"
                           min="100" step="100" class="small-text"> <span>£</span>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="lgl_finance_price_default"><?php esc_html_e( 'Default Value (£)', 'lgl' ); ?></label></th>
                <td>
                    <input type="number" id="lgl_finance_price_default" name="lgl_finance_price_default"
                           value="<?php echo esc_attr( get_option( 'lgl_finance_price_default', 25000 ) ); ?>"
                           min="0" step="500" class="small-text"> <span>£</span>
                </td>
            </tr>

            <!-- ── POST TYPE BUTTONS ────────────────────────── -->
            <tr><th colspan="2"><h2 style="margin:0;padding:16px 0 4px;"><?php esc_html_e( 'Search Buttons', 'lgl' ); ?></h2></th></tr>
            <tr><th colspan="2"><p class="description"><?php esc_html_e( 'Configure the label and search URL for each post type. The shortcode will append ?max_price=XXXXX to each URL automatically.', 'lgl' ); ?></p></th></tr>

            <?php
            $post_types = [
                [ 'key' => 'btn1', 'label_default' => 'Search Motorhomes', 'url_default' => '/motorhomes/', 'title' => 'Motorhomes' ],
                [ 'key' => 'btn2', 'label_default' => 'Search Caravans',   'url_default' => '/caravans/',   'title' => 'Caravans'   ],
                [ 'key' => 'btn3', 'label_default' => 'Search Campervans', 'url_default' => '/campervans/', 'title' => 'Campervans' ],
            ];
            foreach ( $post_types as $pt ) :
                $label = get_option( "lgl_finance_{$pt['key']}_label", $pt['label_default'] );
                $url   = get_option( "lgl_finance_{$pt['key']}_url",   $pt['url_default'] );
            ?>
            <tr>
                <th scope="row"><?php echo esc_html( $pt['title'] ); ?></th>
                <td>
                    <input type="text"
                           name="lgl_finance_<?php echo esc_attr( $pt['key'] ); ?>_label"
                           value="<?php echo esc_attr( $label ); ?>"
                           class="regular-text"
                           placeholder="<?php echo esc_attr( $pt['label_default'] ); ?>">
                    <input type="url"
                           name="lgl_finance_<?php echo esc_attr( $pt['key'] ); ?>_url"
                           value="<?php echo esc_attr( $url ); ?>"
                           class="regular-text"
                           placeholder="<?php echo esc_attr( $pt['url_default'] ); ?>"
                           style="margin-top:6px;">
                    <p class="description"><?php esc_html_e( 'Button label &amp; search URL', 'lgl' ); ?></p>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>

        <?php submit_button(); ?>
    </form>
    <?php
}

// ──────────────────────────────────────────────
// 3.  SHORTCODE
// ──────────────────────────────────────────────

add_shortcode( 'lgl_finance_specialist', 'lgl_finance_specialist_shortcode' );
function lgl_finance_specialist_shortcode( $atts ) {

    $atts = shortcode_atts(
        [
            /**
             * post_type: controls which button(s) to display.
             * Values: "motorhomes" | "caravans" | "campervans" | "all"
             * Default: "all" — shows all three buttons.
             */
            'post_type' => 'all',
        ],
        $atts,
        'lgl_finance_specialist'
    );

    // ── Pull settings ────────────────────────
    $heading      = get_option( 'lgl_finance_heading',       'Finance Specialist' );
    $description  = get_option( 'lgl_finance_description',   'We can assist you in finding the most suitable vehicle based on your monthly budget. Use our slider below to adjust how much you would like to spend each month.' );
    $footer_text  = get_option( 'lgl_finance_footer_text',   'Finance provided by Close Brothers Motor Finance' );
    $price_min    = (int) get_option( 'lgl_finance_price_min',     5000 );
    $price_max    = (int) get_option( 'lgl_finance_price_max',   150000 );
    $price_step   = (int) get_option( 'lgl_finance_price_step',    1000 );
    $price_def    = (int) get_option( 'lgl_finance_price_default', 25000 );

    $all_buttons = [
        'motorhomes' => [
            'label'   => get_option( 'lgl_finance_btn1_label', 'Search Motorhomes' ),
            'url'     => get_option( 'lgl_finance_btn1_url',   '/motorhomes/' ),
            'style'   => 'filled',
        ],
        'caravans' => [
            'label'   => get_option( 'lgl_finance_btn2_label', 'Search Caravans' ),
            'url'     => get_option( 'lgl_finance_btn2_url',   '/caravans/' ),
            'style'   => 'outline',
        ],
        'campervans' => [
            'label'   => get_option( 'lgl_finance_btn3_label', 'Search Campervans' ),
            'url'     => get_option( 'lgl_finance_btn3_url',   '/campervans/' ),
            'style'   => 'outline',
        ],
    ];

    // ── Filter by post_type attr ─────────────
    $post_type = strtolower( trim( $atts['post_type'] ) );

    if ( 'all' === $post_type ) {
        $buttons = $all_buttons;
        // Make first button filled, rest outlined
        $first = true;
        foreach ( $buttons as $key => $btn ) {
            $buttons[ $key ]['style'] = $first ? 'filled' : 'outline';
            $first = false;
        }
    } elseif ( isset( $all_buttons[ $post_type ] ) ) {
        $buttons = [ $post_type => $all_buttons[ $post_type ] ];
        $buttons[ $post_type ]['style'] = 'filled';
    } else {
        // Fallback: show all
        $buttons = $all_buttons;
    }

    // ── Unique ID for multiple instances ─────
    static $instance = 0;
    $instance++;
    $uid = 'lgl-fs-' . $instance;

    // ── Enqueue styles & scripts ─────────────
    lgl_finance_enqueue_assets();

    // ── Build HTML ───────────────────────────
    ob_start();
    ?>
    <div class="lgl-finance-specialist" id="<?php echo esc_attr( $uid ); ?>">

        <h2 class="lgl-fs__heading"><?php echo esc_html( $heading ); ?></h2>

        <div class="lgl-fs__description">
            <?php echo wp_kses_post( $description ); ?>
        </div>

        <div class="lgl-fs__slider-wrap">
            <input
                type="range"
                class="lgl-fs__slider"
                min="<?php echo esc_attr( $price_min ); ?>"
                max="<?php echo esc_attr( $price_max ); ?>"
                step="<?php echo esc_attr( $price_step ); ?>"
                value="<?php echo esc_attr( $price_def ); ?>"
                aria-label="<?php esc_attr_e( 'Maximum vehicle price', 'lgl' ); ?>"
                data-uid="<?php echo esc_attr( $uid ); ?>"
            >
        </div>

        <p class="lgl-fs__price-label">
            <?php esc_html_e( 'Max vehicle price', 'lgl' ); ?>
            <strong class="lgl-fs__price-value" id="<?php echo esc_attr( $uid ); ?>-price">
                £<?php echo number_format( $price_def ); ?>
            </strong>
        </p>

        <div class="lgl-fs__buttons">
            <?php foreach ( $buttons as $key => $btn ) :
                $btn_class = 'lgl-fs__btn lgl-fs__btn--' . esc_attr( $btn['style'] );
                $href_base = esc_url( $btn['url'] );
            ?>
            <a  href="<?php echo $href_base; ?>?max_price=<?php echo esc_attr( $price_def ); ?>"
                class="<?php echo $btn_class; ?>"
                data-uid="<?php echo esc_attr( $uid ); ?>"
                data-base-url="<?php echo $href_base; ?>">
                <?php echo esc_html( strtoupper( $btn['label'] ) ); ?>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if ( $footer_text ) : ?>
        <p class="lgl-fs__footer"><?php echo esc_html( $footer_text ); ?></p>
        <?php endif; ?>

    </div>
    <?php
    return ob_get_clean();
}

// ──────────────────────────────────────────────
// 4.  ASSETS
// ──────────────────────────────────────────────

function lgl_finance_enqueue_assets() {

    // Inline CSS (registered once)
    if ( ! wp_style_is( 'lgl-finance-specialist', 'registered' ) ) {
        $css = lgl_finance_get_css();
        wp_register_style( 'lgl-finance-specialist', false );
        wp_enqueue_style( 'lgl-finance-specialist' );
        wp_add_inline_style( 'lgl-finance-specialist', $css );
    }

    // Inline JS (registered once)
    if ( ! wp_script_is( 'lgl-finance-specialist', 'registered' ) ) {
        $js = lgl_finance_get_js();
        wp_register_script( 'lgl-finance-specialist', false, [], false, true );
        wp_enqueue_script( 'lgl-finance-specialist' );
        wp_add_inline_script( 'lgl-finance-specialist', $js );
    }
}

function lgl_finance_get_css() {
    return '
/* ─── Finance Specialist Widget ──────────────────────────────── */
.lgl-finance-specialist {
    background-color: #1a3a8c;
    border-radius: 12px;
    padding: 40px 44px 32px;
    color: #ffffff;
    max-width: 860px;
    box-sizing: border-box;
}

.lgl-fs__heading {
    font-size: clamp(1.75rem, 3.5vw, 2.5rem);
    font-weight: 800;
    margin: 0 0 18px;
    color: #ffffff;
    line-height: 1.15;
}

.lgl-fs__description {
    font-size: 1rem;
    line-height: 1.65;
    margin: 0 0 28px;
    max-width: 640px;
    opacity: 0.95;
}

/* ── Slider ──────────────────────────────────────────────────── */
.lgl-fs__slider-wrap {
    margin-bottom: 12px;
}

.lgl-fs__slider {
    -webkit-appearance: none;
    appearance: none;
    width: 100%;
    height: 8px;
    border-radius: 4px;
    background: #4a5f9e;
    outline: none;
    cursor: pointer;
    /* Filled portion is applied dynamically via JS */
}

.lgl-fs__slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #ffffff;
    border: 3px solid #e0e0e0;
    box-shadow: 0 2px 6px rgba(0,0,0,.25);
    cursor: grab;
    transition: transform .15s;
}

.lgl-fs__slider::-webkit-slider-thumb:active {
    transform: scale(1.15);
    cursor: grabbing;
}

.lgl-fs__slider::-moz-range-thumb {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #ffffff;
    border: 3px solid #e0e0e0;
    box-shadow: 0 2px 6px rgba(0,0,0,.25);
    cursor: grab;
}

/* ── Price label ─────────────────────────────────────────────── */
.lgl-fs__price-label {
    font-size: .95rem;
    margin: 0 0 28px;
    color: #ffffff;
    font-weight: 500;
}

.lgl-fs__price-value {
    font-weight: 700;
}

/* ── Buttons ─────────────────────────────────────────────────── */
.lgl-fs__buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 28px;
}

.lgl-fs__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 28px;
    border-radius: 6px;
    font-weight: 800;
    font-size: .85rem;
    letter-spacing: .08em;
    text-decoration: none;
    transition: background .2s, color .2s, transform .15s;
    min-width: 220px;
    text-align: center;
}

.lgl-fs__btn--filled {
    background-color: #f5c800;
    color: #1a1a1a;
    border: 2px solid #f5c800;
}

.lgl-fs__btn--filled:hover {
    background-color: #ffd900;
    border-color: #ffd900;
    transform: translateY(-2px);
    color: #1a1a1a;
}

.lgl-fs__btn--outline {
    background-color: transparent;
    color: #f5c800;
    border: 2px solid #f5c800;
}

.lgl-fs__btn--outline:hover {
    background-color: #f5c800;
    color: #1a1a1a;
    transform: translateY(-2px);
}

/* ── Footer ──────────────────────────────────────────────────── */
.lgl-fs__footer {
    font-size: .75rem;
    margin: 0;
    opacity: .65;
}

/* ── Responsive ──────────────────────────────────────────────── */
@media (max-width: 600px) {
    .lgl-finance-specialist {
        padding: 28px 20px 24px;
    }
    .lgl-fs__btn {
        min-width: 100%;
    }
}
';
}

function lgl_finance_get_js() {
    return '
(function () {
    "use strict";

    function formatPrice(val) {
        return "\u00a3" + parseInt(val, 10).toLocaleString("en-GB");
    }

    function updateSlider(slider) {
        var uid      = slider.getAttribute("data-uid");
        var min      = parseFloat(slider.min);
        var max      = parseFloat(slider.max);
        var val      = parseFloat(slider.value);
        var pct      = ((val - min) / (max - min)) * 100;

        // Fill track
        slider.style.background =
            "linear-gradient(to right, #f5c800 " + pct + "%, #4a5f9e " + pct + "%)";

        // Update price label
        var label = document.getElementById(uid + "-price");
        if (label) label.textContent = formatPrice(val);

        // Update button hrefs
        var btns = document.querySelectorAll(
            "#" + uid + " .lgl-fs__btn[data-base-url]"
        );
        btns.forEach(function (btn) {
            btn.href = btn.getAttribute("data-base-url") + "?max_price=" + val;
        });
    }

    function init() {
        document.querySelectorAll(".lgl-fs__slider").forEach(function (slider) {
            updateSlider(slider);
            slider.addEventListener("input", function () {
                updateSlider(slider);
            });
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
';
}