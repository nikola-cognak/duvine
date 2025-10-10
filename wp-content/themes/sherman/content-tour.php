<?php
/**
 * @package sherman
 */

// the permalink for this
$tourlink = get_the_permalink();
$tourlink_encoded = urlencode($tourlink);

// the collections
$collections = get_field('d_tour_collection');

// some checks
$privatetour = get_field('d_private_only');

// if this is not a private tour, then need to load data from Centaur XML
$centaur_id = '';
if( !$privatetour ) :
    $centaur_id = trim(get_field('d_tour_centaur_id'));

    if( $centaur_id ) :
        $centaur_data = get_tour_data_from_centaur($centaur_id);
        if( $centaur_data === false ) :
            error_log('No tour data from get_tour_data: '.$centaur_id);
        else : 
            error_log('got back data for this tour: '.$centaur_id);
            //echo 'centaur: <br>';
            //print_r($centaur_data);
        endif;
    endif;
endif;

// location/weather info
$weather_location = get_field('d_tour_location');
if( $weather_location) :
    $weather_location = end($weather_location);
endif;
//error_log( 'THE LOCATION:');
//error_log( print_r( $location, true ) );

// pages
$trip_planning_fieldgroup = get_field('d_plan_trip_page', 'option');
$plan_your_trip_page = false;

if($trip_planning_fieldgroup){
    $collections = get_field('d_tour_collection');
	if (is_array($collections) && in_array(50658, $collections)) {
		$plan_your_trip_page = $trip_planning_fieldgroup['villa'];
    } elseif(duvine_is_family_tour($collections)){
        $plan_your_trip_page = $trip_planning_fieldgroup['family'];
    } elseif($privatetour) {
        $plan_your_trip_page = $trip_planning_fieldgroup['private'];
    } else {
        $plan_your_trip_page = $trip_planning_fieldgroup['scheduled'];
    }   
}
?>

<nav class="tournav" id="tour-main-navigation">
    <p class="tournav__title">On this page:</p>
    <ul class="menu tournav__list"></ul>
    <?php if( $plan_your_trip_page ) : ?>
        <a href="<?php echo $plan_your_trip_page['url']; ?>" class="cta tournav__plan">Start Planning</a>
    <?php endif; ?>
</nav>


<?php
    sk_the_field('d_hero_image', array(
        'before'      => '<header class="tourhero">',
        'after'       => '</header>',
        'filter'      => 'sk_img_markup',
        'filter_args' => array(
            'img_size' => 'banner_hero'
        )
    ));
?>


