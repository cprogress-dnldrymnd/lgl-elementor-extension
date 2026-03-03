<?php

/**
 * Standardized partial for a single vehicle result card.
 * Context: Runs within the standard WordPress loop.
 */

$post_id = get_the_ID();
$condition = get_post_meta($post_id, 'condition', true);
$price = get_post_meta($post_id, 'price', true);
$berth = get_post_meta($post_id, 'berth', true);
$mileage = get_post_meta($post_id, 'mileage', true);
$year = get_post_meta($post_id, 'year', true);
$link = get_the_permalink();
$title = get_the_title();
?>

<article <?php post_class('bt-post car type-car status-publish has-post-thumbnail hentry'); ?>>
    <div class="bt-post--inner">
        <div class="bt-post--thumbnail">
            <div class="bt-post--featured">
                <a href="<?php echo esc_url($link); ?>">
                    <div class="bt-cover-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large'); ?>
                        <?php else: ?>
                            <img src="" alt="Placeholder">
                        <?php endif; ?>
                    </div>
                </a>
            </div>
            <div class="bt-post--icon-btn">
                <a class="bt-icon-btn bt-car-wishlist-btn" href="#" data-id="<?php echo esc_attr($post_id); ?>">
                </a>
                <a class="bt-icon-btn bt-car-compare-btn" href="#" data-id="<?php echo esc_attr($post_id); ?>">
                </a>
            </div>
        </div>

        <div class="bt-post--infor">
            <div class="bt-post--body">
                <span class="bt-value"><?php echo esc_html($condition); ?></span>
            </div>

            <div class="bt-post--info-inner">
                <div class="bt-post--price">
                    $<?php echo esc_html(number_format((float)$price, 2)); ?>
                </div>
                <h3 class="bt-post--title">
                    <a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a>
                </h3>
            </div>

            <div class="bt-post--meta">
                <div class="bt-post--meta-row">
                    <div class="bt-post--meta-col">
                        <div class="bt-post--meta-item bt-post--fuel-type">
                            <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false">
                                <rect width="14" height="16" fill="url(#pattern0_461_2)"></rect>
                                <defs>
                                    <pattern id="pattern0_461_2" patternContentUnits="objectBoundingBox" width="1" height="1">
                                        <use xlink:href="#image0_461_2" transform="matrix(0.0336134 0 0 0.0294118 -0.00420168 0)"></use>
                                    </pattern>
                                    <image id="image0_461_2" width="30" height="34" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAiCAYAAABIiGl0AAAABHNCSVQICAgIfAhkiAAAArRJREFUWEe9V91x2kAQ3pX8HncQxZDn4AosKjB+R45SQewKIlcQXIFlmwJMBYEKojwHEjoIfke67OokJNDP3RFNdoYZ6XS73+3e/nwgmEh/7ILAj6Ti0s/JVOeAEMHWvod1uNY1h1obHd8BO37IAJtVECZ0gDs6wEZlVw383h9AEn8jQ6cqY+l3ISJIToYq8HZgxz8lT38fgC7oPQQUMqwJDijUN/T0tnSwF1g9X7UdtB24Nw4B0jvN5RMZpLUaqewVV7CavjSBNwNLb//sFBHvYPkUtIa7583p+8Uu5L+m5+bAPc8nJU4olleIbUd1byCznvNBSmy/a8r0Zo/71wElypfMxIxCPGr1Nv/Y80QRJTGE5ZSjUBE9YJ0wF8BFuPFfgQH+o8f7d7zJ7qu9MXRyx8dlNSeWm0Yd4QcsnwfmWc0aunUsD/mVNPwC6Ng6ZgvSYERP5a5EyYOlziWocyF3LqfknTIndHs1Z+obrXLiEG9tV1XzamDpOU+nkJ5kV2oUvIfVE3uvFD3g3EyatXSPSINBwIdsmYYGRhBbk+7nsfL85hvMPDa333wpHdoyMqX2uH99CUnCJeNqWN6kDMQ+mcHPkMvQ0GOuX2v7OatPPcpThVhT0gWU5Y916FWPTTmWKgwNHGwfeH8wlE3OyHsicWIDFoWyVqzsKsSoVGr5TtKzh+XwF8CHk4VVeA5v0/pU0tW9s8iGM6G1y9L6mibceW5LAlfZ5CuxyFETe1BFd/f9zLuhKcXDI5c5MZlh6lO6cjiFLDqZIiuPB5dTC7M+zNxZignN0UWvYZ8Ix7BJXcB8Xw0zYWAm3VkS6E8XU2zoe9Eu2wXcIpyNv6fThkWIACycGxvVURAQ0DY3v072uODBOga62bNgYK5RPXbRDSiH9hGzvx0hvZV5VWcQNYYW1Ej8v5uqG8L2dcLZAAAAAElFTkSuQmCC"></image>
                                </defs>
                            </svg>
                            <span class="bt-label">Berth</span><span class="bt-value"><?php echo esc_html($berth ? $berth : 'N/A'); ?></span>
                        </div>
                    </div>
                    <div class="bt-post--meta-col">
                        <div class="bt-post--meta-item bt-post--mileage">
                            <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false">
                                <rect width="17" height="16" fill="url(#pattern0_461_3)"></rect>
                                <defs>
                                    <pattern id="pattern0_461_3" patternContentUnits="objectBoundingBox" width="1" height="1">
                                        <use xlink:href="#image0_461_3" transform="scale(0.0294118 0.03125)"></use>
                                    </pattern>
                                    <image id="image0_461_3" width="34" height="32" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACIAAAAgCAYAAAB3j6rJAAAABHNCSVQICAgIfAhkiAAAAb9JREFUWEftWL1OwlAUPqeEONrVTWmNxkmfAN5A3Ftk1UUmHcVRJ110FegD6BNYnkAmo7Ho6CiOBuF47i1YpJaSok1M7hmatOfvu19PTvlAiLKctQOI5+zuAtAatB0vMnaSw7BMALzjkCwQ7cKTc/FTOEbWMOx99h1Lf59W4dl5SARkyVoBDe8HuQfQbpxMBmLaeW64zkG6DERYYEayPhB4ZFbmEgEBfAcNlmUuURcIXgZ1OgywBV6j6bdbLOugfdxwUwEiMMQj8OpV+cCwXb7mkwGBJrNQkLlmqcpgDsfquNDLbCEY1iXj2Q43wTNo1yvyec66DQGdFhVRi+diwz9Q6ZRp2QunUo2B2K/s8F9H2Fw+gZ4YxLCeAIPY4VufmbB1BBCa9nB/GaeAjLOrGIljRC6XFO1rN428Gqrx96ScIgjeK8EOC4CMbtK00IxsWgVEkq4YGZ89xYhiJG4fqRlRM/K/ZwTgmvVHMe4Qv+o37Cuutylqiq+v+Jk/LxsQVaT6SsOEqkRknSPtbYLASgPNsIcQWEJyZnqCoqSSclbETZacxeDfANMqQJ/1r4ZRqm/Wht/z+8QinBWg57jC8QnCkuRI6X88sAAAAABJRU5ErkJggg=="></image>
                                </defs>
                            </svg>
                            <span class="bt-label">Year</span><span class="bt-value"><?php echo esc_html($year ? $year : 'N/A'); ?></span>
                        </div>
                    </div>
                    <div class="bt-post--meta-col">
                        <div class="bt-post--meta-item bt-post--transmission">
                            <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false">
                                <rect width="17.5" height="14" fill="url(#pattern0_461_4)"></rect>
                                <defs>
                                    <pattern id="pattern0_461_4" patternContentUnits="objectBoundingBox" width="1" height="1">
                                        <use xlink:href="#image0_461_4" transform="scale(0.0285714 0.0357143)"></use>
                                    </pattern>
                                    <image id="image0_461_4" width="35" height="28" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAcCAYAAADr9QYhAAAABHNCSVQICAgIfAhkiAAAA39JREFUSEulV01a20AM1ZDsgRNgarounIBwgpI9BnOCwgkIJyicAAPZl5yA5ASl6xKanqBhTZzpkzXG479kHPR9iR17pHl60kiKoqbyOdwlHZ+Qpl2j2jHXKa5Pyb3WDzRvD2gSTZqYV06LvXCDWvF3rD3EZ8NJR0AxuIhe+tcuOovBMIi12TdS6qwCxAi7wXPFzGzhg980JUUeWPtS2JzX9Wh8d7sIVD0YDkc8uwGQNBxshzeMKG49IAQcFiI/GOJ7H+su6fmulzzzQo/W4kMAYycYaCqse/6uW0BWDWbnqENa/bDYGJHSPXru88Z5qQJjr/CDED8ZpIDi0M3b3ap8KoMRII/v9jSd08v9VQlE+sAPHnD7FaGpXyc5FyXrRKZgaLvIUB4Mh2YeMxA7SU9pfM+G6oX1fkdykurED27wilkSYYZe+nv28gyMJOtjIUdSzS6N+8zAauIfg1n9zSi/4rpu7iM4epoazcDsHPeA9kJQg3IFKonYG5YRlDqrIYGWH+hEV9EvmrU6CBk7ti/P9EGaiwJGYvoHdxyebGP/CHUlOdbsQbQ6mIQZD3kSJnki+00MQ0PYPhCsLP4RNlInRaQrb+6iaEfCsGPABP9KrLgY/MiaHDv6FjkZKpIT9NPYXX5yXAFwiDVqy6JWkEVkilBtKrIzPW5t1lVHVwzJOrtWWQlasiE5ycUVorsAY8r5R0+MvdNO8GT60yuS1qt1UELFKQJM6tICI3FrxEDV4k/BGY4Fd/hkg/d+VWfYD7iErKdgpAYQTcyH7zmG3cbA8iXiL2x4JRvcRFtxWr/4NTdiLikDZiYFk9dba+0tLfHFnez8q8sVm7m8/sgGg86shsn7uZ4ubI5VlInHXDhZ6is2s9eecyEV0TrEN3d0MJMl2wC08iTXXARIBEUp8dKRJ06GfFPjTAKbEaDcRRcaSzyML3BqMi8TBXWNia7wrMaSfZrQD8GM1SCb1Jl0jinv4144ZfCSZEaOrlaB8/kxADtDa8ScINzbjiGSqBC9Qmcj7U0c362qgafSaFWFtRke3y//15FzSEIrSrnjppcPUvl+FoGVCCfwygxm1fWl6JU9KZgyYs8zPDbyEXOjOWsj+W1cqm6OlazyZ3RmTcvNMzkJ2cSWHCSH8s/rMma5d+2mZaA8kL+BmfQ/kUsWspftmUez9lMjPQb0hrZj1aP/jSCoRa29TVAAAAAASUVORK5CYII="></image>
                                </defs>
                            </svg>
                            <span class="bt-label">Mileage</span><span class="bt-value"><?php echo esc_html($mileage ? $mileage : 'N/A'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bt-post--readmore">
                <a href="<?php echo esc_url($link); ?>">
                    View Details
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 12H20M20 12L16 8M20 12L16 16" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</article>