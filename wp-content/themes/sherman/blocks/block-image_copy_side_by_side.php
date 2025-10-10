<?php 
/**
 * MODULE - Image Copy Side by Side
 *
 * Display image 50% / content 50%...alternate rows
 *
 * @subfield image_slider_items (repeater)
 *
 */


?>
<?php if( have_rows('content_rows') ): $cnt=1; ?>
        <div class="l-container l-container--small d-content">
            <div class="image-content-side-by-side">
                <?php while ( have_rows('content_rows') ) : the_row(); ?>
                <div class="image-content-side-by-side__row d-column-container<?php if( $cnt % 2 == 0 ) echo ' reverse'; ?>">
                    <div class="d-col d-col--1-2">
                        <?php if(get_sub_field('headline')) : ?>
                        <h3><?php the_sub_field('headline'); ?></h3>
                        <?php endif; ?>
                        <?php if(get_sub_field('content')) : ?>
                        <div class="image-content-side-by-side__row-content">
                            <?php the_sub_field('content'); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-col d-col--1-2">
                        <?php if(get_sub_field('image')) : 
                            $image = get_sub_field('image');
                        ?>
                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                        <?php endif; ?>
                    </div>
                </div>
                <?php ++$cnt; endwhile; ?>
            </div>
        </div>
<?php endif; ?>