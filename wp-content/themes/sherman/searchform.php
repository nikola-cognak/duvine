<?php
/**
 * Template for displaying search forms in Twenty Eleven
 *
 * @package Sherman
 */
?>
<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label for="s" class="assistive-text"><?php _e( 'Search', 'twentyeleven' ); ?></label>
    <input type="search" name="s" placeholder="<?php esc_attr_e( 'Search', 'twentyeleven' ); ?>" />
    <button type="submit" class="searchform__submit" id="searchsubmit">Search</button>
</form>