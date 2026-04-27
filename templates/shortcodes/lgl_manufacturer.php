<?php

/**
 * Template for displaying the Manufacturer Logos Slider
 * * Available variables:
 * $settings (array) - Global manufacturer logo settings
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="lgl-manufacturer-slider">
    <?php
    foreach ($settings as $term_id => $data) :

        // Only render if the box is checked AND a logo exists
        if (isset($data['display']) && $data['display'] == '1' && ! empty($data['logo'])) :

            // Get the term to safely use its name as the image Alt Text
            $term = get_term($term_id, 'listing-make-model');
            $alt_text = (! is_wp_error($term) && $term) ? esc_attr($term->name) : 'Manufacturer Logo';
    ?>

            <div class="lgl-manufacturer-slide">
                <img src="<?php echo esc_url($data['logo']); ?>" alt="<?php echo $alt_text; ?>">
            </div>

        <?php endif; ?>
    <?php endforeach; ?>
</div>