<?php
if (!defined('ABSPATH')) {
    exit;
}

$post_id   = get_the_ID();
$post_type = get_post_type($post_id);
$options   = get_option('lgl_settings', array());

// Fetch the dynamic layout mapping for this specific CPT
$meta_summary = isset($options['meta_summary_' . $post_type]) ? $options['meta_summary_' . $post_type] : array();

// Fetch all possible field labels dynamically to replace the removed 'Frontend Label' setting
$all_fields = array();
if (class_exists('LGL_Import_Post_Types')) {
    $listing_fields = LGL_Import_Post_Types::get_listing_detail_fields();
    $all_fields = array_merge(
        isset($listing_fields['common']) ? $listing_fields['common'] : array(),
        isset($listing_fields['motorhome_campervan']) ? $listing_fields['motorhome_campervan'] : array(),
        isset($listing_fields['caravan']) ? $listing_fields['caravan'] : array()
    );
    $all_fields['listing-fuel-type'] = __('Fuel Type', 'lgl-shortcodes');
    $all_fields['listing-chassis']   = __('Chassis', 'lgl-shortcodes');
    $all_fields['listing-gearbox']   = __('Gearbox', 'lgl-shortcodes');
    $all_fields['price']             = __('Price', 'lgl-shortcodes');
}

// Establish safe defaults if the user has not configured the repeater yet
if (empty($meta_summary)) {
    $meta_summary = array(
        array('meta_key' => 'berth', 'icon_key' => 'berth'),
        array('meta_key' => 'year',  'icon_key' => 'year'),
        array(
            'meta_key' => ($post_type === 'caravan' ? 'axles' : 'mileage'), 
            'icon_key' => ($post_type === 'caravan' ? 'axles' : 'mileage')
        ),
    );
}
?>
<div class="lgl-post--meta">
    <div class="lgl-post--meta-row">
        <?php foreach ($meta_summary as $meta) : 
            if (empty($meta['meta_key'])) continue; // Skip unselected rows

            // Taxonomies need get_the_terms(), standard meta needs get_post_meta()
            if (in_array($meta['meta_key'], array('listing-fuel-type', 'listing-chassis', 'listing-gearbox'))) {
                $terms = get_the_terms($post_id, $meta['meta_key']);
                $meta_value = ($terms && !is_wp_error($terms)) ? join(', ', wp_list_pluck($terms, 'name')) : '';
            } else {
                $meta_value = get_post_meta($post_id, $meta['meta_key'], true);
            }

            // Determine the correct label from our unified array fallback
            $label = isset($all_fields[$meta['meta_key']]) ? $all_fields[$meta['meta_key']] : ucfirst(str_replace('_', ' ', $meta['meta_key']));
        ?>
            <div class="lgl-post--meta-col">
                <div class="lgl-post--meta-item lgl-post--<?php echo esc_attr($meta['meta_key']); ?>">
                    <?php
                    // Priority 1: Admin-uploaded Media Library SVG
                    if (!empty($meta['icon_id'])) {
                        LGL_Shortcodes::render_attachment_svg((int) $meta['icon_id']);
                    } 
                    // Priority 2: Legacy local hardcoded SVG fallback (for defaults)
                    elseif (!empty($meta['icon_key'])) {
                        LGL_Shortcodes::render_inline_svg($meta['icon_key']);
                    }
                    ?>
                    <div class="label-val">
                        <span class="lgl-label"><?php echo esc_html($label); ?></span>
                        <span class="lgl-value"><?php echo esc_html($meta_value ? $meta_value : 'N/A'); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>