<div class="infobox__container l-container">
    <div class="infobox">
        <?php
            duvine_tour_flags();

            duvine_tour_breadcrumbs();

            the_title('<h1 class="infobox__title">', '</h1>');

            sk_the_field('d_tour_subtitle', array(
                'before' => '<p class="infobox__subtitle">',
                'after'  => '</p>'
            ));
        ?>

        <div class="infobox__meta">
            <?php
                sk_the_field('d_tour_duration', array(
                    'before' => '<span class="tourmeta">',
                    'after'  => '</span>'
                ));
            ?>

            <?php
                $tooltip_activity_level = duvine_get_tooltip( 'd_tooltip_activity_level', 'light' );
                $tooltip_price_from     = duvine_get_tooltip( 'd_tooltip_price_from', 'light' );
                $tourDates = duvine_get_tour_dates();

                if($privatetour || !is_array($tourDates)) {
                    sk_the_field('d_cycling_level', [
                        'before' => '<span class="tourmeta">',
                        'after'  => "$tooltip_activity_level</span>",
                        'filter' => 'duvine_cycling_level'
                    ]);
                } else {
                    // get level from Centaur XML feed
                    if ($centaur_id) :
                        $level = duvine_get_tour_level($centaur_id);
                        $level = array('value' => $level);
                    else :
                        $level = array('value' => 0);
                    endif;
                    echo '<span class="tourmeta">';
                    echo duvine_render_cycling_level($level);
                    echo $tooltip_activity_level . '</span>';
                }

                //$att = 'dates';
                //$centaur_dates = $centaur_data[0]->$att;

                $minPrice = duvine_get_minimum_price();

                if( $minPrice ){
                    echo '<span class="tourmeta"><strong>Price From: </strong>' . $minPrice . $tooltip_price_from . '</span>';
                }

                if( $collections ){
                    echo '<span class="tourmeta">';
                    echo apply_filters('duvine_tour_collection', $collections);
                    echo '</span>';
                }

                if( get_field('d_non_rider') ){
                    $tooltip_nonrider = duvine_get_tooltip( 'd_tooltip_nonrider', 'light' );
                    echo '<span class="tourmeta">Non-Rider Options' . $tooltip_nonrider . '</span>';
                }

            ?>
        </div>


        <div class="infobox__cta">
            <?php if($privatetour) : ?>
                <?php if($plan_your_trip_page) : ?>
                    <a href="<?php echo $plan_your_trip_page['url']; ?>" class="cta cta--hoverwhite">Book Now</a>
                <?php endif; ?>
            <?php else : ?>
                <a href="#tour-dates-and-availability" class="cta cta--hoverwhite">Book Now</a>
            <?php endif; ?>

            <?php if( $plan_your_trip_page ) : ?>
                <a href="<?php echo $plan_your_trip_page['url']; ?>" class="cta cta--outline">Contact Us</a>
            <?php endif; ?>
        </div>

    </div>
</div>


