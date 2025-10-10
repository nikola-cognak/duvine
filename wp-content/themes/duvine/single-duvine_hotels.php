<?php get_header(); ?>

    <div class="Page Double">

        <article class="Page">
            <div class="TwoColumn Detail Clear Section Page Top">

                <?php
                $destination_connected  = p2p_type( 'hotels_to_destinations' )->get_connected( $post->ID );
                $tours_connected        = p2p_type( 'tours_to_hotels' )->set_direction( 'to' )->get_connected( $post->ID );
                $gallery_connected      = p2p_type( 'hotels_to_galleries' )->get_connected( $post->ID );

                // Bit of a hack to get the region
                while( $destination_connected->have_posts() ) {
                    $destination_connected->the_post();
                    $hotel_region = do_shortcode( '[p2p_connected type=destinations_to_regions mode=inline]' );
                }
                wp_reset_postdata();
                ?>

                <?php while ( have_posts() ) : the_post(); ?>

                    <div class="Column One">
                        <div class="Breadcrumb Section">
                            <a href="/experience">Your Experience</a> &rsaquo;
                            <a href="/hotels">Sleep</a> &rsaquo;
                            <a><?php the_title(); ?></a>
                        </div>

                        <?php if( $gallery_connected->have_posts() ) : ?>

                            <div class="Slideshow Hotel-Detail">
                                <div class="slick-is-loading slick-slider slider">
                                <?php while( $gallery_connected->have_posts() ) {
                                    $gallery_connected->the_post();
                                    $slide_images = get_field('photography_gallery', $post->ID);

                                    if( $slide_images ) {
                                        foreach( $slide_images as $slide_image ) { ?>

                                            <a href="#">
                                                <img data-lazy="<?php echo esc_url( $slide_image['sizes']['gallery-medium'] ); ?>" aria-label="<?php echo $slide_image['alt'] ?>" />
                                            </a>

                                        <?php } // foreach slide_images
                                    } // if slide_images
                                } // while gallery_connect
                                wp_reset_postdata(); ?>
                                </div>
                            </div>

                        <?php else: ?>

                            <?php the_post_thumbnail( 'guide-detail' ); ?>

                        <?php endif; ?>



                    </div><!--/.Column.One-->

                    <div class="Column Two">
                        <h1 class="Detail-Title"><?php the_title(); ?></h1>

                        <p>
                            <?php echo $hotel_region; ?> / <?php echo do_shortcode( '[p2p_connected type=hotels_to_destinations mode=inline]' ); ?>
                        </p>

                        <div class="Clear Body">
                            <?php the_content(); ?>
                        </div><!--/.Clear.Body-->


                        <?php if( $tours_connected->have_posts() ): ?>
                        <div class="Section">
                            <span class="Title">Stay here with us on these tours:</span><br>
                            <?php while( $tours_connected->have_posts() ): $tours_connected->the_post(); ?>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                        <?php endif; ?>

                    </div><!--/.Column.Two-->

                <?php endwhile; // end of the loop. ?>

            </div><!--/.TwoColumn.Detail.Clear.Section.Page.Top-->
        </article><!--/.Page-->

        <?php duvine_sharethis(); ?>

        <div class="Action-Bar Post-Navigation Clear">
            <?php
            global $post;
            duvine_get_previous_post_link_alpha( 'duvine_hotels', $post->post_title, 'Previous Hotel'  );
            duvine_get_next_post_link_alpha( 'duvine_hotels', $post->post_title, 'Next Hotel'  );
            ?>
        </div>

    </div><!--/.Page.Double-->

<?php get_footer(); ?>
