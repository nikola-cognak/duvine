<div class='wrap'>
	<h2>Check for Tour in Feed</h2>
	<p>Use a Centaur ID to see if a tour exists in the feed.</p>
	<div class="check-tour-report"></div>
	<div class="ajax-spinner" style="display: none;"><img src="<?php echo get_template_directory_uri(); ?>/img/spinner.gif" width="70" height="70" /></div>
	<div class="settings">
		<form action="options.php" method="post">
			<?php 
			settings_fields('sagittarius_check_tour_options');
			settings_errors();
			do_settings_sections('sagittarius_plugin_tour');
			$args = array( 'id' => 'check-tour' );
			submit_button('Check Tour', 'primary', 'check-tour', false, $args);
			?>
		</form>
	</div>
</div>

