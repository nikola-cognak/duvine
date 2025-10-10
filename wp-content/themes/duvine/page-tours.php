<?php
/**
 * Template Name: Tours Dates Page
 */
get_header(); ?>

        <h1 class="Page-Title Center Highlight HightlightCustomPage">Bike Tour Trip Finder</h1>

        <?php while ( have_posts() ) : the_post(); ?>

            <?php if( ! isset( $_GET['tourId'] ) ) { ?>

              <?php get_template_part( 'partials/tour', 'datesall' ); ?>

            <?php } else { ?>

              <?php get_template_part( 'partials/tour', 'datesingle' ); ?>

            <?php } // !isset ?>

        <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>