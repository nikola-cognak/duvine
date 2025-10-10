<div class='wrap'>
	<h2>Sagittarius Setting</h2>
	<p>Setttings for Centaur API.</p>
	<div class="settings">
		<form action="options.php" method="post">
			<?php 
			settings_fields('sagittarius_plugin_options');
			//settings_errors();
			do_settings_sections('sagittarius_plugin');
			$args = array( 'id' => 'save-settings' );
			submit_button('Save Settings', 'primary', 'save-settings', false, $args);
			?>
		</form>
	</div>
</div>