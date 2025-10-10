<?php
/**
 * @package sherman
 */

// Featured Post
$hero = get_field('d_blog_featured_image');

if( ! $hero ){
	$hero = get_field('d_blog_banner');
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('blogpost__featured'); ?>>
    
	<div class="featured-post__banner-image<?php if( ! $hero ) echo ' l-container featured-post__banner-image--nobg'; ?>">
		<?php if( $hero ) : ?>
			<div class="featured-post__bannerwrapper">
				<a href="<?php echo get_permalink(); ?>"><?php echo apply_filters('sk_img_markup', $hero, array( 'img_size' => 'banner_hero_page')); ?></a>
			</div>
		<?php endif; ?>

        <div class="featured-post__banner-text">
	        <?php the_title( sprintf('<h2 class="entry-title blockheader"><a href="%s">', get_permalink()), '</a></h2>' ); ?>

	        <?php $featured_text = get_field('d_blog_featured_summary'); ?>
	        <?php if(!$featured_text) $featured_text = get_field('d_blog_lead'); ?>	        
	        <?php if(!$featured_text) $featured_text = get_field('duvine_blog_cover_image_caption'); ?>	        
			<?php if($featured_text) : ?>
				<div class="blog-thumb__leade d-content">
					<?php echo $featured_text; ?>
				</div>
			<?php endif; ?>
	    </div>
	</div>
</article><!-- #post-## -->