<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package sherman
 */

$postType = get_post_type();

$postObj = get_post_type_object( $postType );

$postUrl = $postType === 'press' ? duvine_get_press_url() : esc_url( get_permalink() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('d-searchresult'); ?>>
	<header class="entry-header searchresult__header">
		<?php if( isset( $postObj->labels->singular_name )) : ?>
			<p class="searchresult__type basiclink"><?php echo $postObj->labels->singular_name; ?></p>
		<?php endif; ?>

		<?php the_title( sprintf( '<h3 class="blockheader blockheader--accent"><a href="%s" rel="bookmark" class="searchresult__link">', $postUrl ), '</a></h3>' ); ?>

		<?php if ( 'post' == $postType ) : ?>
			<div class="entry-meta"><?php sk_posted_on(); ?></div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if( $postType === 'tour' && ($tourExcerpt = get_field('d_tour_subtitle')) ) : ?>
		<div class="search__contentbox">
			<div class="search__entrysummary"><?php echo $tourExcerpt; ?></div><!-- .entry-summary -->
		</div>
	<?php elseif($postType === 'post' && ($lead = get_field('d_blog_lead')) ) : ?>
		<div class="search__contentbox">
			<div class="search__entrysummary"><?php echo $lead; ?></div><!-- .entry-summary -->
		</div>
	<?php elseif($post->post_content) : ?>
		<div class="search__contentbox">
			<div class="search__entrysummary"><?php the_excerpt(); ?></div><!-- .entry-summary -->
		</div>
	<?php endif; ?>

</article><!-- #post-## -->