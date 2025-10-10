<?php
/**
 * Template Name: Tours Calendar Page
 */
get_header();

$current_year   = date('Y');
if( $current_year < 2016 ) {
  $current_year = 2016;
}
$min_year       = $current_year - 5;
$max_year       = $current_year + 5;

// Pull year from url "page" param
$the_year = get_query_var( 'page' );

$the_year = filter_var($the_year, FILTER_VALIDATE_INT, array(
    'options' => array(
        'default'   => $current_year,
        'min_range' => $min_year,
        'max_range' => $max_year
    )
));

// January 1st
$start_date = $the_year . '-01-01 00:00:00';

// December 31st
$end_date = $the_year . '-12-31 11:59:59';

    while ( have_posts() ) : the_post(); ?>

        <div class="Page Double Calendar-Header">
            <a href="/tours" class="Back Uppercase Calendar-Link">Go back to tours by date</a>

            <div class="Calendar-Legend">
                <div class="Clear"><span class="Square Limited-Space"></span>Limited Space Available</div>
                <div class="Clear"><span class="Square Sold-Out"></span>Sold Out</div>
            </div>
            <div class="Center Calendar-Title">
                <h1 class="Page-Title Center"><?php the_title(); ?></h1>

                <a class="Calendar-Nav Prev" href="<?php the_permalink(); if( ($the_year - 1) >= $min_year ){echo ($the_year - 1);} ?>"></a>
                <span class="Current-Year"><?php echo $the_year; ?></span>
                <a class="Calendar-Nav Next" href="<?php the_permalink(); if( ($the_year + 1) <= $max_year ){echo ($the_year + 1);} ?>"></a>
            </div>
            <hr>
        </div><!--/.Page.Double.Calendar-Header-->

        <div class="Page Double Calendar">

            <?php
            $all_regions      = array();
            $all_destinations = array();
            $all_tours        = array();
            $all_tour_dates   = array();
            $has_dates        = array();


            $regions_query = new WP_Query(array(
                'post_type'     => 'duvine_regions',
                'post_status'   => 'publish',
                'posts_per_page'=> '-1',
                'orderby'       => 'menu_order',
                'order'         => 'ASC'
            ));

            // Connect Regions to Destinaations
            p2p_type( 'destinations_to_regions' )->each_connected( $regions_query, array('orderby' => 'title', 'order' => 'ASC'), 'destinations' );

            if ( $regions_query->have_posts() ) {

                while( $regions_query->have_posts() ) {
                    $regions_query->the_post();

                    $region_name = get_the_title();

                    $all_regions[] = $region_name;

                    $all_destinations[ $region_name ] = array();

                    // Connect Destinations to Tours
                    p2p_type( 'tours_to_destination' )->each_connected( $post->destinations, array('orderby' => 'title', 'order' => 'ASC'), 'tours' );

                    foreach ( $post->destinations as $post ) {
                        setup_postdata( $post );

                        $destination_name = get_the_title();

                        $has_dates[ $destination_name ] = 0;

                        $all_destinations[ $region_name ][] = $destination_name;

                        // Connect Tours to Dates
                        p2p_type( 'tours_to_dates' )->each_connected( $post->tours, array(), 'dates' );

                        if( $post->tours ) {

                          $all_tours[ $destination_name ] = array();

                          foreach ( $post->tours as $post ) {
                            setup_postdata( $post );

                            $tour_price = get_post_meta( $post->ID, 'duvine_tour_price', true);
                            $tour_name  = get_the_title() ;

                            if( $post->dates ) {

                              $all_tours[ $destination_name ][] = array(
                                'price'     => $tour_price,
                                'name'      => $tour_name,
                                'permalink' => get_permalink()
                              );

                              $all_tour_dates[ $tour_name ] = array();

                              foreach ( $post->dates as $post ) { setup_postdata( $post );

                                  $tour_availability  = get_post_meta( $post->ID, duvine_tour_date_availability, true );
                                  $tour_private       = get_post_meta( $post->ID, duvine_tour_date_private, true );
                                  $tour_date_live     = get_post_meta( $post->ID, duvine_tour_date_live, true );
                                  $tour_deleted       = get_post_meta( $post->ID, duvine_tour_date_deleted, true );

                                  if( 0 == $tour_private && 1 == $tour_date_live && 0 == $tour_deleted ) {
                                      $tour_start_date    = get_post_meta( $post->ID, 'duvine_tour_date_start', true );
                                      $tour_end_date      = get_post_meta( $post->ID, 'duvine_tour_date_end', true );
                                      $tour_start_month   = date('n', strtotime($tour_start_date));
                                      $tour_start_year    = date('Y', strtotime($tour_start_date));
                                      $tour_start_day     = date('j', strtotime($tour_start_date));
                                      $tour_end_day       = date('j', strtotime($tour_end_date));


                                      if( $tour_start_year == $the_year ) {

                                        if( !isset( $all_tour_dates[ $tour_name ][$tour_start_month] ) ) {
                                          $all_tour_dates[ $tour_name ][$tour_start_month] = array();
                                        }

                                        $all_tour_dates[ $tour_name ][$tour_start_month][] = array(
                                          'start'         => strtotime( $tour_start_date ),
                                          'end'           => strtotime( $tour_end_date ),
                                          'availability'  => $tour_availability
                                        );

                                        $has_dates[ $destination_name ]++;
                                      }
                                  }

                              } // foreach post->dates
                            } // if post->dates

                          } // foreach post->tours
                        } // if post->tours

                    } // foreach destinations

                } // while regions_query
            } // if regions_query

            //***************************************************************************
            // Output calendar
            //***************************************************************************
            if( $all_regions ) {
              foreach( $all_regions as $region ) {

                $destinations = ( isset( $all_destinations[ $region ] ) ? $all_destinations[ $region ] : '' );
                sort( $destinations );
              ?>

                  <div class="RegionGroup">
                    <div class="Region-Title"><?php echo $region; ?><!--COUNT: <?php echo $has_dates[ $region ]; ?>--></div>
                  </div><!--/.RegionGroup-->

                  <?php
                  if( $destinations ) {
                    foreach( $destinations as $destination ) {

                      $tours = ( isset( $all_tours[ $destination ] ) ? $all_tours[ $destination ] : '' );

                      if( $tours && $has_dates[ $destination ] ) { ?>
                        <table class="Calendar">
                          <thead>
                              <tr>
                                  <th class="Destination"><?php echo $destination, ' ', $the_year; ?></th>
                                  <th>Jan</th>
                                  <th>Feb</th>
                                  <th>Mar</th>
                                  <th>Apr</th>
                                  <th>May</th>
                                  <th>Jun</th>
                                  <th>Jul</th>
                                  <th>Aug</th>
                                  <th>Sep</th>
                                  <th>Oct</th>
                                  <th>Nov</th>
                                  <th>Dec</th>
                              </tr>
                          </thead>
                          <tbody>

                            <?php
                              foreach( $tours as $tour ) {
                                $name = $tour['name'];
                                $tour_dates = ( isset( $all_tour_dates[ $name ] ) ? $all_tour_dates[ $name ] : '' );


                                if( $tour_dates ) { ?>

                                  <tr>
                                    <td class="Tour">
                                      <a href="<?php echo $tour['permalink']; ?>"><?php echo $tour['name']; ?></a><br />Price From: $<?php echo duvine_format_price( $tour['price'] ); ?>
                                    </td>

                                    <?php for($i=1; $i<=12; $i++) { ?>

                                      <td>
                                          <?php if( isset( $tour_dates[$i] ) ) {

                                            uasort( $tour_dates[$i], function($a, $b){
                                                return $a['start'] > $b['start'];
                                            });

                                            foreach( $tour_dates[$i] as $tour_date ) {

                                              $date_class = '';
                                              if( 'limited' == $tour_date['availability'] ) {
                                                $date_class = 'Limited-Space';
                                              }
                                              else
                                              if( 'soldout' == $tour_date['availability'] ) {
                                                $date_class = 'Sold-Out';
                                              }
                                            ?>

                                            <div class="<?php echo $date_class; ?>"><?php echo date('j', $tour_date['start'] ), ' - ', date('j', $tour_date['end'] ); ?></div>

                                          <?php } // foreach tour_dates
                                          } // if tour_dates ?>
                                      </td>

                                    <?php } // for $i ?>
                                  </tr>
                                <?php
                                } // if $tour_dates
                            } // foreach $tours ?>
                          </tbody>
                        </table><!--/.Calendar-->

                      <?php } // if tours
                    } // foreach $destinations
                  } // if $destinations
              } // foreach $all_regions
            } // if $all_regions
            ?>

        </div><!--/.Page.Double.Calendar-->

    <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
