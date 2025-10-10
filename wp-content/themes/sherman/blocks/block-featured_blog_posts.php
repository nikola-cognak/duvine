<?php 
/**
 * MODULE - Featured blog posts
 *
 * Features any number of blog posts and places them in a slider.
 *
 * @subfield featured_posts (relationship)
 *
 */
?>

<?php if( $featuredPosts = get_sub_field('featured_posts') ) : ?>
    <div class="l-container">
        <div class="featuredblogs">
            <div class="featuredblogs__slider">
                <?php foreach( $featuredPosts as $blogpost ) : ?>
                    <div class="featuredblog__slide">
                        <?php
                        $blog_featured_image = get_field('d_blog_featured_image', $blogpost->ID);
                        if($blog_featured_image) : ?>
                        <div class="featuredblog__image">
                            <img src="<?php echo $blog_featured_image["url"]; ?>" alt="<?php echo $blogpost->post_title; ?>" class="no-lazyload"/>
                        </div>
                        <?php endif; ?>

                        <div class="featuredblog__content">
                            <header class="featuredblog__header">
                                <h3 class="blockheader blockheader--white"><?php echo $blogpost->post_title; ?></h3>
                            </header>

                            <?php
                                sk_the_field('d_blog_featured_summary', array(
                                    'before' => '<div class="featuredblog__summary">',
                                    'after'  => '</div>',
                                    'id'     => $blogpost->ID
                                ));
                            ?>

                            <a href="<?php echo get_the_permalink( $blogpost->ID ); ?>" class="cta cta--hoverwhite">Read More</a>
                        </div>              
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="featuredblogs__arrows"></div>
        </div>
    </div>
<?php endif; ?>