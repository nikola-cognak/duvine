<?php
/**
 * Template Name: Two Column Page
 */
get_header(); ?>

    <?php while ( have_posts() ) : the_post(); ?>

            <?php get_template_part( 'partials/content', 'twocolumn' ); ?>

    <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
