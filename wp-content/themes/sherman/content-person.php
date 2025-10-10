<?php
/**
 * @package sherman
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="yellow__nav"></div>

	<div class="l-container l-container--small">

		<header class="person__header">
		
			<?php
				sk_the_field('duvine_headshot', array(
					'before' =>'<div class="person__headshot">',
					'after'  => '</div>',
					'filter' => 'sk_img_markup'
				));
			?>

			<div class="person__intro">

				<?php
					$role_term = get_the_terms($post, 'role');

					$title_args = array(
						'before' => '<span class="person__role">',
						'after'  => '</span>'
					);

					if( $role_term ){
						$title_args['default'] = $role_term[0]->name;
					}

					sk_the_field('duvine_person_title', $title_args);
					the_title( '<h1 class="superheader">', '</h1>' );
				?>

				<div class="person__metacontainer">
					<?php
						sk_the_field('duvine_person_languages_spoken', array(
							'before' => '<p class="person__meta"><strong>Languages Spoken: </strong><span>',
							'after'  => '</span></p>'
						));
						sk_the_field('duvine_person_hometown', array(
							'before' => '<p class="person__meta"><strong>Hometown: </strong><span>',
							'after'  => '</span></p>'
						));
						sk_the_field('duvine_person_tenure', array(
							'before' => '<p class="person__meta"><strong>Guide Since: </strong><span>',
							'after'  => '</span></p>'
						));
					?>
				</div>
			</div>
		</header>


		<div class="person__main">
			<div class="person__info">
				<div class="person__columns">
					<?php
						sk_the_field('duvine_person_bike', array(
							'before' => '<div class="info__entry"><h2 class="blockheader">Bike</h2><p>',
							'after'  => '</p></div>'
						));
						sk_the_field('duvine_person_eat', array(
							'before' => '<div class="info__entry"><h2 class="blockheader">Eat</h2><p>',
							'after'  => '</p></div>'
						));
						sk_the_field('duvine_person_drink', array(
							'before' => '<div class="info__entry"><h2 class="blockheader">Drink</h2><p>',
							'after'  => '</p></div>'
						));
						sk_the_field('duvine_person_sleep', array(
							'before' => '<div class="info__entry"><h2 class="blockheader">Sleep</h2><p>',
							'after'  => '</p></div>'
						));
					?>
				</div>
			</div>

			<div class="person__content">
				<?php the_content(); ?>
			</div>
			
			<?php $related_tours = get_field('duvine_bike_on_tours'); ?>
			<?php if( has_term(58, 'role') && $related_tours ) : ?>

				<div class="person__relatedtours">
					<h3 class="tertiaryheader">Meet me on these tours:</h3>
					<ul class="baselist">
						<?php foreach( $related_tours as $tour ): ?>
							<?php if(get_post_status($tour->ID) === 'publish') { ?>
					    		<li>
					    			<a class="basiclink" href="<?php echo get_permalink( $tour->ID ); ?>"><?php echo get_the_title( $tour->ID ); ?></a>
					    		</li>
				    		<?php } ?>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
	
		</div>
	</div>

</article><!-- #post-## -->
