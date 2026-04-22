<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initializes the Elementor Integration safely.
 */
class LGL_Elementor_Integration {
    public function __construct() {
        // Wait until all plugins are loaded before checking for Elementor
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        // Abort entirely if Elementor is not active
        if (!did_action('elementor/loaded')) {
            return;
        }

        // Elementor is active: hook into the registration processes
        add_action('elementor/elements/categories_registered', [$this, 'add_category']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
    }

    public function add_category($elements_manager) {
        $elements_manager->add_category(
            'lgl-elements',
            [
                'title' => __('LGL Leisure Vehicles', 'lgl-shortcodes'),
                'icon'  => 'eicon-car',
            ]
        );
    }

    public function register_widgets($widgets_manager) {
        // ONLY require the widget class file when Elementor explicitly asks for widgets.
        // This completely prevents the "Class not found" fatal error.
        require_once __DIR__ . '/class-lgl-elementor-widget.php';
        $widgets_manager->register(new \LGL_Elementor_Widget());
    }
}

new LGL_Elementor_Integration();