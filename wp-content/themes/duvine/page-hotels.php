<?php get_header(); ?>

    <div class="Page Double">

        <?php while ( have_posts() ) : the_post(); ?>

            <?php duvine_featured_image(); ?>

            <div class="Page">
                <?php get_template_part( 'partials/content', 'page' ); ?>
            </div><!--/.Page-->

            <hr class="Large">

            <div class="Listing-Section Clear">
                <div class="Page">
                <?php
                //***************************************************************************
                // Output Hotels
                //***************************************************************************
                $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
                $posts_per_page = 16;
                $hotels_query = new WP_Query(array(
                    'post_type'     => 'duvine_hotels',
                    'orderby'       => 'title',
                    'order'         => 'ASC',
                    'posts_per_page'=> $posts_per_page,
                    'paged'         => $paged,
                ));

                p2p_type( 'hotels_to_destinations' )->each_connected( $hotels_query, array(), 'destination' );

                if ( $hotels_query->have_posts() ) { ?>

                    <?php duvine_pagination_links( $hotels_query, $label = 'Hotels' ); ?>

                    <div class="Listing Grid Clear">
                        <?php
                        $counter = 1;
                        $div_is_open = false;
                        while ( $hotels_query->have_posts() ) {
                            $hotels_query->the_post();
                            $div_is_open = false;
                        ?>


                            <?php if( 1 == $counter ) { ?>
                        		<div class="Listing-Row Clear">
                            <?php } ?>

                            <?php get_template_part( 'partials/hotels', 'index' ); ?>

                            <?php if( $counter % 4 == 0 ) { $div_is_open = true; ?>

                                </div><!--/.Listing-Row.Clear-->
                                <div class="Listing-Row Clear">

                    		<?php } // if $video_count % 4 ?>

                        <?php
                            $counter++;
                        } // while $hotels_query ?>

                        <?php if( $div_is_open || $counter <= $posts_per_page ) { ?>
                            </div><!--/.Listing-Row.Clear-->
                        <?php } ?>

                    </div><!--/.Listing.Grid.Clear-->

                    <div class="Section">
                        <?php duvine_pagination_links( $hotels_query, $label = 'Hotels' ); ?>
                    </div>

                <?php } // if $hotels_query->have_posts

                wp_reset_postdata();
                ?>
                </div>
            </div><!--/.Listing-Section.Clear-->



        <?php endwhile; // end of the loop. ?>

    </div><!--/.Page.Double-->

<?php get_footer(); ?>
