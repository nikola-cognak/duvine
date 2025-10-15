<?php
/**
 * Template Name: Guides (Internal)
 * Template Post Type: page
 */

get_header(); ?>

<?php if( !is_front_page() ) : ?>
    <div class="l-container l-container--small breadcrumbs__pad">
        <ul class="breadcrumbs baselist">
            <li class="breadcrumb__item">
                <a class="breadcrumb__link" href="/">Home</a>
            </li>
            <li class="breadcrumb__item"><?php echo get_the_title(); ?></li>
        </ul>
    </div>
<?php endif; ?>

<div id="primary" class="content-area">
    <header class="entry-header l-container l-container--small">
        <?php the_title( '<h1 class="superheader">', '</h1>' ); ?>
    </header>

    <?php if ( '' !== $post->post_content ) : ?>
        <main id="main" class="site-main l-container l-container--small d-content" role="main">
            <?php get_template_part( 'content', 'page' ); ?>
        </main>
    <?php endif; ?>

    <?php
    // Query all 'person' posts in the Role term ID 58 (Tour Guide)
    $guides_query = new WP_Query([
        'post_type'      => 'person',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'tax_query'      => [[
            'taxonomy' => 'role',
            'field'    => 'term_id',
            'terms'    => [58],
        ]],
    ]);
    ?>

    <?php if ( $guides_query->have_posts() ) : ?>
        <div class="tourguides l-container">
            <div class="tourguides__grid d-column-container">
                <?php while ( $guides_query->have_posts() ) : $guides_query->the_post(); ?>
                <div class="tourguide d-col d-col--1-4">
                        <a href="<?php the_permalink(); ?>">
                            <div class="person__headshot tourguide__headshot" style="width: 100% !important;">
                                <?php 
                                    sk_the_field('duvine_headshot', array(
                                        'id'          => get_the_ID(),
                                        'filter'      => 'sk_img_markup',
                                        'filter_args' => array(
                                            'img_size' => 'gridcell_image'
                                        )
                                    ));
                                ?>
                            </div>
                            <h3 class="tourguide__name tertiaryheader"><?php the_title(); ?></h3>
                            <?php 
                                sk_the_field('duvine_person_title', array(
                                    'before' => '<p class="person__jobtitle">',
                                    'after'  => '</p>'
                                ));
                            ?>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif; wp_reset_postdata(); ?>

</div>

<?php get_footer(); ?>
