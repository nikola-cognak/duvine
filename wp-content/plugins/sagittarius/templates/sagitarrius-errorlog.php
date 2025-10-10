<div class='wrap'>
	<h2>Sagittarius Error Log</h2>
	<p>A listing of errors from the <strong>last</strong> time Sync was run.</p>
	<div class="error_log">
	<?php
	if( isset($GLOBALS['sagittarius']) ) :
		$client = $GLOBALS['sagittarius'];

		if( file_exists($client->error_log) ) {
			$fh = fopen($client->error_log, 'r');
			$size = filesize($client->error_log) > 0 ? filesize($client->error_log) : 1;
		    $pageText = fread($fh, $size);
		    echo nl2br($pageText);
		} else {
			echo '<strong>No error log to display.</strong>';
		}
	else :
		echo '<strong>No Sagittarius instance.</strong>';
	endif;
	?>
	</div>
</div>