<?php
/**
 * @package sherman
 */

$pressURL = duvine_get_press_url();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('press-article d-col--1-3 d-col cellblock'); ?>>
    <?php sk_posted_on(); ?>

    <div class="pressblock__image pressblock__image--margin"><a href="<?php echo $pressURL; ?>" target="_blank">
        <?php
            sk_the_field('d_press_freatured_photo', array(
                'filter'      => 'sk_img_markup',
                'filter_args' => array(
                    'img_size' => 'tour_thumbnail'
                ),
                'default'     => '<div class="pressblock__placeholder"></div>'
            ));
        ?>
    </a></div>

    <div class="pressblock__content">
        <?php duvine_render_publication_logo( get_field('d_press_publication') ); ?>

        <h3 class="blockheader"><a href="<?php echo $pressURL; ?>" target="_blank">
            <?php echo the_title(); ?>
        </a></h3>
    </div>
</article><!-- #post-## -->
