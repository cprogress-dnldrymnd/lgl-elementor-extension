<?php
$data = $args['data'];
?>

<div class="bt-elwg-cars-search-inner">
    <div class="bt-elwg-cars-search--header">
        <?php if ($args['layout'] == 'style-1') : ?>
            <svg width="96" height="49" viewBox="0 0 96 49" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <rect width="96" height="49" fill="url(#pattern0_374_2230)" />
                <defs>
                    <pattern id="pattern0_374_2230" patternContentUnits="objectBoundingBox" width="1" height="1">
                        <use xlink:href="#image0_374_2230" transform="scale(0.0104167 0.0204082)" />
                    </pattern>
                    <image id="image0_374_2230" width="96" height="49" xlink:href="data:image/png;base64,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" />
                </defs>
            </svg>

            <?php if (isset($data['car_form_search_heading']) && !empty($data['car_form_search_heading'])) : ?>
                <h3> <?php echo esc_attr($data['car_form_search_heading']) ?> </h3>
            <?php endif; ?>

            <?php if (isset($data['car_form_search_sub_heading']) && !empty($data['car_form_search_sub_heading'])) : ?>
                <p> <?php echo esc_attr($data['car_form_search_sub_heading']) ?> </p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="bt-elwg-cars-search--form">
        <form class="bt-car-search-form" action="#" method="get">
            <?php
            // Read existing car_price=MIN-MAX if present
            $car_price = isset($_GET['car_price']) ? sanitize_text_field($_GET['car_price']) : '';
            $parts     = array_map('trim', explode('-', $car_price));
            $price_min = $parts[0] ?? '';
            $price_max = $parts[1] ?? '';

            // Price dropdown settings (adjust if you want)
            $price_step = 1000;
            $price_from = 0;
            $price_to   = 200000;
            ?>

            <?php
            $condition_value = isset($_GET['car_condition']) ? sanitize_text_field($_GET['car_condition']) : '';
            $conditions = get_terms([
                'taxonomy'   => 'car_condition',
                'hide_empty' => false,
            ]);
            ?>
            <div class="bt-form-field bt-field-type-select bt-field-car-condition">
                <select name="car_condition">
                    <option value=""><?php echo esc_html__('Condition', 'autoart'); ?></option>
                    <?php if (!is_wp_error($conditions) && !empty($conditions)) : ?>
                        <?php foreach ($conditions as $term) : ?>
                            <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($condition_value, $term->slug); ?>>
                                <?php echo esc_html($term->name); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <?php
            // 2) Make
            $field_name  = __('Leisure Vehicle Type', 'autoart');
            $field_value = isset($_GET['car_categories']) ? $_GET['car_categories'] : '';
            autoart_cars_field_select_html('car_categories', $field_name, $field_value);

            // 3) Make
            $field_name  = __('Make', 'autoart');
            $field_value = isset($_GET['car_make']) ? $_GET['car_make'] : '';
            autoart_cars_field_select_html('car_make', $field_name, $field_value);

            // 4) Model
            $field_name  = __('Model', 'autoart');
            $field_value = isset($_GET['car_model']) ? $_GET['car_model'] : '';
            autoart_cars_field_select_html('car_model', $field_name, $field_value);

            // 5) Berth (your taxonomy)
            $field_name  = __('Berth', 'autoart');
            $field_value = isset($_GET['car_berth']) ? $_GET['car_berth'] : '';
            autoart_cars_field_select_html('car_berth', $field_name, $field_value);
            ?>

            <!-- 6) Price (min.) -->
            <div class="bt-form-field bt-field-type-select bt-field-car-price-min">
                <select name="car_price_min" id="car_price_min">
                    <option value=""><?php echo esc_html__('Price (min.)', 'autoart'); ?></option>
                    <?php for ($p = $price_from; $p <= $price_to; $p += $price_step): ?>
                        <option value="<?php echo esc_attr($p); ?>" <?php selected((string)$price_min, (string)$p); ?>>
                            <?php echo esc_html(number_format_i18n($p)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="bt-form-field bt-field-type-select bt-field-car-price-max">
                <select name="car_price_max" id="car_price_max">
                    <option value=""><?php echo esc_html__('Price (max.)', 'autoart'); ?></option>
                    <?php for ($p = $price_from; $p <= $price_to; $p += $price_step): ?>
                        <option value="<?php echo esc_attr($p); ?>" <?php selected((string)$price_max, (string)$p); ?>>
                            <?php echo esc_html(number_format_i18n($p)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Hidden field: the theme understands car_price=MIN-MAX -->
            <input type="hidden" name="car_price" id="bt_car_price_combined" value="<?php echo esc_attr($car_price); ?>">
            <div class="bt-form-field bt-field-submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <path d="M14.4792 14.4935L19.25 19.25M16.5 9.625C16.5 13.4219 13.4219 16.5 9.625 16.5C5.82804 16.5 2.75 13.4219 2.75 9.625C2.75 5.82804 5.82804 2.75 9.625 2.75C13.4219 2.75 16.5 5.82804 16.5 9.625Z" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <input type="submit" class="btn btn-primary" value="Search Now">
            </div>
        </form>

        <?php if (!empty($data['cars_top_search']) && isset($data['cars_top_search'])) : ?>
            <div class="bt-elwg-cars-search--form-top-search">
                <?php if (!empty($data['top_search_title']) && isset($data['top_search_title'])) : ?>
                    <p> <?php echo esc_html($data['top_search_title'])  ?> </p>
                <?php endif; ?>

                <div class="bt-elwg-cars-search--form-top-search-inner">
                    <?php foreach ($data['cars_top_search'] as $index => $item) : ?>
                        <?php if (!empty($item['top_search_text']) && !empty($item['top_search_link'])) : ?>
                            <div class="item-top-search">
                                <a href="<?php echo esc_url($item['top_search_link']) ?>">
                                    <?php echo esc_html($item['top_search_text']) ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($args['layout'] == 'style-1') : ?>
        <div class="bt-elwg-cars-search--cta">
            <?php if (!empty($data['car_form_search_cta_link']) && isset($data['car_form_search_cta_link']) && !empty($data['car_form_search_cta_text']) && isset($data['car_form_search_cta_text'])) : ?>
                <a href="<?php echo esc_url($data['car_form_search_cta_link']) ?>">
                    <span> <?php echo esc_attr($data['car_form_search_cta_text']) ?> </span>
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 6.35 6.35" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                        <g>
                            <path d="M2.258 1.315a.265.265 0 0 0-.174.469L3.703 3.17l-1.62 1.386a.265.265 0 1 0 .345.4L4.28 3.373a.265.265 0 0 0 0-.403L2.428 1.382a.265.265 0 0 0-.17-.067z" fill="#000000" opacity="1" data-original="#000000"></path>
                        </g>
                    </svg>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>