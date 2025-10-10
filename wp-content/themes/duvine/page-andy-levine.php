<?php
/**
* Template Name: Meet Andy
**/

get_header(); ?>


    <?php while ( have_posts() ) : the_post(); ?>

        <div class="Page">

             <?php if ( has_post_thumbnail() ) {  ?>
                <div class="Content-Header-Image">
                    <?php the_post_thumbnail(); ?>
                    <span class="Image-Caption"><?php echo get_post_meta( $post->ID, 'duvine_page_header_image_text', true ); ?></span>
                </div>
            <?php } ?>

            <div class="TwoColumn Body-Sub Clear Page">
                    <div class="Column One Andy-Bio">
                        <p class="Clear Body"></p>
                        <?php the_content(); ?>
                        <hr>
                        <div class="Page">
                            <?php
                            $connected_videos = new WP_Query( array(
                              'connected_type'  => 'page_to_videos',
                              'connected_items' => get_queried_object(),
                              'nopaging'        => true
                            ) );

                            if ( $connected_videos->have_posts() ): while( $connected_videos->have_posts() ): $connected_videos->the_post();
                            ?>

                            <strong><?php the_title(); ?></strong>

                                <div class="Border-Lines Offset No-Print">
                                    <a href="<?php the_permalink(); ?>" rel=".Overlay" class="Video-Overlay">
                                        <?php the_post_thumbnail( 'full', array( 'style' => 'width:393px;height:auto;' ) ); ?>
                                    </a>
                                </div>

                            <?php endwhile; endif; wp_reset_postdata(); ?>
                        </div>
                        <hr>

                        <a href="<?php echo esc_url(get_post_meta( $post->ID, 'duvine_page_image_url', true ) ); ?>">
                            <?php
                            $image_id = get_post_meta( $post->ID, 'duvine_page_image_id', true );
                            if( $image_id ) {
                              echo wp_get_attachment_image( $image_id, 'full', false, array( 'style' => 'width:560px;height:auto' ) );
                            }
                            ?>
                        </a>
                    </div>
                    <div class="Column Two Andy-Bio">
                        <div class="Page Double Section Source-Andy">
                            <q>&ldquo;<?php echo get_post_meta( $post->ID, 'duvine_page_pull_quote', true ); ?>&rdquo;</q>
                            <div class="Page Double Right Uppercase">-&nbsp;<?php echo get_post_meta( $post->ID, 'duvine_page_pull_quote_name', true ); ?></div>
                        </div>

                        <div class="Accordian Yellow" role="tablist">
                            <span class="AccordianHeader"><?php echo get_post_meta( $post->ID, 'duvine_page_accordion_1_title', true ); ?></span>
                            <div class="AccordianItem" style="display: block;"><?php echo get_post_meta( $post->ID, 'duvine_page_accordion_1_body', true ); ?></div>

                            <span class="AccordianHeader"><?php echo get_post_meta( $post->ID, 'duvine_page_accordion_2_title', true ); ?></span>
                            <div class="AccordianItem" style="display:none"><?php echo get_post_meta( $post->ID, 'duvine_page_accordion_2_body', true ); ?></div>

                            <span class="AccordianHeader"><?php echo get_post_meta( $post->ID, 'duvine_page_accordion_3_title', true ); ?></span>
                            <div class="AccordianItem" style="display:none"><?php echo get_post_meta( $post->ID, 'duvine_page_accordion_3_body', true ); ?></div>

                            <span class="AccordianHeader"><?php echo get_post_meta( $post->ID, 'duvine_page_accordion_4_title', true ); ?></span>
                            <div class="AccordianItem" style="display:none"><?php echo get_post_meta( $post->ID, 'duvine_page_accordion_4_body', true ); ?></div>

                            <span class="AccordianHeader"><?php echo get_post_meta( $post->ID, 'duvine_page_accordion_5_title', true ); ?></span>
                            <div class="AccordianItem" style="display:none"><?php echo get_post_meta( $post->ID, 'duvine_page_accordion_5_body', true ); ?></div>

                            <span class="AccordianHeader"><?php echo get_post_meta( $post->ID, 'duvine_page_accordion_6_title', true ); ?></span>
                            <div class="AccordianItem" style="display:none"><?php echo get_post_meta( $post->ID, 'duvine_page_accordion_6_body', true ); ?></div>
                        </div>
                    </div>
                </div>

        </div><!--/.Page-->

    <?php endwhile; // end of the loop. ?>

    <script>
        jQuery(function( $ ) {
            $(".Accordian").accordion({
                collapsible: true,
                heightStyle: "content"
            });
        });
    </script>

<?php get_footer(); ?>
