<?php
/*
Template Name: Dark Background Tour Collection
Template Post Type: tour_collection
*/

/**
 * The template for displaying a single tour collection, with alternate styling.
 *
 * @package sherman
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

        <?php while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php if($herovideo = get_field('d_hero_video')) : ?>
        			<div class="collection__hero">
           	 			<div style="padding:56.25% 0 0 0;position:relative;width:100%"><iframe src="https://player.vimeo.com/video/<?php echo $herovideo; ?>?h=0fb282a0ab&title=0&byline=0&portrait=0&background=1&muted=1&controls=0&autoplay=1&loop=1" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div>
       				</div>
    			<?php elseif ($hero = duvine_get_url_from_object(get_field('d_hero_image'), 'banner_hero_page')) : ?>
					<div class="collection__hero">
						<?php echo '<img src="' . $hero . '" alt="">'; ?>
					</div>
				<?php endif; ?>

                <div class="l-container l-container--small">
                    <header class="location__header">
                        <div class="breadcrumbs__pad">
                            <ul class="breadcrumbs baselist">
                                <li class="breadcrumb__item breadcrumb__item--white">Collections</li>
                                <li class="breadcrumb__item breadcrumb__item--white"><?php the_title(); ?></li>
                            </ul>
                        </div>
                        
                        <?php
                            sk_the_field('duvine_collection_title', array(
                                'before' => '<h1 class="superheader t-half-rule-under">',
                                'after'  => '</h1>',
                                'default' => get_the_title()
                            ));
                        ?>
                    </header>

                    <div class="collection__main">
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
                    </div>

                    <?php
                        $childCollectionArgs = array(
                            'post_type'      => 'tour_collection',
                            'posts_per_page' => -1,
                            'post_parent'    => $post->ID
                        );

                        $childCollections = get_posts( $childCollectionArgs );
                    ?>
                    <?php if( $childCollections ) : ?>
                        <?php $numbermappings = array("zero", "one","two","three", "four", "five", "six", "seven", "eight", "nine", "ten"); ?>
                        <?php $numChildren = count( $childCollections ); ?>
                        <section class="collection__children">
                            <p class="collection__childtitle"><?php echo $numbermappings[ $numChildren ]; ?> challenge tour types:</p>
                            
                            <div class="childcollections__grid">
                                <?php foreach( $childCollections as $coll ) : ?>
                                    <?php $coll_link = get_the_permalink( $coll->ID ); ?>
                                    <div class="childcollection">
                                        <header class="t-half-rule-under--shorter childcollection__header"><h3 class="blockheader"><?php echo $coll->post_title; ?></h3></header>

                                        <?php
                                            sk_the_field('d_hero_image', array(
                                                'before'      => '<a href="' . $coll_link . '" class="childcollection__image">',
                                                'after'       => '</a>',
                                                'id'          => $coll->ID,
                                                'filter'      => 'sk_img_markup',
                                                'filter_args' => array(
                                                    'img_size' => 'gridcell_image'
                                                )
                                            ));

                                            sk_the_field('d_tour_collection_excerpt', array(
                                                'id'     => $coll->ID,
                                                'before' => '<p class="childcollection__excerpt">',
                                                'after'  => '</p>'
                                            ));
                                        ?>
                                        <div class="childcollection__cta">
                                            <a class="cta cta--hoverwhite" href="<?php echo $coll_link; ?>">Find Your <?php echo $coll->post_title; ?> Tour</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>

                </div>
                
            </article><!-- #post-## -->


            <section class="tourloop tourloop--padtop l-container tourloop__section">

                <?php
                    $tourArgs = array(
                        'posts_per_page' => -1,
						'is_challenge' => $post->post_name === 'challenge',
                        'meta_query'    => array(
                            array(
                                'key'     => 'd_tour_collection',
                                'value'   => get_the_ID(),
                                'compare' => 'LIKE'
                            ),
                        ),
                    );

                    duvine_render_name_grouped_tourloop($tourArgs, 2);
                ?>

            </section>

        <?php endwhile; // end of the loop. ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php get_footer(); ?>