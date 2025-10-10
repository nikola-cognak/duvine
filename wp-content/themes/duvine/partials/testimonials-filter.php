<?php
$region         = 0;
$destination    = 0;
$level          = 'anyLevel';
$type           = 0;
$search_string  = '';

if( isset( $_GET['regionId'] ) && $region_var = $_GET['regionId'] ) {
    $region = abs((int) filter_input(INPUT_GET, 'regionId', FILTER_SANITIZE_NUMBER_INT) );
}

if( isset( $_GET['destinationId'] ) && $destination_var = $_GET['destinationId'] ) {
    $destination = abs((int) filter_input(INPUT_GET, 'destinationId', FILTER_SANITIZE_NUMBER_INT) );
}

if( isset( $_GET['level'] ) && $level_var = $_GET['level'] ) {
    $level = urldecode( $level_var );
}
if( isset( $_GET['type'] ) && $type_var = $_GET['type'] ) {
    $type = abs((int) filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT) );
}
if( isset( $_GET['searchString'] ) ) {
    $search_string = urldecode( $_GET['searchString'] );
}
?>

<form class="Story-Form Inline-Form" action="/testimonials#listingContent" method="get">
    <div class="Inline-Group Clear" style="height: 30px;">
        <div class="Field First">
            <label class="Label" for="searchString">Search Term / Keyword</label>
            <input type="text" id="searchString" name="searchString" class="Input" value="<?php echo esc_attr( $search_string ); ?>">
        </div>

        <div class="Field">
            <label class="Label" for="regionId">Region</label>
            <select id="regionId" name="regionId" class="SelectBox">
                    <option value="0">Any Region</option>
                    <?php
                    $regions_query = wp_cache_get( 'region_filter_query' );
                    if( false == $regions_query ) {
                        $regions_query = new WP_Query( array(
                            'post_type' => 'duvine_regions',
                            'order'     => 'ASC',
                            'orderby'   => 'ID',
                            'posts_per_page' => -1
                        ) );

                        wp_cache_set( 'region_filter_query', $regions_query );
                    }
                    while( $regions_query->have_posts() ) { $regions_query->the_post(); ?>
                            <option value="<?php echo $post->ID; ?>" <?php echo $region == $post->ID ? 'selected="selected"' : null ?>><?php the_title(); ?></option>
                    <?php } wp_reset_postdata(); ?>
            </select>
        </div>

        <div class="Field">
            <label class="Label" for="destinationId">Destination</label>
            <select name="destinationId" class="SelectBox <?php echo $region == 0 ? 'selectBox-disabled' : null; ?> selectBox" style="display: none;">
                <option value="0">Any Destination</option>
                <?php
                if( $region != 0 ) :
                    $destinations_query = new WP_Query( array(
                        'post_type' => 'duvine_destinations',
                        'order'     => 'ASC',
                        'orderby'   => 'title',
                        'posts_per_page' => -1,
                        'connected_type' => 'destinations_to_regions',
                        'connected_items' => $region,
                        'connected_direction' => 'to',
                    ) );

                    while( $destinations_query->have_posts() ) : $destinations_query->the_post();
                ?>
                    <option value="<?php echo $post->ID; ?>" <?php echo $destination == $post->ID ? 'selected="selected"' : null ?>><?php the_title(); ?></option>
                <?php endwhile; endif; wp_reset_postdata(); ?>
            </select>
        </div>

        <div class="Field">
            <label class="Label" for="level">Level <span class="Help"><a class="Link Overlay-Link" rel=".Overlay" href="/experience/levels-modal"><img class="InformationIcon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/information.png" alt="(what&rsquo;s this)" /></a></span></label>
            <select id="level" name="level" class="SelectBox">
                <option value="anyLevel"  <?php echo $level == 'anyLevel' ? 'selected="selected"' : null ?>>Any Level</option>
                <option value="occasionalRider" <?php echo $level == 'occasionalRider' ? 'selected="selected"' : null ?>>One</option>
                <option value="weekendRider" <?php echo $level == 'weekendRider' ? 'selected="selected"' : null ?>>Two</option>
                <option value="activeRider" <?php echo $level == 'activeRider' ? 'selected="selected"' : null ?>>Three</option>
                <option value="proRider" <?php echo $level == 'proRider' ? 'selected="selected"' : null ?>>Four</option>
            </select>
        </div>

        <div class="Field">
            <label class="Label" for="type">Tour Type <span class="Help"><a class="Link Overlay-Link" rel=".Overlay" href="/experience/tour-types-modal"><img class="InformationIcon" src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/information.png" alt="(what&rsquo;s this)" /></a></span></label>
            <select id="type" name="type" class="SelectBox">
                <option value="0">Any Tour Type</option>
                <?php
                $type_query = wp_cache_get( 'type_filter_query' );
                if( false == $type_query ) {
                    $type_query = new WP_Query( array(
                        'post_type' => 'duvine_tour_types',
                        'order'     => 'ASC',
                        'orderby'   => 'ID',
                        'posts_per_page' => -1,
                        'meta_query' => array(
                                array(
                                    'key' => 'duvine_abstract',
                                    'value'   => array(''),
                                    'compare' => 'NOT IN'
                                )
                            )
                    ) );
                    wp_cache_set( 'type_filter_query', $type_query );
                }
                while( $type_query->have_posts() ) { $type_query->the_post(); ?>

                    <option value="<?php echo $post->ID; ?>" <?php echo $type == $post->ID ? 'selected="selected"' : null ?>><?php the_title(); ?></option>

                <?php } wp_reset_postdata(); ?>
            </select>
        </div>
    </div>
</form>
