<h1 class="Page-Title Tour Center">Tours by Region</h1>

    <div class="Page Section Regions">

        <div class="Listing-Section Clear">

        <?php
        $counter = 1;
        $div_is_open = false;

        while ( have_posts() ) : the_post();  $div_is_open = false; ?>

              <?php if( 1 == $counter ) { ?>
                  <div class="Row Clear">
              <?php } ?>

              <div class="Region <?php echo ($counter % 3 == 1) ? 'First' : null ?>">
                <?php get_template_part( 'partials/regions', 'index' ); ?>
              </div>

              <?php
              if( $counter % 3 == 0 ) { $div_is_open = true; ?>

                  </div><!--/.Row.Clear-->
                  <div class="Row Clear">

              <?php
              }
              $counter++;
              ?>
        <?php endwhile; // end of the loop. ?>


          <?php if( $div_is_open ) { ?>
              </div><!--/.Row.Clear-->
          <?php } ?>

        </div><!--/.Listing-Section.Clear-->

    </div><!--/.Page.Section.Regions-->
