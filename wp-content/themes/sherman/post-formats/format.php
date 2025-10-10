<?php
/**
 * @package sherman
 *
 * The "Feature" post format
 */
$hero = get_field('d_blog_banner');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <?php
        sk_the_field('d_blog_banner', array(
            'before'      => '<div class="featured-post__banner-image feature-post-landing">',
            'after'       => '</div>',
            'filter'      => 'sk_img_markup',
            'filter_args' => array(
                'img_size' => 'banner_hero'
            )
        ));
    ?>

    <?php 
        if(get_field('d_blog_banner')['caption']) {
            echo '<p style="text-align:left;" class="image__caption l-container">' . get_field('d_blog_banner')['caption'] . '</p>';
        }
    ?>

    <header class="entry-header entry-header--blog l-container l-container--small t-center">

        <?php sk_posted_on(); ?>

        <?php duvine_render_social_icons( 'social-icons__wrapper--centered social-icons__wrapper--fixedright'); ?>
        
        <?php the_title( '<h1 class="entry-title pageheader">', '</h1>' ); ?>

        <div class="entry-meta">
            <div class="blogpost__leade d-content__leade"><?php echo get_field('d_blog_lead'); ?></div>
            <?php sk_posted_by(); ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->


    <?php if( have_rows('d_blog_content_sections') ): ?>

        <div class="entry-content"> 

            <?php while( have_rows('d_blog_content_sections') ): the_row(); ?>
                <section class="featured-post__content">

                    <?php // vars ?>
                    <?php $blockquote    = get_sub_field('blockquote'); ?>
                    <?php $copy          = get_sub_field('copy'); ?>
                    <?php $gallery       = get_sub_field('gallery'); ?>
                    <?php $gallery_type  = get_sub_field('gallery_type'); ?>
                    <?php $gallery_count = ($gallery) ? count($gallery) : 0; ?>
                    
                    <?php if ($blockquote) : ?>
                        <div class="l-container l-container--small">
                            <blockquote class="featured-post__blockquote"><?php echo $blockquote; ?></blockquote>
                        </div><!-- blockquote -->
                    <?php endif; // blockquote ?>


                    <?php if ($copy) : ?>
                        <div class="featured-post__copy d-content l-container l-container--small">
                            <?php echo $copy; ?>
                        </div><!-- copy -->
                    <?php endif; // copy ?>

                    
                    <?php if ($gallery) : ?>
                        <?php if( $gallery_type === 'gridded' ) : ?>
                            <div class="l-container l-container--small">
                                <div class="tourgallery">
                                    <?php
                                        foreach( $gallery as $img ) {
                                            $width  = $img['width'];
                                            $height = $img['height'];
                                            $ratio  = $height / $width;
                                            $renderArgs = array( 'img_size' => 'full', 'container_class' => 'tourgallery__image' );

                                            if( $width < 960 && $ratio < .7 ){
                                                $renderArgs['img_size'] = 'tourgallery_rectangle';
                                                $renderArgs['container_class'] = 'tourgallery__image tourgallery__image--rectangle';
                                            } else if( $width < 960 ){
                                                $renderArgs['img_size'] = 'tourgallery_square';
                                                $renderArgs['container_class'] = 'tourgallery__image tourgallery__image--square';
                                            }

                                            duvine_render_img( $img, $renderArgs );

                                        }
                                    ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="featured-post__gallery l-container<?php if( $gallery_count > 1 ) echo ' blogpost__galleryslider'; ?>">
                                <?php foreach( $gallery as $galleryImage ) : ?>
                                    <div class="featured-post__gallery-image">
                                        <img src="<?php echo $galleryImage['url']; ?>" alt="">

                                        <?php if( $galleryImage['caption'] ) : ?>
                                            <p class="image__caption"><?php echo $galleryImage['caption']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            
                            </div><!-- gallery -->
                        <?php endif; ?>
                    <?php endif; //gallery ?>
                </section><!-- featured post content -->
            <?php endwhile; ?>
        
        </div><!-- .entry-content -->
    <?php else : ?>
        <div class="entry-content d-content l-container l-container--small">
            <?php the_content(); ?>
        </div>
    <?php endif; ?>
</article><!-- #post-## -->