<div class="tourmain l-container" id="tour-main-content">

    <?php $tourUinque   = get_field('d_tour_unique'); ?>
    <?php $tourEats     = get_field('d_tour_eat'); ?>
    <?php $tourDrinks   = get_field('d_tour_drink'); ?>
    <?php $imagegallery = get_field('d_tour_gallery'); ?>
    <?php $featuredIn   = get_field('d_as_featured_in'); ?>

    <section class="toursection toursection--socialtop" data-section="Tour Highlights">
        
        <?php duvine_render_social_icons( 'toursection__social' ); ?>

        <?php if( $tourUinque || $tourEats || $tourDrinks ) : ?>
            <div class="d-column-container">
                <?php if( $tourUinque ) : ?>
                    <div class="d-col d-col--1-2">
                        <h3 class="blockheader">Tour Highlights</h3>
                        <div class="d-content d-content--padtop">
                            <?php echo $tourUinque; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if( $tourEats || $tourDrinks ) : ?>
                    <?php $colClass = $tourUinque ? 'd-col--1-2' : 'd-flex-col'; ?>
                    <div class="d-col <?php echo $colClass; ?>">
                        <?php if( $tourEats ) : ?>
                            <div class="toursection__subsection">
                                <h3 class="blockheader">Eat</h3>

                                <div class="d-content d-content--padtop toursection__flexsection">
                                    <div><?php echo $tourEats; ?></div>

                                    <?php if( has_term(17, 'culinary')) : ?>
                                        <div class="michelinstar__logo">
                                            <img src="<?php echo get_template_directory_uri(); ?>/img/Michelin-Star-Icon.png" alt="Michelin Star Restaurants">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if( $tourDrinks ) : ?>
                            <div class="toursection__subsection">
                                <h3 class="blockheader">Drink</h3>
                                <div class="d-content d-content--padtop">
                                    <?php echo $tourDrinks; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php duvine_render_featuredin( $featuredIn ); ?>

        <?php duvine_render_tourgallery( $imagegallery ); ?>

    </section><!-- At a glance -->
    
    <section class="toursection" data-section="Itinerary">

        <header class="toursection__header toursection__header--flex">
            <h2 class="pageheader">Itinerary</h2>

            <!--<a href="#" onclick="window.print(); return false;" class="cta tour__print">Print Itinerary</a>-->
            <a href="/print-itinerary/?tour_id=<?php the_ID(); ?>" class="cta tour__print" target="_blank">Print Itinerary</a>
        </header>

        <?php if( get_field('d_intinerary') ) : ?>
            <div class="duvine-accordion">
                <div class="t-right">
                    <a href="#" class="basiclink js-duvine-accordion-expandall" data-alt="Close All Days">Expand All Days</a>
                </div>
                
                <ul class="itinerary baselist">

                    <?php
                        if( get_field('d_itinerary_pre_tour') ) {
                            duvine_render_optional_itinerary('pre');
                        }

                        $itineraryDay = 0;
                        while( have_rows( 'd_intinerary' ) ) {
                            the_row();
                            $itineraryDay++;
                            duvine_render_itinerary_day($itineraryDay);
                        }

                        if( get_field('d_itinerary_post_tour') ) {
                            duvine_render_optional_itinerary('post');
                        }
                    ?>

                </ul>
            </div>

            <?php duvine_text_tour_disclaimer(); ?>

            <br/><br/>
            
            <?php duvine_render_tourmap(); ?>

        <?php endif; ?>


        <?php
            // itinerary details text field
            sk_the_field('d_itinerary_details', array(
                'before' => '<div class="quicksection">',
                'after'  => '</div>'
            ));
        ?>

        <?php $arrival_city = get_field('d_itinerary_arrival_city'); ?>
        <?php $arrival_location = get_field('d_itinerary_arrival_location'); ?>
        <?php $arrival_time = get_field('d_itinerary_arrival_time'); ?>

        <?php $departure_city = get_field('d_itinerary_departure_city'); ?>
        <?php $departure_location = get_field('d_itinerary_departure_location'); ?>
        <?php $departure_time = get_field('d_itinerary_departure_time'); ?>


        <?php if( $arrival_city || $arrival_location || $arrival_time || $departure_city || $departure_location || $departure_time ) : ?>
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
        <?php endif; ?>

        <?php
            // straight talk - in ACF field group "Tour Details"
            $straightTalkHeader = sk_get_field('d_tour_always_unique_header', array('before' => '<h5 class="tertiaryheader">', 'after' => '</h5>'));
            sk_the_field('d_tour_always_unique', array(
                'before' => '<div class="quicksection">' . $straightTalkHeader . '<div class="d-content d-content--padtop">',
                'after'  => '</div></div>'
            ));

            // the logistics content. In ACF field group "Tour Details"
            sk_the_field('d_tour_logistics', array(
                'before' => '<div class="quicksection quicksection--printonly"><h5 class="tertiaryheader">Additional Details</h5><div class="d-content d-content--padtop">',
                'after'  => '</div></div>'
            ));
        ?>


        <?php if( $privatetour && $weather_location ) :
            $weather_data = get_field('d_location_weather',$weather_location);
            
            // look through all the monthly data. Make sure there are 12 months, and only numerics. If not, don't show the weather
            $show_weather_data = true;
            $month_count = 0;
            $data = $weather_data['d_location_weather_data'];
            if( $data ):
                foreach($data as $i => $item) {
                    ++$month_count;
                    if( !is_numeric($item['d_location_month_high']) || !is_numeric($item['d_location_month_low']) || !is_numeric($item['d_location_month_rain']) ) :
                        $show_weather_data = false;
                        break;
                    endif;
                }
            endif;

            // only show section if there is actual weather data
            if( $show_weather_data && $month_count == 12 ) :
        ?>

        <div class="quicksection private-weather<?php if( strlen($straightTalkHeader) > 0 ) echo ' border'; ?>">
            <header class="toursection__header"><h2 class="tertiaryheader">Weather by Month</h2></header>
            <div class="quicksection weather__widget">
                <div class="duvine-accordion">
                    <?php if( $weather_data['d_location_weather_sub_title'] ) : ?>
                    <div class="accordion__header js-duvine-accordion-trigger">
                        <div class="accordion-title"><?php echo $weather_data['d_location_weather_sub_title']; ?></div>
                    </div>
                    <p class="desktop-subtitle"><?php echo $weather_data['d_location_weather_sub_title']; ?></p>
                    <?php endif; ?>
                    <div class="accordion__content">
                        <div class="weather-table">
                            <div class="weather-degree-toggle">
                                <a href="#">View in &deg;<span>C</span></a>
                            </div>
                            <?php 
                            $months = array();
                            $highs = array();
                            $lows = array();
                            $rain = array();

                            $high_label = $weather_data['d_location_weather_high_label'];
                            $low_label = $weather_data['d_location_weather_low_label'];
                            $rain_label = $weather_data['d_location_weather_rain_label'];

                            // load date in arrays, so we can display in proper order
                            $data = $weather_data['d_location_weather_data'];
                            if( $data ):
                                foreach($data as $i => $item) {
                                    $months[] = $item['d_location_month_name'];
                                    $highs[] = $item['d_location_month_high'];
                                    $lows[] = $item['d_location_month_low'];
                                    $rain[] = $item['d_location_month_rain'];
                                }

                                echo '<div class="mobile-column desktop-header">';
                                echo '<div class="cell mobile-header empty">&nbsp;</div>';
                                for($i = 0, $iMax = count($months); $i < $iMax; $i++) {
                                    echo '<div class="cell">'.$months[$i].'</div>';
                                }
                                echo '</div>';

                                echo '<div class="mobile-column">';
                                echo '<div class="cell mobile-header temp">'.$high_label.' (&deg;<span>F</span>)</div>';
                                for($i = 0, $iMax = count($highs); $i < $iMax; $i++) {
                                    echo '<div class="cell temperature"><span>'.$highs[$i].'</span>&deg;</div>';
                                }
                                echo '</div>';

                                echo '<div class="mobile-column">';
                                echo '<div class="cell mobile-header temp">'.$low_label.' (&deg;<span>F</span>)</div>';
                                for($i = 0, $iMax = count($lows); $i < $iMax; $i++) {
                                    echo '<div class="cell temperature"><span>'.$lows[$i].'</span>&deg;</div>';
                                }
                                echo '</div>';

                                echo '<div class="mobile-column">';
                                echo '<div class="cell mobile-header rain">'.$rain_label.' (<span>in</span>)</div>';
                                for($i = 0, $iMax = count($rain); $i < $iMax; $i++) {
                                    $mm = round($rain[$i] * 25.4, 0);
                                    echo '<div class="cell rain"><span class="inches">'.$rain[$i].'</span><span class="mm">'.$mm.'</span></div>';
                                }
                                echo '</div>';
                            endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>

    </section><!-- Itinerary -->


    <?php if(!$privatetour) : ?>

        <a class="page-anchor" id="tour-dates"></a>
        <section class="toursection" data-section="Dates + availability" id="tour-dates-and-availability">
            <header class="toursection__header"><h2 class="pageheader">Dates + Availability</h2></header>
            <?php duvine_text_private_promo(); ?>

            <?php $tourdates = duvine_get_tour_dates(); ?>

            <?php if( $tourdates && is_array($tourdates) ) : ?>

                <div class="dateblock duvine-accordion">
                    <?php $first = true; ?>
                    <?php foreach( $tourdates as $year => $dates ) : ?>
                        <div class="dateblock__year<?php if( $first ) echo ' active'; ?>">
                            <header class="accordion__header accordion__header--year js-duvine-accordion-trigger<?php if( $first ) echo ' active'; ?>">

                                <?php 

                                    echo "<span class=\"accordion__headertitle\">$year</span>";

                                    $supplement_price = get_single_supplement_price($centaur_id);

                                    $single_supplement_tooltip = duvine_get_tooltip( 'd_tooltip_single_supplement', 'light' );
                                    
                                    if( $supplement_price ){
                                        echo '<span class="tour__singlesupplement">Single Supplement From: ' . apply_filters('format_price', $supplement_price) . $single_supplement_tooltip . '</span>';
                                    }
                                ?>
                            </header>
                            <div class="accordion__content accordion__content--dates<?php if( $first ) echo ' accordion__content--active'; ?>">
                                <ul class="baselist">
                                    <li class="dateblock__row dateblock__row--header">
                                        <div class="dateblock__cell dateblock__cell--dates dateblock__colheader">Dates</div>
                                        <div class="dateblock__cell dateblock__cell--price dateblock__colheader">Price</div>
                                        <div class="dateblock__cell dateblock__cell--events dateblock__colheader">Special Events</div>
                                        <div class="dateblock__cell dateblock__cell--booking">&nbsp;</div>
                                    </li>
                                
                                    <?php foreach( $dates as $date ) : ?>

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
                                        if (($pos = strpos($text, "tel:")) !== FALSE) {
                                          $tel = substr($text, strpos($text, "tel:") + 4, 12);
                                        }

                                      ?>
                                        
                                        <li class="dateblock__row<?php if( $tour_status === 'sold_out' || $tour_status === 'sold_out_w_private' ) echo ' dateblock__row--soldout'; elseif( $tour_status === 'book_now_limited_space' ) echo ' dateblock__row--limited_space' ?>">
                                            <div class="dateblock__cell dateblock__cell--dates"><?php echo $date['label']; ?></div>
                                            <div class="dateblock__cell dateblock__cell--price"><?php echo apply_filters('format_price', $date['price'] ); ?></div>
                                            <div class="dateblock__cell dateblock__cell--events"><?php if( is_string($date['events']) && strlen($date['events']) > 0 ) echo $date['events']; ?></div>
                                            <div class="dateblock__cell dateblock__cell--booking">
                                                <?php $centaurLink = duvine_get_centaur_link( $centaur_id, $date['start_date'] ); ?>
                                                <?php if( $tour_status === 'sold_out' ) : ?>
                                                    <div><button class="cta cta--secondary cta--noclick sold-out">Sold Out</button></div>
                                                <?php elseif( $tour_status === 'sold_out_w_private' ) : ?>
                                                    <div><button class="cta cta--secondary cta--noclick sold-out">Sold Out</button></div> <span class="note">Reserved by a private group</span>
                                                <?php elseif( $tour_status === 'book_now_limited_space' ) : ?>
                                                    <a target="_blank" href="<?php echo $centaurLink; ?>" class="cta cta--hoverwhite">Book Now</a>
                                                    <span class="note">Space is limited!</span>
                                                <?php elseif( $tour_status === 'call_to_book_limited_space' ) : ?>
                                                    <?php if(strlen($tel) > 0):?>
                                                        <div><a class="cta cta--secondary call-to-book" href="tel:<?php echo $tel; ?>">Call to Book</a></div>
                                                    <?php else: ?>
                                                        <div><button class="cta cta--secondary cta--noclick call-to-book">Call to Book</button></div>
                                                    <?php endif; ?>
                                                    <?php duvine_text_call_to_book_space_limited(); ?>
                                                <?php elseif( $tour_status !== 'call_to_book' && $tour_status !== 'call_to_book_30_days' && $centaurLink ) : ?>
                                                    <a target="_blank" href="<?php echo $centaurLink; ?>" class="cta cta--hoverwhite">Book Now</a>
                                                <?php else : ?>
                                                    <?php if(strlen($tel) > 0):?>
                                                      <div><a class="cta cta--secondary call-to-book" href="tel:<?php echo $tel; ?>">Call to Book</a></div>
                                                    <?php else: ?>
                                                      <div><button class="cta cta--secondary cta--noclick call-to-book">Call to Book</button></div>
                                                    <?php endif; ?>

                                                    <?php if( $tour_status === 'call_to_book_30_days') :
                                                        duvine_text_30_days_call_to_book();
                                                    else :
                                                        duvine_text_call_to_book();
                                                    endif;
                                                endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <?php $first = false; ?>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <?php duvine_text_dates_coming_soon('tourdates__noscheduled tourdates__noscheduled--background'); ?>
            <?php endif; ?>

            <?php if( $tourdates && ($cancellation_policy_link = get_field('d_cancellation_policy_page', 'option') ) ) : ?>
                <p class="tourdates__cancellation"><a href="<?php echo $cancellation_policy_link['url']; ?>" class="basiclink">See our cancellation policy</a></p>
            <?php endif; ?>

            <?php if( $weather_location) : 
                $weather_data = get_field('d_location_weather',$weather_location);
            
                if( $weather_data['d_location_weather_data'] ) :

                    // look through all of the monthly data. Make sure there are 12 months, and only numerics. If not, don't show the weather
                    $show_weather_data = true;
                    $month_count = 0;
                    $data = $weather_data['d_location_weather_data'];
                    if( $data ):
                        foreach($data as $i => $item) {
                            ++$month_count;
                            if( !is_numeric($item['d_location_month_high']) || !is_numeric($item['d_location_month_low']) || !is_numeric($item['d_location_month_rain']) ) :
                                $show_weather_data = false;
                                break;
                            endif;
                        }
                    endif;

                    // only show section if there is actual weather data
                    if( $show_weather_data && $month_count == 12 ) :

            ?>
                <div class="quicksection weather__widget">
                    <h5 class="tertiaryheader">Weather by Month</h5>
                    <div class="duvine-accordion">
                        <?php if( $weather_data['d_location_weather_sub_title'] ) : ?>
                        <div class="accordion__header js-duvine-accordion-trigger">
                            <div class="accordion-title"><?php echo $weather_data['d_location_weather_sub_title']; ?></div>
                        </div>
                        <p class="desktop-subtitle"><?php echo $weather_data['d_location_weather_sub_title']; ?></p>
                        <?php endif; ?>
                        <div class="accordion__content">
                            <div class="weather-table">
                                <div class="weather-degree-toggle">
                                    <a href="#">View in &deg;<span>C</span></a>
                                </div>
                                <?php 
                                $months = array();
                                $highs = array();
                                $lows = array();
                                $rain = array();

                                $high_label = $weather_data['d_location_weather_high_label'];
                                $low_label = $weather_data['d_location_weather_low_label'];
                                $rain_label = $weather_data['d_location_weather_rain_label'];

                                // load up date in arrays so we can display in proper order
                                $data = $weather_data['d_location_weather_data'];
                                if( $data ):
                                    foreach($data as $i => $item) {
                                        $months[] = $item['d_location_month_name'];
                                        $highs[] = $item['d_location_month_high'];
                                        $lows[] = $item['d_location_month_low'];
                                        $rain[] = $item['d_location_month_rain'];
                                    }

                                    echo '<div class="mobile-column desktop-header">';
                                    echo '<div class="cell mobile-header temp">Month</div>';
                                    for($i = 0, $iMax = count($months); $i < $iMax; $i++) {
                                        echo '<div class="cell">'.$months[$i].'</div>';
                                    }
                                    echo '</div>';

                                    echo '<div class="mobile-column">';
                                    echo '<div class="cell mobile-header temp">'.$high_label.' (&deg;<span>F</span>)</div>';
                                    for($i = 0, $iMax = count($highs); $i < $iMax; $i++) {
                                        echo '<div class="cell temperature"><span>'.$highs[$i].'</span>&deg;</div>';
                                    }
                                    echo '</div>';

                                    echo '<div class="mobile-column">';
                                    echo '<div class="cell mobile-header temp">'.$low_label.' (&deg;<span>F</span>)</div>';
                                    for($i = 0, $iMax = count($lows); $i < $iMax; $i++) {
                                        echo '<div class="cell temperature"><span>'.$lows[$i].'</span>&deg;</div>';
                                    }
                                    echo '</div>';

                                    echo '<div class="mobile-column">';
                                    echo '<div class="cell mobile-header rain">'.$rain_label.' (<span>in</span>)</div>';
                                    for($i = 0, $iMax = count($rain); $i < $iMax; $i++) {
                                        if( !is_numeric($rain[$i]) ) $rain[$i] = 0;
                                        $mm = round($rain[$i] * 25.4, 0);
                                        echo '<div class="cell rain"><span class="inches">'.$rain[$i].'</span><span class="mm">'.$mm.'</span></div>';
                                    }
                                    echo '</div>';
                                endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php endif; ?>
            <?php endif; ?>
        </section><!-- Dates and Availability -->

    <?php endif; ?>

    <section class="toursection" data-section="Inclusions + details">

        <header class="toursection__header"><h2 class="pageheader">Inclusions + Details</h2></header>

        <div class="d-column-container">

            <?php $challengeTour = in_array( 174, $collections ) || in_array( 175, $collections ) || in_array(176, $collections) ; ?>
            <?php $tourAccomodations = $challengeTour ? get_field('d_challenge_tour_accomodations', 'option') : get_field('d_tour_accomodations', 'option'); ?>
            <?php $tourMeals = $challengeTour ? get_field('d_challenge_tour_meals', 'option') : get_field('d_tour_meals', 'option'); ?>
            <?php $tourGear = $challengeTour ? get_field('d_challenge_tour_gear', 'option') : get_field('d_tour_gear', 'option'); ?>
            <?php $tourSupport = $challengeTour ? get_field('d_challenge_tour_support', 'option') : get_field('d_tour_support', 'option'); ?>
            <?php $tourActivities = $challengeTour ? get_field('d_challenge_tour_activities', 'option') : get_field('d_tour_activities', 'option'); ?>

            <?php $tourImg = $challengeTour ? get_field('d_challenge_tour_img', 'option') : get_field('d_tour_img', 'option'); ?>

            <?php if($tourImg) { ?>
                <div class="d-col"><img src="<?php echo $tourImg; ?>" /></div>
            <?php } ?>

            <div class="d-col d-col--1-2">
                <?php if( $tourAccomodations ) : ?>
                    <div class="quicksection">
                        <h4>Accommodations</h4>
                        <div class="d-content d-content--padtop"><?php echo $tourAccomodations; ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if( $tourMeals ) : ?>
                    <div class="quicksection">
                        <h4>Meals</h4>
                        <div class="d-content d-content--padtop"><?php echo $tourMeals; ?></div>
                    </div>
                <?php endif; ?>

                <?php if( $tourActivities ) : ?>
                    <div class="quicksection">
                        <h4>Activities</h4>
                        <div class="d-content d-content--padtop"><?php echo $tourActivities; ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-col d-col--1-2">
            
                <?php if( $tourGear ) : ?>
                    <div class="quicksection">
                        <h4>Gear</h4>
                        <div class="d-content d-content--padtop"><?php echo $tourGear; ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if( $tourSupport ) : ?>
                    <div class="quicksection">
                        <h4>Support</h4>
                        <div class="d-content d-content--padtop"><?php echo $tourSupport; ?></div>
                    </div>
                <?php endif; ?>

                <?php if( $tour_inclusions = get_field('d_included') ) : ?>
                    <div class="quicksection">
                        <h4 class="tertiaryheader">Additional Inclusions</h4>
                        <div class="d-content d-content--padtop">
                            <ul>
                                <?php foreach($tour_inclusions as $inclusion) : ?>
                                    <li><?php echo $inclusion; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            
            </div>

        </div>

        <div class="d-column-container">

            <div class="d-col d-col--1-2">

                <?php
                    sk_the_field('d_not_included', array(
                        'before' => '<div class="quicksection" style="margin-top: 28px;"><span style="font-size:14px;"><strong>NOT INCLUDED:</strong></span><br/><div class="d-content d-content--padtop">',
                        'after'  => '</div></div>',
                        'filter' => 'list_items'
                    ));
                ?>

            </div>

            <div class="d-col d-col--1-2">

                <?php if( $faq_page = get_field('d_faq_page', 'option')) : ?>

                    <div class="quicksection" style="margin-top: 28px;">
                        <span style="font-size:14px;"><strong>HAVE MORE QUESTIONS?</strong></span><br/>
                        <p>See our FAQs page for answers to common questions.</p>
                        <a href="<?php echo $faq_page['url']; ?>" class="cta">See FAQs</a>
                    </div>
                <?php endif; ?>

            </div>

        </div>


    </section><!-- Inclusions and details -->



    <?php if( $bikes = get_field('d_tour_bikes') ) : ?>
        <section class="toursection" data-section="Bikes">

            <header class="toursection__header"><h2 class="pageheader">Bikes</h2></header>

        
            <div class="d-column-container bikegrid">
                <?php foreach( $bikes as $bike ) : ?>
                    <?php if(get_post_status($bike->ID) === 'publish') : ?>
                        <?php $bikelink   = get_the_permalink($bike); ?>
                        <?php $bikePhoto  = duvine_get_url_from_object( get_field('bike_photo', $bike->ID), 'bike_thumbnail' ); ?>
                        <?php $bikeStyle  = $bikePhoto ? ' style="background-image: url(' . $bikePhoto . ')"' : ''; ?>
                        <?php $bgImgClass = $bikePhoto ? ' bikeblock__image--hasphoto' : 'bikeblock__image--placeholder'; ?>
                        <div class="d-col d-col--1-3 bikeblock">
                            <a href="<?php echo $bikelink; ?>" class="bikeblock__image <?php echo $bgImgClass; ?>"<?php echo $bikeStyle; ?>></a>
                            <h4 class="tertiaryheader"><a href="<?php echo $bikelink; ?>"><?php echo $bike->post_title; ?></a></h4>

                            <?php if( $manufacturer = get_field('bike_manufacturer', $bike->ID) ) : ?>
                                <p class="bikeblock__meta"><strong>Manufacturer: </strong><?php echo $manufacturer->name; ?></p>
                            <?php endif; ?>

                            <?php if( $bikeType = get_field('bike_type', $bike->ID) ) : ?>
                                <p class="bikeblock__meta"><strong>Type: </strong><?php echo $bikeType->name; ?></p>
                            <?php endif; ?>
                            

                        </div><!-- .d-col -->
                    <?php endif; ?>
                <?php endforeach; ?>
            </div><!-- .d-column-container -->
        </section><!-- Bikes -->
    <?php endif; ?>



    <?php if( $guides = get_field('d_tour_guides')) : ?>
        <section class="toursection" data-section="Tour Guides">

            <header class="toursection__header"><h2 class="pageheader">Tour Guides</h2></header>
            
            <div class="tourguides__grid d-column-container">
                <?php foreach( $guides as $guide ) : ?>
                    <?php if(get_post_status($guide->ID) === 'publish') { ?>
                        <div class="tourguide d-col d-col--1-3">
                            <a href="<?php echo get_permalink($guide); ?>">
                                <div class="tourguide__headshot">
                                    <?php 
                                        sk_the_field('duvine_headshot', array(
                                            'id'          => $guide->ID,
                                            'default'     => '<div class="tourguide__headshotplaceholder"></div>',
                                            'filter'      => 'sk_img_markup',
                                            'filter_args' => array(
                                                'img_size' => 'gridcell_image'
                                            )
                                        ));
                                    ?>
                                </div>

                                <h3 class="tourguide__name tertiaryheader"><?php echo $guide->post_title; ?></h3>
                            </a>
                        </div>
                    <?php } ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>



</div><!-- .tourmain -->
