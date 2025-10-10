<?php 
/**
 * MODULE - Featured content carousel
 *
 * Features any number of content blocks. If more than the screen can handle, it fits into a carousel
 *
 * @subfield title (text)
 * @subfield content (repeater)
 *    @subfield image (image)
 *    @subfield content (text area)
 *    @subfield call_to_action (clone)
 *
 */

?>

<div class="l-container">
    <header class="t-center">
        <?php
            sk_block_field('title', array(
                'before' => '<h2 class="pageheader">',
                'after'  => '</h2>',
                'filter' => 'smart_type'
            ));
        ?>
    </header>
    <?php $slider = get_sub_field('disable_slider'); ?>
    <?php if( have_rows('content') ) : ?>
	
        <div class="featuredcontent__river <?php if ($slider == 1){ echo 'disable';} ?>">
            <?php while( have_rows('content') ) : the_row(); ?>
                <div class="featuredcontent__cell">
                    <?php 
                        $cta = get_sub_field('d_cta');

                        sk_the_subfield('image', array(
                            'before'      => '<div class="featuredcontent__image"><a href="' . $cta['url'] . '">',
                            'after'       => '</a></div>',
                            'filter'      => 'sk_img_markup',
                            'filter_args' => array(
                                'img_size' => 'gridcell_image'
                            )
                        ));

                        sk_the_subfield('content', array(
                            'before' => '<div class="featuredcontent__content">',
                            'after'  => '</div>',
                        ));

                        duvine_render_cta();
                    ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

