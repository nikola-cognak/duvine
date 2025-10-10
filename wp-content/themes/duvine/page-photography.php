<?php
/**
* Template Name: Photography Page
**/

get_header(); ?>

    <?php while ( have_posts() ) : the_post(); ?>

        <div class="Page Double">
            <div class="Listing-Section">

                <?php
                //***************************************************************************
                // Output Galleries
                //***************************************************************************
                $posts_per_page = 4;
                $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
                $galleries_query = new WP_Query(array(
                    'post_type'     => 'duvine_gallery',
                    'tax_query' => array(
                      array(
                        'taxonomy' => 'gallerytag',
                        'field'    => 'slug',
                        'terms'    => 'photography',
                      ),
                    ),
                    'orderby'       => 'menu_order',
                    'order'         => 'ASC',
                    'posts_per_page'=> $posts_per_page,
                    'paged'         => $paged,
                ));

                if ( $galleries_query->have_posts() ) { ?>


                    <div class="Listing-Total Section">
                        <?php duvine_pagination_links( $galleries_query, $label = 'Photography' ); ?>
                    </div><!--/.Listing-Total.Section-->


                    <div class="Section Listing Gallery Banner">


                        <?php
                        $counter = 1;
                        while ( $galleries_query->have_posts() ) { $galleries_query->the_post(); ?>

                            <?php get_template_part( 'partials/photography', 'index' ); ?>

                            <?php if( $counter % $posts_per_page != 0  ) { ?>
                                <hr>
                            <?php } ?>

                        <?php
                            $counter++;
                        } // while $galleries_query ?>

                    </div><!--/.Section.Listing.Gallery.Banner-->


                    <div class="Listing-Total Section">
                        <?php duvine_pagination_links( $galleries_query, $label = 'Photography' ); ?>
                    </div><!--/.Listing-Total.Section-->


                <?php } // if $galleries_query->have_posts
                wp_reset_postdata();
                ?>


            </div><!--/.Listing-Section-->
        </div><!--/.Page.Double-->


    <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
