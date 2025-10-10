<?php
/**
* Template Name: Bikes Page
**/

get_header(); ?>

    <?php while ( have_posts() ) : the_post(); ?>

        <div class="Page Double">

            <?php get_template_part( 'partials/content', 'page' ); ?>

            <hr class="Large" />

            <div class="Listing-Section Clear">
                <div class="Page">

                    <?php
                    //***************************************************************************
                    // Output Bikes
                    //***************************************************************************
                    $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
                    $posts_per_page = 16;
                    $bike_query = new WP_Query(array(
                        'post_type'     => 'duvine_bikes',
                        'orderby'       => 'menu_order',
                        'order'         => 'ASC',
                        'posts_per_page'=> $posts_per_page,
                        'paged'         => $paged,
                    ));


                    if ( $bike_query->have_posts() ) { ?>

                        <?php duvine_pagination_links( $bike_query, $label = 'Bikes' ); ?>

                        <div class="Listing Grid Grid-Bikes Clear">
                            <?php
                            $counter = 1;
                            $div_is_open = false;
                            while ( $bike_query->have_posts() ) {
                                $bike_query->the_post();
                                $div_is_open = false;
                            ?>


                                <?php if( 1 == $counter ) { ?>
                                    <div class="Listing-Row Clear">
                                <?php } ?>


                                <?php get_template_part( 'partials/bikes', 'index' ); ?>

                                <?php if( $counter % 4 == 0 ) { $div_is_open = true; ?>

                                    </div><!--/.Listing-Row-->
                                    <div class="Listing-Row Clear">

                                <?php } // if $bike_count % 4


                                $counter++;
                            } // while $bike_query ?>


                            <?php if( $div_is_open || $counter <= $posts_per_page ) { ?>
                                </div><!--/.Listing-Row.Clear-->
                            <?php } ?>

                        </div><!--/.Listing.Grid-->

                        <div class="Section">
                            <?php duvine_pagination_links( $bike_query, $label = 'Bikes' ); ?>
                        </div>


                    <?php } // if $bike_query->have_posts

                    wp_reset_postdata();
                    ?>
                </div><!--/.Page-->
            </div><!--/.Listing-Section-->


        </div><!--/.Page.Double-->

    <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
