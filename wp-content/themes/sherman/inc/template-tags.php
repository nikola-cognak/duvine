<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package sherman
 */

if ( ! function_exists( 'sk_paging_nav' ) ) :
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 */
	function sk_paging_nav() {
		// Don't print empty markup if there's only one page.
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}
		?>
		<nav class="navigation paging-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'sherman' ); ?></h1>
			<div class="nav-links">

				<?php if ( get_next_posts_link() ) : ?>
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> More posts', 'sherman' ) ); ?></div>
				<?php endif; ?>

				<?php if ( get_previous_posts_link() ) : ?>
				<div class="nav-next"><?php previous_posts_link( __( 'Previous posts', 'sherman' ) ); ?></div>
				<?php endif; ?>

			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
endif;


if ( ! function_exists( 'sk_post_nav' ) ) :
	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function sk_post_nav() {
		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		}
		?>
		<nav class="navigation post-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'sherman' ); ?></h1>
			<div class="nav-links">
				<?php
					previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'sherman' ) );
					next_post_link( '<div class="nav-next">%link</div>', _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link', 'sherman' ) );
				?>
			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
endif;




if ( ! function_exists( 'sk_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time.
 *
 * @param string $format PHP date format.
 */
function sk_posted_on( $format = 'n.j.y' ) {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date( $format ) )
	);

	$additionalClasses = $format === 'n.j.y' ? ' blogpost__postedon--tracked' : '';


	echo '<span class="blogpost__postedon' . $additionalClasses . '">' . $time_string . '</span>';

}
endif;






if ( ! function_exists( 'sk_posted_by' ) ) :
/**
 * Prints HTML with meta information for the current author.
 */
function sk_posted_by() {
	global $post;

	$authorField = get_field('d_blog_author');

	$author = $authorField ? $authorField->post_title : get_the_author();

	$byline = sprintf(
		_x( 'Written by %s', 'post author', 'sherman' ),
		'<span class="author vcard">' . esc_html( $author ) . '</span>'
	);

	echo '<span class="blogpost__byline capsheader"> ' . $byline . '</span>';

}
endif;










if ( ! function_exists( 'sk_categorized_blog' ) ) :
	/**
	 * Returns true if a blog has more than 1 category.
	 *
	 * @return bool
	 */
	function sk_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'sk_categories' ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array(
				'fields'     => 'ids',
				'hide_empty' => 1,

				// We only need to know if there is more than one category.
				'number'     => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'sk_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so sk_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so sk_categorized_blog should return false.
			return false;
		}
	}
endif;


/**
 * Flush out the transients used in sk_categorized_blog.
 */
function sk_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'sk_categories' );
}
add_action( 'edit_category', 'sk_category_transient_flusher' );
add_action( 'save_post',     'sk_category_transient_flusher' );
