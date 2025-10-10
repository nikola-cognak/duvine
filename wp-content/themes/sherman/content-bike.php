<?php
/**
 * @package sherman
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="yellow__nav">

	</div>

	<header class="bike__header l-container l-container--small">
		<?php if( $bikeThumb = get_field('bike_photo') ) : ?>
			<div class="bike__photo">
				<?php echo '<img src="' . duvine_get_url_from_object( $bikeThumb ) . '" alt="">' ?>
			</div>
		<?php endif; ?>

		<div class="bike__subheader">
			<?php if( $term_manufacturer = get_field('bike_manufacturer') ) : ?>
				<p class="bike__quickinfo"><?php echo $term_manufacturer->name; ?></p>
			<?php endif; ?>

			<?php the_title( '<h1 class="superheader">', '</h1>' ); ?>
		</div>
	</header>


	<div class="l-container l-container--small d-content">

		<div class="bike__entry">
			<div class="bike__info">
				<?php
					
					if( $term_manufacturer = get_field('bike_manufacturer') ){
						$name_manufacturer = $term_manufacturer->name;
						echo '<p class="bike__meta"><strong>Manufacturer: </strong>' . $name_manufacturer . '</p>';
					}

					if( $term_type = get_field('bike_type') ){
						$name_type = $term_type->name;
						echo '<p class="bike__meta"><strong>Type: </strong>' . $name_type . '</p>';
					}

					sk_the_field('bike_frame', array(
						'before' => '<p class="bike__meta"><strong>Frame: </strong>',
						'after'  => '</p>'
					));

					sk_the_field('bike_wheels', array(
						'before' => '<p class="bike__meta"><strong>Wheels: </strong>',
						'after'  => '</p>'
					));

					sk_the_field('bike_tires', array(
						'before' => '<p class="bike__meta"><strong>Tires: </strong>',
						'after'  => '</p>'
					));

					sk_the_field('bike_stem', array(
						'before' => '<p class="bike__meta"><strong>Stem: </strong>',
						'after'  => '</p>'
					));

					sk_the_field('bike_components', array(
						'before' => '<p class="bike__meta"><strong>Shifter: </strong>',
						'after'  => '</p>'
					));

					sk_the_field('bike_front_cassette', array(
						'before' => '<p class="bike__meta"><strong>Crankset: </strong>',
						'after'  => '</p>'
					));

					sk_the_field('bike_rear_cassette', array(
						'before' => '<p class="bike__meta"><strong>Cassette: </strong>',
						'after'  => '</p>'
					));
				?>

			</div>
			<div class="bike__content">
				<?php the_content(); ?>
			</div>
		</div>
	</div>

</article><!-- #post-## -->
