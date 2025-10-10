<?php
$date           = date( 'F j, Y', time() );
$range          = 365;
$region         = 0;
$level          = 'anyLevel';
$destination    = 0;
$type           = 0;

if( isset( $_GET['date'] ) && $date_var = $_GET['date'] ) {
    $date = urldecode( $date_var );
}
if( isset( $_GET['range'] ) && $range_var = $_GET['range'] ) {
    $range = urldecode( $range_var );
}
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
?>

<form class="Tours-Form Inline-Form" action="<?php the_permalink(); ?>" method="get">
	<span class="Form-Title">Filter By</span>

	<div class="Inline-Group Clear">
		<div class="Field First">
			<label class="Label" for="date">Starting Date</label>
			<input id="date" name="date" type="Text" class="SelectDate" readonly="readonly" placeholder="Start Date" value="<?php echo esc_attr( $date ) ?>" />
		</div>

		<div class="Field">
			<label class="Label" for="range">Date Range</label>
			<select id="range" name="range" class="SelectBox">
				<option value="14" <?php echo $range == 14 ? 'selected="selected"' : null ?>>14 Days</option>
				<option value="30" <?php echo $range == 30 ? 'selected="selected"' : null ?>>30 Days / 1 Month</option>
				<option value="60" <?php echo $range == 60 ? 'selected="selected"' : null ?>>60 Days / 2 Month</option>
				<option value="90" <?php echo $range == 90 ? 'selected="selected"' : null ?>>90 Days / 3 Month</option>
				<option value="120" <?php echo $range == 120 ? 'selected="selected"' : null ?>>120 Days</option>
				<option value="365" <?php echo $range == 365 ? 'selected="selected"' : null ?>>1 Year</option>
			</select>
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
    						'orderby'   => 'menu_order',
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

	<div class="Action-Buttons">
		<a href="/create-your-own-dates" class="Button Action Overlay-Link" rel=".Overlay" style="margin-right: 13px;"><span>Create Your Own Dates</span></a>
		<a href="/tours/private" class="Button Action"><span class="GoPrivate">Go Private</span></a>
	</div>

	<hr class="Dark" />

</form>
