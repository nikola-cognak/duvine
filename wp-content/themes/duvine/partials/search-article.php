<div class="Section">
    <span class="Page-Title">Articles</span>
    <div class="Listing Search">
      <?php
      $posts_per_page = 8;
      $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
      $article_search = new WP_Query(array(
        'post_type'     => 'post',
        'post_status'   => 'publish',
        's'             => get_search_query(),
        'posts_per_page'=> $posts_per_page,
        'paged'         => $paged,
      ));

      if( $article_search->have_posts() ) { ?>

        <?php duvine_pagination_links( $article_search, $label = 'Article' ); ?>

        <?php while( $article_search->have_posts() ) { $article_search->the_post(); ?>

          <article class="Listing-Item Clear">
            <a class="Title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            <time class="Sub-Title" datetime="<?php echo get_the_date( 'Y-m-d' ); ?>"><?php echo get_the_date( 'l, F j, Y g:i a' ); ?></time>
            <p class="Abstract"><?php echo get_the_excerpt(); ?></p>
          </article>

        <?php } wp_reset_postdata(); // while have_posts ?>

        <?php duvine_pagination_links( $article_search, $label = 'Article' ); ?>

      <?php } else { ?>

        <article class="Listing-Item Clear">
          <p>No article results found</p>
        </article>

      <?php } // if have_posts ?>
    </div><!--/.Listing.Search-->


</div><!--/.Section-->
