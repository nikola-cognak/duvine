<div class="Page Double Top Breadcrumb">
        <a>On The Road</a> ›
        <a href="/videos">Videos</a> ›
        <a><?php the_title(); ?></a>
    </div><!--/.Page.Double.Top.Breadcrumb-->

    <div class="Page Double TwoColumn Body-Related Clear">
        <div class="Column One">
            <article class="Video Detail">

                <h1 class="Page-Title"><?php the_title(); ?></h1>

                <div class="Clear video-container">
                    <?php
                    $youtube_url = get_post_meta(get_the_ID(), 'duvine_video_youtube', true);
                    if( !empty( $youtube_url ) )  { ?>

                        <div id="youtube-player" data-url="<?php echo esc_url( $youtube_url ); ?>"></div>

                    <?php } else { ?>

                        <?php get_template_part( 'partials/videos', 'player' ); ?>

                    <?php } ?>
                </div><!--/.Clear-->

                <aside>
                    <?php
                    $connected_tours = new WP_Query( array(
                      'connected_type'  => 'tours_to_video',
                      'connected_items' => get_queried_object(),
                      'nopaging'        => true,
                      'orderby'         => 'title',
                      'order'           => 'ASC'
                    ) );

                    if ( $connected_tours->have_posts() ) { ?>

                        <div class="Video-Detail Title">
                            See it for yourself on these tours:
                        </div>

                        <div class="Listing Grid Related Video-Detail Clear">

                                <?php
                                $tour_counter = 1;
                                $div_is_open = false;
                                while ( $connected_tours->have_posts() ) {
                                    $connected_tours->the_post();
                                    $div_is_open = false;
                                ?>

                                    <?php if( 1 == $tour_counter ) { ?>
                                        <div class="Listing-Row Clear">
                                    <?php } ?>

                                    <div class="Listing-Item Related-Item">
                                        <a href="<?php the_permalink(); ?>">
                                            <span class="Title"><?php the_title(); ?></span>
                                        </a>
                                    </div>

                                    <?php if( $tour_counter % 4 == 0 ) { $div_is_open = true; ?>

                                        </div><!--/.Listing-Row-->
                                        <div class="Listing-Row Clear">

                                    <?php } // if $video_count % 4 ?>

                                <?php
                                    $tour_counter++;
                                } // while $connected_tours ?>


                            <?php if( $div_is_open ) { ?>
                                </div><!--/.Listing-Row.Clear-->
                            <?php } ?>


                        </div><!--/.Listing.Grid.Related.Video-Detail.Clear-->
                        <?php
                        wp_reset_postdata();
                    } // if $connected_tours
                    ?>
                </aside>

                <div class="text">
                    <?php the_content(); ?>
                </div><!--/.text-->

            </article><!--/.Video Detail-->

        </div><!--/.Column.One-->

        <div class="Column Two">
            <div class="Section">
                <?php
                $related_videos = new WP_Query( array(
                    'post_type' => 'duvine_videos',
                    'post__in' => array( 5672, 5678, 5678, 5669 ),
                    'post__not_in' => array( $post->ID )
                ) );
                ?>

                <div class="Listing Related Gallery">
                    <?php while ( $related_videos->have_posts() ) : $related_videos->the_post(); ?>

                        <div class="Listing-Item">
                            <a class="Listing-Image" href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'medium', array( 'class' => 'Grayscale' ) ); ?>
                            </a>
                            <a class="Title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </div>

                    <?php endwhile; wp_reset_postdata(); ?>
                </div><!--/.Listing Related Gallery-->
            </div><!--/.Section-->
        </div><!--.Column.Two-->
    </div><!--/.Page.Double.TwoColumn.Body-Related.Clear-->
    <script>
      (function($){
        $(function(){
          //$('.video-container').fitVids();
        });
      })(jQuery);
    </script>
