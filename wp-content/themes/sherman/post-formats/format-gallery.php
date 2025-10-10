<?php
/**
 * @package sherman
 *
 * The "Gallery" post format
 */

$coverImage = get_field('duvine_blog_cover_image');
$count = count(get_field('duvine_blog_slides'));

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('galleryarticle duvine__loading'); ?>>
    <div class="gallery-entry-header l-container">

        <a href="#" class="gallery-slider__pagenav nav-prev"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 612 792"><polyline points="427.9,646.4 168.2,389.4 427.9,129.7"/></svg></a>
        <span class="gallery-post__slide-count"><span class="current-slide">1</span> / <?php echo $count + 1; ?></span>
        <a href="#" class="gallery-slider__pagenav nav-next"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 612 792"><polyline points="168.2,129.7 427.9,386.7 168.2,646.4"/></svg></a>
    </div>


    <div class="gallery-post__gallery-slider">
        
        <div class="sk-slide gallery-post__slide">
            <div class="l-container gallery-post__slidecontainer">
                <div class="gallery-slider__text">
                    <?php the_title( '<h1 class="entry-title pageheader">', '</h1>' ); ?>
                    <?php sk_posted_by(); ?>
                    <?php sk_posted_on(); ?>

                    <div class="gallery-image__caption d-content">
                        <?php echo get_field('duvine_blog_cover_image_caption'); ?>
                    </div>

                </div><!-- txt -->

                <div class="gallery-slider__image">
                    <?php
                        sk_the_field('duvine_blog_cover_image', array(
                            'before' => '<div class="the-image">',
                            'after'  => '</div>',
                            'filter' => 'sk_img_markup'
                        ));
                    ?>

                    <p class="image__caption d-content"><?php echo get_field('duvine_blog_cover_image')['caption']; ?></p>
                    
                </div><!-- image -->    
            </div>        
        </div><!-- slide -->


        <?php if( have_rows('duvine_blog_slides') ): ?>
            <?php while( have_rows('duvine_blog_slides') ): the_row(); ?>
                <?php $slideImage = get_sub_field('image'); ?>
                <div class="sk-slide gallery-post__slide">
                    <div class="l-container gallery-post__slidecontainer">
                        <div class="gallery-slider__text">
                            <?php
                                sk_the_subfield('title', array(
                                    'before' => '<h4 class="blockheader">',
                                    'after'  => '</h4>'
                                ));

                                sk_the_subfield('caption', array(
                                    'before' => '<div class="gallery-image__caption d-content">',
                                    'after'  => '</div>'
                                ));
                            ?>
                        </div>

                        <div class="gallery-slider__image">
                            <?php if( $slideImage ) : ?>
                                <div class="the-image">
                                    <?php echo apply_filters('sk_img_markup', $slideImage); ?>
                                    <?php if( $slideImage['caption'] ) : ?>
                                        <p class="image__caption d-content"><?php echo $slideImage['caption']; ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div><!-- image -->
                    </div>
                </div><!-- slide -->
                
            <?php endwhile; ?>
        <?php endif; //slides ?>
    </div><!-- gallery slider -->

    
</article><!-- #post-## -->

<div class="l-container"><div class="social-icons__wrapper--fixedright-gallery"><?php duvine_render_social_icons(); ?></div></div>

