<?php
/**
 * The template for news page
 *
 * Template Name: Press & News
 *
 * @package sherman
 */

$q_publication = get_query_var('publication');

get_header(); ?>

	<div id="primary" class="content-area">
        
        <?php while ( have_posts() ) : the_post(); ?>
            <?php
            // FEATURED Article
            $featuredArgs = array(
                'posts_per_page' => 5,
                'orderby'        => 'meta_value',
                'meta_key'		 => 'd__press_featured_order',
                'order'          => 'ASC',
                'post_type'      => 'press',
                'post_status'    => 'publish',
                'meta_query'     => array(
                    array(
                        'key'   => 'd_press_featured',
                        'value' => 1
                    )
                )
            );
            ?>

            <?php if( $featuredNews = get_posts($featuredArgs)) : ?>
                <div class="image-slider">
                <section class="featured-press-articles image-slider__slider">
                    <?php foreach( $featuredNews as $f_news ) : ?>
                        <?php $pressURL = duvine_get_press_url($f_news->ID); ?>

                        <div class="sk-slide sk-slider">
                            <div class="featuredpress">
                                <div class="featuredpress__image">
                                    <a href="<?php echo $pressURL; ?>" target="_blank">
                                        <?php
                                        $imageOptions = [
                                            'id'          => $f_news->ID,
                                            'filter'      => 'sk_img_markup',
                                            'filter_args' => array(
                                                'img_size' => 'banner_hero_page'
                                            ),
                                            'default'     => '<div class="pressblock__placeholder"></div>'
                                        ];
                                        if (get_field('d_press_header_freatured_photo', $f_news->ID)) {
                                            sk_the_field('d_press_header_freatured_photo', $imageOptions);
                                        } else {
                                            sk_the_field('d_press_freatured_photo', $imageOptions);
                                        }
                                        ?>
                                    </a>
                                </div><!-- image -->
                                <div class="featuredpress__text">
                                    <?php duvine_render_publication_logo(get_field('d_press_publication', $f_news->ID), true); ?>

                                    <h2 class="blockheader"><a href="<?php echo $pressURL; ?>" target="_blank"><?php echo $f_news->post_title; ?></a></h2>

                                    <?php if( $f_news->post_content ) : ?>
                                        <div class="press-slider__content">
                                            <?php echo apply_filters('the_content', $f_news->post_content); ?>
                                        </div>
                                    <?php endif; ?>
                                </div><!-- txt -->
                            </div>
                        </div><!-- slide -->
                
                  
                    <?php endforeach; ?>

                </section><!-- featured press articles -->
                <div class="image-slider__arrows"></div>
            </div>
            <?php endif; ?>

        <?php if( !get_field('d_hide_page_title') ) : ?>
            <header class="entry-header l-container press-page">
                <?php the_title( '<h1 class="superheader">', '</h1>' ); ?>
            </header><!-- .entry-header -->
        <?php endif; ?>

            <?php if( $recognized = get_field('d_press_recently_recognized') ) : ?>
                <div id="featuredpubs"></div>
                <section class="featuredpubs">
                    <header class="featuredpubs-header l-container"><h6 class="capsheader featuredpubs__header">Recently recognized in:</h6></header>

                    <ul class="featuredpubs__publications">
                        <?php foreach( $recognized as $pub ) : ?>
                            <?php if( $pubthumb = get_the_post_thumbnail( $pub, 'full' ) ) : ?>
                                <li class="featuredpubs__publication">
                                    <?php echo $pubthumb; ?>
                                    <?php $cta_activeclass = $q_publication === $pub->post_name ? ' active' : ''; ?>
                                    <div class="featuredpubs__cta<?php echo $cta_activeclass; ?>">
                                        <a href="?publication=<?php echo $pub->post_name; ?>#featuredpubs" class="featuredpubs__viewarticles" data-publication="<?php echo $pub->post_name; ?>">View articles</a>
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endif; ?>

            <section id="press-articles-section" class="pressloop">            
                <div class="press-articles__container l-container loadmore__container">
                    <?php 
                        // NOT FEATURED POSTS
                        $regularNewsArgs = array(
                            'posts_per_page' => 12,
                            'orderby'        => 'date',
                            'order'          => 'DESC',
                            'post_type'      => 'press',
                            'post_status'    => 'publish',
                            'meta_query'     => array(
                                'relation' => 'AND',
                                array(
                                    'relation' => 'OR',
                                    array(
                                        'key' => 'd_press_featured',
                                        'compare' => 'NOT EXISTS' 
                                    ),
                                    array(
                                        'key' => 'd_press_featured',
                                        'value' => 0
                                    )
                                ),
                            )
                        );



                        if( $q_publication ){
                            $publicationId = duvine_get_post_by_slug( $q_publication, 'publication' );
                            array_push( $regularNewsArgs['meta_query'], array(
                                'key'     => 'd_press_publication',
                                'value'   => $publicationId,
                                'compare' => 'LIKE'
                            ));
                        }

                        $regularNews = new WP_Query( $regularNewsArgs );
                    ?>

                    <?php if( $regularNews->have_posts() ) : ?>
                        <div class="press__articleholder d-column-container d-column-container--cells loadmore__mainposts">
                            <?php while( $regularNews->have_posts() ) : $regularNews->the_post(); ?>
                                <?php get_template_part( 'content', 'press-article' ); ?>
                            <?php endwhile; ?>
                        </div>

                        <div class="t-center t-padtop">
                            <?php sk_load_more_button( $regularNews, 'press-article' ); ?>
                        </div>
                    <?php else : ?>
                        <p>No posts found.</p>
                    <?php endif; ?>

                    <?php wp_reset_postdata(); ?>
                        
                </div>

            </section>
        </div>
        
        <?php sk_the_page_blocks(); ?>

    <?php endwhile; // end of the loop. ?>


<?php get_footer(); ?>
