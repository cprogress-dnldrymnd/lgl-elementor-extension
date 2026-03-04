<?php
$listing_fields = LGL_Shortcodes::get_external_listing_fields();

// Define an array of meta keys you want to exclude from the frontend display.
// Populate this array with the exact keys defined in get_listing_detail_fields() (e.g., 'price', 'year', 'internal_stock_number').
$exclude_keys = array( 'internal_stock_number', 'rrp', 'warranty', 'feature', 'extras' ); 

if (!empty($listing_fields)) {
    // Access the specific field groupings
    $common_fields = $listing_fields['common'];
    $motorhome_campervan_fields = $listing_fields['motorhome_campervan'];
    
    echo "<div class='lgl-meta-list'>";
    
    // Example iteration over common fields
    foreach ($common_fields as $meta_key => $label) {
        
        // Intercept and skip the current iteration if the meta key exists in the exclusion array.
        // The 3rd parameter 'true' ensures strict type checking.
        if (in_array($meta_key, $exclude_keys, true)) {
            continue;
        }

        $meta_value = get_post_meta($post_id, $meta_key, true);
        
        if (!empty($meta_value)) {
            echo "<div class='lgl-meta-item lgl-{$meta_key}'>";
            echo "<span class='lgl-meta-icon-label'>";
            echo "<span class='lgl-label'>";
            echo esc_html($label);
            echo "</span>";
            echo "</span>";

            echo "<span class='lgl-value'>";
            echo esc_html($meta_value);
            echo "</span>";

            echo "</div>";
        }
    }
    echo "</div>";
}
?>