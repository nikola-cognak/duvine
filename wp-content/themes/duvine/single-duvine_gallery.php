<?php get_header(); ?>

    <?php while ( have_posts() ) : the_post(); ?>

        <div class="Page Double Top Breadcrumb">
            <a>On The Road</a> ›
            <a href="/photography">Photography</a> ›
            <a><?php the_title(); ?></a>
        </div>

        <div class="TwoColumn Body-Related Clear Page Double">
            <div class="Column One">
                <article class="Gallery Detail">
                    <h1 class="Page-Title"><?php the_title(); ?></h1>
                    <div class="Section Clear">
                        <?php
                        $slide_images = get_field('photography_gallery');
                        if( $slide_images ) { ?>

                            <div id="galleria" class="slick-is-loading slick-slider slider slider-for">
                                <?php foreach( $slide_images as $slide_image ) { ?>
                                        <div><img data-lazy="<?php echo esc_url( $slide_image['sizes']['gallery-detail'] ); ?>" aria-label="<?php echo esc_attr($slide_image['alt']); ?>" /></div>
                                <?php } // foreach slide_images ?>
                            </div>
                            <div class="slick-is-loading slick-slider slider slider-nav">
                                <?php foreach( $slide_images as $slide_image ) { ?>
                                        <div><img data-lazy="<?php echo esc_url( $slide_image['sizes']['gallery-thumb'] ); ?>" aria-label="<?php echo esc_attr($slide_image['alt']); ?>" /></div>
                                <?php } // foreach slide_images ?>
                            </div>
                        <?php } // if slide_images ?>
                    </div><!--/.Section.Clear-->

                    <div class="Section">
                        <p>
                            <?php the_tags( '<strong>Tags: </strong>', ', '); ?>
                        </p>
                    </div><!--/.Section-->

                    <?php duvine_sharethis(); ?>

                </article><!--/.Gallery.Detail-->

                <div class="Action-Bar Post-Navigation Clear">
                    <?php previous_post_link( '<div class="Back Left">%link</div>', 'Previous Gallery' ); ?>
                    <?php next_post_link( '<div class="Next Right">%link</div>', 'Next Gallery' ); ?>
                </div>

            </div><!--/.Column.One-->
            <div class="Column Two">
                <div class="Section">
                    <div class="Listing Related Gallery">
                        <?php
                        $post_in = array_delete( array( 3874, 4077, 4150, 4163, 4149 ), $post->ID );
                        $related_galleries = new WP_Query(array(
                            'post_type' => 'duvine_gallery',
                            'post__in' => $post_in
                        ));

                        while ( $related_galleries->have_posts() ) : $related_galleries->the_post(); ?>
                          <div class="Listing-Item">
                              <a class="Listing-Image" href="<?php the_permalink(); ?>">
                                  <?php
                                      $slide_images = get_field('photography_gallery');
                                      $image_url = $slide_images[0]['sizes']['gallery-thumb'];
                                  ?>
                                  <img src="<?php echo $image_url; ?>" alt="<?php echo $slide_images[0]['alt']; ?>" style="width:162px;height:auto;">
                              </a>
                              <a class="Title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                          </div>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div><!--/.Listing.Related.Gallery-->
                </div><!--/.Section-->

                <div class="Section">
                    <?php
                    $connected_tours = new WP_Query(array(
                        'connected_type'  => 'tours_to_gallery',
                        'connected_items' => get_queried_object(),
                        'nopaging'        => true,
                    ));
                    if( $connected_tours->have_posts()  ) { ?>
                        <span class="Title">Find these places on our:</span>
                        <?php
                        while ( $connected_tours->have_posts() ) : $connected_tours->the_post(); ?>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br>
                        <?php endwhile; wp_reset_postdata(); ?>
                    <?php } // $connected_tours->have_posts ?>
                </div><!--/.Section-->

            </div><!--/.Column.Two-->
        </div><!--/.TwoColumn.Body-Related.Clear.Page.Double-->

    <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
