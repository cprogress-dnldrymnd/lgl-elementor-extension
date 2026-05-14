<?php
if ($post_type) {
    $results = LGL_Shortcodes::get_search_results_data(
        post_type: explode(',', $post_type),
        paged: 1,
        posts_per_page: $limit,
        is_carousel: $is_carousel,
        style: $style,
        is_featured: $is_featured
    );
    if ($is_carousel) {
        echo '<div class="vehicle-slider-holder">';
        echo '<div class="vehicle-slider-js swiper">';
        echo '<div class="swiper-wrapper">';
    } else {
        echo '<div class="lgl-grid-layout lgl-cols--3 lgl-layout-default ">';
    }
    echo $results['html'];
    if ($is_carousel) {
        echo '</div>';
        echo '</div>';
        echo '<div class="swiper-button-next"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"></path> </svg></div>';
        echo '<div class="swiper-button-prev"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"></path> </svg></div>';
    }
    echo '</div>';
}
