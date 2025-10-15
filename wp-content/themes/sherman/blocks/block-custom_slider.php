<?php 
/**
 * MODULE - Custom Slider
 *
 * Slider of custom content and/or blog posts
 *
 * @subfield custom_slider_items (repeater)
 *
 */


?>

<?php if( have_rows('custom_slider_items') ): ?>
        <div class="l-container">
            <div class="featuredblogs">
                <div class="featuredblogs__slider">
                    <?php while ( have_rows('custom_slider_items') ) : the_row(); ?>

                        <div class="featuredblog__slide">

                        <?php if(get_sub_field('post_type') == 'custom-post') { ?>

                            <?php if(get_sub_field('image_or_video') == 'video') : ?>

                                <?php if(get_sub_field('video')) : 
                                    $video = get_sub_field('video');?>
                                    <div class="featuredblog__video">
                                        <div class="">
											<div class="">
												<div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/<?php echo $video; ?>?h=0fb282a0ab&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div>
        									</div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            <?php else : ?>

                                <?php if(get_sub_field('image')) : ?>
                                    <div class="featuredblog__image">
                                        <img src="<?php the_sub_field("image"); ?>" alt="<?php the_sub_field('title'); ?>" />
                                    </div>
                                <?php endif; ?>

                            <?php endif; ?>

                            <div class="featuredblog__content">
                                <header class="featuredblog__header">
                                    <h3 class="blockheader blockheader--white"><?php echo get_sub_field('title'); ?></h3>
                                </header>

                                <?php if(get_sub_field('text')) { ?>
                                    <div class="featuredblog__summary">
                                        <?php the_sub_field('text'); ?>
                                    </div>
                                <?php } ?>

                                <?php if(get_sub_field('link')) { ?>
                                <a href="<?php the_sub_field('link'); ?>" class="cta cta--hoverwhite">Read More</a>
                                <?php } ?>
                            </div>

                        <?php } else { ?>

                            <?php $blogpost = get_sub_field('blog_post'); ?>

                            <?php
                            $blog_featured_image = get_field('d_blog_featured_image', $blogpost->ID);
                            if($blog_featured_image) : ?>
                            <div class="featuredblog__image">
                                <img src="<?php echo $blog_featured_image["url"]; ?>" alt="<?php echo $blog_featured_image["alt"]; ?>" />
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

                        <?php } ?>

                        </div>              

                    <?php endwhile; ?>
                </div>
                <div class="featuredblogs__arrows"></div>
            </div>
        </div>
<?php endif; ?>