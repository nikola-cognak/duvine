<?php
/**
* Template Name: Testimonials Page
**/

get_header(); ?>


    <div class="Page Double Top">

        <?php while ( have_posts() ) : the_post(); ?>
                <div id="storiesLanding">
                    <div class="promoContent">
                        <?php the_content(); ?>
                    </div><!--/.promoContent-->
                </div><!--/.storiesLanding-->

                <a id="listingContent" name="listingContent"></a>
                <div class="FloatLeft Clear">
                  <?php get_template_part( 'partials/testimonials', 'filter' ); ?>
                </div><!--/.FloatLeft.Clear-->


                <?php
                //***************************************************************************
                // Output Testimonials
                //***************************************************************************
                $testimonial_id = '';
                if( isset( $_GET['testimonial'] ) ) {
                  $testimonial_id = abs((int) filter_input(INPUT_GET, 'testimonial', FILTER_SANITIZE_NUMBER_INT) );
                }

                if( $testimonial_id  ) {

                  $testimonial_args = array(
                      'post_type'             => 'duvine_story',
                      'orderby'               => 'post_date',
                      'order'                 => 'DESC',
                      'p'                     => $testimonial_id
                  );

                }
                else {

                  $meta_query = array();

                  if( isset( $_GET['level'] ) && $_GET['level'] != 'anyLevel' && in_array($_GET['level'], duvine_valid_tour_levels()) ) {
                     $meta_query[] = array(
                      'key'       => 'duvine_tour_level',
                      'value'     => $_GET['level'],
                      'compare'   => '='
                    );
                  }

                  $tour_type_items = $region_items = $destination_items = 'any';
                  $search_string = $search_tag = '';

                  if( isset( $_GET['type'] ) && $_GET['type'] != 0 ) {
                      $tour_type_items = abs((int) filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT) );
                  }


                  if( isset( $_GET['regionId'] ) && $_GET['regionId'] != 0 ) {
                      $region_items = abs((int) filter_input(INPUT_GET, 'regionId', FILTER_SANITIZE_NUMBER_INT) );
                  }


                  if( isset( $_GET['destinationId'] ) && $_GET['destinationId'] != 0 ) {
                    $destination_items = abs((int) filter_input(INPUT_GET, 'destinationId', FILTER_SANITIZE_NUMBER_INT) );
                  }


                  if( isset( $_GET['searchString'] ) && $_GET['searchString'] != '' ) {
                    $search_string = trim(strip_tags( $_GET['searchString'] ));
                  }

                  if( isset( $_GET['tag'] ) && $_GET['tag'] != '' ) {
                    $search_tag  = trim(strip_tags( $_GET['tag'] ));
                  }

                  $tour_type_ids = get_posts( array(
                    'fields'                 => 'ids',
                    'post_type'            => 'duvine_tour_types',
                    'connected_type'         => 'tours_to_types',
                    'connected_direction'  => 'to',
                    'connected_items'      => $tour_type_items,
                    'nopaging'               => true,
                    'suppress_filters'       => false,
                  ) );

                  $destination_ids = get_posts( array (
                    'fields'                 => 'ids',
                    'post_type'            => 'duvine_regions',
                    'connected_type'         => 'destinations_to_regions',
                    'connected_items'      => $region_items,
                    'connected_direction'  => 'to',
                    'nopaging'               => true,
                    'suppress_filters'       => false,
                  ) );

                  $tour_filtered_destination_ids = get_posts( array (
                    'fields'                 => 'ids',
                    'post_type'            => 'duvine_destinations',
                    'connected_type'         => 'tours_to_destination',
                    'connected_items'      => $destination_items,
                    'connected_direction'  => 'to',
                    'connected_query'      => array(
                      'post__in' => $destination_ids
                    ),
                    'nopaging'               => true,
                    'suppress_filters'       => false,
                  ) );

                  $merged_ids = array_intersect($tour_filtered_destination_ids, $tour_type_ids);

                  if( empty( $merged_ids ) ) $merged_ids = array(0);

                  $posts_per_page = 5;
                  $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

                  add_filter('posts_groupby', 'duvine_query_group_by_id');

                  $testimonial_args = array(
                      'post_type'             => 'duvine_story',
                      'orderby'               => 'post_date',
                      'order'                 => 'DESC',
                      'posts_per_page'        => $posts_per_page,
                      'paged'                 => $paged,
                      'connected_type'        => 'tours_to_story',
                      'connected_direction'  => 'from',
                      'connected_items'      => $merged_ids,
                      'connected_query'      => array(
                        'post_type'     => 'duvine_tours',
                        'meta_query'    => $meta_query,
                      )
                  );

                  if( $search_string ) {
                      $testimonial_args['s'] = $search_string;
                  }

                  if( $search_tag ) {
                      $testimonial_args['tax_query'] = array(
                          array(
                              'taxonomy' => 'storytag',
                              'field'    => 'slug',
                              'terms'    => $search_tag
                          )
                      );
                  }
                }

                $testimonials_query = new WP_Query( $testimonial_args );

                if ( $testimonials_query->have_posts() ) { ?>

                    <div class="Listing-Section">
                        <div class="Listing-Total Section">
                            <?php duvine_pagination_links( $testimonials_query, $label = 'Testimonials' ); ?>
                        </div>

                        <div class="Listing Article">

                            <?php
                            $counter = 1;
                            while ( $testimonials_query->have_posts() ) { $testimonials_query->the_post(); ?>

                                <?php get_template_part( 'partials/testimonials', 'index' ); ?>

                                <?php if( $counter % $posts_per_page != 0  ) { ?>
                                    <hr>
                                <?php } ?>

                            <?php
                                $counter++;
                            } // while $testimonials_query ?>

                        </div><!--/.Listing.Article-->


                        <div class="Listing-Total Section">
                            <?php duvine_pagination_links( $testimonials_query, $label = 'Testimonials' ); ?>
                        </div>
                    </div><!--/.Listing-Section-->

                <?php } else { // if $testimonials_query->have_posts ?>

                  <div class="Listing-Section"><p>No stories found.</p></div>

                <?php } wp_reset_postdata();
                ?>


        <?php endwhile; // end of the loop. ?>

    </div><!--/.Page.Double.Top-->

    <script>
    jQuery(function( $ ) {
      var $form = $(".Story-Form");

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

<?php get_footer(); ?>
