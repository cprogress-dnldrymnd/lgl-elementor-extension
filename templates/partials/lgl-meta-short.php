<?php
if (!defined('ABSPATH')) {
    exit;
}

$post_id   = get_the_ID();
$post_type = get_post_type($post_id);
$options   = get_option('lgl_settings', array());

// Fetch the dynamic layout mapping for this specific CPT
$short_meta = isset($options['short_meta_' . $post_type]) ? $options['short_meta_' . $post_type] : array();

// Establish safe defaults if the user has not configured the repeater yet
if (empty($short_meta)) {
    $short_meta = array(
        array('meta_key' => 'berth', 'label' => 'Berth', 'icon_key' => 'berth'),
        array('meta_key' => 'year',  'label' => 'Year',  'icon_key' => 'year'),
        array(
            'meta_key' => ($post_type === 'caravan' ? 'axles' : 'mileage'), 
            'label'    => ($post_type === 'caravan' ? 'Axles' : 'Mileage'), 
            'icon_key' => ($post_type === 'caravan' ? 'axles' : 'mileage')
        ),
    );
}
?>
<div class="lgl-post--meta">
    <div class="lgl-post--meta-row">
        <?php foreach ($short_meta as $meta) : 
            $meta_value = get_post_meta($post_id, $meta['meta_key'], true);
        ?>
            <div class="lgl-post--meta-col">
                <div class="lgl-post--meta-item lgl-post--<?php echo esc_attr($meta['meta_key']); ?>">
                    <?php
                    // Priority 1: Admin-uploaded Media Library SVG
                    if (!empty($meta['icon_id'])) {
                        LGL_Shortcodes::render_attachment_svg((int) $meta['icon_id']);
                    } 
                    // Priority 2: Legacy local hardcoded SVG fallback
                    elseif (!empty($meta['icon_key'])) {
                        LGL_Shortcodes::render_inline_svg($meta['icon_key']);
                    }
                    ?>
                    <div class="label-val">
                        <span class="lgl-label"><?php echo esc_html($meta['label']); ?></span>
                        <span class="lgl-value"><?php echo esc_html($meta_value ? $meta_value : 'N/A'); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>