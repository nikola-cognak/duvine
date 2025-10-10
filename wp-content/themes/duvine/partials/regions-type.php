<?php
$tour_slug = (isset( $_GET['tourType'] ) ? trim(strip_tags( $_GET['tourType'] )) : '');
$tour_type = get_posts(array(
    'name' => $tour_slug,
    'posts_per_page' => 1,
    'post_type' => 'duvine_tour_types',
    'post_status' => 'publish'
));

//***************************************************************************
// Tour type found
//***************************************************************************
if( $tour_type ) {
    foreach( $tour_type as $type) {
        $type_id    = $type->ID;
        $type_name  = $type->post_title;
    ?>


    <h1 class="Page-Title Tour Center"><?php echo $type_name; ?> Tours by Region</h1>

    <div class="Page Section Regions">

        <div class="Listing-Section Clear">

            <?php
            $the_regions = array();

            //***************************************************************************
            // Look up tours by the passed type, then build an array of the regions data
            //***************************************************************************
            $tours_query = new WP_Query( array(
                'post_type'     => 'duvine_tours',
                'post_status'   => 'publish',
                'posts_per_page'=> -1,
                'connected_type'        => 'tours_to_types',
                'connected_direction'   => 'to',
                'connected_items'       => $type->ID,

            ) );

            if ( $tours_query->have_posts() )  {

                p2p_type( 'tours_to_destination' )->each_connected( $tours_query, array(), 'destinations' );

                while( $tours_query->have_posts() ) {
                    $tours_query->the_post();

                    if( $post->destinations ) {

                        p2p_type( 'destinations_to_regions' )->each_connected( $post->destinations, array(), 'region' );

                        foreach( $post->destinations as $post ) { setup_postdata( $post );

                            if( $post->region ) {
                                foreach( $post->region as $post ) {
                                    $region_name = get_the_title( $post->ID );

                                    if( !isset( $the_regions[ $region_name ] ) ) {
                                        $the_regions[ $region_name ] = array(
                                            'ID'            => $post->ID,
                                            'menu_order'    => $post->menu_order,
                                            'permalink'     => get_permalink( $post->ID ),
                                        );
                                    }

                                }
                            }
                        }
                    }
                }
            }

            //***************************************************************************
            // Output the regions
            //***************************************************************************
            if( $the_regions ) {

                //***************************************************************************
                // Sort the regions by the menu_order element
                //***************************************************************************
                uasort( $the_regions, function($a, $b){
                    return $a['menu_order'] > $b['menu_order'];
                });

                $counter = 1;
                $div_is_open = false;
                foreach( $the_regions as $region_name => $region ) { ?>

                      <?php if( 1 == $counter ) { ?>
                          <div class="Row Clear">
                      <?php } ?>

                      <div class="Region <?php echo ($counter % 3 == 1) ? 'First' : null ?>">

                        <a class="Grid-Link" href="<?php echo esc_url( $region['permalink'] . '?tourType=' . $tour_slug ); ?>">

                            <?php $small_image = get_post_meta( $region['ID'], 'duvine_region_small_image', true ); ?>
                            <?php if( ! $small_image ) : ?>
                                <?php $small_image = get_post_meta( $region['ID'], 'duvine_region_index_image_id', true ); ?>
                            <?php endif; ?>
                            <?php if( $small_image ) { ?>
                                <?php echo wp_get_attachment_image( $small_image, 'large') ?>
                            <?php } ?>

                            <span class="Title"><?php echo $region_name; ?></span>
                        </a>

                        <a class="LabelButton" href="<?php echo esc_url( $region['permalink'] . '?tourType=' . $tour_slug ); ?>">
                            <img class="Icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/destination.png" alt="Destination Icon">
                            <span class="Label"><strong>Destinations</strong> in <?php echo $region_name; ?></span><img class="RightArrowIcon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/right-arrow.png" alt="Right arrow">
                        </a>

                        <a class="LabelButton" href="/tours?regionId=<?php echo $region['ID']; ?>&type=<?php echo $type_id; ?>">
                            <img class="Icon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/date.png" alt="Dates Icon">
                            <span class="Label"><strong><?php echo $type_name; ?> Dates</strong> in <?php echo $region_name; ?></span><img class="RightArrowIcon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/right-arrow.png" alt="Right arrow">
                        </a>
                      </div><!--/.Region-->

                      <?php
                      if( $counter % 3 == 0 ) { $div_is_open = true; ?>

                          </div><!--/.Row.Clear-->
                          <div class="Row Clear">

                      <?php } ?>


                <?php $counter++; } // foreach the_regions ?>

                  <?php if( $div_is_open ) { ?>
                      </div><!--/.Row.Clear-->
                  <?php } ?>

            <?php } // if the_regions ?>

        </div><!--/.Listing-Section.Clear-->

    </div><!--/.Page.Section.Regions-->


<?php
    } //foreach tour_type
}
else {
//***************************************************************************
// Tour type not found
//***************************************************************************
?>

<?php } ?>
