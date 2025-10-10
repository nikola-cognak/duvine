<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package sherman
 */
?>

<section class="no-results not-found">
	<header class="page-header">
		<h3 class="page-title tertiaryheader"><?php _e( 'Nothing Found', 'sherman' ); ?></h3>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php if ( is_search() ) : ?>
			<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'sherman' ); ?></p>
		<?php else : ?>

			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'sherman' ); ?></p>

		<?php endif; ?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
