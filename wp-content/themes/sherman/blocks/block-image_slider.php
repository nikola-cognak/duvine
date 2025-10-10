<?php 
/**
 * MODULE - Image Slider
 *
 * Slider of custom images with title and link
 *
 * @subfield image_slider_items (repeater)
 *
 */


?>

<?php if( have_rows('image_slider_items') ): ?>
        <div class="l-container l-container--small">
            <div class="image-slider">
                <div class="image-slider__slider">
                    <?php while ( have_rows('image_slider_items') ) : the_row(); ?>
                        <?php
                        $link = get_sub_field('link');
                        if( $link ): 
                            $link_url = $link['url'];
                            //$link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                        endif;
                        ?>
                        <div class="image-slider__slide sk-slide">
                            
                            <?php if(get_sub_field('image')) : ?>
                                <?php if( $link ) echo '<a href="'.$link_url.'"'.' target="'.$link_target.'">'; ?>
                                <img src="<?php the_sub_field("image"); ?>" alt="<?php the_sub_field('title'); ?>" />
                                <?php if( $link ) echo '</a>'; ?>
                            <?php endif; ?>
                            <?php if(get_sub_field('title')) : ?>
                                <div class="image-slider__caption">
                                    <?php the_sub_field('title'); ?>
                                </div>
                            <?php endif; ?>

                        </div>

                    <?php endwhile; ?>
                </div>
                <div class="image-slider__arrows"></div>
            </div>
        </div>
<?php endif; ?>