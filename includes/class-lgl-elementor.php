<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initializes the Elementor Integration safely.
 */
class LGL_Elementor_Integration
{
    public function __construct()
    {
        add_action('elementor/elements/categories_registered', [$this, 'add_category']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
    }

    public function add_category($elements_manager)
    {
        $elements_manager->add_category(
            'lgl-elements',
            [
                'title' => __('LGL Leisure Vehicles', 'lgl-shortcodes'),
                'icon'  => 'eicon-car',
            ]
        );
    }

    public function register_widgets($widgets_manager)
    {
        $widgets_manager->register(new LGL_Elementor_Widget());
    }
}

// Only initialize if Elementor is active on the site
add_action('plugins_loaded', function () {
    if (did_action('elementor/loaded')) {
        new LGL_Elementor_Integration();
    }
});


/**
 * The unified Elementor Widget.
 */
if (did_action('elementor/loaded')) {

    class LGL_Elementor_Widget extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'lgl_unified_shortcode';
        }

        public function get_title()
        {
            return __('LGL Shortcode', 'lgl-shortcodes');
        }

        public function get_icon()
        {
            return 'eicon-shortcode';
        }

        public function get_categories()
        {
            return ['lgl-elements'];
        }

        protected function register_controls()
        {

            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Shortcode Settings', 'lgl-shortcodes'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            // 1. Primary Shortcode Selector
            $this->add_control(
                'shortcode_type',
                [
                    'label' => __('Select Component', 'lgl-shortcodes'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'lgl_search',
                    'options' => [
                        'lgl_search'           => __('Vehicle Search Form', 'lgl-shortcodes'),
                        'lgl_type_tabs'        => __('Vehicle Type Tabs', 'lgl-shortcodes'),
                        'lgl_search_results'   => __('Search Results Grid', 'lgl-shortcodes'),
                        'lgl_listing'          => __('Vehicle Listing Grid', 'lgl-shortcodes'),
                        'lgl_compare'          => __('Compare Table', 'lgl-shortcodes'),
                        'lgl_compare_duo'      => __('Compare Duo Card', 'lgl-shortcodes'),
                        'lgl_wishlist'         => __('Full Wishlist Page', 'lgl-shortcodes'),
                        'lgl_my_account'       => __('My Account Dashboard', 'lgl-shortcodes'),
                        'lgl_breadcrumbs'      => __('Breadcrumbs', 'lgl-shortcodes'),
                    ],
                ]
            );

            // 2. Dynamic Attribute: Post Type (Used by Listing & Search)
            $this->add_control(
                'attr_post_type',
                [
                    'label' => __('Vehicle Type', 'lgl-shortcodes'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''          => __('Global / All', 'lgl-shortcodes'),
                        'caravan'   => __('Caravan', 'lgl-shortcodes'),
                        'motorhome' => __('Motorhome', 'lgl-shortcodes'),
                        'campervan' => __('Campervan', 'lgl-shortcodes'),
                    ],
                    'condition' => [
                        'shortcode_type' => ['lgl_listing', 'lgl_search'],
                    ],
                ]
            );

            // 3. Dynamic Attributes: Listing Grid
            $this->add_control(
                'attr_limit',
                [
                    'label' => __('Items Limit', 'lgl-shortcodes'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 9,
                    'condition' => ['shortcode_type' => 'lgl_listing'],
                ]
            );

            $this->add_control(
                'attr_style',
                [
                    'label' => __('Card Style', 'lgl-shortcodes'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'style-1',
                    'options' => [
                        'style-1' => 'Style 1',
                        'style-2' => 'Style 2',
                    ],
                    'condition' => ['shortcode_type' => 'lgl_listing'],
                ]
            );

            $this->add_control(
                'attr_is_carousel',
                [
                    'label' => __('Enable Carousel', 'lgl-shortcodes'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'return_value' => 'true',
                    'condition' => ['shortcode_type' => 'lgl_listing'],
                ]
            );

            $this->add_control(
                'attr_is_featured',
                [
                    'label' => __('Show Only Featured', 'lgl-shortcodes'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'return_value' => 'true',
                    'condition' => ['shortcode_type' => 'lgl_listing'],
                ]
            );

            // 4. Dynamic Attributes: Search Form
            $this->add_control(
                'attr_search_type',
                [
                    'label' => __('Search Layout Mode', 'lgl-shortcodes'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'default',
                    'options' => [
                        'default' => __('Default (Dropdown)', 'lgl-shortcodes'),
                        'tabs'    => __('Tabs Mode (Pairs with Type Tabs)', 'lgl-shortcodes'),
                    ],
                    'condition' => ['shortcode_type' => 'lgl_search'],
                ]
            );

            $this->add_control(
                'attr_live_search',
                [
                    'label' => __('Live AJAX Refresh', 'lgl-shortcodes'),
                    'description' => __('Disable this if you want the form to redirect to an archive page instead of refreshing results inline.', 'lgl-shortcodes'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'return_value' => 'true',
                    'condition' => ['shortcode_type' => 'lgl_search'],
                ]
            );

            // 5. Dynamic Attributes: Compare Duo Card
            $this->add_control(
                'attr_post_id_1',
                [
                    'label' => __('First Vehicle ID', 'lgl-shortcodes'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'condition' => ['shortcode_type' => 'lgl_compare_duo'],
                ]
            );

            $this->add_control(
                'attr_post_id_2',
                [
                    'label' => __('Second Vehicle ID', 'lgl-shortcodes'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'condition' => ['shortcode_type' => 'lgl_compare_duo'],
                ]
            );

            $this->end_controls_section();
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();
            $shortcode_tag = $settings['shortcode_type'];

            $atts_string = '';

            // Build shortcode string based on the active selection
            if ($shortcode_tag === 'lgl_listing') {
                if (!empty($settings['attr_post_type']))   $atts_string .= ' post_type="' . esc_attr($settings['attr_post_type']) . '"';
                if (!empty($settings['attr_limit']))       $atts_string .= ' limit="' . esc_attr($settings['attr_limit']) . '"';
                if (!empty($settings['attr_style']))       $atts_string .= ' style="' . esc_attr($settings['attr_style']) . '"';
                if ($settings['attr_is_carousel'] === 'true') $atts_string .= ' is_carousel="true"';
                if ($settings['attr_is_featured'] === 'true') $atts_string .= ' is_featured="true"';
            } elseif ($shortcode_tag === 'lgl_search') {
                if (!empty($settings['attr_post_type']))   $atts_string .= ' post_type="' . esc_attr($settings['attr_post_type']) . '"';
                if (!empty($settings['attr_search_type'])) $atts_string .= ' search_type="' . esc_attr($settings['attr_search_type']) . '"';

                // If live search is disabled, output 'false'
                if (empty($settings['attr_live_search'])) {
                    $atts_string .= ' live_search="false"';
                }
            } elseif ($shortcode_tag === 'lgl_compare_duo') {
                if (!empty($settings['attr_post_id_1'])) $atts_string .= ' post_id_1="' . esc_attr($settings['attr_post_id_1']) . '"';
                if (!empty($settings['attr_post_id_2'])) $atts_string .= ' post_id_2="' . esc_attr($settings['attr_post_id_2']) . '"';
            }

            // Execute shortcode
            $final_shortcode = '[' . $shortcode_tag . $atts_string . ']';

            echo do_shortcode($final_shortcode);
        }
    }
}
