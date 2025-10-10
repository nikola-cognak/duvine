<div class="Page">
                <?php get_template_part( 'partials/tours', 'filter' ); ?>
            </div><!--/.Page-->

            <div id="tourLisiting" class="Section Listing-Section">

                <div class="Page">

                <?php
                //***************************************************************************
                // Output Tours
                //***************************************************************************
                $posts_per_page = 10;
                $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

                $meta_query = array();

                $tour_type_items = $region_items = $destination_items = 'any';
                $search_string = $tour_type_name = $header_text = '';

                $start_date = date("Y-m-d H:i");
                $end_date   = date("Y-m-d H:i", strtotime("+365 day") );

                //***************************************************************************
                // Process GET params for use in tour dates query
                //***************************************************************************
                // Starting date dropdown value
                if( isset( $_GET['date'] ) && $_GET['date'] != '' ) {
                    $start_timestamp = strtotime( $_GET['date'] );
                    $start_date = date("Y-m-d H:i", $start_timestamp );
                }

                // Date range dropdown value
                if( isset( $_GET['range'] ) && $_GET['range'] != '' ) {
                    $end_timestamp = strtotime( "+{$_GET['range']} day", $start_timestamp );
                    $end_date = date("Y-m-d H:i", $end_timestamp );
                }

                // Region dropdown value
                if( isset( $_GET['regionId'] ) && $_GET['regionId'] != 0 ) {
                    $region_items = abs((int) filter_input(INPUT_GET, 'regionId', FILTER_SANITIZE_NUMBER_INT) );
                }

                // Destination dropdown value
                if( isset( $_GET['destinationId'] ) && $_GET['destinationId'] != 0 ) {
                    $destination_items = abs((int) filter_input(INPUT_GET, 'destinationId', FILTER_SANITIZE_NUMBER_INT) );
                }

                // Level dropdown value
                if( isset( $_GET['level'] ) && $_GET['level'] != 'anyLevel' && in_array($_GET['level'], duvine_valid_tour_levels()) ) {
                    $meta_query[] = array(
                        'key'       => 'duvine_tour_level',
                        'value'     => $_GET['level'],
                        'compare'   => '='
                    );
                }

                // Tour Type dropdown value
                if( isset( $_GET['type'] ) && $_GET['type'] != 0 ) {
                    $tour_type_items    = abs((int) filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT) );
                    $tour_type_name     = get_the_title( $tour_type_items );
                }

                if( isset( $_GET['searchString'] ) && $_GET['searchString'] != '' ) {
                    $search_string = $_GET['searchString'];
                }

                $tour_ids_filtered_by_type          = duvine_get_tour_ids_filtered_by_type( $tour_type_items );
                $destination_ids                    = duvine_get_destination_ids( $region_items );
                $tour_ids_filtered_by_destination   = duvine_get_tour_ids_filtered_by_destinations( $destination_items, $destination_ids );

                $merged_ids = array_intersect($tour_ids_filtered_by_destination, $tour_ids_filtered_by_type);

                if( empty( $merged_ids ) ) {
                    $merged_ids = array(0);
                }

                $args = array(
                    'post_type'         => 'duvine_tour_dates',
                    'orderby'           => 'meta_value',
                    'order'             => 'ASC',
                    'posts_per_page'    => $posts_per_page,
                    'paged'             => $paged,
                    'meta_query'        => array(
                        array(
                            'key'       => 'duvine_tour_date_start',
                            'value'     => array( $start_date, $end_date ),
                            'compare'   => 'BETWEEN',
                            'type'      => 'DATETIME'
                        ),
                        array(
                            'key'       => 'duvine_tour_date_availability',
                            'value'     => 'soldout',
                            'compare'   => '!='
                        ),
                        array(
                            'key'       => 'duvine_tour_date_private',
                            'value'     => '0',
                            'compare'   => '='
                        ),
                        array(
                            'key'       => 'duvine_tour_date_live',
                            'value'     => '1',
                            'compare'   => '='
                        ),
                        array(
                            'key'       => 'duvine_tour_date_deleted',
                            'value'     => '0',
                            'compare'   => '='
                        )
                    ),
                    'connected_type'        => 'tours_to_dates',
                    'connected_direction'   => 'from',
                    'connected_items'       => $merged_ids,
                    'connected_query'       => array(
                        'meta_query' => $meta_query,
                    )
                );

                $tours_query = new WP_Query( $args );

                p2p_type( 'tours_to_dates' )->each_connected( $tours_query, array(), 'tours' );

                if ( $tours_query->have_posts() ) : ?>

                    <div class="Listing-Total Section">
                        <?php duvine_pagination_links( $tours_query, $label = 'Tours', '<a href="/tours/calendar" class="Button Light"><span>Year at a glance</span></a>' ); ?>
                    </div>

                    <div class="Listing Tours">

                       <?php
                        while ( $tours_query->have_posts() ) : $tours_query->the_post(); ?>

                            <?php
                            // Output tour date header if it's a new month
                            global $is_first_tour;
                            $tour_start_timestamp = strtotime( get_post_meta( $post->ID, 'duvine_tour_date_start', true ) );
                            if( $header_text != date( 'F Y', $tour_start_timestamp ) ) {
                              $header_text = date( 'F Y', $tour_start_timestamp );
                              $is_first_tour = true;
                            ?>

                                  <div class="Action-Bar Black Header Bg-Tour-<?php echo esc_attr( $tour_type_name ); ?>">
                                       <span class="Left"><?php echo $header_text; ?></span>
                                  </div>

                            <?php } else {
                              $is_first_tour = false;
                            } //if header_text ?>

                            <?php
                            p2p_type( 'tours_to_destination' )->each_connected( $post->tours, array(), 'destination' );

                            get_template_part( 'partials/tours', 'index' );
                            ?>

                        <?php endwhile; // while $tours_query ?>

                    </div><!--/.Listing.Tours-->

                    <div class="Listing-Total Section">
                        <?php duvine_pagination_links( $tours_query, $label = 'Tours', '<a href="/tours/calendar" class="Button Light"><span>Year at a glance</span></a>' ); ?>
                    </div>

                <?php else: ?>

                    <p class="Page"></p>
                    <p><span>Unfortunately, we weren't able to match any of our bike tours to your search. But that doesn't mean we can't go there! Please&nbsp;</span><strong><a class="Video-Overlay" href="/contact" rel=".Overlay"><span style="color:#00aec5">contact us</span></a></strong><span> and we'll look into adding a&nbsp;new date as either a public or&nbsp;</span><strong><a href="http://www.duvine.com/tours/private"><span style="color:#00aec5">private bike tour</span></a></strong><span>.</span><br>&nbsp;</p><p></p>

                <?php endif; // if $tours_query->have_posts

                wp_reset_postdata();
                ?>

                </div><!--/.Page-->

            </div><!--/.Listing-Section.Clear-->

            <script>
                jQuery(function( $ ) {
                    var $form = $(".Tours-Form");
                    $form.on({
                        change: function( event ) {
                            formSubmit();
                        }
                    }, "input, select").on({
                        gfRadiobuttons_selected: function(event) {
                            $form.submit();
                        }
                    }, ".Radio-Group");
                    function formSubmit() {
                        $form.submit();
                        //Fake ajax - Show loading screen
                    }
                });
            </script>
