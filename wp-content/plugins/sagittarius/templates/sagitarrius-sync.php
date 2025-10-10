<div class='wrap'>
	<h2>Sagittarius Sync</h2>
	<p>Get the latest tour data from Centaur. <strong>Please note:</strong> the current tour data will be backed up so you can later restore if needed.</p>
	<div class="settings">
		<div class="ajax-spinner" style="display: none;"><img src="<?php echo get_template_directory_uri(); ?>/img/spinner.gif" width="70" height="70" /></div>
		<div class="sync-report"></div>
		<form method="post"> 
			<?php
			submit_button('Sync Data', 'primary', 'submit', false, array('id' => 'get-sync-data'));
			?>
			<div class="make-live" style="display: none;">
				<div class="ajax-spinner-live" style="display: none;"><img src="<?php echo get_template_directory_uri(); ?>/img/spinner.gif" width="70" height="70" /></div>
				<?php
				submit_button('Make Live', 'primary', 'submit', false, array('id' => 'make-live'));
				?>
			</div>
		</form>
	</div>
</div>
