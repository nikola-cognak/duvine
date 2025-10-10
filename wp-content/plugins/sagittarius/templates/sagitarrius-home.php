<div class='wrap'>
	<h2>Sagittarius Administration</h2>
	<p>This plugin pulls data from the Centaur API and moves data into a local data feed. The tour pages then read this data in to display dates and pricing.</p>
	<?php
	$client = $GLOBALS['sagittarius'];
	$endpoint = get_option('sagittarius_plugin_options');
	if( !$client->is_valid_endpoint($endpoint['text_string']) ) :
	  echo '<p><strong>You will need to set the API endpoint in <a href="/wp-admin/admin.php?page=sagittarius/templates/sagitarrius-settings.php">settings</a> before using this plugin.</strong></p>';
	endif;
	?>
	<p>
		<ul>
			<li><a href="/wp-admin/admin.php?page=sagittarius/templates/sagitarrius-sync.php">Sync</a></li>
			<li><a href="/wp-admin/admin.php?page=sagittarius/templates/sagitarrius-restore.php">Restore</a></li>
			<li><a href="/wp-admin/admin.php?page=sagittarius/templates/sagitarrius-check-tour.php">Check for Tour</a></li>
			<li><a href="/wp-admin/admin.php?page=sagittarius/templates/sagitarrius-errorlog.php">Error Log</a></li>
			<li><a href="/wp-admin/admin.php?page=sagittarius/templates/sagitarrius-settings.php">Settings</a></li>
		</ul>
	</p>
</div>

