<div id="tourLisiting" class="Section Listing-Section">
        <div class="Page">
        <?php
        $tour_id = abs((int) filter_input(INPUT_GET, 'tourId', FILTER_SANITIZE_NUMBER_INT) );
        if( $tour_id ) {

            // Get the tour record
            $tours_query = new WP_Query(array(
                'p'         => $tour_id,
                'post_type' => 'duvine_tours',
            ));

            // Associate the connected tour dates
            p2p_type( 'tours_to_dates' )->each_connected( $tours_query, array(), 'tour_dates' );
            p2p_type( 'tours_to_destination' )->each_connected( $tours_query, array(), 'destination' );
            if ( $tours_query->have_posts() ) { ?>

                <div class="Listing-Total Section">
                  <a href="/tours/calendar" class="Button Light"><span>Year at a glance</span></a>
                </div>

                <div class="Listing Tours">
                <?php
                $the_tour_dates = array();
                while ( $tours_query->have_posts() ) {
                    $tours_query->the_post();

                    // Get the region name using the destination ID
                    $tour_region = '';
                    foreach( $post->destination as $destination ) {
                        $region_id = get_posts( array(
                            'fields'                => 'ids',
                            'post_type'             => 'duvine_regions',
                            'connected_type'        => 'destinations_to_regions',
                            'connected_direction'   => 'from',
                            'connected_items'       => $destination->ID,
                            'nopaging'              => true,
                            'suppress_filters'      => false,
                        ) );

                        if( $region_id ) {
                            $tour_region = get_the_title( $region_id[0] );
                        }
                    }


                    $tour_name              = get_the_title();
                    $tour_link              = get_permalink();
                    $tour_thumbnail         = get_the_post_thumbnail( $post->ID, 'medium', array( 'class' => 'Thumbnail', 'style' => 'width:174px;height:104px' ) );
                    $tour_abstract          = get_post_meta( $post->ID, 'duvine_abstract', true );
                    $tour_price             = get_post_meta( $post->ID, 'duvine_tour_price', true);
                    $tour_price_supplement  = get_post_meta( $post->ID, 'duvine_tour_single_supplement', true);
                    $tour_duration          = get_post_meta( $post->ID, 'duvine_tour_duration', true );
                    $tour_level_value       = get_post_meta( $post->ID, 'duvine_tour_level', true );
                    $tour_level             = duvine_tour_level( $tour_level_value );
                    $tour_centaur_id        = get_post_meta( $post->ID, 'duvine_tour_centaur_id', true );
                    $tour_company           = get_post_meta( $post->ID, 'duvine_tour_company', true );

                    foreach ( $post->tour_dates as $post ) {
                        setup_postdata( $post );

                        $start_timestamp    = strtotime( get_post_meta( $post->ID, 'duvine_tour_date_start', true ) );
                        $end_timestamp      = strtotime( get_post_meta( $post->ID, 'duvine_tour_date_end', true ) );
                        $tour_availability  = get_post_meta( $post->ID, 'duvine_tour_date_availability', true );
                        $tour_date_price    = get_post_meta( $post->ID, 'duvine_tour_date_price', true );
                        $special_text       = get_post_meta( $post->ID, 'duvine_tour_date_special_text', true );
                        $available_text     = get_post_meta( $post->ID, 'duvine_tour_date_available_text', true );
                        $call_or_book       = get_post_meta( $post->ID, 'duvine_tour_date_booking', true );

                        $start =  date('Y-m-d', $start_timestamp);

                        $today_timestamp = strtotime(date('Y-m-d 00:00:00'));

                        if( $start_timestamp >= $today_timestamp ) {
                            $the_tour_dates[ $start ] = array(
                                'start'         => $start_timestamp,
                                'end'           => $end_timestamp,
                                'availability'  => $tour_availability,
                                'price'         => $tour_date_price,
                                'special_text'  => $special_text,
                                'available_text'=> $available_text,
                                'call_or_book'  => $call_or_book
                            );
                        }
                    } // foreach tour_dates

                    if( $the_tour_dates ) {
                        ksort( $the_tour_dates );

                        $month_date_header = '';
                        $first_in_month = false;

                        foreach( $the_tour_dates as $the_tour_date ) {
                            $the_month = date( 'F Y', $the_tour_date['start']);

                            // Output month header
                            if( $the_month != $month_date_header ) {
                                $month_date_header = $the_month;
                                $first_in_month = true;
                            ?>
                                <div class="Action-Bar Black Header "><span class="Left"><?php echo $the_month; ?></span></div>
                            <?php } // if the_month ?>

                            <div class="Listing-Item Clear<?php if( $first_in_month ){ $first_in_month = false; echo ' First';} ?>">

                              <div class="Date">
                                <?php if( date('M', $the_tour_date['start']) == date('M', $the_tour_date['end']) ) { ?>

                                  <span class="Month"><?php echo date( 'M', $the_tour_date['start'] ) ?></span>
                                  <span class="Day"><?php echo date( 'j', $the_tour_date['start'] ) ?> - <?php echo date( 'j', $the_tour_date['end'] ) ?></span>

                                <?php } else { ?>

                                  <span class="Month"><?php echo date( 'M j', $the_tour_date['start'] ) ?></span>
                                  <span class="Day">-</span>
                                  <span class="Month"><?php echo date( 'M j', $the_tour_date['end'] ) ?></span>

                                <?php } ?>
                              </div><!--/.Date-->

                                <a href="<?php echo $tour_link; ?>"><?php echo $tour_thumbnail; ?></a>
                                <div class="Info ">
                                    <div class="Clear">
                                        <div class="Main">
                                            <a class="Title" href="<?php echo $tour_link; ?>"><?php echo $tour_name; ?> | <span class="SubTitle"><?php echo $tour_region; ?></span></a>
                                            <p class="Abstract">
                                                <?php echo $tour_abstract; ?>
                                                <a href="<?php echo $tour_link; ?>" class="Next">learn more</a>
                                            </p>
                                        </div><!--/.Main-->
                                        <div class="Extended">
                                            <span class="Inline-Title">Duration:</span> <?php echo $tour_duration; ?><br>
                                            <span class="Inline-Title">Level:</span> <?php echo $tour_level; ?><br>
                                            <span class="Inline-Title">Price:</span> $<?php echo duvine_format_price( $the_tour_date['price'] ); ?><br>

                                            <?php if( 'available' == $the_tour_date['availability'] && !empty( $the_tour_date['available_text'] ) ){ ?>
                                              <span class="Limited"><?php echo $the_tour_date['available_text']; ?></span><br>
                                            <?php } ?>

                                            <?php if( 'limited' == $the_tour_date['availability'] ){ ?>
                                              <span class="Limited">Limited Space</span>
                                            <?php } elseif( 'special' == $the_tour_date['availability'] ) { ?>
                                              <span class="Inline-Title">Travel Specials:</span> <?php echo $the_tour_date['special_text']; ?><br>
                                            <?php } ?>

                                        </div><!--/.Extended-->
                                    </div><!--/.Clear-->
                                    <p class="Action-Bar Black">

                                      <?php if( 'Book Now' == $the_tour_date['call_or_book'] ) { ?>

                                        <a class="Right" target="_blank" href="<?php echo duvine_booking_link( $tour_centaur_id, $tour_company, $the_tour_date['start'] ); ?>">Book Now</a>

				      <?php } else if( 'soldout' == $the_tour_date['availability'] ) { ?>
                          		
					<a class="Right">Sold out</a>

                                      <?php } else if( 'Call to Book' == $the_tour_date['call_or_book'] ) { ?>

                                        <a class="Right" href="/reserve">Call to book</a>

                                      <?php } else { ?>

                                          <?php if( 'limited' == $the_tour_date['availability'] ){ ?>

                                            <a class="Right" href="/reserve">Call to book</a>

                                          <?php } else { ?>

                                            <a class="Right" target="_blank" href="<?php echo duvine_booking_link( $tour_centaur_id, $tour_company, $the_tour_date['start'] ); ?>">Book Now</a>

                                          <?php } ?>

                                      <?php } // if $call_or_book ?>
                                    </p>
                                </div><!--/.Info-->
                            </div><!--/.Listing-Item.Clear-->

                        <?php } // foreach the_tour_dates
                    } // if  the_tour_dates
                } // while $tours_query ?>

                  </div><!--/.Listing.Tours-->

                  <div class="Listing-Total Section">
                    <a href="/tours/calendar" class="Button Light"><span>Year at a glance</span></a>
                  </div>

              <?php } // if $tours_query
        } // if tour_id
        ?>
        </div><!--/.Page-->
    </div><!--/.Listing-Section.Clear-->