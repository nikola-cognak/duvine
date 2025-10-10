<?php
get_header();

$args_dates = array(
    'connected_type'    => 'tours_to_dates',
    'connected_items'   => $post,
    'nopaging'          => true,
    'orderby'           => 'meta_value',
    'order'             => 'ASC',
    'meta_key'          => 'duvine_tour_date_start',
    'meta_value'        => date("Y-m-d H:i"),
    'meta_compare'      => '>',
    'meta_type'         => 'DATETIME'
);
$dates_connected = new WP_Query( $args_dates );

//*****************************************************************************
// Get tour destination and region
//*****************************************************************************
$destination_connected  = p2p_type( 'tours_to_destination' )->get_connected( $post->ID );
$tour_destination = '';
// Bit of a hack to get the region
while( $destination_connected->have_posts() ) {
    $destination_connected->the_post();
    if( $post->ID ) {
      $tour_destination .= get_the_title( $post->ID ) . ', ';
    }
    $tour_region = do_shortcode( '[p2p_connected type=destinations_to_regions mode=inline]' );
}
wp_reset_postdata();

//*****************************************************************************
// Get tour type
//*****************************************************************************
$tour_type = '';
$type_connected = p2p_type( 'tours_to_types' )->get_connected( $post->ID );
if( $type_connected ) {
    while( $type_connected->have_posts() ) {
        $type_connected->the_post();
        $tour_type = get_the_title();
    }
}
wp_reset_postdata();



// Set the tour data
$tour_title             = get_the_title();
$tour_overview          = get_the_content();
$tour_price             = get_post_meta( $post->ID, 'duvine_tour_price', true);
$tour_price_supplement  = get_post_meta( $post->ID, 'duvine_tour_single_supplement', true);
$tour_duration          = get_post_meta( $post->ID, 'duvine_tour_duration', true );
$tour_level_value       = get_post_meta( $post->ID, 'duvine_tour_level', true );
$tour_level             = duvine_tour_level( $tour_level_value );
$tour_centaur_id        = get_post_meta( $post->ID, 'duvine_tour_centaur_id', true );
$tour_company           = get_post_meta( $post->ID, 'duvine_tour_company', true );

