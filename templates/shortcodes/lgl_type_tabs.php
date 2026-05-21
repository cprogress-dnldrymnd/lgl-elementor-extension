<?php

/**
 * Renders interactive buttons for Caravans, Motorhomes, and Campervans.
 * Respects the global enable/disable settings.
 */

if (!defined('ABSPATH')) {
    exit;
}

$options = get_option('lgl_settings', array());

// Fetch the enable/disable toggles from the database
$enable_caravan   = isset($options['enable_caravan']) ? $options['enable_caravan'] : '1';
$enable_motorhome = isset($options['enable_motorhome']) ? $options['enable_motorhome'] : '1';
$enable_campervan = isset($options['enable_campervan']) ? $options['enable_campervan'] : '1';

// Fetch the assigned archive page URLs
$caravan_page   = $options['caravan_page'] ?? false;
$motorhome_page = $options['motorhome_page'] ?? false;
$campervan_page = $options['campervan_page'] ?? false;

// Determine current active post type to highlight the correct tab on load
global $wp_query;
$current_url = get_permalink();
$active_type = get_query_var('post_type') ? get_query_var('post_type') : '';

?>
<div class="lgl-type-tabs-wrapper">
    <ul class="lgl-type-tabs">

        <?php if ($enable_caravan && $caravan_page) :
            $url = get_the_permalink($caravan_page);
            $is_active = ($active_type === 'caravan' || $current_url === $url || empty($active_type)) ? 'is-active' : '';
        ?>
            <li><button type="button" class="lgl-tab-btn lgl-btn <?php echo esc_attr($is_active); ?>" data-post-type="caravan" data-url="<?php echo esc_url($url); ?>"> <span class="hide-desktop">Start</span> Caravans <span class="hide-desktop">Search</span></button></li>
        <?php endif; ?>

        <?php if ($enable_motorhome && $motorhome_page) :
            $url = get_the_permalink($motorhome_page);
            $is_active = ($active_type === 'motorhome' || $current_url === $url) ? 'is-active' : '';
        ?>
            <li><button type="button" class="lgl-tab-btn lgl-btn <?php echo esc_attr($is_active); ?>" data-post-type="motorhome" data-url="<?php echo esc_url($url); ?>">Motorhomes</button></li>
        <?php endif; ?>

        <?php if ($enable_campervan && $campervan_page) :
            $url = get_the_permalink($campervan_page);
            $is_active = ($active_type === 'campervan' || $current_url === $url) ? 'is-active' : '';
        ?>
            <li><button type="button" class="lgl-tab-btn lgl-btn <?php echo esc_attr($is_active); ?>" data-post-type="campervan" data-url="<?php echo esc_url($url); ?>">Campervans</button></li>
        <?php endif; ?>

    </ul>
</div>