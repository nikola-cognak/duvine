<?php

// the permalink for this
$tourlink = get_the_permalink();
$tourlink_encoded = urlencode($tourlink);

// the collections
$collections = get_field('d_tour_collection');
// Ensure $collections is an array
if (!is_array($collections)) {
    $collections = [];
}
// check is tour in challenge collection
$challengeTour = in_array(174, $collections, true) || in_array(175, $collections, true) || in_array(176, $collections, true);

// some checks
$privatetour = get_field('d_private_only');

// if this is not a private tour, then need to load data from Centaur XML
$centaur_id = '';
if (!$privatetour) {
    $centaur_id = trim(get_field('d_tour_centaur_id'));

    if ($centaur_id) {
        $centaur_data = get_tour_data_from_centaur($centaur_id);
        if (false === $centaur_data) {
            error_log('No tour data from get_tour_data: '.$centaur_id);
        } else {
            error_log('got back data for this tour: '.$centaur_id);
            // echo 'centaur: <br>';
            // print_r($centaur_data);
        }
    }
}

// location/weather info
$weather_location = get_field('d_tour_location');
if ($weather_location) {
    $weather_location = end($weather_location);
}
// error_log( 'THE LOCATION:');
// error_log( print_r( $location, true ) );

// pages
$trip_planning_fieldgroup = get_field('d_plan_trip_page', 'option');
$plan_your_trip_page = false;

if ($trip_planning_fieldgroup) {
    $collections = get_field('d_tour_collection');
    if($challengeTour){
        $plan_your_trip_page = $trip_planning_fieldgroup['challenge'];
    } elseif (is_array($collections) && in_array(50658, $collections, true)) {
        $plan_your_trip_page = $trip_planning_fieldgroup['villa'];
    } elseif (duvine_is_family_tour($collections)) {
        $plan_your_trip_page = $trip_planning_fieldgroup['family'];
    } elseif ($privatetour) {
        $plan_your_trip_page = $trip_planning_fieldgroup['private'];
    } else {
        $plan_your_trip_page = $trip_planning_fieldgroup['scheduled'];
    }

}
?>

<nav class="tournav <?php echo $challengeTour ? 'tour_challange' : ''; ?>" id="tour-main-navigation">
    <p class="tournav__title">On this page:</p>
    <ul class="menu tournav__list"></ul>
    <?php if ($plan_your_trip_page) { ?>
        <a href="<?php echo $plan_your_trip_page['url']; ?>" class="cta tournav__plan">Start Planning</a>
    <?php } ?>
    <a href="/request-a-brochure/" class="cta tournav__plan2" style="<?php echo !$plan_your_trip_page ? 'margin-top: 40px;' : ''; ?>">Brochure Request</a>
</nav>



<?php if ($herovideo = get_field('d_hero_video')) { ?>
    <header class="tourhero">
        <div style="padding:56.25% 0 0 0;position:relative;width:100%;"><iframe src="https://player.vimeo.com/video/<?php echo $herovideo; ?>?h=0fb282a0ab&title=0&byline=0&portrait=0&background=1&muted=1&controls=0&autoplay=1&loop=1" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div>
    </header>
<?php } elseif ($hero = duvine_get_url_from_object(get_field('d_hero_image'), 'banner_hero_page')) { ?>
	<?php
    sk_the_field('d_hero_image', [
        'before' => '<header class="tourhero">',
        'after' => '</header>',
        'filter' => 'sk_img_markup',
        'filter_args' => [
            'img_size' => 'banner_hero',
        ],
    ]);
    ?>
<?php } ?>


<div class="infobox__container l-container">
    <div class="infobox">
        <?php
            duvine_tour_flags();

duvine_tour_breadcrumbs();

the_title('<h1 class="infobox__title">', '</h1>');

sk_the_field('d_tour_subtitle', [
    'before' => '<p class="infobox__subtitle">',
    'after' => '</p>',
]);
?>

        <div class="infobox__meta">
            <?php
        sk_the_field('d_tour_duration', [
            'before' => '<span class="tourmeta">',
            'after' => '</span>',
        ]);
?>

<?php
$tooltip_activity_level = duvine_get_tooltip('d_tooltip_activity_level', 'light');
$tooltip_price_from = duvine_get_tooltip('d_tooltip_price_from', 'light');
$tourDates = duvine_get_tour_dates();

if ($privatetour || !is_array($tourDates)) {
    sk_the_field('d_cycling_level', [
        'before' => '<span class="tourmeta">',
        'after' => "{$tooltip_activity_level}</span>",
        'filter' => 'duvine_cycling_level',
    ]);
} else {
    // get level from Centaur XML feed
    if ($centaur_id) {
        $level = duvine_get_tour_level($centaur_id);
        $level = ['value' => $level];
    } else {
        $level = ['value' => 0];
    }
    echo '<span class="tourmeta">';
    echo duvine_render_cycling_level($level);
    echo $tooltip_activity_level.'</span>';
}

// $att = 'dates';
// $centaur_dates = $centaur_data[0]->$att;

$minPrice = duvine_get_minimum_price();

if ($minPrice) {
    echo '<span class="tourmeta"><strong>Price From: </strong>'.$minPrice.$tooltip_price_from.'</span>';
}

if ($collections) {
    echo '<span class="tourmeta">';
    echo apply_filters('duvine_tour_collection', $collections);
    echo '</span>';
}

if (get_field('d_non_rider')) {
    $tooltip_nonrider = duvine_get_tooltip('d_tooltip_nonrider', 'light');
    echo '<span class="tourmeta">Non-Rider Options'.$tooltip_nonrider.'</span>';
}