$header_class = $button_class = '';
switch( $tour_type ) {
    case 'Classic':
      $header_class = 'Text-Tour-Classic';
      $button_class = 'Tour-Classic';
      break;
    case 'Couture':
      $header_class = 'Text-Tour-Couture';
      $button_class = 'Tour-Couture';
      break;
    case 'Pro':
      $header_class = 'Text-Tour-Pro';
      $button_class = 'Tour-Pro';
      break;
    case 'Family':
      $header_class = 'Text-Tour-Family';
      $button_class = 'Tour-Family';
      break;
    case 'Private':
      $header_class = 'Text-Tour-Private';
      $button_class = 'Tour-Private';
      break;
}
?>

    <div class="TwoColumn Tour-Detail Clear Page Top">

        <?php while ( have_posts() ) : the_post(); ?>


        <div class="Column Two Page">
            <h1 class="Page-Title Tour-Detail<?php echo ' ',$header_class; ?>"><?php the_title(); ?></h1>

            <div class="slideshow-smallscreen" data-set="slideshow"></div>

            <ul class="Slim-List Uppercase Section">
                <li><strong>Region: </strong><?php echo $tour_region;  ?></li>
                <li><strong>Destination: </strong><?php echo rtrim($tour_destination, ', '); ?></li>
                <li><strong>Duration:</strong> <?php echo $tour_duration; ?></li>
                <li><strong>Level:</strong> <?php echo $tour_level; ?> <a class="Link Overlay-Link No-Print" rel=".Overlay" href="/experience/levels-modal"><img class="Info" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/information.png" alt=""></a></li>
                <li><strong>Price From:</strong> $<?php echo duvine_format_price($tour_price ); ?></li>
                <li><strong>Single Supplement:</strong> $<?php echo duvine_format_price( $tour_price_supplement ); ?></li>
            </ul>

            <?php
            // No tour dates
            if( $dates_connected->have_posts() ) { ?>
            <span class="Inline-Title"><strong>Tour Dates</strong></span>
            <?php } ?>

            <?php
            $date_counter = 1;
            if( $dates_connected->have_posts() ) { ?>
              <table class="Tour-Dates Uppercase">
              <tbody>
              <?php while( $dates_connected->have_posts() ) : $dates_connected->the_post();

                  $availability   = get_post_meta( $post->ID, 'duvine_tour_date_availability', true );
                  $tour_date_live = get_post_meta( $post->ID, 'duvine_tour_date_live', true );

                  //...skip a date is in the past, or
                  $start_timestamp  = strtotime(get_post_meta( $post->ID, 'duvine_tour_date_start', true ));
                  if( $start_timestamp < time() || $date_counter > 5 || 0 == $tour_date_live) {
                      continue;
                  }

                  $start_date         = date( 'M j', $start_timestamp );
                  $end_date           = date( 'M j, Y', strtotime(get_post_meta( $post->ID, 'duvine_tour_date_end', true )) );
                  $year               = date( 'Y', strtotime(get_post_meta( $post->ID, 'duvine_tour_date_start', true )) );
                  $tour_booking_url   = duvine_booking_link( $tour_centaur_id, $tour_company, $start_timestamp );
                  $tour_date_price    = get_post_meta( $post->ID, 'duvine_tour_date_price', true );
                  $available_text     = get_post_meta( $post->ID, 'duvine_tour_date_available_text', true );
                  $call_or_book       = get_post_meta( $post->ID, 'duvine_tour_date_booking', true );
              ?>

                <tr colspan="3">
                    <td class="Tour-Dates-Note">

                        <?php if( 'available' == $availability && !empty($available_text) ) { ?>
                          <span class="Limited"><?php echo $available_text; ?></span><br>
                        <?php } ?>

                        <?php if( 'limited' == $availability ){ ?>
                          <span class="Limited">limited space</span>
                        <?php } elseif( 'special' == $availability ) { ?>
                          <span class="Limited"><?php echo get_post_meta( $post->ID, 'duvine_tour_date_special_text', true ); ?></span>
                        <?php } ?>
                    </td>
                </tr>
		<tr>
                    <td class="Tour-Dates-Date"><?php echo "$start_date - $end_date"; ?></td>
                    <td class="Tour-Dates-Price">$<?php echo duvine_format_price( $tour_date_price ); ?></td>
                    <td class="Tour-Dates-Button No-Print">

                        <?php if( 'Book Now' == $call_or_book ) { ?>
                          <a href="<?php echo $tour_booking_url; ?>" target="_blank" class="Book-Button Book-Overlay">Book Now</a>
			<?php } else if( $availability == 'soldout' ) { ?>
                          <span class="Book-Button">Sold out</span>
                        <?php } else if( 'Call to Book' == $call_or_book ) { ?>
                          <span class="Book-Button"><a href="/reserve">Call to book</a></span>
                        <?php } else { ?>

                          <?php if( $availability == 'limited' ): ?>
                              <span class="Book-Button"><a href="/reserve">Call to book</a></span>
                          <?php else: ?>
                              <a href="<?php echo $tour_booking_url; ?>" target="_blank" class="Book-Button Book-Overlay">Book Now</a>
                          <?php endif; ?>

                        <?php } // if $call_or_book ?>

                    </td>
                </tr>

              <?php
                  $date_counter++;
              endwhile;
              wp_reset_postdata();
              ?>
              </tbody>
              </table>

              <p class="Clear No-Print">
                  <a href="/tours?tourId=<?php echo $post->ID ?>" class="<?php echo $header_class; ?>"><strong>View All Dates</strong></a>&nbsp;&nbsp;
                  <a href="/tours/private/planning/" class="Button Float-Right Tour-Private"><span>Go Private</span></a>
              </p>

            <?php } // if dates_connected->have_posts?>

            <?php
            // No tour dates
            if( 1 == $date_counter ) { ?>
              <br><br>
              <table class="Tour-Dates">
              <tbody>
                <tr>
                  <th scope="col" style="background-color:rgb(116, 38, 111); height:23px"><span style="color:#FFFFFF">PRIVATE COLLECTION ITINERARY</span></th>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td style="text-align:justify"><br>This tour is part of our private tours collection. Please complete a&nbsp;<strong><a href="http://www.duvine.com/tours/private/planning">Private Tour Planning Form</a></strong>&nbsp;to start designing a private tour that is everything you want - and nothing you don't - where, when, and how you want it, to share with whomever you choose. Or, call <strong>888-396-5383</strong> to inquire about joining a future public date that may become available.<br>&nbsp;</td>
                </tr>
              </tbody>
              </table>

              <p class="Clear No-Print">
                  <a href="/tours/private/planning/" class="Button Float-Right Tour-Private"><span>Go Private</span></a>
              </p>

            <?php } ?>




            <div class="Action-Buttons Right Section">
                <a href="/tours/planning/" class="Button Action No-Print"><span>Contact Us</span></a>
            </div>

            <?php if( $google_maps_content = get_post_meta( $post->ID, 'duvine_tour_google_maps', true ) ) { ?>
              <div class="Section No-Print">
                <?php echo apply_filters('the_content', $google_maps_content); ?>
              </div>
            <?php } ?>

            <div class="Accordian Section" role="tablist">

                <?php
                // Region content
                if( $region_content = get_post_meta( $post->ID, 'duvine_tour_region', true ) ) { ?>
                <a class="AccordianHeader" data-accordion-name="region" role="tab" tabindex="0"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Region</a>
                <div class="AccordianItem " role="tabpanel" style="display: none;">
                    <?php echo apply_filters('the_content', $region_content); ?>
                </div>
                <?php } ?>

                <?php
                // Highlights content
                if( $tour_highlights = get_post_meta( $post->ID, 'duvine_tour_highlights', true ) ) { ?>
                <a class="AccordianHeader" data-accordion-name="highlights" role="tab" ><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Highlights</a>
                <div class="AccordianItem" style="display: none;">
                    <?php echo apply_filters('the_content',  $tour_highlights); ?>
                </div>
                <?php } ?>

                <?php
                // Younger travelers content
                if( $younger_travelers = get_post_meta( $post->ID, 'duvine_tour_younger_travelers', true ) ) { ?>
                <a class="AccordianHeader" data-accordion-name="younger-travelers" role="tab" ><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Younger Travelers</a>
                <div class="AccordianItem" style="display: none;">
                    <?php echo apply_filters('the_content',  $younger_travelers); ?>
                </div>
                <?php } ?>

                <?php
                // Related Staff
                $staff_connected = new WP_Query(array(
                    'connected_type'  => 'tours_to_staff',
                    'connected_items' => get_queried_object(),
                    'nopaging'        => true,
                    'orderby'         => 'title',
                    'order'           => 'ASC',
                    'meta_query'      => array(
                        array(
                            'key'   => 'duvine_staff_group',
                            'value' => 'guides'
                        )
                    )
                ));

                if( $staff_connected->have_posts() ) { ?>

                  <a class="AccordianHeader No-Print ui-accordion-header ui-helper-reset ui-state-default ui-corner-all ui-accordion-icons" data-accordion-name="tour-guides" role="tab" id="ui-accordion-1-header-2" aria-controls="ui-accordion-1-panel-2" aria-selected="false" tabindex="-1"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Tour Guides</a>
                  <div class="AccordianItem No-Print" style="display: none;">
                      <div class="Listing Grid Related Grid-Tour-Sidebar Clear">

                      <?php
                      $staff_count = 1;
                      while( $staff_connected->have_posts() ) : $staff_connected->the_post();

                        if( $staff_count % 2 == 1 ) {
                          echo '<div class="Listing-Row Clear 1">';
                        }
                        ?>
                          <div class="Listing-Item Related-Item">
                              <a href="<?php the_permalink(); ?>">
                                  <?php the_post_thumbnail( 'thumb' ); ?>
                                  <span class="Title"><?php the_title(); ?></span>
                              </a>
                              <span class="Sub-Title"><?php echo get_post_meta( $post->ID, 'duvine_staff_job_title', true ); ?></span>
                          </div>

                          <?php
                          if( $staff_count % 2 == 0  || $staff_connected->found_posts == $staff_count  ) {
                              echo '</div>';
                          }

                          $staff_count++;

                      endwhile; wp_reset_postdata(); ?>

                      </div>
                  </div>

                <?php } ?>

                <?php
                // Related Hotels
                $hotels_connected = new WP_Query( array(
                  'connected_type'  => 'tours_to_hotels',
                  'connected_items' => get_queried_object(),
                  'orderby'         => 'title',
                  'order'           => 'ASC',
                  'nopaging'        => true,
                ) );

                if( $hotels_connected->have_posts() ) { ?>

                  <a class="AccordianHeader" data-accordion-name="hotels"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Accommodations</a>
                  <div class="AccordianItem" style="display: none;">
                      <div class="Listing Grid Related Grid-Tour-Sidebar Clear">

                        <?php
                        $hotels_count = 1;
                        while( $hotels_connected->have_posts() ) : $hotels_connected->the_post();

                            if( $hotels_count % 2 == 1 ) {
                              echo '<div class="Listing-Row Clear 1">';
                            }
                            ?>
                            <div class="Listing-Item Related-Item">
                                <a class="Grid-Link" href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'thumb' ); ?>
                                    <span class="Title"><?php the_title(); ?></span>
                                </a>
                                <span class="Sub-Title"><?php echo $tour_region ?> | <?php echo do_shortcode( '[p2p_connected type=hotels_to_destinations mode=inline]' ); ?></span>
                            </div>
                            <?php
                            if( $hotels_count % 2 == 0 || $hotels_connected->found_posts == $hotels_count ) {
                                echo '</div>';
                            }
                            $hotels_count++;

                        endwhile; wp_reset_postdata(); ?>

                    </div>
                </div>

                <?php } ?>

                <?php
                // Related Bikes
                $bikes_connected = new WP_Query( array(
                  'connected_type'  => 'tours_to_bikes',
                  'connected_items' => get_queried_object(),
                  'orderby'         => 'title',
                  'order'           => 'ASC',
                  'nopaging'        => true,
                ) );

                if( $bikes_connected->have_posts() ) { ?>

                  <a class="AccordianHeader No-Print" data-accordion-name="bikes"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Bikes</a>
                  <div class="AccordianItem No-Print" style="display: none;">
                      <div class="Listing Grid Related Grid-Tour-Sidebar Clear">

                        <?php
                        $bikes_count = 1;
                        while( $bikes_connected->have_posts() ) : $bikes_connected->the_post();

                            if( $bikes_count % 2 == 1 ) {
                                echo '<div class="Listing-Row Clear 1">';
                            }
                            ?>
                            <div class="Listing-Item Related-Item">
                                <a class="Grid-Link" href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'thumb' ); ?>
                                    <span class="Listing-Title"><?php the_title(); ?></span>
                                </a>
                                <span class="Listing-Sub-Line"><?php echo get_post_meta( $post->ID, 'duvine_bike_manufacturer', true ); ?></span>
                                <span class="Listing-Sub-Line"><?php echo get_post_meta( $post->ID, 'duvine_bike_style', true ); ?></span>
                            </div>
                        <?php
                            if( $bikes_count % 2 == 0 || $bikes_connected->found_posts == $bikes_count ) {
                                echo '</div>';
                            }
                            $bikes_count++;

                        endwhile; wp_reset_postdata(); ?>

                        </div>
                    </div>

                <?php } ?>


                <?php
                // Whats included content
                if( $whats_included = get_post_meta( $post->ID, 'duvine_tour_whats_included', true ) ) { ?>
                  <a class="AccordianHeader" data-accordion-name="whats-included"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>What's Included</a>
                  <div class="AccordianItem" style="display: none;">
                      <?php echo apply_filters('the_content',  $whats_included); ?>
                  </div>
                <?php } ?>


                <?php
                // Testimonials
                $stories_connected = new WP_Query( array(
                  'connected_type'  => 'tours_to_story',
                  'connected_items' => get_queried_object(),
                  'nopaging'        => true,
                ) );
                if( $stories_connected->have_posts() ) { $testimonials_page = get_permalink( 5776 ) ; ?>

                  <a class="AccordianHeader No-Print" data-accordion-name="testimonials"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Testimonials</a>
                  <div class="AccordianItem No-Print" style="display: none;">
                    <div class="Listing Grid Related Clear">
                      <?php
                      $testimonial_counter = 1;
                      while( $stories_connected->have_posts() ) :
                        $stories_connected->the_post();
                        $div_is_open = false;
                      ?>

                        <?php if( 1 == $testimonial_counter ) { ?>
                            <div class="Listing-Row Clear">
                        <?php } ?>

                        <div class="Listing-Item Related-Item">
                          <a href="<?php echo $testimonials_page; ?>?testimonial=<?php echo get_the_ID(); ?>#listingContent">
                            <span class="Title"><?php the_title(); ?></span>
                          </a>
                          <span class="Sub-Title"><?php echo $tour_title; ?></span>
                          <p class="Abstract"><?php echo wp_trim_words( get_the_content(), 8, '...' ); ?></p>
                        </div>

                        <?php if( $testimonial_counter % 2 == 0 ) { $div_is_open = true; ?>

                          </div><!--/.Listing-Row.Clear-->
                          <div class="Listing-Row Clear">

                        <?php } // if $testimonial_counter % 2 ?>

                      <?php $testimonial_counter++; endwhile; wp_reset_postdata(); ?>

                      <?php
                      if( $div_is_open || $testimonial_counter % 2 == 0 ) { ?>
                          </div><!--/.Listing-Row.Clear-->
                      <?php } ?>


                    </div><!--/.Listing.Grid.Related.Clear-->
                  </div>
                <?php } ?>


                <?php
                // Related posts
                $posts_connected = new WP_Query( array(
                  'connected_type'  => 'tours_to_blog',
                  'connected_items' => get_queried_object(),
                  'nopaging'        => true,
                ) );

                if( $posts_connected->have_posts() ) { ?>

                  <a class="AccordianHeader No-Print" data-accordion-name="blogs"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Blogs</a>
                  <div class="AccordianItem No-Print" style="display: none;">

                    <?php while( $posts_connected->have_posts() ) : $posts_connected->the_post(); ?>

                      <a href="<?php the_permalink(); ?>" class="RelatedBlogItem Clear">
                          <?php the_post_thumbnail( 'thumb', array( 'class' => 'RelatedBlogImage' ) ); ?>
                          <div>
                              <b><?php the_title(); ?></b><br>
                              <?php the_date( '(m-d-Y)' ); ?><br>
                              <?php the_excerpt(); ?>
                          </div>
                      </a>

                    <?php endwhile; wp_reset_postdata(); ?>

                    <a href="<?php echo get_permalink( get_option('page_for_posts' ) ); ?>" class="view-all-blog"><strong>View All Blog Posts</strong></a>
                  </div>

                <?php } ?>

                <?php
                // Travel Tips
                if( $travel_tips = get_post_meta( $post->ID, 'duvine_tour_travel_tips', true ) ) { ?>
                <a class="AccordianHeader" data-accordion-name="travel-tips" role="tab" ><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Travel Tips</a>
                <div class="AccordianItem" style="display: none;">
                    <?php echo apply_filters('the_content',  $travel_tips); ?>
                </div>
                <?php } ?>

              </div><!--/.Accordian.Section-->


            <?php
                $videos_connected = new WP_Query( array(
                  'connected_type'  => 'tours_to_video',
                  'connected_items' => get_queried_object(),
                  'nopaging'        => true,
                ) );

                if( $videos_connected->have_posts() ) : ?>
                    <div class="video-initial No-Print" data-set="video">
                        <div class="Border-Lines Offset No-Print">
                            <?php while( $videos_connected->have_posts() ) { $videos_connected->the_post(); ?>
                            <a href="<?php the_permalink(); ?>" rel=".Overlay" class="Video-Overlay">
                                <?php the_post_thumbnail( 'full', array('style' => 'width:100%;height:auto;') ); ?>
                            </a>
                            <?php } wp_reset_postdata(); ?>
                        </div>
                        <p class="Right No-Print"><a href="/videos" class="Button <?php echo $button_class; ?>"><span>More Videos</span></a></p>
                    </div>
            <?php endif; ?>
        </div><!--/.Column.Two-->


        <div class="Column One Page">
            <?php
                $gallery_connected = new WP_Query( array(
                  'connected_type'  => 'tours_to_gallery',
                  'connected_items' => get_queried_object(),
                  'nopaging'        => true,
                ) );

                if( $gallery_connected->have_posts() ) : ?>
                    <div class="slideshow-initial" data-set="slideshow">
                        <div class="Slideshow Tour-Detail">
                            <div class="slick-is-loading slick-slider slider">
                            <?php while( $gallery_connected->have_posts() ) {
                                  $gallery_connected->the_post();
                                  $slide_images = get_field('photography_gallery', $post->ID);

                                  $gallery_link = '#';
                                  if( has_term( 'photography', 'gallerytag', $post->ID ) ) {
                                    $gallery_link = get_permalink();
                                  }


                                  if( $slide_images ) {
                                    foreach( $slide_images as $slide_image ) { ?>
                                      <a href="<?php echo $gallery_link; ?>">
                                          <img data-lazy="<?php echo esc_url( $slide_image['sizes']['gallery-medium'] ); ?>" aria-label="<?php echo $slide_image['alt'] ?>" />
                                      </a>
                                    <?php }
                                  }
                                ?>

                            <?php } // while gallery_connected
                            wp_reset_postdata(); ?>
                            </div>
                        </div><!--/.Slideshow-->
                    </div>

            <?php endif; ?>

            <div class="Clear">
              <?php
              //echo apply_filters('the_content', get_post_meta( $post->ID, 'duvine_tour_overview', true ) );
              echo apply_filters('the_content', $tour_overview );
              ?>
            </div>

            <hr/>
            <p class="Right Uppercase No-Print">

                <?php if( $pdf_id = get_post_meta( $post->ID, 'duvine_tour_pdf_file_id', true ) ) { ?>
                  <a href="<?php echo esc_url( wp_get_attachment_url( $pdf_id ) ); ?>" target="_blank">Download Tour</a> |
                <?php } ?>

                <a href="#" class="js-print" target="_blank">Print</a>
            </p>

            <div class="Content Clear Body">
                <?php echo apply_filters('the_content', get_post_meta( $post->ID, 'duvine_tour_itinenary', true ) ); ?>
            </div>

            <div class="video-smallscreen" data-set="video"></div>

            <div class="Section Social-Content">
                <?php duvine_sharethis(); ?>
            </div><!--/.Section.Social-Content-->
        </div><!--/.Column.One-->


        <?php endwhile; // end of the loop. ?>

    </div><!--/.TwoColumn-->

    <script>
    jQuery(function( $ ) {
        $( '.js-print' ).on('click', function(e){
            e.preventDefault();
            window.print();
        });

        $( '.Slideshow.Tour-Detail' ).appendAround();
        $( '.Border-Lines.Offset').appendAround();

        //var pageURL = "/tours/305/Chile-Vineyards-Bike-Tour";
        var accordionElement = $(".Accordian");
        var accordionNames = $(".AccordianHeader", accordionElement);


        function openAccordionFromURL(url) {
            var accordionItemName = url.replace(new RegExp(".*/(.*)"), "$1");
            var index = indexOfAccordionItemWithName(accordionItemName);

            if (index > -1) {
                accordionElement.accordion( "option", "active", index );
            }
        }

        accordionElement.accordion({
            collapsible: true,
            heightStyle: "content",
            active: false,
            change: function( event, ui) {
                var clickedItem = ui.newHeader.data("accordion-name");

                if (typeof clickedItem === "undefined") {
                    return;
                }

                var index = indexOfAccordionItemWithName(clickedItem);
                var accordionItem = $(".AccordianItem", accordionElement).eq(index);
                var elementToUpdate = $(".AjaxContent:empty", accordionItem);

                if (elementToUpdate.length > 0) {
                    elementToUpdate.hide();
                    elementToUpdate.slideDown(350);
                }

            },

            activate: function( event, ui ) {
                 var clickedItem = ui.newHeader.data("accordion-name");
                 var index = indexOfAccordionItemWithName(clickedItem);
                 var accordionItem = $(".AccordianItem", accordionElement).eq(index);
            }
        });

        function indexOfAccordionItemWithName(name) {
            for (i = 0; i < accordionNames.length; ++i) {
                if (accordionNames.eq(i).data("accordion-name") == name) {
                    return i;
                }
            }

            return -1;
        }
    });
    </script>

<?php get_footer(); ?>