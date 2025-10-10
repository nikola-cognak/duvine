<?php get_header(); ?>

        <?php while ( have_posts() ) : the_post(); ?>

            <div class="Page Double Top Breadcrumb">
                <a>On The Road</a> ›
                <a href="/where-in-the-world-is-andy">Where In The World Is Andy</a> ›
                <a><?php the_title(); ?></a>
            </div><!--/.Page.Double.Top.Breadcrumb-->

            <div class="Page Double TwoColumn Body-Related Clear">
                <div class="Column One">
                    <article class="Blog Detail">
                        <h1 class="Page-Title"><?php the_title(); ?></h1>
                        <time class="Sub-Title Slim" datetime="<?php the_date( 'c' ); ?>"><?php echo get_the_date( 'l, F j, Y \a\t g:i a' ); ?></time>

                        <span class="Author">by Andy Levine</span>


                        <div class="Section Clear Body">
                            <?php the_content(); ?>
                        </div>

                        <?php duvine_sharethis(); ?>
                    </article>

                    <div class="Action-Bar Post-Navigation Clear">
                        <?php previous_post_link( '<div class="Back Left">%link</div>', 'Previous Blog' ); ?>
                        <?php next_post_link( '<div class="Next Right">%link</div>', 'Next Blog' ); ?>
                    </div>


                </div><!--/.Column.One-->

                <div class="Column Two">

                </div><!--/.Column.Two-->
            </div><!--/.Page.Double.TwoColumn.Body-Related.Clear-->

        <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
