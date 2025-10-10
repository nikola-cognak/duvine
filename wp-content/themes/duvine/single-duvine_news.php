<?php get_header(); ?>

        <?php while ( have_posts() ) : the_post(); ?>


            <div class="Page Double Top">
                <div class="Breadcrumb">
                    <a>On The Road</a> ›
                    <a href="/news">News</a> ›
                    <a><?php the_title(); ?></a>
                </div>
            </div><!--/.Page.Double.Top-->


            <div class="Page Double TwoColumn Body-Related Clear">
                <div class="Column One">
                    <article class="News Detail">
                        <div class="Header">
                            <h1 class="Page-Title"><?php the_title(); ?></h1>
                            <div class="Header-Details">
                                &nbsp;
                            </div>
                            <time class="Sub-Title" datetime="<?php the_date( 'c' ); ?>"><?php echo get_the_date( 'l, F j, Y \a\t g:i a' ); ?></time>
                        </div><!--/.Header-->

                        <div class="Section Clear">
                            <?php if ( has_post_thumbnail() ) {  ?>
                                <?php the_post_thumbnail( 'news-detail' ); ?>
                            <?php } ?>

                            <div class="Clear Body">
                                <?php the_content(); ?>
                            </div><!--/.Clear.Body-->
                        </div><!--/.Section.Clear-->


                        <?php
                        $connected_tours = new WP_Query( array(
                          'connected_type'  => 'tours_to_news',
                          'connected_items' => get_queried_object(),
                          'nopaging'        => true,
                          'orderby'         => 'title',
                          'order'           => 'ASC'
                        ) );

                        if ( $connected_tours->have_posts() ) { ?>


                            <div class="Video-Detail Title">
                                Experience it yourself on our:
                            </div>
                            <div class="Listing Grid Related RelatedTours Clear">

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

                            </div><!--/.Listing.Grid.Related.RelatedTours.Clear-->

                            <?php
                            wp_reset_postdata();
                        } // if $connected_tours
                        ?>

                        <?php duvine_sharethis(); ?>
                    </article><!--/.News.Detail-->


                    <div class="Action-Bar Post-Navigation Clear">
                        <?php previous_post_link( '<div class="Back Left">%link</div>', 'Previous News' ); ?>
                        <?php next_post_link( '<div class="Next Right">%link</div>', 'Next News' ); ?>
                    </div>

                </div><!--/.Column.One-->

                <div class="Column Two">

                </div><!--/.Column.Two-->
            </div><!--/.Page.Double.TwoColumn.Body-Related.Clear-->



        <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
