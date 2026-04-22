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
        // Wait until all plugins are loaded before checking for Elementor
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init()
    {
        // Abort entirely if Elementor is not active
        if (!did_action('elementor/loaded')) {
            return;
        }

        add_action('elementor/elements/categories_registered', [$this, 'add_category']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
    }

    public function add_category($elements_manager)
    {
        // 1. Add the category normally
        $elements_manager->add_category(
            'lgl-elements',
            [
                'title' => __('LGL Leisure Vehicles', 'lgl-shortcodes'),
                'icon'  => 'eicon-car',
                'active' => true, // Keeps the panel expanded by default so it's immediately visible
            ]
        );

        // 2. Use a PHP closure to reorder Elementor's internal categories array
        $reorder_categories = function () {
            // Extract our custom category
            $lgl_cat = $this->categories['lgl-elements'];
            unset($this->categories['lgl-elements']);

            // Merge it back at the absolute top of the array
            $this->categories = array_merge(['lgl-elements' => $lgl_cat], $this->categories);
        };

        // 3. Execute the closure bound to the elements manager to bypass private property restrictions
        $reorder_categories->call($elements_manager);
    }
    public function register_widgets($widgets_manager)
    {
        // Load the file containing all our individual widget classes
        require_once __DIR__ . '/class-lgl-elementor-widgets.php';

        // Register each widget individually
        $widgets_manager->register(new \LGL_Widget_Search());
        $widgets_manager->register(new \LGL_Widget_Type_Tabs());
        $widgets_manager->register(new \LGL_Widget_Search_Results());
        $widgets_manager->register(new \LGL_Widget_Listing());
        $widgets_manager->register(new \LGL_Widget_Compare());
        $widgets_manager->register(new \LGL_Widget_Compare_Duo());
        $widgets_manager->register(new \LGL_Widget_Wishlist());
        $widgets_manager->register(new \LGL_Widget_My_Account());
        $widgets_manager->register(new \LGL_Widget_Breadcrumbs());
    }
}

new LGL_Elementor_Integration();
