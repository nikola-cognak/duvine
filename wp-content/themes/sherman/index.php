<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package sherman
 */


remove_filter('posts_request', 'relevanssi_prevent_default_request'); 
remove_filter('the_posts', 'relevanssi_query');

$blogsearch = get_query_var('blogsearch');
$catfilter = get_query_var('category_name');

$blogurl = get_permalink( get_option( 'page_for_posts' ) );

$featured_ids = array();

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main blog-content" role="main">
            <?php if(!$blogsearch) : ?>
                <?php
                    // FEATURED POST
                    // $featuredArgs = array(
                    //     'posts_per_page' => 3,
                    //     'orderby'        => 'menu_order',
                    //     'post_type'      => 'post',
                    //     'post_status'    => 'publish',
                    //     'meta_query'     => array(
                    //         array(
                    //             'key'   => 'd_featured_post',
                    //             'value' => 1
                    //         )
                    //     )
                    // );

                    // $featuredQuery = new WP_Query( $featuredArgs );
                    $featured_posts = get_field('featured_blog_posts','option');
                ?>

                <?php if( $featured_posts ) : ?>
                    <div class="blogpost__featurewrapper blogpost__featureslider">
                        <?php foreach($featured_posts as $post) { ?>
                            <?php setup_postdata($post); ?>
                            <?php if( $post->ID ) $featured_ids[] = $post->ID; ?>
                            <?php get_template_part( 'content', 'featured-post' ); ?>
                        <?php } ?>
                    </div>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>

                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        
            <div class="blog-posts__container l-container">
                <h2 class="pageheader"><?php echo $blogsearch ? 'Blog Search: ' . $blogsearch : 'Latest Blogs'; ?></h2>
                
                <form class="duvine-blogfilters blog-posts__container-subnav d-column-container" action="<?php echo $blogurl; ?>" method="get">
                    
                    <?php if( $cats = get_categories() ) : ?>
                        <div class="container-subnav__topics d-col d-col-1-2">
                            <h5 class="filtercell__label">Topics</h5>
                            <ul class="menu">
                                <?php foreach( $cats as $cat ) : ?>
                                    <?php if( $cat->term_id === 1 ) continue; ?>

                                    <li class="basiclink__input">
                                        <input type="radio" name="category_name" value="<?php echo $cat->slug; ?>" id="blogfilter--<?php echo $cat->slug; ?>"<?php if( $catfilter === $cat->slug ) echo ' checked'; ?>>
                                        <label for="blogfilter--<?php echo $cat->slug; ?>"><?php echo $cat->name; ?></label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <div class="container-subnav__search d-col d-col--1-2">
                        <span class="filtercell__label">Search</span>
                        <div class="d-searchinput">
                            <input type="search" name="blogsearch" placeholder="Search by destination or keyword" value="<?php echo $blogsearch; ?>" />
                            <button type="submit" class="searchinput__submit">Submit</button>
                        </div>
                    </div>

                </form><!-- blog posts container subnav -->
                
                <?php 
                
                    // NOT FEATURED POSTS
                    $regpostArgs = array(
                        'posts_per_page' => 10,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'post_type'      => 'post',
                        'post_status'    => 'publish',
                    );

                    if( $blogsearch ){
                        $regpostArgs['s'] = $blogsearch;
                    } elseif( $featured_ids ){
                        $regpostArgs['post__not_in'] = $featured_ids;
                    }

                    if( $catfilter ){
                        $regpostArgs['tax_query'] = array(
                            array(
                                'taxonomy' => 'category',
                                'field'    => 'slug',
                                'terms'    => $catfilter
                            )
                        );
                    }

                    $regposts = new WP_Query( $regpostArgs );
                ?>

                <?php if( $regposts->have_posts() ) : ?>
                    <div class="loadmore__container">
                        <div class="blogloop d-column-container d-column-container--cells loadmore__mainposts">

                            <?php // The Loop ?>
                            <?php while ( $regposts->have_posts() ) : $regposts->the_post(); ?>
                                <?php get_template_part( 'content', 'blog-thumb' ); ?>
                            <?php endwhile; ?>
                        </div>

                        <div class="t-center t-padtop">
                            <?php sk_load_more_button( $regposts, 'blog-thumb' ); ?>
                        </div>
                    </div>
                <?php else : ?>
                    <?php get_template_part('content', 'none'); ?>
                <?php endif; ?>
                <?php wp_reset_postdata();?>

                
            </div><!-- posts container -->
        </main><!-- #main -->
    </div><!-- #primary -->

<?php get_footer(); ?>
