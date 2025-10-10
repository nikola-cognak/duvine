<?php
/**
 * @package sherman
 */

// route to the correct format theme
$format = get_post_format();

$footerContainerClass = 'l-container';

if( $format !== 'gallery' ){
    $footerContainerClass .= ' l-container--small';
}
?>

<div class="breadcrumbs__pad l-container">
    <ul class="breadcrumbs baselist">
        <li class="breadcrumb__item"><a class="breadcrumb__link" href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>">Blog</a></li>
        <li class="breadcrumb__item"><?php echo the_title(); ?></li>
    </ul>
</div>

<?php get_template_part( 'post-formats/format', $format ); ?>

<?php if( $author = get_field('d_blog_author') ) : ?>
    <?php if( $author_bio = get_post_field('post_content', $author->ID) ) : ?>
        <div class="blog-post__about-the-author d-content">
            <div class="<?php echo $footerContainerClass; ?>">
                <p class="about-author__header capsheader">About the author</p>
                <?php echo $author_bio; ?>
            </div>
        </div><!-- about the author -->
    <?php endif; ?>
<?php endif; ?>

<?php if( $recoTours = get_field('d_blog_related_tours') || $next_post = get_next_post() ) : ?>
<?php //if($next_post = get_next_post()) : ?>
    <section class="blog-post__blog-footer <?php echo $footerContainerClass; ?>">
        <div class="d-column-container">

            <?php if( $recoTours = get_field('d_blog_related_tours')) : ?>

                <div class="d-col--1-2 d-col">
                    <h6 class="blockheader">Ready to travel?</h6>
                    <ul class="blockmenu">
                        <?php foreach( $recoTours as $tour ) : ?>
                            <li><a href="<?php echo get_the_permalink( $tour->ID ); ?>" class="basiclink"><?php echo $tour->post_title; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div><!-- recommend -->
            <?php endif; ?>
            
            <?php if($next_post = get_next_post()) : ?>
            <div class="d-col--1-2 d-col">
                <h6 class="blockheader">Up Next: <?php echo get_the_title($next_post); ?></h6>
                <div class="t-padtop-2em"><a href="<?php echo get_the_permalink($next_post); ?>" class="cta">Read Now</a></div>
            </div><!-- recommend -->
            <?php endif; ?>
        </div>

    </section>
<?php endif; ?>

<?php if($recoArticles = get_field('d_blog_recommended_articles')) : ?>
    <div class="tourrelated">
        <div class="l-container l-container--small">

            <h3 class="blockheader">Recommended Articles</h3>

            <div class="tourrelated__grid">
                <?php foreach( $recoArticles as $article ) : ?>
                    <?php $articleLink = get_the_permalink( $article->ID ); ?>
                    <div class="relatedtour">
                        <?php
                            sk_the_field('d_blog_featured_image', array(
                                'before'      => '<a href="' . $articleLink . '" class="relatedtour__image">',
                                'after'       => '</a>',
                                'id'          => $article->ID,
                                'filter'      => 'sk_img_markup',
                                'filter_args' => array(
                                    'img_size' => 'tour_thumbnail'
                                )
                            ));
                        ?>
                        <a href="<?php echo $articleLink; ?>" class="basiclink"><?php echo $article->post_title; ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>