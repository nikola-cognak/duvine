<?php get_header(); ?>

        <?php while ( have_posts() ) : the_post(); ?>

            <div class="TwoColumn Detail Staff Clear Section Page Top">

                <div class="Column One">
                    <?php if( has_post_thumbnail()) { ?>
                        <?php the_post_thumbnail( 'guide-detail' ); ?>
                    <?php } ?>
                </div><!--/.Column.One-->

                <div class="Column Two">
                    <h1 class="Detail-Title"><?php the_title(); ?></h1>

                    <?php
                    $staff_group = get_post_meta( get_the_ID(), 'duvine_staff_group', true );

                    if( 'behind-the-scenes' == $staff_group ) { ?>
                        <div class="Detail-SubTitle">
                            <?php echo get_post_meta( get_the_ID(), 'duvine_staff_job_title', true ); ?>
                        </div>
                    <?php } ?>

                    <ul class="Slim-List Section">

                        <?php if( $bike_content = get_post_meta( get_the_ID(), 'duvine_staff_bike', true ) ) { ?>
                            <li><strong class="Uppercase">Bike:</strong> <?php echo $bike_content; ?></li>
                        <?php } ?>

                        <?php if( $eat_content = get_post_meta( get_the_ID(), 'duvine_staff_eat', true ) ) { ?>
                            <li><strong class="Uppercase">Eat:</strong> <?php echo $eat_content; ?></li>
                        <?php } ?>

                        <?php if( $drink_content = get_post_meta( get_the_ID(), 'duvine_staff_drink', true ) ) { ?>
                            <li><strong class="Uppercase">Drink:</strong> <?php echo $drink_content; ?></li>
                        <?php } ?>

                        <?php if( $sleep_content = get_post_meta( get_the_ID(), 'duvine_staff_sleep', true ) ) { ?>
                            <li><strong class="Uppercase">Sleep:</strong> <?php echo $sleep_content; ?></li>
                        <?php } ?>
                    </ul>

                    <?php
                    $connected_tours = new WP_Query( array(
                      'connected_type'  => 'tours_to_staff',
                      'connected_items' => get_queried_object(),
                      'nopaging'        => true,
                      'orderby'         => 'title',
                      'order'           => 'ASC'
                    ) );

                    if ( $connected_tours->have_posts() ) { ?>

                        <span class="Section-Title Staff">
                            <?php if( 'guides' == $staff_group ) { ?>
                                Meet me on these tours:
                            <?php } else { ?>
                                Ask me about my trips to:
                            <?php } ?>
                        </span>

                        <ul class="Slim-List Section">

                            <?php
                            $tour_counter = 1;
                            while ( $connected_tours->have_posts() ) { $connected_tours->the_post(); ?>

                                <li<?php if( $tour_counter > 5 ){ echo ' class="Collapse"';}?>><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

                            <?php
                                $tour_counter++;
                            } // while $connected_tours ?>

                            <?php if( $tour_counter > 5 ) { ?>
                                <li class="Link">
                                    <a href="#" class="Expand">Show All</a>
                                    <a href="#" class="Collapse">Show Less</a>
                                </li>
                            <?php } ?>

                        </ul><!--/.Slim-List.Section-->

                        <?php

                        wp_reset_postdata();
                    } // if $connected_tours
                    ?>

                    <div class="Action-Bar Black">
                        <a class="Left" href="#">Print</a>
                        <span class="Left Page">|</span>
                        <a class="Left" href="#">Share</a>
                    </div>

                </div><!--/.Column.Two-->


            </div><!--/.TwoColumn.Detail.Staff.Clear.Section.Page.Top-->

            <hr class="Large-Page">

            <div class="TwoColumn Clear Section Page Double">
                <div class="Column One">
                    <div class="Clear Body"><?php the_content(); ?></div><!--/.Clear.Body-->
                </div><!--/.Column.One-->

                <?php
                    $connected_videos = new WP_Query( array(
                      'connected_type'  => 'staff_to_video',
                      'connected_items' => get_queried_object(),
                      'nopaging'        => true,
                      'orderby'         => 'title',
                      'order'           => 'ASC'
                    ) );

                    if ( $connected_videos->have_posts() ):
                ?>

                <div class="Column Two Right">
                    <?php while( $connected_videos->have_posts() ): $connected_videos->the_post(); ?>


                    <div class="Guide-Related-Video Clear">
                        <div class="Offset No-Print">
                            <a href="<?php the_permalink(); ?>" rel=".Overlay" class="Border-Lines Inline Video-Overlay">
                                <?php echo get_the_post_thumbnail( $post->ID, 'large', array( 'style' => 'width:393px;height:auto;' ) ); ?>
                            </a>
                        </div>
                        <div class="Video-Link">
                            <span class="Right"><a class="Button" href="/videos"><span>Watch More</span></a></span>
                        </div>
                    </div>

                    <?php endwhile; ?>
                </div><!--/.Column.Two.Right-->

                <?php endif; ?>

            </div><!--/.TwoColumn.Clear.Section.Page.Double-->


        <?php endwhile; // end of the loop. ?>

        <div class="Overlay Border-Lines">
            <div class="Close">close</div>
            <div class="Overlay-Content"></div>
            <div class="Overlay-Loading"><img src="/wp-content/themes/duvine/assets/images/icons/loading.gif" alt="Loading" /></div>
        </div>

<?php get_footer(); ?>
