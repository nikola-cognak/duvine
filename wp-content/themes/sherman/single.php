<?php
/**
 * The template for displaying all single posts.
 *
 * @package sherman
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', $post->post_type ); ?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->

        <?php 
            if($post->post_type === 'tour') { 
                $related = get_field('d_related_tours'); 
            }
        ?>

        <?php if( $related ) : ?>

            <div class="tourrelated">
                <div class="l-container">

                    <?php if($post->post_type === 'tour') { ?>
                    <div class="tourrelated__wrapper">
                    <?php } ?>
                    
                        <h3 class="blockheader">Related Tours</h3>

                        <div class="tourrelated__grid">
                            <?php foreach( $related as $tour ) : ?>
                                <?php $tourlink = get_the_permalink( $tour->ID ); ?>
                                <div class="relatedtour">
                                    <?php
                                        sk_the_field('d_thumbnail_image', array(
                                            'before'      => '<a href="' . $tourlink . '" class="relatedtour__image">',
                                            'after'       => '</a>',
                                            'id'          => $tour->ID,
                                            'filter'      => 'sk_img_markup',
                                            'filter_args' => array(
                                                'img_size' => 'tour_thumbnail'
                                            )
                                        ));
                                    ?>
                                    <a href="<?php echo $tourlink; ?>" class="basiclink"><?php echo $tour->post_title; ?></a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>
	</div><!-- #primary -->

<?php get_footer(); ?>