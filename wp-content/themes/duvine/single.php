<?php get_header(); ?>

        <?php while ( have_posts() ) : the_post(); ?>

            <div class="Page Double Top Breadcrumb">
                <a>On The Road</a> ›
                <a href="/blog">Blog</a> ›
                <a><?php the_title(); ?></a>
            </div><!--/.Page.Double.Top.Breadcrumb-->

            <div class="Page Double TwoColumn Body-Related Clear">
                <div class="Column One">

                  <?php get_template_part( 'partials/content', 'single' ); ?>

                  <?php
                  // If comments are open or we have at least one comment, load up the comment template
                  if ( comments_open() || get_comments_number() ) :
                      //comments_template();
                  endif;
                  ?>

                  <div class="Action-Bar Post-Navigation Clear">
                      <?php previous_post_link( '<div class="Back Left">%link</div>', 'Previous Blog' ); ?>
                      <?php next_post_link( '<div class="Next Right">%link</div>', 'Next Blog' ); ?>
                  </div>

                </div><!--/.Column.One-->

                <div class="Column Two">
                    <div class="Section">
                        <?php
                        get_sidebar( 'blog' );

                        //***************************************************************************
                        // Related Tours
                        //***************************************************************************
                        $connected_tours = new WP_Query( array(
                          'connected_type'  => 'tours_to_blog',
                          'connected_items' => get_queried_object(),
                          'nopaging'        => true,
                          'orderby'         => 'title',
                          'order'           => 'ASC'
                        ) );

                        if( $connected_tours->have_posts() ) { ?>

                            <span class="Title">Learn more about our:</span>

                            <?php while( $connected_tours->have_posts() ) { $connected_tours->the_post(); ?>

                                <p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>

                                <?php
                            } // while $connected_tours->have_posts
                            wp_reset_postdata();
                        } // if $connected_tours->have_posts

                        ?>
                    </div>
                </div><!--/.Column.Two-->

            </div><!--/.Page.TwoColumn-->


        <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
