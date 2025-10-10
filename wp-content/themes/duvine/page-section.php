<?php
/**
 * Template Name: Section Page
 */
get_header(); ?>

    <?php while ( have_posts() ) : the_post(); ?>

        <div class="Page Top">

            <?php duvine_featured_image(); ?>

            <div class="Page Section">
                <?php get_template_part( 'partials/content', 'pagecontent' ); ?>
            </div><!--/.Page.Section-->
        </div><!--/.Page.Top-->

    <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