?>
        </div>


        <div class="infobox__cta <?php echo $challengeTour ? 'tour_challange' : ''; ?>">
            <?php if ($privatetour) { ?>
                <?php if ($plan_your_trip_page) { ?>
                    <a href="<?php echo $plan_your_trip_page['url']; ?>" class="cta cta--hoverwhite">Book Now</a>
                <?php } ?>
            <?php } else { ?>
                <a href="#tour-dates-and-availability" class="cta cta--hoverwhite">Book Now</a>
            <?php } ?>

            <?php if ($plan_your_trip_page) { ?>
                <a href="<?php echo $plan_your_trip_page['url']; ?>" class="cta cta--hoverwhite">Contact Us</a>
            <?php } ?>
        </div>

    </div>
</div>

<div class="cta-wrapper-container l-container">
        <div class="infobox__cta <?php echo $challengeTour ? 'tour_challange' : ''; ?>">
                <?php if ($privatetour) { ?>
                <?php if ($plan_your_trip_page) { ?>
                <a href="<?php echo $plan_your_trip_page['url']; ?>" class="cta cta--hoverwhite">Book Now</a>
                <?php } ?>
                <?php } else { ?>
                <a href="#tour-dates-and-availability" class="cta cta--hoverwhite">Book Now</a>
                <?php } ?>
                <?php if ($plan_your_trip_page) { ?>
                <a href="<?php echo $plan_your_trip_page['url']; ?>" class="cta cta--hoverwhite">Contact Us</a>
                <?php } ?>
        </div>
</div>


