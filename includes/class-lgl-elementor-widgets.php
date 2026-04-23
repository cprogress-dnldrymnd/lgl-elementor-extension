<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Trait: LGL Elementor Style Controls
 * Allows us to easily inject the exact same Style Tab into all 13 widgets
 * without repeating code. Uses Elementor's native {{WRAPPER}} CSS generator.
 */
trait LGL_Elementor_Style_Controls {
    protected function register_common_style_controls() {
        $this->start_controls_section(
            'section_lgl_style',
            [
                'label' => __('LGL General Styles', 'lgl-shortcodes'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'lgl_bg_color',
            [
                'label'     => __('Background Color', 'lgl-shortcodes'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Targets the direct container of the widget
                    '{{WRAPPER}} > .elementor-widget-container' => 'background-color: {{VALUE}}; padding: 20px; border-radius: 8px;',
                ],
            ]
        );

        $this->add_control(
            'lgl_text_color',
            [
                'label'     => __('Text Color', 'lgl-shortcodes'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    // Uses !important to guarantee it overrides theme/plugin default CSS
                    '{{WRAPPER}}, {{WRAPPER}} p, {{WRAPPER}} span:not(.dashicons), {{WRAPPER}} label, {{WRAPPER}} div, {{WRAPPER}} a' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'lgl_heading_color',
            [
                'label'     => __('Heading Color', 'lgl-shortcodes'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3, {{WRAPPER}} h4, {{WRAPPER}} h5, {{WRAPPER}} h6, {{WRAPPER}} h3 a' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'lgl_button_bg',
            [
                'label'     => __('Button Background', 'lgl-shortcodes'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} button, {{WRAPPER}} .lgl-btn, {{WRAPPER}} input[type="submit"]' => 'background-color: {{VALUE}} !important; border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'lgl_button_text',
            [
                'label'     => __('Button Text Color', 'lgl-shortcodes'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} button, {{WRAPPER}} .lgl-btn, {{WRAPPER}} input[type="submit"], {{WRAPPER}} button *' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }
}


/* ==========================================================================
   1. Vehicle Search Form Widget
   ========================================================================== */
class LGL_Widget_Search extends \Elementor\Widget_Base {
    use LGL_Elementor_Style_Controls;

    public function get_name() { return 'lgl_search_widget'; }
    public function get_title() { return __('Vehicle Search Form', 'lgl-shortcodes'); }
    public function get_icon() { return 'eicon-search'; }
    public function get_categories() { return ['lgl-elements']; }
    public function get_keywords() { return ['lgl', 'search', 'filter', 'vehicle', 'caravan', 'motorhome']; }

    protected function register_controls() {
        $this->start_controls_section('content_section', ['label' => __('Search Settings', 'lgl-shortcodes'), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);

        $this->add_control('attr_post_type', [
            'label' => __('Lock to Vehicle Type', 'lgl-shortcodes'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => '',
            'options' => ['' => 'Global / All', 'caravan' => 'Caravan', 'motorhome' => 'Motorhome', 'campervan' => 'Campervan'],
        ]);

        $this->add_control('attr_search_type', [
            'label' => __('Search Layout Mode', 'lgl-shortcodes'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'default',
            'options' => ['default' => 'Default (Dropdown)', 'tabs' => 'Tabs Mode (Pairs with Type Tabs)'],
        ]);

        $this->add_control('attr_live_search', [
            'label' => __('Live AJAX Refresh', 'lgl-shortcodes'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'return_value' => 'true',
        ]);

        $this->end_controls_section();

        // INJECT STYLE TAB
        $this->register_common_style_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $atts = '';
        if (!empty($settings['attr_post_type'])) $atts .= ' post_type="' . esc_attr($settings['attr_post_type']) . '"';
        if (!empty($settings['attr_search_type'])) $atts .= ' search_type="' . esc_attr($settings['attr_search_type']) . '"';
        if (empty($settings['attr_live_search'])) $atts .= ' live_search="false"';
        echo do_shortcode('[lgl_search' . $atts . ']');
    }
}


/* ==========================================================================
   2. Vehicle Listing Grid Widget
   ========================================================================== */
class LGL_Widget_Listing extends \Elementor\Widget_Base {
    use LGL_Elementor_Style_Controls;

    public function get_name() { return 'lgl_listing_widget'; }
    public function get_title() { return __('Vehicle Listing Grid', 'lgl-shortcodes'); }
    public function get_icon() { return 'eicon-gallery-grid'; }
    public function get_categories() { return ['lgl-elements']; }
    public function get_keywords() { return ['lgl', 'listing', 'grid', 'carousel', 'vehicles', 'caravan', 'motorhome']; }

    protected function register_controls() {
        $this->start_controls_section('content_section', ['label' => __('Listing Settings', 'lgl-shortcodes'), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);

        $this->add_control('attr_post_type', [
            'label' => __('Vehicle Type', 'lgl-shortcodes'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'caravan',
            'options' => ['caravan' => 'Caravan', 'motorhome' => 'Motorhome', 'campervan' => 'Campervan'],
        ]);

        $this->add_control('attr_limit', [
            'label' => __('Items Limit', 'lgl-shortcodes'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 9,
        ]);

        $this->add_control('attr_style', [
            'label' => __('Card Style', 'lgl-shortcodes'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'style-1',
            'options' => ['style-1' => 'Style 1', 'style-2' => 'Style 2'],
        ]);

        $this->add_control('attr_is_carousel', [
            'label' => __('Enable Carousel', 'lgl-shortcodes'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'true',
        ]);

        $this->add_control('attr_is_featured', [
            'label' => __('Show Only Featured', 'lgl-shortcodes'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'true',
        ]);

        $this->end_controls_section();

        // INJECT STYLE TAB
        $this->register_common_style_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $atts = '';
        if (!empty($settings['attr_post_type'])) $atts .= ' post_type="' . esc_attr($settings['attr_post_type']) . '"';
        if (!empty($settings['attr_limit'])) $atts .= ' limit="' . esc_attr($settings['attr_limit']) . '"';
        if (!empty($settings['attr_style'])) $atts .= ' style="' . esc_attr($settings['attr_style']) . '"';
        if ($settings['attr_is_carousel'] === 'true') $atts .= ' is_carousel="true"';
        if ($settings['attr_is_featured'] === 'true') $atts .= ' is_featured="true"';
        echo do_shortcode('[lgl_listing' . $atts . ']');
    }
}


/* ==========================================================================
   3. Compare Duo Card Widget
   ========================================================================== */
class LGL_Widget_Compare_Duo extends \Elementor\Widget_Base {
    use LGL_Elementor_Style_Controls;

    public function get_name() { return 'lgl_compare_duo_widget'; }
    public function get_title() { return __('Compare Duo Card', 'lgl-shortcodes'); }
    public function get_icon() { return 'eicon-columns'; }
    public function get_categories() { return ['lgl-elements']; }
    public function get_keywords() { return ['lgl', 'compare', 'duo', 'vs', 'versus', 'vehicles']; }

    protected function register_controls() {
        $this->start_controls_section('content_section', ['label' => __('Compare Vehicles', 'lgl-shortcodes'), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);

        $this->add_control('attr_post_id_1', [
            'label' => __('First Vehicle ID', 'lgl-shortcodes'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'description' => 'Enter the numeric post ID of the first vehicle.',
        ]);

        $this->add_control('attr_post_id_2', [
            'label' => __('Second Vehicle ID', 'lgl-shortcodes'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'description' => 'Enter the numeric post ID of the second vehicle.',
        ]);

        $this->end_controls_section();

        // INJECT STYLE TAB
        $this->register_common_style_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $atts = '';
        if (!empty($settings['attr_post_id_1'])) $atts .= ' post_id_1="' . esc_attr($settings['attr_post_id_1']) . '"';
        if (!empty($settings['attr_post_id_2'])) $atts .= ' post_id_2="' . esc_attr($settings['attr_post_id_2']) . '"';
        echo do_shortcode('[lgl_compare_duo' . $atts . ']');
    }
}


/* ==========================================================================
   4. Simple Widgets (No Attributes, Just Styles)
   ========================================================================== */

class LGL_Widget_Type_Tabs extends \Elementor\Widget_Base {
    use LGL_Elementor_Style_Controls;
    public function get_name() { return 'lgl_type_tabs_widget'; }
    public function get_title() { return __('Vehicle Type Tabs', 'lgl-shortcodes'); }
    public function get_icon() { return 'eicon-button'; }
    public function get_categories() { return ['lgl-elements']; }
    public function get_keywords() { return ['lgl', 'tabs', 'type', 'caravan', 'motorhome', 'campervan']; }
    protected function register_controls() { $this->register_common_style_controls(); }
    protected function render() { echo do_shortcode('[lgl_type_tabs]'); }
}

class LGL_Widget_Search_Results extends \Elementor\Widget_Base {
    use LGL_Elementor_Style_Controls;
    public function get_name() { return 'lgl_search_results_widget'; }
    public function get_title() { return __('Search Results Grid', 'lgl-shortcodes'); }
    public function get_icon() { return 'eicon-archive'; }
    public function get_categories() { return ['lgl-elements']; }
    public function get_keywords() { return ['lgl', 'search', 'results', 'grid']; }
    protected function register_controls() { $this->register_common_style_controls(); }
    protected function render() { echo do_shortcode('[lgl_search_results]'); }
}

class LGL_Widget_Compare extends \Elementor\Widget_Base {
    use LGL_Elementor_Style_Controls;
    public function get_name() { return 'lgl_compare_widget'; }
    public function get_title() { return __('Compare Table', 'lgl-shortcodes'); }
    public function get_icon() { return 'eicon-table'; }
    public function get_categories() { return ['lgl-elements']; }
    public function get_keywords() { return ['lgl', 'compare', 'table', 'vehicles']; }
    protected function register_controls() { $this->register_common_style_controls(); }
    protected function render() { echo do_shortcode('[lgl_compare]'); }
}

class LGL_Widget_Wishlist extends \Elementor\Widget_Base {
    use LGL_Elementor_Style_Controls;
    public function get_name() { return 'lgl_wishlist_widget'; }
    public function get_title() { return __('Full Wishlist Page', 'lgl-shortcodes'); }
    public function get_icon() { return 'eicon-heart'; }
    public function get_categories() { return ['lgl-elements']; }
    public function get_keywords() { return ['lgl', 'wishlist', 'saved', 'favorites']; }
    protected function register_controls() { $this->register_common_style_controls(); }
    protected function render() { echo do_shortcode('[lgl_wishlist]'); }
}

class LGL_Widget_My_Account extends \Elementor\Widget_Base {
    use LGL_Elementor_Style_Controls;
    public function get_name() { return 'lgl_my_account_widget'; }
    public function get_title() { return __('My Account Dashboard', 'lgl-shortcodes'); }
    public function get_icon() { return 'eicon-lock-user'; }
    public function get_categories() { return ['lgl-elements']; }
    public function get_keywords() { return ['lgl', 'account', 'dashboard', 'login', 'register']; }
    protected function register_controls() { $this->register_common_style_controls(); }
    protected function render() { echo do_shortcode('[lgl_my_account]'); }
}

class LGL_Widget_Breadcrumbs extends \Elementor\Widget_Base {
    use LGL_Elementor_Style_Controls;
    public function get_name() { return 'lgl_breadcrumbs_widget'; }
    public function get_title() { return __('Breadcrumbs', 'lgl-shortcodes'); }
    public function get_icon() { return 'eicon-navigation-horizontal'; }
    public function get_categories() { return ['lgl-elements']; }
    public function get_keywords() { return ['lgl', 'breadcrumbs', 'navigation', 'back']; }
    protected function register_controls() { $this->register_common_style_controls(); }
    protected function render() { echo do_shortcode('[lgl_breadcrumbs]'); }
}


/* ==========================================================================
   5. Related Vehicles Widget
   ========================================================================== */
class LGL_Widget_Related_Vehicles extends \Elementor\Widget_Base {
    use LGL_Elementor_Style_Controls;

    public function get_name() { return 'lgl_related_vehicles_widget'; }
    public function get_title() { return __('Related Vehicles', 'lgl-shortcodes'); }
    public function get_icon() { return 'eicon-post-list'; }
    public function get_categories() { return ['lgl-elements']; }
    public function get_keywords() { return ['lgl', 'related', 'vehicles', 'similar']; }

    protected function register_controls() {
        $this->start_controls_section('content_section', ['label' => __('Related Settings', 'lgl-shortcodes'), 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);

        $this->add_control('attr_post_type', [
            'label' => __('Vehicle Type', 'lgl-shortcodes'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => '',
            'options' => ['' => 'Current Post Type', 'caravan' => 'Caravan', 'motorhome' => 'Motorhome', 'campervan' => 'Campervan'],
        ]);

        $this->add_control('attr_count', [
            'label' => __('Number of Vehicles', 'lgl-shortcodes'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 3,
        ]);

        $this->end_controls_section();

        // INJECT STYLE TAB
        $this->register_common_style_controls();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $atts = '';
        if (!empty($settings['attr_post_type'])) $atts .= ' post_type="' . esc_attr($settings['attr_post_type']) . '"';
        if (!empty($settings['attr_count'])) $atts .= ' count="' . esc_attr($settings['attr_count']) . '"';
        echo do_shortcode('[lgl_related_vehicles' . $atts . ']');
    }
}


/* ==========================================================================
   6. Mini Components (No Attributes, Just Styles)
   ========================================================================== */
class LGL_Widget_Mini_Account extends \Elementor\Widget_Base {
    use LGL_Elementor_Style_Controls;
    public function get_name() { return 'lgl_mini_account_widget'; }
    public function get_title() { return __('Mini Account Bar', 'lgl-shortcodes'); }
    public function get_icon() { return 'eicon-person'; }
    public function get_categories() { return ['lgl-elements']; }
    public function get_keywords() { return ['lgl', 'mini', 'account', 'login', 'bar']; }
    protected function register_controls() { $this->register_common_style_controls(); }
    protected function render() { echo do_shortcode('[lgl_mini_account]'); }
}

class LGL_Widget_Mini_Compare extends \Elementor\Widget_Base {
    use LGL_Elementor_Style_Controls;
    public function get_name() { return 'lgl_mini_compare_widget'; }
    public function get_title() { return __('Mini Compare Button', 'lgl-shortcodes'); }
    public function get_icon() { return 'eicon-exchange'; }
    public function get_categories() { return ['lgl-elements']; }
    public function get_keywords() { return ['lgl', 'mini', 'compare', 'button']; }
    protected function register_controls() { $this->register_common_style_controls(); }
    protected function render() { echo do_shortcode('[lgl_mini_compare]'); }
}

class LGL_Widget_Mini_Wishlist extends \Elementor\Widget_Base {
    use LGL_Elementor_Style_Controls;
    public function get_name() { return 'lgl_mini_wishlist_widget'; }
    public function get_title() { return __('Mini Wishlist Dropdown', 'lgl-shortcodes'); }
    public function get_icon() { return 'eicon-heart-o'; }
    public function get_categories() { return ['lgl-elements']; }
    public function get_keywords() { return ['lgl', 'mini', 'wishlist', 'dropdown', 'favorites']; }
    protected function register_controls() { $this->register_common_style_controls(); }
    protected function render() { echo do_shortcode('[lgl_mini_wishlist]'); }
}