<form role="search" method="get" id="searchform" class="Search-Form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="screen-reader-text" for="s"><?php _x( 'Search for:', 'label' ); ?></label>
    <input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" class="Search-Input locationString" placeholder="Search" />
<!--     <input type="submit" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>" /> -->
</form>
