<div class="Section">
  <span class="Page-Title">Tours</span>
  <div class="Listing Grid Related Clear">
    <?php
    $tour_search = new WP_Query(array(
      'post_type'       => 'duvine_tours',
      'post_status'     => 'publish',
      's'               => get_search_query(),
      'posts_per_page'  => 20
    ));

    $tour_counter = 1;
    if( $tour_search->have_posts() ) { ?>

      <?php while( $tour_search->have_posts() ) {
        $tour_search->the_post();
        $div_is_open = false;
        $post_id = get_the_ID();
        ?>

          <div class="Listing-Item Related-Item Search-Tour-Item">
              <a href="<?php the_permalink(); ?>">
                  <?php the_post_thumbnail( 'medium', array( 'style' => 'width:160px;height:auto' ) ); ?>
                  <span class="Title"><?php the_title(); ?></span>
              </a>


              <?php
              $tour_region = '';
              $destination_connected = p2p_type( 'tours_to_destination' )->get_connected( $post_id  );
              while( $destination_connected->have_posts() ) {
                $destination_connected->the_post();
                $tour_region = do_shortcode( '[p2p_connected type=destinations_to_regions mode=inline]' );
              }
              wp_reset_postdata();
              ?>

              <span class="Sub-Title"><?php echo $tour_region; ?></span>

              <p class="Abstract"><?php echo wp_trim_words(get_post_meta( $post_id, 'duvine_abstract', true ), 8, '...'); ?></p>
          </div>

      <?php $tour_counter++; } wp_reset_postdata(); // while have_posts ?>

    <?php } else { ?>

      <article class="Listing-Item Clear">
        <p>No tour results found</p>
      </article>

    <?php } // if have_posts ?>

  </div><!--/.Listing.Grid.Related-->
</div><!--/.Section-->

<div class="Section">
    <span class="Page-Title">Articles</span>
    <div class="Listing Search">
      <?php
      $posts_per_page = 8;
      $article_search = new WP_Query(array(
        'post_type'     => 'post',
        'post_status'   => 'publish',
        's'             => get_search_query(),
        'posts_per_page'=> $posts_per_page
      ));

      if( $article_search->have_posts() ) { ?>

        <?php while( $article_search->have_posts() ) { $article_search->the_post(); ?>

          <article class="Listing-Item Clear">
            <a class="Title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            <time class="Sub-Title" datetime="<?php echo get_the_date( 'Y-m-d' ); ?>"><?php echo get_the_date( 'l, F j, Y g:i a' ); ?></time>
            <p class="Abstract"><?php echo get_the_excerpt(); ?></p>
          </article>

        <?php } wp_reset_postdata(); // while have_posts ?>
      <?php } else { ?>

        <article class="Listing-Item Clear">
          <p>No article results found</p>
        </article>

      <?php } // if have_posts ?>
    </div><!--/.Listing.Search-->

    <?php if( $article_search->found_posts > $posts_per_page ) { ?>
      <a class="Button" href="?s=<?php echo get_search_query(); ?>&type=article">
        <span>View <?php echo $article_search->found_posts; ?> Articles</span>
      </a>
    <?php } ?>
</div><!--/.Section-->


<div class="Section">
  <span class="Page-Title">Content</span>
  <div class="Listing Search">
    <?php
    $posts_per_page = 8;
    $content_search = new WP_Query(array(
      'post_type'     => 'page',
      'post_status'   => 'publish',
      's'             => get_search_query(),
      'posts_per_page'=> $posts_per_page
    ));

    if( $content_search->have_posts() ) { ?>

      <?php while( $content_search->have_posts() ) { $content_search->the_post(); ?>

        <article class="Listing-Item Clear">
          <a class="Title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </article>

      <?php } wp_reset_postdata(); // while have_posts ?>
    <?php } else { ?>

      <article class="Listing-Item Clear">
        <p>No content results found</p>
      </article>

    <?php } // if have_posts ?>
  </div><!--/.Listing.Search-->

  <?php if( $content_search->found_posts > $posts_per_page ) { ?>
    <a class="Button" href="?s=<?php echo get_search_query(); ?>&type=content">
      <span>View <?php echo $content_search->found_posts; ?> Pages</span>
    </a>
  <?php } ?>

</div><!--/.Section-->
