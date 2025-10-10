<?php
/**
 * @package sherman
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if( $hero = duvine_get_url_from_object(get_field('d_hero_image'), 'banner_hero_page') ) : ?>
		<div class="collection__hero">
			<?php echo '<img src="' . $hero . '" alt="">'; ?>
		</div>
	<?php endif; ?>


	<div class="l-container l-container--small">
		<header class="location__header">
			<div class="breadcrumbs__pad">
				<ul class="breadcrumbs baselist">
					<li class="breadcrumb__item">Collections</li>
					<?php if( $post->post_parent !== 0 ) : ?>
						<li class="breadcrumb__item"><a class="breadcrumb__link" href="<?php echo get_permalink( $post->post_parent ); ?>"><?php echo get_the_title($post->post_parent); ?></a></li>
					<?php endif; ?>
					<li class="breadcrumb__item"><?php the_title(); ?></li>
				</ul>
			</div>
			
			<?php
				sk_the_field('duvine_collection_title', array(
					'before' => "<h1 class=\"superheader\">",
					'after'  => '</h1>',
					'default' => get_the_title()
				));
			?>
		</header>

		<section class="collection__main">
			<div class="content__lead">
				<?php the_content(); ?>
			</div>

			<?php
				sk_the_field('duvine_collection_secondary', array(
					'before'  => '<div class="content__secondary d-content">',
					'after'   => '</div>',
					'filters' => 'the_content'
				));
			?>
		</section>
	</div>

	<div class="l-container">
		<section class="tourloop__section tourloop">

			<?php
				$tourArgs = array(
					'posts_per_page' => -1,
					'meta_query'	=> array(
						array(
							'key'     => 'd_tour_collection',
							'value'   => get_the_ID(),
                            'compare' => 'LIKE'
						),
					),
				);

                duvine_render_name_grouped_tourloop($tourArgs, 2);
			?>

			<?php if( $tourfinder_page = get_field('d_tourfinder_page', 'option') ) : ?>
			    <div class="tourloop__footersection t-center"><a href="<?php echo $tourfinder_page['url']; ?>" class="cta">See All DuVine Tours</a></div>
			<?php endif; ?>

		</section>
		

	</div>


</article><!-- #post-## -->
