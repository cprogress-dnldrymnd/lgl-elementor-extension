<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Template for rendering the search filter form.
 * Available variables:
 * @var string $post_type Extracted from shortcode attributes.
 */

$search_type = isset($search_type) ? $search_type : 'default';

// If search_type is tabs, default the initial data pulls to 'caravan' so dropdowns populate
$data_post_type = $post_type;
if ($post_type == false && $search_type === 'tabs') {
    $data_post_type = 'caravan';
}

$conditions = LGL_Shortcodes::get_unique_meta_values($data_post_type, 'condition');
$berths     = LGL_Shortcodes::get_unique_meta_values($data_post_type, 'berth');
$raw_prices = LGL_Shortcodes::get_unique_meta_values($data_post_type, 'price');

$prices = array();
if (!empty($raw_prices)) {
    foreach ($raw_prices as $price) {
        if (is_numeric($price)) {
            $prices[] = (float) $price;
        }
    }
}
$prices = array_unique($prices);
sort($prices, SORT_NUMERIC);

if ($data_post_type) {
    $type_post_ids = get_posts(array(
        'post_type'      => $data_post_type,
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ));

    $makes = array();

    if (!empty($type_post_ids)) {
        $assigned_terms = wp_get_object_terms($type_post_ids, 'listing-make-model', array('fields' => 'all'));

        if (!is_wp_error($assigned_terms) && !empty($assigned_terms)) {
            $make_ids = array();
            foreach ($assigned_terms as $term) {
                $make_ids[] = ($term->parent > 0) ? (int) $term->parent : (int) $term->term_id;
            }
            $make_ids = array_unique($make_ids);

            $makes = get_terms(array(
                'taxonomy'   => 'listing-make-model',
                'include'    => $make_ids,
                'hide_empty' => false,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ));

            if (is_wp_error($makes)) {
                $makes = array();
            }
        }
    }
} else {
    $makes = get_terms(array(
        'taxonomy'   => 'listing-make-model',
        'parent'     => 0,
        'hide_empty' => false,
    ));
}

// Filter active make models to only those belonging to the current post_type as well
if ($data_post_type && $active_make) {

    // Convert the slug string into the term object to retrieve the integer ID
    $active_make_term = get_term_by('slug', $active_make, 'listing-make-model');
    $active_make_id   = $active_make_term ? $active_make_term->term_id : 0;

    $active_make_models = get_terms(array(
        'taxonomy'   => 'listing-make-model',
        'parent'     => $active_make_id, // Pass the strict integer ID here
        'hide_empty' => false,
    ));

    if (!is_wp_error($active_make_models) && !empty($active_make_models) && !empty($type_post_ids)) {
        $assigned_model_ids = array();
        $assigned_all = wp_get_object_terms($type_post_ids, 'listing-make-model', array('fields' => 'ids'));
        if (!is_wp_error($assigned_all)) {
            $assigned_model_ids = array_map('intval', $assigned_all);
        }

        $active_make_models = array_filter($active_make_models, function ($model) use ($assigned_model_ids) {
            return in_array((int) $model->term_id, $assigned_model_ids, true);
        });
    }
}

// -------------------------------------------------------------------
// Read active URL parameters to pre-populate the filter fields
// -------------------------------------------------------------------
// Prioritize native query vars from our rewrite rules, fallback to standard $_GET
$active_make      = get_query_var('listing_make') ? sanitize_text_field(get_query_var('listing_make')) : (isset($_GET['listing_make']) ? sanitize_text_field($_GET['listing_make']) : '');
$active_model     = get_query_var('listing_model') ? sanitize_text_field(get_query_var('listing_model')) : (isset($_GET['listing_model']) ? sanitize_text_field($_GET['listing_model']) : '');
$active_condition = isset($_GET['condition'])     ? sanitize_text_field($_GET['condition'])     : '';
$active_berth     = isset($_GET['berth'])         ? sanitize_text_field($_GET['berth'])         : '';
$active_price_min = isset($_GET['price_min'])     ? sanitize_text_field($_GET['price_min'])     : '';
$active_price_max = isset($_GET['price_max'])     ? sanitize_text_field($_GET['price_max'])     : '';

