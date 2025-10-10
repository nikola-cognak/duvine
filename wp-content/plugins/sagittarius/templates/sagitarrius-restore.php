<div class='wrap'>
	<h2>Sagittarius Restore</h2>
	<p>Restore API data from previous data feed. Backup files are listed newest to oldest.</p>
	<div class="restore-report"></div>
	<div class="ajax-spinner" style="display: none;"><img src="<?php echo get_template_directory_uri(); ?>/img/spinner.gif" width="70" height="70" /></div>
	<form id="restore-form" method="post" action="options.php"> 
	<?php
	if( isset($GLOBALS['sagittarius']) ) :
		$client = $GLOBALS['sagittarius'];
		$client->list_backup_files();
		submit_button('Restore', 'primary', 'submit', false, array('id' => 'restore'));
	else :
		echo '<strong>No Sagittarius instance.</strong>';
	endif;
	?>
	</form>
	<br /><br />
	<h2>Remove Old Feeds</h2>
	<p>Remove all old feed files (leaves main feed intact).</p>
	<div class="clear-report"></div>
	<div class="ajax-spinner-remove" style="display: none;"><img src="<?php echo get_template_directory_uri(); ?>/img/spinner.gif" width="70" height="70" /></div>
	<?php
	submit_button('Remove files', 'primary', 'submit', false, array('id' => 'remove-files'));
	?>
</div>

