<?php
/**
 * The template for displaying search results pages.
 *
 * @package sherman
 */

$query_post_type = get_query_var('post_type');

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main l-container l-container--small searchresults" role="main">

			<header class="page-header">
				<h1 class="page-title pageheader"><?php echo __( 'Search Results', 'sherman' ) ?></h1>
				
				<form class="duvinefilters" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<div class="tourfilters__container tourfilters__container--pad">

					    <div class="filtercell filtercell--search">
					        <span class="filtercell__label">Search</span>

					        <div class="d-searchinput">
					            <input type="search" class="field" name="s" placeholder="<?php esc_attr_e( 'Search', 'twentyeleven' ); ?>" value="<?php echo get_search_query(); ?>">
					            <button type="submit" class="searchinput__submit">Submit</button>
					        </div>
					    </div>


					    <div class="filtercell filtercell--filtersearch">
					        <span class="filtercell__label">Filter</span>
					        <select data-placeholder="Select filter" class="js-duvine-chosen js-filter-searchresults" name="post_type">
					            <option value="">All</option>
					            <option value="post"<?php if( $query_post_type === 'post' ) echo ' selected'; ?>>Blog</option>
					            <option value="tour"<?php if( $query_post_type === 'tour' ) echo ' selected'; ?>>Tours</option>
					            <option value="location"<?php if( $query_post_type === 'location' ) echo ' selected'; ?>>Regions</option>
					            <option value="person"<?php if( $query_post_type === 'person' ) echo ' selected'; ?>>People</option>
					            <option value="page"<?php if( $query_post_type === 'page' ) echo ' selected'; ?>>Pages</option>
					            <option value="press"<?php if( $query_post_type === 'press' ) echo ' selected'; ?>>Press</option>
					        </select>
					    </div>

					</div>
				</form>
			</header><!-- .page-header -->

			<?php if ( have_posts() ) : ?>

				<div class="tourlist__count tourlist__count--padtop">
					Showing results ( <?php echo $wp_query->found_posts; ?> )
				</div>

				<div class="loadmore__container">
					<div class="searchresults__results loadmore__mainposts">
						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>

							<?php
							/**
							 * Run the loop for the search to output the results.
							 * If you want to overload this in a child theme then include a file
							 * called content-search.php and that will be used instead.
							 */
							get_template_part( 'content', 'search' );
							?>

						<?php endwhile; ?>
					</div>

					<div class="t-center tourloop__footersection">
					    <?php sk_load_more_button( null, 'search' ); ?>
					</div>

				</div>

			<?php else : ?>
				<div class="searchresults__results">
					<?php get_template_part( 'content', 'none' ); ?>
				</div>
			<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer(); ?>
