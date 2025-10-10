<?php get_header(); ?>

    <div class="Page Double">

        <?php while ( have_posts() ) : the_post(); ?>

            <?php get_template_part( 'partials/bikes', 'single' ); ?>

        <?php endwhile; // end of the loop. ?>


    </div><!--/.Page.Double-->

<?php get_footer(); ?>
