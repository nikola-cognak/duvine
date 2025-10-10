<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package sherman
 */

get_header(); ?>

    
    <?php if( !is_front_page() ) : ?>
        
        <?php duvine_render_page_hero(); ?>

        <div class="l-container l-container--small breadcrumbs__pad">
            <ul class="breadcrumbs baselist">
                <li class="breadcrumb__item">
                <?php if( $post->post_parent ) : ?>
                    <?php $nolink_array = array(385, 395); ?>
                    <?php echo in_array($post->post_parent, $nolink_array) ? '<span class="breadcrumb__link">' : '<a class="breadcrumb__link" href="' . get_the_permalink( $post->post_parent) . '">'; ?>
                        <?php echo get_the_title( $post->post_parent ); ?>
                    <?php echo in_array($post->post_parent, $nolink_array) ? '</span>' : '</a>' ; ?>
                <?php else : ?>
                    <a class="breadcrumb__link" href="/">Home</a>
                <?php endif; ?>
                </li><li class="breadcrumb__item"><?php echo $post->post_title; ?></li>
            </ul>
        </div>
    <?php else : ?>
        <?php duvine_render_homepage_hero(); ?>
    <?php endif; ?>    

	<div id="primary" class="content-area">
        
        <?php while ( have_posts() ) : the_post(); ?>
            <?php if( !get_field('d_hide_page_title') ) : ?>
                <header class="entry-header l-container l-container--small">
                    <?php the_title( '<h1 class="superheader">', '</h1>' ); ?>
                </header><!-- .entry-header -->
            <?php endif; ?>

            <?php
                sk_the_field('d_page_intro_paragraph', array(
                    'before' => '<div class="pageintro l-container l-container--small"><p>',
                    'after'  => '</p></div>'
                ));
            ?>

            <?php if ( '' !== $post->post_content ) : ?>
                <main id="main" class="site-main l-container l-container--small d-content" role="main">
                    <?php get_template_part( 'content', 'page' ); ?>
                </main><!-- #main -->
            <?php endif; ?>

        <?php endwhile; // end of the loop. ?>

        <?php sk_the_page_blocks(); ?>
		
		<?php if (is_front_page()) : ?>
			<div style="padding: 0 40px;">
				<?php echo do_shortcode('[insta-gallery id="0"]'); ?>
			</div>
		<?php endif; ?>

    </div><!-- #primary -->

<?php get_footer(); ?>
<style>
	.home .swiper-button-next, .home .swiper-button-prev {
		color: #dbe035 !important;
	}
	.home .swiper-pagination-bullet-active {
		background: #dbe035 !important;
	}
	.home .instagram-gallery-item__icon.qligg-icon-instagram {
		display: none !important;
	}
	.home .instagram-gallery-item__icon.qligg-icon-video.instagram-gallery-item__icon--views {
		display: none !important;
	}
	.home .instagram-gallery-item__icon.qligg-icon-gallery.instagram-gallery-item__icon--views {
		display: none !important;
	}
	.home #instagram-gallery-feed-0 .instagram-gallery-item__media-mask {
		background-color: unset !important;
	}
</style>