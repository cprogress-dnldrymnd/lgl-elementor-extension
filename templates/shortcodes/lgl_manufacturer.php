<?php
/**
 * Template for displaying the Manufacturer Logos Marquee
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// 1. Check if we have any valid logos checked for display
$has_logos = false;
if ( ! empty( $settings ) ) {
    foreach ( $settings as $data ) {
        if ( isset( $data['display'] ) && $data['display'] == '1' && ! empty( $data['logo'] ) ) {
            $has_logos = true;
            break;
        }
    }
}

// 2. If no logos are found, show an error message ONLY to administrators
if ( ! $has_logos ) {
    if ( current_user_can('manage_options') ) {
        echo '<div style="padding: 20px; border: 2px dashed red; text-align: center;">';
        echo '<strong>Admin Notice:</strong> The <code>[lgl_manufacturer]</code> shortcode is here, but no logos are set to display. Please go to LGL Settings -> Manufacturer Logos, upload images, and check the "Show in Slider" box.';
        echo '</div>';
    }
    return; // Stop rendering
}
?>

<div class="lgl-marquee-container">
    <div class="lgl-marquee-track">
        <?php 
        // 3. We loop through the logos TWICE to create a seamless, never-ending marquee scroll
        for ( $i = 0; $i < 2; $i++ ) :
            foreach ( $settings as $term_id => $data ) : 
                
                // Only render if the box is checked AND a logo exists
                if ( isset( $data['display'] ) && $data['display'] == '1' && ! empty( $data['logo'] ) ) :
                    
                    $term = get_term( $term_id, 'listing-make-model' );
                    $alt_text = ( ! is_wp_error( $term ) && $term ) ? esc_attr( $term->name ) : 'Manufacturer Logo';
                    ?>
                    
                    <div class="lgl-marquee-item">
                        <img src="<?php echo esc_url( $data['logo'] ); ?>" alt="<?php echo $alt_text; ?>">
                    </div>
                    
                <?php 
                endif; 
            endforeach; 
        endfor;
        ?>
    </div>
</div>