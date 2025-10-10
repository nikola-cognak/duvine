<?php
/**
 * Template Name: No Header/Footer Page
 */
?>

    <?php while ( have_posts() ) : the_post(); ?>

        <?php duvine_featured_image(); ?>

        <div class="Page">
            <div class="Page Double">
                <?php get_template_part( 'partials/content', 'page' ); ?>
            </div><!--/.Page.Double-->
        </div><!--/.Page-->

    <?php endwhile; // end of the loop. ?>