<div class="tourmain l-container" id="tour-main-content">

    <?php $tourUinque = get_field('d_tour_unique'); ?>
    <?php $tourEats = get_field('d_tour_eat'); ?>
    <?php $tourDrinks = get_field('d_tour_drink'); ?>
    <?php $imagegallery = get_field('d_tour_gallery'); ?>
    <?php $featuredIn = get_field('d_as_featured_in'); ?>

    <section class="toursection toursection--socialtop <?php echo $challengeTour ? 'challenge_tour' : ''; ?>" data-section="Tour Highlights">

        <?php duvine_render_social_icons('toursection__social'); ?>

        <?php if ($tourUinque || $tourEats || $tourDrinks) { ?>
            <div class="d-column-container">
                <?php if ($tourUinque) { ?>
                    <div class="d-col d-col--1-2">
                        <h3 class="blockheader">Tour Highlights</h3>
                        <div class="d-content d-content--padtop">
                            <?php echo $tourUinque; ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($tourEats || $tourDrinks) { ?>
                    <?php $colClass = $tourUinque ? 'd-col--1-2' : 'd-flex-col'; ?>
                    <div class="d-col <?php echo $colClass; ?>">
                        <?php if ($tourEats) { ?>
                            <div class="toursection__subsection">
                                <h3 class="blockheader">Eat</h3>

                                <div class="d-content d-content--padtop toursection__flexsection">
                                    <div><?php echo $tourEats; ?></div>

                                    <?php if (has_term(17, 'culinary')) { ?>
                                        <div class="michelinstar__logo">
                                            <img class="no-block" src="<?php echo get_template_directory_uri(); ?>/img/Michelin-Star-Icon.png" alt="Michelin Star Restaurants">
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($tourDrinks) { ?>
                            <div class="toursection__subsection">
                                <h3 class="blockheader">Drink</h3>
                                <div class="d-content d-content--padtop">
                                    <?php echo $tourDrinks; ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if ($challengeTour) {
            duvine_render_ride_profile();
        }  ?>
        <?php duvine_render_featuredin($featuredIn); ?>

        <?php duvine_render_tourgallery($imagegallery); ?>
        <?php $herovideo = get_field('d_tour_gallery_video'); ?>
        <?php if ($herovideo && $challengeTour) { ?>
            <header class="tourgallery__image" style="padding-left: 0px;padding-right: 0px;">
                <div style="padding:56.25% 0 0 0;position:relative;width:100%;"><iframe src="https://player.vimeo.com/video/<?php echo $herovideo; ?>?h=0fb282a0ab&title=0&byline=0&portrait=1&muted=1&controls=1&autoplay=0&loop=1" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div>
            </header>
        <?php } ?>

    </section><!-- At a glance -->

    <section class="toursection <?php echo $challengeTour ? 'tour_challange' : ''; ?>" data-section="Itinerary">

        <header class="toursection__header toursection__header--flex <?php echo $challengeTour ? 'tour_challange' : ''; ?>">
            <h2 class="pageheader">Itinerary</h2>

            <!--<a href="#" onclick="window.print(); return false;" class="cta tour__print">Print Itinerary</a>-->
            <a href="/print-itinerary/?tour_id=<?php the_ID(); ?>" class="cta tour__print" target="_blank">Print Itinerary</a>
        </header>

        <?php if (get_field('d_intinerary')) { ?>
            <div class="duvine-accordion">
                <div class="t-right">
                    <a href="#" class="basiclink js-duvine-accordion-expandall" data-alt="Close All Days">Expand All Days</a>
                </div>

                <ul class="itinerary baselist">

                    <?php
            if (get_field('d_itinerary_pre_tour')) {
                duvine_render_optional_itinerary('pre');
            }

            $itineraryDay = 0;
            while (have_rows('d_intinerary')) {
                the_row();
                ++$itineraryDay;
                duvine_render_itinerary_day($itineraryDay);
            }

            if (get_field('d_itinerary_post_tour')) {
                duvine_render_optional_itinerary('post');
            }
            ?>

                </ul>
            </div>

            <?php duvine_text_tour_disclaimer(); ?>

            <br/><br/>

            <?php duvine_render_tourmap(); ?>

        <?php } ?>


        <?php
            // itinerary details text field
            sk_the_field('d_itinerary_details', [
                'before' => '<div class="quicksection">',
                'after' => '</div>',
            ]);
?>

        <?php $arrival_city = get_field('d_itinerary_arrival_city'); ?>
        <?php $arrival_location = get_field('d_itinerary_arrival_location'); ?>
        <?php $arrival_time = get_field('d_itinerary_arrival_time'); ?>

        <?php $departure_city = get_field('d_itinerary_departure_city'); ?>
        <?php $departure_location = get_field('d_itinerary_departure_location'); ?>
        <?php $departure_time = get_field('d_itinerary_departure_time'); ?>


        <?php if ($arrival_city || $arrival_location || $arrival_time || $departure_city || $departure_location || $departure_time) { ?>
            <div class="quicksection d-column-container">
                <div class="d-col d-col--1-2 arrival-items">
                    <h5 class="tertiaryheader">Arrival Details</h5><br>

                  <?php if ($arrival_city) { ?>
                  <span><strong>Airport City</strong>: <?php echo $arrival_city; ?></span>
                  <?php } ?>

                  <?php if ($arrival_location) { ?>
                    <span><strong>Pick-Up Location</strong>: <?php echo $arrival_location; ?></span>
                    <?php } ?>

                    <?php if ($arrival_time) { ?>
                    <span><strong>Pick-Up Time</strong>: <?php echo $arrival_time; ?></span>
                    <?php } ?>
                </div>

                <div class="d-col d-col--1-2 departure-items">
                    <h5 class="tertiaryheader">Departure Details</h5><br>
                    <?php if ($departure_city) { ?>
                    <span><strong>Airport City</strong>: <?php echo $departure_city; ?></span>
                    <?php } ?>

<?php if ($departure_location) { ?>
                    <span><strong>Drop-Off Location</strong>: <?php echo $departure_location; ?></span>
                    <?php } ?>

<?php if ($departure_time) { ?>
                    <span><strong>Drop-Off Time</strong>: <?php echo $departure_time; ?></span>
                    <?php } ?>
                </div>
            </div>
		<br>
        <?php echo duvine_text_tour_arrival_departure_disclaimer(); ?>
        <?php } ?>

        <?php
    // straight talk - in ACF field group "Tour Details"
    $straightTalkHeader = sk_get_field('d_tour_always_unique_header', ['before' => '<h5 class="tertiaryheader">', 'after' => '</h5>']);
sk_the_field('d_tour_always_unique', [
    'before' => '<div class="quicksection">'.$straightTalkHeader.'<div class="d-content d-content--padtop">',
    'after' => '</div></div>',
]);

// the logistics content. In ACF field group "Tour Details"
sk_the_field('d_tour_logistics', [
    'before' => '<div class="quicksection quicksection--printonly"><h5 class="tertiaryheader">Additional Details</h5><div class="d-content d-content--padtop">',
    'after' => '</div></div>',
]);
?>


        <?php if ($privatetour && $weather_location) {
            $weather_data = get_field('d_location_weather', $weather_location);

            // look through all the monthly data. Make sure there are 12 months, and only numerics. If not, don't show the weather
            $show_weather_data = true;
            $month_count = 0;
            $data = $weather_data['d_location_weather_data'];
            if ($data) {
                foreach ($data as $i => $item) {
                    ++$month_count;
                    if (!is_numeric($item['d_location_month_high']) || !is_numeric($item['d_location_month_low']) || !is_numeric($item['d_location_month_rain'])) {
                        $show_weather_data = false;

                        break;
                    }
                }
            }

            // only show section if there is actual weather data
            if ($show_weather_data && 12 === $month_count) {
                ?>

        <div class="quicksection private-weather<?php if ('' !== $straightTalkHeader) {
            echo ' border';
        } ?>">
            <header class="toursection__header"><h2 class="tertiaryheader">Weather by Month</h2></header>
            <div class="quicksection weather__widget">
                <div class="duvine-accordion">
                    <?php if ($weather_data['d_location_weather_sub_title']) { ?>
                    <div class="accordion__header js-duvine-accordion-trigger">
                        <div class="accordion-title"><?php echo $weather_data['d_location_weather_sub_title']; ?></div>
                    </div>
                    <p class="desktop-subtitle"><?php echo $weather_data['d_location_weather_sub_title']; ?></p>
                    <?php } ?>
                    <div class="accordion__content">
                        <div class="weather-table">
                            <div class="weather-degree-toggle">
                                <a href="#">View in &deg;<span>C</span></a>
                            </div>
                            <?php
                            $months = [];
                $highs = [];
                $lows = [];
                $rain = [];

                $high_label = $weather_data['d_location_weather_high_label'];
                $low_label = $weather_data['d_location_weather_low_label'];
                $rain_label = $weather_data['d_location_weather_rain_label'];

                // load date in arrays, so we can display in proper order
                $data = $weather_data['d_location_weather_data'];
                if ($data) {
                    foreach ($data as $i => $item) {
                        $months[] = $item['d_location_month_name'];
                        $highs[] = $item['d_location_month_high'];
                        $lows[] = $item['d_location_month_low'];
                        $rain[] = $item['d_location_month_rain'];
                    }

                    echo '<div class="mobile-column desktop-header">';
                    echo '<div class="cell mobile-header empty">&nbsp;</div>';
                    for ($i = 0, $iMax = count($months); $i < $iMax; ++$i) {
                        echo '<div class="cell">'.$months[$i].'</div>';
                    }
                    echo '</div>';

                    echo '<div class="mobile-column">';
                    echo '<div class="cell mobile-header temp">'.$high_label.' (&deg;<span>F</span>)</div>';
                    for ($i = 0, $iMax = count($highs); $i < $iMax; ++$i) {
                        echo '<div class="cell temperature"><span>'.$highs[$i].'</span>&deg;</div>';
                    }
                    echo '</div>';

                    echo '<div class="mobile-column">';
                    echo '<div class="cell mobile-header temp">'.$low_label.' (&deg;<span>F</span>)</div>';
                    for ($i = 0, $iMax = count($lows); $i < $iMax; ++$i) {
                        echo '<div class="cell temperature"><span>'.$lows[$i].'</span>&deg;</div>';
                    }
                    echo '</div>';

                    echo '<div class="mobile-column">';
                    echo '<div class="cell mobile-header rain">'.$rain_label.' (<span>in</span>)</div>';
                    for ($i = 0, $iMax = count($rain); $i < $iMax; ++$i) {
                        $mm = round($rain[$i] * 25.4, 0);
                        echo '<div class="cell rain"><span class="inches">'.$rain[$i].'</span><span class="mm">'.$mm.'</span></div>';
                    }
                    echo '</div>';
                } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php } ?>

    </section><!-- Itinerary -->


    <?php if (!$privatetour) { ?>

        <a class="page-anchor" id="tour-dates"></a>
        <section class="toursection" data-section="Dates + availability" id="tour-dates-and-availability">
            <header class="toursection__header"><h2 class="pageheader">Dates + Availability</h2></header>
                <div class="<?= $challengeTour ? 'tour_challange' : '' ?>">
            <?php duvine_text_private_promo(); ?>
        </div>

            <?php $tourdates = duvine_get_tour_dates(); ?>

            <?php if ($tourdates && is_array($tourdates)) { ?>

                <div class="dateblock duvine-accordion">
                    <?php $first = true; ?>
                    <?php foreach ($tourdates as $year => $dates) { ?>
                        <div class="dateblock__year<?php if ($first) {
                            echo ' active';
                        } ?>">
                            <header class="<?php if (2025 === $year) {
                                echo 'accordion_2025';
                            } ?> accordion__header accordion__header--year js-duvine-accordion-trigger<?php if ($first) {
                                echo ' active';
                            } ?>">

                                <?php

                                    echo "<span class=\"accordion__headertitle\">{$year}</span>";

                        if (2025 === $year) {
                            echo duvine_text_tour_2025_date_disclaimer();
                        }

						$supplement_price = get_supplement_price_by_year($dates, $year);

                        $single_supplement_tooltip = duvine_get_tooltip('d_tooltip_single_supplement', 'light');

                        if ($supplement_price) {
                            echo '<span class="tour__singlesupplement">Single Supplement From: '.apply_filters('format_price', $supplement_price).$single_supplement_tooltip.'</span>';
                        }
                        ?>
                            </header>
                            <div class="accordion__content accordion__content--dates<?php if ($first) {
                                echo ' accordion__content--active';
                            } ?>">
                                <ul class="baselist">
                                    <li class="dateblock__row dateblock__row--header">
                                        <div class="dateblock__cell dateblock__cell--dates dateblock__colheader">Dates</div>
                                        <div class="dateblock__cell dateblock__cell--price dateblock__colheader">Price</div>
                                        <div class="dateblock__cell dateblock__cell--events dateblock__colheader">Special Events</div>
                                        <div class="dateblock__cell dateblock__cell--booking">&nbsp;</div>
                                    </li>

                                    <?php foreach ($dates as $date) { ?>

                                        <?php
                                        $date_flags['flags']['OnlineTourStatus'] = $date['flags']['OnlineTourStatus'];
                                        $date_flags['flags']['HoldInventory'] = $date['flags']['HoldInventory'];
                                        $date_flags['flags']['DepartureDateOnlineFlag'] = $date['flags']['DepartureDateOnlineFlag'];
                                        $date_flags['flags']['AvailableInventory'] = $date['flags']['AvailableInventory'];
                                        $date_flags['flags']['ThresholdInventory'] = $date['flags']['TotalRoomsInventory']['ThresholdInventory'];
                                        $date_flags['flags']['NumberOfPax'] = $date['flags']['NumberOfPax'];

                                        $tour_status = duvine_get_tour_date_status($date_flags['flags'], $date['label'], $year);
                                        // need to get phone number from the call to book string
                                        $text = duvine_get_text_call_to_book();
                                        $tel = '';
                                        if (($pos = strpos($text, 'tel:')) !== false) {
                                            $tel = substr($text, strpos($text, 'tel:') + 4, 12);
                                        }

                                        ?>

                                        <li class="dateblock__row<?php if ('sold_out' === $tour_status || 'sold_out_w_private' === $tour_status) {
                                            echo ' dateblock__row--soldout';
                                        } elseif ('book_now_limited_space' === $tour_status) {
                                            echo ' dateblock__row--limited_space';
                                        } ?>">
                                            <div class="dateblock__cell dateblock__cell--dates"><?php echo $date['label']; ?></div>
                                            <div class="dateblock__cell dateblock__cell--price"><?php echo apply_filters('format_price', $date['price']); ?></div>
                                            <div class="dateblock__cell dateblock__cell--events"><?php if (is_string($date['events']) && '' !== $date['events']) {
                                                echo $date['events'];
                                            } ?></div>
                                            <div class="dateblock__cell dateblock__cell--booking">
                                                <?php $centaurLink = duvine_get_centaur_link($centaur_id, $date['start_date']); ?>
                                                <?php if ('sold_out' === $tour_status) { ?>
                                                    <div><button class="cta cta--secondary cta--noclick sold-out">Sold Out</button></div>
                                                <?php } elseif ('sold_out_w_private' === $tour_status) { ?>
                                                    <div><button class="cta cta--secondary cta--noclick sold-out">Sold Out</button></div> <span class="note">Reserved by a private group</span>
                                                <?php } elseif ('book_now_limited_space' === $tour_status) { ?>
                                                    <a target="_blank" href="<?php echo $centaurLink; ?>" class="cta cta--hoverwhite">Book Now</a>
                                                    <span class="note">Space is limited!</span>
                                                <?php } elseif ('call_to_book_limited_space' === $tour_status) { ?>
                                                    <?php if ('' !== $tel) { ?>
                                                        <div><a class="cta cta--secondary call-to-book" href="tel:<?php echo $tel; ?>">Call to Book</a></div>
                                                    <?php } else { ?>
                                                        <div><button class="cta cta--secondary cta--noclick call-to-book">Call to Book</button></div>
                                                    <?php } ?>
                                                    <?php duvine_text_call_to_book_space_limited(); ?>
                                                <?php } elseif ('call_to_book' !== $tour_status && 'call_to_book_30_days' !== $tour_status && $centaurLink) { ?>
                                                    <a target="_blank" href="<?php echo $centaurLink; ?>" class="cta cta--hoverwhite">Book Now</a>
                                                <?php } else { ?>
                                                    <?php if ('' !== $tel) { ?>
                                                      <div><a class="cta cta--secondary call-to-book" href="tel:<?php echo $tel; ?>">Call to Book</a></div>
                                                    <?php } else { ?>
                                                      <div><button class="cta cta--secondary cta--noclick call-to-book">Call to Book</button></div>
                                                    <?php } ?>

                                                    <?php if ('call_to_book_30_days' === $tour_status) {
                                                        duvine_text_30_days_call_to_book();
                                                    } else {
                                                        duvine_text_call_to_book();
                                                    }
                                                } ?>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <?php $first = false; ?>
                    <?php } ?>
                </div>
				<?php if (false === $note2025) { ?>

				<?php } ?>
            <?php } else { ?>
                <?php duvine_text_dates_coming_soon('tourdates__noscheduled tourdates__noscheduled--background'); ?>
            <?php } ?>

            <?php if ($tourdates && ($cancellation_policy_link = get_field('d_cancellation_policy_page', 'option'))) { ?>
                <p class="tourdates__cancellation"><a href="<?php echo $cancellation_policy_link['url']; ?>" class="basiclink">See our cancellation policy</a></p>
            <?php } ?>

            <?php if ($weather_location) {
                $weather_data = get_field('d_location_weather', $weather_location);

                if ($weather_data['d_location_weather_data']) {
                    // look through all of the monthly data. Make sure there are 12 months, and only numerics. If not, don't show the weather
                    $show_weather_data = true;
                    $month_count = 0;
                    $data = $weather_data['d_location_weather_data'];
                    if ($data) {
                        foreach ($data as $i => $item) {
                            ++$month_count;
                            if (!is_numeric($item['d_location_month_high']) || !is_numeric($item['d_location_month_low']) || !is_numeric($item['d_location_month_rain'])) {
                                $show_weather_data = false;

                                break;
                            }
                        }
                    }

                    // only show section if there is actual weather data
                    if ($show_weather_data && 12 === $month_count) {
                        ?>
                <div class="quicksection weather__widget">
                    <h5 class="tertiaryheader">Weather by Month</h5>
                    <div class="duvine-accordion">
                        <?php if ($weather_data['d_location_weather_sub_title']) { ?>
                        <div class="accordion__header js-duvine-accordion-trigger">
                            <div class="accordion-title"><?php echo $weather_data['d_location_weather_sub_title']; ?></div>
                        </div>
                        <p class="desktop-subtitle"><?php echo $weather_data['d_location_weather_sub_title']; ?></p>
                        <?php } ?>
                        <div class="accordion__content">
                            <div class="weather-table">
                                <div class="weather-degree-toggle">
                                    <a href="#">View in &deg;<span>C</span></a>
                                </div>
                        <?php
                        $months = [];
                        $highs = [];
                        $lows = [];
                        $rain = [];

                        $high_label = $weather_data['d_location_weather_high_label'];
                        $low_label = $weather_data['d_location_weather_low_label'];
                        $rain_label = $weather_data['d_location_weather_rain_label'];

                        // load up date in arrays so we can display in proper order
                        $data = $weather_data['d_location_weather_data'];
                        if ($data) {
                            foreach ($data as $i => $item) {
                                $months[] = $item['d_location_month_name'];
                                $highs[] = $item['d_location_month_high'];
                                $lows[] = $item['d_location_month_low'];
                                $rain[] = $item['d_location_month_rain'];
                            }

                            echo '<div class="mobile-column desktop-header">';
                            echo '<div class="cell mobile-header temp">Month</div>';
                            for ($i = 0, $iMax = count($months); $i < $iMax; ++$i) {
                                echo '<div class="cell">'.$months[$i].'</div>';
                            }
                            echo '</div>';

                            echo '<div class="mobile-column">';
                            echo '<div class="cell mobile-header temp">'.$high_label.' (&deg;<span>F</span>)</div>';
                            for ($i = 0, $iMax = count($highs); $i < $iMax; ++$i) {
                                echo '<div class="cell temperature"><span>'.$highs[$i].'</span>&deg;</div>';
                            }
                            echo '</div>';

                            echo '<div class="mobile-column">';
                            echo '<div class="cell mobile-header temp">'.$low_label.' (&deg;<span>F</span>)</div>';
                            for ($i = 0, $iMax = count($lows); $i < $iMax; ++$i) {
                                echo '<div class="cell temperature"><span>'.$lows[$i].'</span>&deg;</div>';
                            }
                            echo '</div>';

                            echo '<div class="mobile-column">';
                            echo '<div class="cell mobile-header rain">'.$rain_label.' (<span>in</span>)</div>';
                            for ($i = 0, $iMax = count($rain); $i < $iMax; ++$i) {
                                if (!is_numeric($rain[$i])) {
                                    $rain[$i] = 0;
                                }
                                $mm = round($rain[$i] * 25.4, 0);
                                echo '<div class="cell rain"><span class="inches">'.$rain[$i].'</span><span class="mm">'.$mm.'</span></div>';
                            }
                            echo '</div>';
                        } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php } ?>
            <?php } ?>
        </section><!-- Dates and Availability -->

    <?php } ?>

    <section class="toursection" data-section="Inclusions + details">
	<?php 
		// Supress inclusions image on specific post by ID
		$excludeTourImages = array(61948); // Add any aditional IDs here to supress the image

		$currentTourId = get_the_ID();
	?>
        <header class="toursection__header" <?php if (in_array($currentTourId, $excludeTourImages)) {
            ?> style="margin-bottom: 0px" <?php
        } ?> ><h2 class="pageheader">Inclusions + Details</h2></header>

        <div class="d-column-container">

            <?php $tourAccomodations = $challengeTour ? get_field('d_challenge_tour_accomodations', 'option') : get_field('d_tour_accomodations', 'option'); ?>
            <?php $tourMeals = $challengeTour ? get_field('d_challenge_tour_meals', 'option') : get_field('d_tour_meals', 'option'); ?>
            <?php $tourGear = $challengeTour ? get_field('d_challenge_tour_gear', 'option') : get_field('d_tour_gear', 'option'); ?>
            <?php $tourSupport = $challengeTour ? get_field('d_challenge_tour_support', 'option') : get_field('d_tour_support', 'option'); ?>
            <?php $tourActivities = $challengeTour ? get_field('d_challenge_tour_activities', 'option') : get_field('d_tour_activities', 'option'); ?>

            <?php $tourImg = $challengeTour ? get_field('d_challenge_tour_img', 'option') : get_field('d_tour_img', 'option'); ?>
			
			<?php 

				if (!in_array($currentTourId, $excludeTourImages)) {
					if ($tourImg) { ?>
						<div class="d-col"><img src="<?php echo $tourImg; ?>" /></div>
					<?php }
				}
			?>

            <div class="d-col d-col--1-2">
                <?php if ($tourAccomodations) { ?>
                    <div class="quicksection">
                        <h4>Accommodations</h4>
                        <div class="d-content d-content--padtop"><?php echo $tourAccomodations; ?></div>
                    </div>
                <?php } ?>

                <?php if ($tourMeals) { ?>
                    <div class="quicksection">
                        <h4>Meals</h4>
                        <div class="d-content d-content--padtop"><?php echo $tourMeals; ?></div>
                    </div>
                <?php } ?>

                <?php if ($tourActivities) { ?>
                    <div class="quicksection">
                        <h4>Activities</h4>
                        <div class="d-content d-content--padtop"><?php echo $tourActivities; ?></div>
                    </div>
                <?php } ?>
            </div>

            <div class="d-col d-col--1-2">

                <?php if ($tourGear) { ?>
                    <div class="quicksection">
                        <h4>Gear</h4>
                        <div class="d-content d-content--padtop"><?php echo $tourGear; ?></div>
                    </div>
                <?php } ?>

                <?php if ($tourSupport) { ?>
                    <div class="quicksection">
                        <h4>Support</h4>
                        <div class="d-content d-content--padtop"><?php echo $tourSupport; ?></div>
                    </div>
                <?php } ?>

                <?php if ($tour_inclusions = get_field('d_included')) { ?>
                    <div class="quicksection">
                        <h4 class="tertiaryheader">Additional Inclusions</h4>
                        <div class="d-content d-content--padtop">
                            <ul>
                                <?php foreach ($tour_inclusions as $inclusion) { ?>
                                    <li><?php echo $inclusion; ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>

            </div>

        </div>

        <div class="d-column-container">

            <div class="d-col d-col--1-2">

                <?php
        sk_the_field('d_not_included', [
            'before' => '<div class="quicksection" style="margin-top: 28px;"><span style="font-size:14px;"><strong>NOT INCLUDED:</strong></span><br/><div class="d-content d-content--padtop">',
            'after' => '</div></div>',
            'filter' => 'list_items',
        ]);
?>

            </div>

            <div class="d-col d-col--1-2">

                <?php if ($faq_page = get_field('d_faq_page', 'option')) { ?>

                    <div class="quicksection <?php echo $challengeTour ? 'tour_challange' : ''; ?>" style="margin-top: 28px;">
                        <span style="font-size:14px;"><strong>HAVE MORE QUESTIONS?</strong></span><br/>
                        <p>See our FAQs page for answers to common questions.</p>
                        <a href="<?php echo $faq_page['url']; ?>" class="cta">See FAQs</a>
                    </div>
                <?php } ?>

            </div>

        </div>


    </section><!-- Inclusions and details -->



    <?php $bikes = get_field('d_tour_bikes'); ?>
    <?php if ($bikes && $challengeTour) { ?>
        <section class="toursection" data-section="<?php echo $challengeTour ? 'Bike' : 'Bikes'; ?>">

            <div class="d-column-container tour_bike_challange">
                <?php foreach ($bikes as $bike) { ?>
                    <?php if ('publish' === get_post_status($bike->ID)) { ?>
            <?php
            $bikeDescription = $bike->post_content;
                        $bikelink = get_the_permalink($bike);
                        $bikePhoto = duvine_get_url_from_object(get_field('bike_photo', $bike->ID), 'large');
                        $bikeStyle = $bikePhoto ? ' style="background-image: url('.$bikePhoto.')"' : '';
                        $bgImgClass = $bikePhoto ? ' bikeblock__image--hasphoto' : 'bikeblock__image--placeholder';
                        $bikeFrame = get_field('bike_frame', $bike->ID);
                        $bikeWheels = get_field('bike_wheels', $bike->ID);
                        $bikeTires = get_field('bike_tires', $bike->ID);
                        $bikeShifter = get_field('bike_components', $bike->ID);
                        $bikeCrankset = get_field('bike_front_cassette', $bike->ID);
                        $bikeCassette = get_field('bike_rear_cassette', $bike->ID);
                        // var_dump(get_field('bike_photo',$bike->ID));
                        ?>
            <div class="d-col-flex m-bike-title" style="justify-self: center;align-self: center;">
                    <h3 class="pageheader"><a href="<?php echo $bikelink; ?>"><?php echo $bike->post_title; ?></a></h3>
            </div>
            <div class="d-col d-col--1-2">
                <a href="<?php echo $bikelink; ?>" class="bikeblock__image <?php echo $challengeTour ? 'challenge-bike-image' : ''; ?>" >
                    <img src="<?php echo $bikePhoto; ?>" alt="<?php echo $bike->post_title; ?> " class="tour_bike-image"/>
                </a>
                        </div>
            <div class="d-col d-col--1-2">
                <div class="challenge-bike-container">
                    <div class="d-col-flex l-bike-title">
                        <h3 class="pageheader"><a href="<?php echo $bikelink; ?>"><?php echo $bike->post_title; ?></a></h3>
                    </div>

                    <div class="d-col d-column-container" >
                            <?php if ($manufacturer = get_field('bike_manufacturer', $bike->ID)) { ?>
                        <div class="bike-specification" style="min-width: 40%;">
                            <p class="bikeblock__meta"><strong style="font-weight: bold;">Manufacturer: </br></strong><?php echo $manufacturer->name; ?></p>
                        </div>
                            <?php } ?>

                            <?php if ($bikeType = get_field('bike_type', $bike->ID)) { ?>
                        <div class="bike-specification" >
                            <p class="bikeblock__meta"><strong style="font-weight: bold;">Type:</br> </strong><?php echo $bikeType->name; ?></p>
                        </div>
                            <?php } ?>

							<?php if ($bikeFrame) { ?>
                        <div class="bike-specification">
                            <p class="bikeblock__meta"><strong style="font-weight: bold;">Frame:</br> </strong><?php echo $bikeFrame; ?> </p>
                        </div>
                            <?php } ?>
							<?php if ($bikeWheels) { ?>
                        <div class="bike-specification" style="min-width: 40%;">
                            <p class="bikeblock__meta"><strong style="font-weight: bold;">Wheels:</br> </strong><?php echo $bikeWheels; ?> </p>
                        </div>
                            <?php } ?>
							<?php if ($bikeTires) { ?>
                        <div class="bike-specification">
                            <p class="bikeblock__meta"><strong style="font-weight: bold;">Tires:</br> </strong><?php echo $bikeTires; ?> </p>
                        </div>
                            <?php } ?>
							<?php if ($bikeShifter) { ?>
                        <div class="bike-specification">
                            <p class="bikeblock__meta"><strong style="font-weight: bold;">Shifter:</br> </strong><?php echo $bikeShifter; ?> </p>
                        </div>
                            <?php } ?>
							<?php if ($bikeCrankset) { ?>
                        <div class="bike-specification" style="min-width: 40%;">
                            <p class="bikeblock__meta"><strong style="font-weight: bold;">Crankset:</br> </strong><?php echo $bikeCrankset; ?> </p>
                        </div>
                            <?php } ?>
							<?php if ($bikeCassette) { ?>
                        <div class="bike-specification">
                            <p class="bikeblock__meta"><strong style="font-weight: bold;">Cassette:</br> </strong><?php echo $bikeCassette; ?> </p>
                        </div>
                            <?php } ?>

                </div>
                    <div class="bike-desctiprion-button" style="margin-bottom: 5px;">
                        <p onclick="showdescription(event);" class="bikeblock__meta" style="font-weight: bold; cursor: pointer;">Read more
                            <span class="chevron-down">
                        </p>
                    </div>

                        </div><!-- .d-col -->
                    <?php } ?>
                <?php break; ?>
                <?php } ?>
            </div><!-- .d-column-container -->
            </div>
            <div id="bike-description" >
                <p>
                    <?php echo $bikeDescription; ?>
                </p>
            </div>
        </section><!-- Bikes -->
<script>
    function showdescription(e){
        const chevron = e.currentTarget.children[0];
        const bikeDescription = document.getElementById("bike-description");
        console.log(bikeDescription.height);
        if(bikeDescription && bikeDescription.style.height && bikeDescription.style.height != '0px'){
            bikeDescription.style.height = '0px';
            chevron.style.transform = 'none';
        }else{
            bikeDescription.style.height = bikeDescription.scrollHeight + 'px';
            chevron.style.transform = 'scaleY(-1)';
        }
    }
</script>
    <?php } elseif ($bikes) { ?>
        <section class="toursection" data-section="Bikes">

            <header class="toursection__header"><h2 class="pageheader">Bikes</h2></header>


            <div class="d-column-container bikegrid">
                <?php foreach ($bikes as $bike) { ?>
                    <?php if ('publish' === get_post_status($bike->ID)) { ?>
                        <?php $bikelink = get_the_permalink($bike); ?>
                        <?php $bikePhoto = duvine_get_url_from_object(get_field('bike_photo', $bike->ID), 'bike_thumbnail'); ?>
                        <?php $bikeStyle = $bikePhoto ? ' style="background-image: url('.$bikePhoto.')"' : ''; ?>
                        <?php $bgImgClass = $bikePhoto ? ' bikeblock__image--hasphoto' : 'bikeblock__image--placeholder'; ?>
                        <div class="d-col d-col--1-3 bikeblock">
                            <a href="<?php echo $bikelink; ?>" class="bikeblock__image <?php echo $bgImgClass; ?>"<?php echo $bikeStyle; ?>></a>
                            <h4 class="tertiaryheader"><a href="<?php echo $bikelink; ?>"><?php echo $bike->post_title; ?></a></h4>

                            <?php if ($manufacturer = get_field('bike_manufacturer', $bike->ID)) { ?>
                                <p class="bikeblock__meta"><strong>Manufacturer: </strong><?php echo $manufacturer->name; ?></p>
                            <?php } ?>

                            <?php if ($bikeType = get_field('bike_type', $bike->ID)) { ?>
                                <p class="bikeblock__meta"><strong>Type: </strong><?php echo $bikeType->name; ?></p>
                            <?php } ?>

							<?php if ($pricing = get_field('pricing_text', $bike->ID)) { ?>
                                <p class="bikeblock__meta"><i><?php echo $pricing; ?></i></p>
                            <?php } ?>

                        </div><!-- .d-col -->
                    <?php } ?>
                <?php } ?>
            </div><!-- .d-column-container -->
        </section><!-- Bikes -->
    <?php } ?>

    <?php if (($reviewImage = get_field('d_tour_review_image')) && $challengeTour) { ?>
        <section class="toursection reviews-container" data-section="Reviews" style="border-top: 1px solid #bbb;" >
            <img class="desktop" src="<?php echo $reviewImage; ?>" />
            <img class="mobile" src="<?php echo get_field('d_tour_review_image_mobile'); ?>" />
        </section>
    <?php } ?>

    <?php if (have_rows('d_tour_guides_pdf') && $challengeTour) { ?>

       <section class="toursection" style="border-top: 1px solid #bbb;" data-section="Resources">
        <div class="d-column-container resources-container" style="justify-content: center;">
            <?php while (have_rows('d_tour_guides_pdf')) {
                the_row(); ?>
                <div class="d-col d-col--1-3 resources-item">
                    <h2 class="resources-title"><?php echo get_sub_field('guide_title'); ?> </h2>
                    <div class="resource-image">
                    <img style="width: 200px;" src="<?php echo get_sub_field('guide_image'); ?>" alt="guide image" />
                    </div>
                <a class="resources-cta"
                   href="<?php echo get_sub_field('guide_resource_url'); ?>" class="" target="_blank">DOWNLOAD THE <?php echo get_sub_field('guide_type'); ?>
                </a>
                </div>
        <?php } ?>
        </div>
    </section>
    <?php } ?>

<?php /*
    <?php if ($guides = get_field('d_tour_guides', $post->ID)) { ?>
        <section class="toursection" data-section="Tour Guides">

        <header class="toursection__header"><h2 class="pageheader">Tour Guides</h2></header>

        <div class="tourguides__grid d-column-container">
            <?php foreach ($guides as $guide) { ?>
                <?php if ('publish' === get_post_status($guide)) { ?>
                    <div class="tourguide d-col d-col--1-3">
                    <a href="<?php echo get_permalink($guide); ?>">
                        <?php $tourHeadshot = get_field('duvine_tour_image', $guide); ?>
                                <div class="tourguide__headshot">
                        <?php
                            if ($tourHeadshot && $challengeTour) {
                                sk_the_field('duvine_tour_image', [
                                    'id' => $guide,
                                    'default' => '<div class="tourguide__headshotplaceholder"></div>',
                                    'filter' => 'sk_img_markup',
                                    'filter_args' => [
                                        'img_size' => 'gridcell_image',
                                    ],
                                ]);
                            } else {
                                sk_the_field('duvine_headshot', [
                                    'id' => $guide,
                                    'default' => '<div class="tourguide__headshotplaceholder"></div>',
                                    'filter' => 'sk_img_markup',
                                    'filter_args' => [
                                        'img_size' => 'gridcell_image',
                                    ],
                                ]);
                            }
                
                ?>
                    </div>
                    <h3 class="tourguide__name tertiaryheader"><?php echo get_the_title($guide); ?></h3>
                </a>
                    </div>
                <?php }} ?>
            </div>
        </section>
    <?php } ?>
 */ ?>


</div><!-- .tourmain -->
