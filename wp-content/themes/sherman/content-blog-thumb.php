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

<article id="post-<?php the_ID(); ?>" <?php post_class('d-col--1-2 d-col cellblock'); ?>>
    
	<div class="blog-thumb__banner-image"><a href="<?php the_permalink(); ?>">
		<?php if( $hero ) : ?>
			<?php echo apply_filters('sk_img_markup', $hero, array( 'img_size' => 'tour_thumbnail')); ?>
		<?php else : ?>
			<div class="blogpost__placeholder"></div>
		<?php endif; ?>
	</a></div><!-- image -->

	
    <div class="blog-thumb__banner-text">
    	<h2 class="entry-title blockheader"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<?php $bloglead = get_field('d_blog_featured_summary'); ?>
		<?php if(!$bloglead) $bloglead = get_field('d_blog_lead'); ?>
		<?php if(!$bloglead) $bloglead = get_field('duvine_blog_cover_image_caption'); ?>
		<?php if($bloglead) : ?>
			<div class="blog-thumb__leade d-content"><?php echo wp_trim_words($bloglead, 40); ?></div>
		<?php endif; ?>
  	</div><!-- text -->

</article><!-- #post-## -->