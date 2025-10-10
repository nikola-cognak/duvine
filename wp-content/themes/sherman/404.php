<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package sherman
 */

$bgImage = get_field('d_not_found_background', 'option');

$bgImageStyle = $bgImage ? $bgImage['url'] : '/wp-content/themes/sherman/img/aidan-meyer-133702.jpg';

get_header(); ?>

	<div id="primary" class="content-area content-area--404" style="background-image: url(<?php echo $bgImageStyle; ?>)">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found l-container l-container--small">
				<header class="page-header">
					<h1 class="superheader">
						<?php
							if($notfound_header = get_field('d_not_found_headline', 'option')) {
								echo $notfound_header;
							} else {
								echo _e( 'Lost?', 'sherman' );
							}
						?>
					</h1>
				</header><!-- .page-header -->

				<div class="page-content page404__content">
					<?php if($notfound = get_field('d_not_found_text', 'option')) : ?>
						<?php echo $notfound; ?>
					<?php else : ?>
						<p><?php _e( "We can't seem to find what you’re looking for. Search our site to find where you need to be.", 'sherman' ); ?></p>
					<?php endif; ?>
				</div><!-- .page-content -->

				<div class="page404__searchform">
					<?php get_search_form(); ?>
				</div>
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
