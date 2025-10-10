<?php
/*
 * Template Name: Tour Types Page
 */
get_header(); ?>

    <div class="Page Section Regions">

        <h1 class="Page-Title Tour Center">Tours by Type</h1>

        <?php while ( have_posts() ) : the_post(); ?>

            <div class="Tour-Type-Listing Clear Section">
                <?php
                //***************************************************************************
                // Output Regions
                //***************************************************************************
                $tour_types_query = new WP_Query(array(
                    'post_type'     => 'duvine_tour_types',
                    'posts_per_page'=> '-1',
                    'orderby'       => 'menu_order',
                    'order'         => 'ASC'
                ));

                if ( $tour_types_query->have_posts() ) { ?>

                    <?php

                    while ( $tour_types_query->have_posts() ) { $tour_types_query->the_post(); ?>

                        <?php get_template_part( 'partials/tours-type', 'index' ); ?>

                    <?php
                    } // while $tour_types_query ?>

                <?php } // if $tour_types_query->have_posts

                wp_reset_postdata();
                ?>

            </div><!--/.Row.Clear-->



        <?php endwhile; // end of the loop. ?>

    </div><!--/.Page.Double-->

<?php get_footer(); ?>