// Calculate the clean Base URL for JavaScript path construction
$base_archive_url = '';
if ($post_type) {
    $options = get_option('lgl_settings', array());
    $page_key = $post_type . '_page';
    if (!empty($options[$page_key])) {
        $base_archive_url = get_permalink($options[$page_key]);
    } else {
        $base_archive_url = get_post_type_archive_link($post_type);
    }
}

// Evaluate if the offcanvas markup should be loaded
$is_offcanvas = ($post_type || $search_type === 'tabs');
$offcanvas_class = 'lgl-search-offcanvas';
$backdrop_class = 'lgl-search-offcanvas-backdrop';
?>

<?php if ($post_type && $search_type !== 'tabs') : ?>
    <div class="lgl-search-mobile-toggle-wrapper">
        <button type="button"
            class="lgl-search-mobile-toggle"
            aria-expanded="false"
            aria-controls="lgl-search-offcanvas"
            aria-label="Start a new search">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
            </svg>
            Start a New Search
        </button>
    </div>
<?php endif; ?>

<?php if ($is_offcanvas) : ?>
    <div class="<?php echo esc_attr($backdrop_class); ?>" id="lgl-search-backdrop" aria-hidden="true"></div>

    <div class="<?php echo esc_attr($offcanvas_class); ?>"
        id="lgl-search-offcanvas"
        role="dialog"
        aria-modal="true"
        aria-label="Search filters">

        <div class="lgl-offcanvas-header">
            <h3>Search Filters</h3>
            <button type="button" class="lgl-offcanvas-close" aria-label="Close search filters">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                </svg>
            </button>
        </div>

        <div class="lgl-offcanvas-body">
        <?php endif; ?>
        <div class="lgl-search-container lgl-holder">
            <?php
            // Evaluate string 'true' or 'false' into an actual boolean
            $is_live_search = filter_var($live_search, FILTER_VALIDATE_BOOLEAN);
            // Apply ajax class if live_search is true, otherwise apply no-ajax class to trigger a redirect
            $form_class = $is_live_search ? 'lgl-filter-form-ajax' : 'lgl-filter-form-no-ajax';

            // Apply Layout Class
            $form_layout = isset($layout) ? $layout : 'horizontal';
            $form_class .= ' lgl-layout-' . $form_layout;
            ?>
            <form id="lgl-search-form" class="lgl-filter-form <?php echo esc_attr($form_class); ?>">
                <div class="lgl-search-form-inner">
                    <input type="hidden" name="post_type" id="lgl_target_post_type" value="<?php echo esc_attr($data_post_type); ?>">
                    <input type="hidden" id="lgl_base_archive_url" value="<?php echo esc_url($base_archive_url); ?>">

                    <?php if ($post_type == false) { ?>
                        <?php
                        $options = get_option('lgl_settings', array());

                        $caravan_page   = $options['caravan_page']   ?? false;
                        $motorhome_page = $options['motorhome_page'] ?? false;
                        $campervan_page = $options['campervan_page'] ?? false;

                        $enable_caravan   = isset($options['enable_caravan']) ? $options['enable_caravan'] : '1';
                        $enable_motorhome = isset($options['enable_motorhome']) ? $options['enable_motorhome'] : '1';
                        $enable_campervan = isset($options['enable_campervan']) ? $options['enable_campervan'] : '1';

                        $vehicle_types = array();
                        if ($enable_caravan && $caravan_page)   $vehicle_types[] = array('url' => get_the_permalink($caravan_page),   'label' => 'Caravan',   'slug' => 'caravan');
                        if ($enable_motorhome && $motorhome_page) $vehicle_types[] = array('url' => get_the_permalink($motorhome_page), 'label' => 'Motorhome', 'slug' => 'motorhome');
                        if ($enable_campervan && $campervan_page) $vehicle_types[] = array('url' => get_the_permalink($campervan_page), 'label' => 'Campervan', 'slug' => 'campervan');
                        ?>

                        <?php if (!empty($vehicle_types)) : ?>
                            <div class="lgl-filter-group" <?php echo ($search_type === 'tabs') ? 'style="display: none;"' : ''; ?>>
                                <label for="lgl_vehicle_type">Leisure Vehicle Type</label>
                                <select name="post_type" id="lgl_post_type" class="lgl-select2" data-placeholder="Leisure Vehicle Type">
                                    <option value="">Leisure Vehicle Type</option>
                                    <?php foreach ($vehicle_types as $type) :
                                        $is_selected = selected($active_post_type, $type['url'], false);
                                        // Default to caravan internally if tabs mode is active
                                        if ($search_type === 'tabs' && empty($active_post_type) && $type['slug'] === 'caravan') {
                                            $is_selected = 'selected="selected"';
                                        }
                                    ?>
                                        <option value="<?php echo esc_attr($type['url']); ?>"
                                            data-post-type="<?php echo esc_attr($type['slug']); ?>"
                                            <?php echo $is_selected; ?>>
                                            <?php echo esc_html($type['label']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                    <?php } ?>

                    <!-- Make -->
                    <div class="lgl-filter-group">
                        <label for="lgl_make">Make</label>
                        <select name="listing_make" id="lgl_make" class="lgl-select2" data-placeholder="Select Make" <?php echo ($data_post_type == false) ? 'disabled' : ''; ?>>
                            <option value=""><?php echo ($data_post_type == false) ? 'Select Vehicle Type First' : 'All Makes'; ?></option>
                            <?php if ($data_post_type != false) : ?>
                                <?php foreach ($makes as $make) : ?>
                                    <option value="<?php echo esc_attr($make->slug); ?>" <?php selected($active_make, $make->slug); ?>>
                                        <?php echo esc_html($make->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Model -->
                    <div class="lgl-filter-group">
                        <label for="lgl_model">Model</label>
                        <?php
                        $is_disabled = (empty($active_make_models) && empty($active_model)) ? 'disabled' : '';
                        $model_found = false;
                        if (!empty($active_make_models) && $active_model) {
                            foreach ($active_make_models as $model) {
                                if ($model->slug === $active_model) {
                                    $model_found = true;
                                    break;
                                }
                            }
                        }
                        ?>
                        <select name="listing_model" id="lgl_model" class="lgl-select2" data-placeholder="Select Model" <?php echo $is_disabled; ?>>
                            <?php if (empty($active_make_models) && empty($active_model)) : ?>
                                <option value="">Select Make First</option>
                            <?php else : ?>
                                <option value="">All Models</option>
                                <?php
                                if ($active_model && !$model_found) :
                                    $fallback_term = get_term_by('slug', $active_model, 'listing-make-model');
                                    if ($fallback_term) :
                                ?>
                                        <option value="<?php echo esc_attr($fallback_term->slug); ?>" selected="selected"><?php echo esc_html($fallback_term->name); ?></option>
                                <?php endif;
                                endif; ?>
                                <?php if (!empty($active_make_models)) : ?>
                                    <?php foreach ($active_make_models as $model) : ?>
                                        <option value="<?php echo esc_attr($model->slug); ?>" <?php selected($active_model, $model->slug); ?>><?php echo esc_html($model->name); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <?php
                    $show_filters_bool = filter_var($show_all_filters ?? true, FILTER_VALIDATE_BOOLEAN);
                    if ($show_filters_bool) :

                        $listing_fields = class_exists('LGL_Import_Post_Types') ? LGL_Import_Post_Types::get_listing_detail_fields() : array();
                        $type_fields = isset($listing_fields['common']) ? $listing_fields['common'] : array();

                        // Always merge fields so they exist in the DOM for JS to populate globally
                        if ($data_post_type === 'caravan' || $data_post_type == false) {
                            if (isset($listing_fields['caravan'])) $type_fields = array_merge($type_fields, $listing_fields['caravan']);
                        }
                        if (in_array($data_post_type, array('motorhome', 'campervan')) || $data_post_type == false) {
                            if (isset($listing_fields['motorhome_campervan'])) $type_fields = array_merge($type_fields, $listing_fields['motorhome_campervan']);
                        }

                        $fuel_types   = get_terms(array('taxonomy' => 'listing-fuel-type', 'hide_empty' => true));
                        $chassis_list = get_terms(array('taxonomy' => 'listing-chassis', 'hide_empty' => true));
                        $gearboxes    = get_terms(array('taxonomy' => 'listing-gearbox', 'hide_empty' => true));

                        $active_fuel_type = isset($_GET['listing-fuel-type']) ? sanitize_text_field($_GET['listing-fuel-type']) : '';
                        $active_chassis   = isset($_GET['listing-chassis'])   ? sanitize_text_field($_GET['listing-chassis'])   : '';
                        $active_gearbox   = isset($_GET['listing-gearbox'])   ? sanitize_text_field($_GET['listing-gearbox'])   : '';

                        $options = get_option('lgl_settings', array());
                        $default_order = array_merge(array_keys($type_fields), array('price', 'listing-fuel-type', 'listing-chassis', 'listing-gearbox'));
                        $search_order = isset($options['search_order_' . $data_post_type]) ? $options['search_order_' . $data_post_type] : $default_order;

                        foreach ($search_order as $field_key) {
                            if (!empty($options['hide_search_' . $data_post_type . '_' . $field_key])) continue;

                            // Fetch global label overrides
                            $default_label = isset($type_fields[$field_key]) ? $type_fields[$field_key] : ucfirst(str_replace('-', ' ', $field_key));
                            $label = !empty($options['label_override_' . $field_key]) ? $options['label_override_' . $field_key] : $default_label;

                            if ($field_key === 'price') { ?>
                                <div class="lgl-filter-group">
                                    <label for="lgl_price_min">Min <?php echo esc_html($label); ?></label>
                                    <select name="price_min" id="lgl_price_min" class="lgl-select2" data-placeholder="Min <?php echo esc_attr($label); ?>">
                                        <option value="">Min <?php echo esc_html($label); ?></option>
                                        <?php foreach ($prices as $price) : ?>
                                            <option value="<?php echo esc_attr($price); ?>" <?php selected((float) $active_price_min, $price); ?>><?php echo esc_html(LGL_Shortcodes::format_price($price)); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="lgl-filter-group">
                                    <label for="lgl_price_max">Max <?php echo esc_html($label); ?></label>
                                    <select name="price_max" id="lgl_price_max" class="lgl-select2" data-placeholder="Max <?php echo esc_attr($label); ?>">
                                        <option value="">Max <?php echo esc_html($label); ?></option>
                                        <?php foreach ($prices as $price) : ?>
                                            <option value="<?php echo esc_attr($price); ?>" <?php selected((float) $active_price_max, $price); ?>><?php echo esc_html(LGL_Shortcodes::format_price($price)); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php } elseif ($field_key === 'listing-fuel-type') { ?>
                                <div class="lgl-filter-group">
                                    <label for="lgl_fuel_type"><?php echo esc_html($label); ?></label>
                                    <select name="listing-fuel-type" id="lgl_fuel_type" class="lgl-select2" data-placeholder="Any <?php echo esc_attr($label); ?>">
                                        <option value="">Any <?php echo esc_html($label); ?></option>
                                        <?php if (!is_wp_error($fuel_types)) foreach ($fuel_types as $term) : ?>
                                            <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($active_fuel_type, $term->slug); ?>><?php echo esc_html($term->name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php } elseif ($field_key === 'listing-chassis') { ?>
                                <div class="lgl-filter-group">
                                    <label for="lgl_chassis"><?php echo esc_html($label); ?></label>
                                    <select name="listing-chassis" id="lgl_chassis" class="lgl-select2" data-placeholder="Any <?php echo esc_attr($label); ?>">
                                        <option value="">Any <?php echo esc_html($label); ?></option>
                                        <?php if (!is_wp_error($chassis_list)) foreach ($chassis_list as $term) : ?>
                                            <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($active_chassis, $term->slug); ?>><?php echo esc_html($term->name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php } elseif ($field_key === 'listing-gearbox') { ?>
                                <div class="lgl-filter-group">
                                    <label for="lgl_gearbox"><?php echo esc_html($label); ?></label>
                                    <select name="listing-gearbox" id="lgl_gearbox" class="lgl-select2" data-placeholder="Any <?php echo esc_attr($label); ?>">
                                        <option value="">Any <?php echo esc_html($label); ?></option>
                                        <?php if (!is_wp_error($gearboxes)) foreach ($gearboxes as $term) : ?>
                                            <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($active_gearbox, $term->slug); ?>><?php echo esc_html($term->name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php } elseif (isset($type_fields[$field_key])) {
                                // DYNAMIC META FIELD RENDERER
                                $active_val = isset($_GET[$field_key]) ? sanitize_text_field($_GET[$field_key]) : '';
                                $meta_values = LGL_Shortcodes::get_unique_meta_values($data_post_type, $field_key);
                            ?>
                                <div class="lgl-filter-group">
                                    <label for="lgl_<?php echo esc_attr($field_key); ?>"><?php echo esc_html($label); ?></label>
                                    <select name="<?php echo esc_attr($field_key); ?>" id="lgl_<?php echo esc_attr($field_key); ?>" class="lgl-select2" data-placeholder="Any <?php echo esc_attr($label); ?>">
                                        <option value="">Any <?php echo esc_html($label); ?></option>
                                        <?php foreach ($meta_values as $val) : ?>
                                            <option value="<?php echo esc_attr($val); ?>" <?php selected($active_val, $val); ?>><?php echo esc_html($val); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                        <?php }
                        } ?>
                    <?php endif; ?>

                    <div class="lgl-filter-group lgl-submit-group">
                        <button type="submit" class="lgl-search-submit">
                            <?php if ($post_type != false) { ?>
                                SEARCH NOW
                            <?php } else { ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                </svg>
                            <?php } ?>
                        </button>
                    </div>
                </div>
                <?php
                if ($post_type != false) {
                    // Evaluate if any core filter parameter is currently active
                    $has_active_filters = (!empty($active_make) || !empty($active_model) || !empty($active_condition) || !empty($active_berth) || !empty($active_price_min) || !empty($active_price_max));
                    $reset_display_style = $has_active_filters ? '' : 'display: none;';
                ?>
                    <button type="button" class="lgl-reset-filters-btn" aria-label="Reset all search filters" style="<?php echo $reset_display_style; ?>">
                        RESET ALL
                    </button>
                <?php } ?>
            </form>


        </div>


        <?php if ($is_offcanvas) : ?>
        </div><!-- /.lgl-offcanvas-body -->
    </div><!-- /.lgl-search-offcanvas -->
    <script>
        (function() {
            var toggleBtn = document.querySelector('.lgl-search-mobile-toggle');
            var panel = document.getElementById('lgl-search-offcanvas');
            var backdrop = document.getElementById('lgl-search-backdrop');
            var closeBtn = panel ? panel.querySelector('.lgl-offcanvas-close') : null;
            var searchForm = document.getElementById('lgl-search-form');

            if (!toggleBtn || !panel || !backdrop) return;

            function openPanel() {
                panel.classList.add('is-open');
                backdrop.classList.add('is-visible');
                document.body.classList.add('lgl-offcanvas-open');
                toggleBtn.setAttribute('aria-expanded', 'true');
                // Shift focus to the close button for keyboard accessibility
                if (closeBtn) closeBtn.focus();
            }

            function closePanel() {
                panel.classList.remove('is-open');
                backdrop.classList.remove('is-visible');
                document.body.classList.remove('lgl-offcanvas-open');
                toggleBtn.setAttribute('aria-expanded', 'false');
                toggleBtn.focus();
            }

            toggleBtn.addEventListener('click', openPanel);
            if (closeBtn) closeBtn.addEventListener('click', closePanel);
            backdrop.addEventListener('click', closePanel);

            // Intercept form submission to automatically collapse the mobile offcanvas
            if (searchForm) {
                searchForm.addEventListener('submit', function() {
                    if (panel.classList.contains('is-open')) {
                        closePanel();
                    }
                });
            }

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && panel.classList.contains('is-open')) {
                    closePanel();
                }
            });
        })();
    </script>
<?php endif; ?>