<?php
//***************************************************************************
// Tour type
//***************************************************************************
$tour_slug = (isset( $_GET['tourType'] ) ? trim(strip_tags( $_GET['tourType'] )) : '');
$tour_type = get_posts(array(
    'name' => $tour_slug,
    'posts_per_page' => 1,
    'post_type' => 'duvine_tour_types',
    'post_status' => 'publish'
));

$tour_type_id = $tour_type_name = '';
if( $tour_type ) {
  foreach( $tour_type as $type) {
      $tour_type_id     = $type->ID;
      $tour_type_name   = $type->post_title;
  }
}


//***************************************************************************
// Destinations
//***************************************************************************
$destinations = array();
$connected = new WP_Query( array(
  'connected_type'  => 'destinations_to_regions',
  'connected_items' => get_queried_object(),
  'nopaging'        => true,
  'orderby'         => 'menu_order',
  'order'           => 'ASC'
) );

if ( $connected->have_posts() ) {
    while ( $connected->have_posts() ) {
        $connected->the_post();

        $destination = array(
            'id'        => get_the_ID(),
            'title'     => get_the_title()
        );

        $connection_args = array(
          'posts_per_page'  => -1,
          'orderby'         => 'menu_order',
          'order'           => 'ASC'
        );

        $connected_tours = p2p_type( 'tours_to_destination' )->set_direction( 'to' )->get_connected( get_the_ID(),  $connection_args);

        $typed_tours = array();

        while( $connected_tours->have_posts() ) {
          $connected_tours->the_post();

          $p2p_id = p2p_type( 'tours_to_types' )->get_p2p_id( get_the_id(), $tour_type_id );
          if( $p2p_id ) {
            $typed_tours[] = get_the_id();
          }
        }
        wp_reset_postdata();

        $destination['tours'] = $typed_tours;


        array_push($destinations, $destination);
    }
    wp_reset_postdata();
}
?>


<?php if( has_post_thumbnail() && $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' ) ) { ?>
    <div class="Header" style="background-image: url('<?php echo esc_url($featured_image[0]); ?>');">
        <div class="Text"></div>
    </div>
<?php }?>

<div class="Page">
    <div class="Abstract">
        <?php
        the_content();

        //***************************************************************************
        // Output destination anchors
        //***************************************************************************
        if( $destinations ) { ?>
            <div class="DestinationAnchors Clear">
                <?php foreach( $destinations as $destination ) { ?>
                    <?php if( $destination['tours'] ) { ?>
                      <a class="Item" href="#<?php echo $destination['title']; ?>"><?php echo $destination['title']; ?></a>
                    <?php } ?>
                <?php } ?>
            </div><!--/.DestinationAnchors-->
        <?php } ?>

    </div><!--/.Abstract-->

    <h1><?php echo $tour_type_name; ?> Destinations In <?php the_title(); ?></h1>

    <?php
    if ( $destinations ) {
        foreach( $destinations as $destination ) { ?>

            <?php

            if( ! $destination['tours'] ) {
                continue;
            }
            ?>

            <a name="<?php echo $destination['title']; ?>">&nbsp</a>

            <div class="Destination Clear ">
                <div class="Column">
                    <h2><?php echo $destination['title']; ?></h2>

                    <a class="LabelButton" href="/tours?regionId=<?php echo get_the_ID(); ?>&destinationId=<?php echo $destination['id']; ?>&type=0">
                        <img class="Icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/region-listing-date-icon.png" alt="Dates Icon">
                        <span class="Label"><strong>View All Dates</strong></span>
                        <img class="RightArrowIcon" src="<?php echo get_template_directory_uri(); ?>/assets/images/region-listing-right-arrow-icon.png" alt="Right arrow">
                    </a>

                    <?php
                    //***************************************************************************
                    // Destination Image
                    //***************************************************************************
                    if( has_post_thumbnail( $destination['id'] ) ) {
                        $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $destination['id'] ), 'full' );

                        if( $featured_image && is_array( $featured_image ) ) { ?>

                            <div class="Image" style="background-image: url('<?php echo esc_url($featured_image[0]); ?>');">&nbsp;</div>

                        <?php
                        }
                    } ?>

                </div><!--/.Column-->

                <div class="Column Two">
                    <div class="Abstract Clear">
                        <?php echo get_post_meta( $destination['id'], 'duvine_abstract', true ); ?>
                    </div><!--/.Abstract-->

                    <div class="Scrollable-Area Clear">
                        <div class="Scrollable">
                                <div class="Listing-Row">

                                    <?php
                                    //***************************************************************************
                                    // Destination Tours
                                    //***************************************************************************
                                    if( $destination['tours'] ) {
                                      foreach( $destination['tours'] as $typed_tour_id ) {

                                        $tour_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $typed_tour_id ), 'medium' );
                                        ?>


                                        <div class="Tour Clear">
                                            <a href="<?php echo get_permalink( $typed_tour_id ); ?>">
                                                <div class="Image" style="background-image: url('<?php echo $tour_thumbnail[0]; ?>');background-size: 180px 108px" alt="Tour Thumbnail Image"></div>
                                                <h2 class="TourTitle"><?php echo get_the_title( $typed_tour_id ); ?></h2>
                                            </a>

                                            <div class="TourInfo">
                                                <div class="Item"><strong>Rider Level:</strong><?php echo duvine_tour_level( get_post_meta( $typed_tour_id, 'duvine_tour_level', true ) ); ?></div>
                                                <div class="Item"><strong>Tour Type:</strong><?php echo $tour_type_name; ?></div>


                                                <a class="LabelButton" style="margin-top: 3px;" href="<?php get_permalink($typed_tour_id); ?>">
                                                    <img class="Icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/info.png" alt="Dates Icon">
                                                    <span>View Itinerary</span>
                                                    <img class="RightArrowIcon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/right-arrow.png" alt="Right arrow">
                                                </a>


                                                <a class="LabelButton" href="/tours?tourId=<?php echo $typed_tour_id; ?>">
                                                    <img class="Icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/calendar.png" alt="Dates Icon">
                                                    <span>View All Dates</span>
                                                    <img class="RightArrowIcon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/right-arrow.png" alt="Right arrow">
                                                </a>
                                            </div>
                                        </div>

                                        <?php
                                      }
                                    }
                                    ?>

                                </div><!--/.Listing-Row-->
                        </div><!--/.Scrollable-->
                        <span class="Scrollable-Prev">prev</span>
                        <span class="Scrollable-Next">next</span>
                    </div><!--/.Scrollable-Area-->
                </div><!--/.Column.Two-->
            </div><!--/.Destination-->

        <?php
        }  // foreach $destinations
    } // if $destinations
    ?>


</div><!--/.Page-->
