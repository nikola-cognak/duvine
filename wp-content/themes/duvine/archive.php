<?php get_header(); ?>

    <div class="Page">
        <div class="Page Double Listing-Section">

            <div class="Listing-Total Section">
                <?php
                global $wp_query;
                duvine_pagination_links( $wp_query, $label = 'Blog' ); ?>
            </div>


            <div class="Listing Blog">

                <?php if ( have_posts() ) : ?>

                    <?php the_archive_title( '<h1 class="Title">', '</h1>' ); ?>

                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php get_template_part( 'partials/content', 'index'); ?>

                    <?php endwhile; ?>

                <?php else : ?>

                    <?php get_template_part( 'partials/content', 'none' ); ?>

                <?php endif; ?>

            </div><!--/.Listing.Blog-->

            <div class="Listing-Total Section">
                <?php duvine_pagination_links( $wp_query, $label = 'Blog'  ); ?>
            </div>

        </div><!--/.Page.Double-->
    </div><!--/.Page-->

<?php get_footer(); ?>
