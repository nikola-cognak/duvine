<?php get_header(); ?>

    <?php
    // Featured image for blog
    $posts_page_id = get_option( 'page_for_posts');
    if( $posts_page_id && has_post_thumbnail( $posts_page_id )  ) { ?>

      <div class="Content-Header-Image">
        <?php echo get_the_post_thumbnail( $posts_page_id, 'full' ); ?>
        <span class="Image-Caption"></span>
      </div><!--/.Content-Header-Image-->

    <?php } ?>

    <div class="Page">
        <div class="Page Double Listing-Section">

            <form class="Listing-Search" action="<?php echo get_permalink( get_option('page_for_posts' ) ); ?>" method="get">
                <input type="text" class="Input" id="searchString" name="searchString" placeholder="Search Blog" value="">
                <button class="Button Action" id="searchVideoButton" type="submit"><span>Search</span></button>
            </form>


            <div class="Listing-Total Section">
                <?php
                global $wp_query;
                duvine_pagination_links( $wp_query, $label = 'Blog' ); ?>
            </div>

            <div class="Listing Blog">

                <?php if ( have_posts() ) : ?>

                    <?php
                    $counter = 1;
                    while ( have_posts() ) : the_post(); ?>

                        <?php get_template_part( 'partials/content', 'index'); ?>

                        <?php if( $counter % get_option('posts_per_page') != 0  ) { ?>
                            <hr>
                        <?php } ?>

                    <?php
                            $counter++;
                    endwhile; ?>

                <?php else : ?>

                    <?php get_template_part( 'partials/content', 'none' ); ?>

                <?php endif; ?>


            </div><!--/.Listing.Blog-->

            <div class="Listing-Total Listing-Last Section">
                <?php duvine_pagination_links( $wp_query, $label = 'Blog'  ); ?>
            </div>

        </div><!--/.Page.Double-->
    </div><!--/.Page-->

<?php get_footer(); ?>
