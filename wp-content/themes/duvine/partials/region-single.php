<?php
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

        array_push($destinations, array(
            'id'        => get_the_ID(),
            'title'     => get_the_title()
        ));
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
                    <a class="Item" href="#<?php echo $destination['title']; ?>"><?php echo $destination['title']; ?></a>
                <?php } ?>
            </div><!--/.DestinationAnchors-->
        <?php } ?>

    </div><!--/.Abstract-->

    <h1>Destinations In <?php the_title(); ?></h1>

    <?php
    if ( $destinations ) {
        foreach( $destinations as $destination ) { ?>

            <?php
            $connection_args = array(
              'posts_per_page'  => -1,
              'orderby'         => 'menu_order',
              'order'           => 'ASC'
            );
            $connected_tours = p2p_type( 'tours_to_destination' )->set_direction( 'to' )->get_connected( $destination['id'],  $connection_args);

            if( ! $connected_tours->have_posts() ) {
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
                                    while( $connected_tours->have_posts() ) : $connected_tours->the_post();

                                        $tour_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
                                    ?>

                                    <div class="Tour Clear">
                                        <a href="<?php the_permalink(); ?>">
                                            <div class="Image" style="background-image: url('<?php echo $tour_thumbnail[0]; ?>');background-size: 180px 108px" alt="Tour Thumbnail Image"></div>
                                            <h2 class="TourTitle"><?php the_title(); ?></h2>
                                        </a>

                                        <div class="TourInfo">
                                            <div class="Item"><strong>Rider Level:</strong><?php echo duvine_tour_level( get_post_meta( $post->ID, 'duvine_tour_level', true ) ); ?></div>
                                            <div class="Item"><strong>Tour Type:</strong><?php echo do_shortcode( '[p2p_connected type=tours_to_types mode=inline]' ); ?></div>


                                            <a class="LabelButton" style="margin-top: 3px;" href="<?php the_permalink(); ?>">
                                                <img class="Icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/info.png" alt="Dates Icon">
                                                <span>View Itinerary</span>
                                                <img class="RightArrowIcon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/right-arrow.png" alt="Right arrow">
                                            </a>


                                            <a class="LabelButton" href="/tours?tourId=<?php echo $post->ID ?>">
                                                <img class="Icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/calendar.png" alt="Dates Icon">
                                                <span>View All Dates</span>
                                                <img class="RightArrowIcon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/right-arrow.png" alt="Right arrow">
                                            </a>
                                        </div>
                                    </div>

                                    <?php endwhile; wp_reset_postdata(); ?>

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
