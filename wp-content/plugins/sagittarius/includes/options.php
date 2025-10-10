<?php
// admin options for the sagittarius plugin
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Register Sagittarius custom menu page and subpages.
 */
function sagittarius_register_custom_menu_page() {
    add_menu_page(
        'Tour Data',
        'Tour Data',
        'read',
        'sagittarius/templates/sagitarrius-home.php',
        '',
        'dashicons-chart-line',
        110
    );
    add_submenu_page( 
    	'Tour Data', 
    	'Tour Data', 
    	'Tour Data',
    	'read',
    	'sagittarius/templates/sagitarrius-home.php'
    );
    add_submenu_page( 
    	'sagittarius/templates/sagitarrius-home.php', 
    	'Sync', 
    	'Sync',
    	'read',
    	'sagittarius/templates/sagitarrius-sync.php'
    );
    add_submenu_page( 
    	'sagittarius/templates/sagitarrius-home.php', 
    	'Restore', 
    	'Restore',
    	'read',
    	'sagittarius/templates/sagitarrius-restore.php'
    );
    add_submenu_page( 
        'sagittarius/templates/sagitarrius-home.php', 
        'Check for Tour', 
        'Check for Tour',
        'read',
        'sagittarius/templates/sagitarrius-check-tour.php'
    );
    add_submenu_page( 
    	'sagittarius/templates/sagitarrius-home.php', 
    	'Error Log', 
    	'Error Log',
    	'read',
    	'sagittarius/templates/sagitarrius-errorlog.php'
    );
    add_submenu_page( 
    	'sagittarius/templates/sagitarrius-home.php', 
    	'Settings', 
    	'Settings',
    	'read',
    	'sagittarius/templates/sagitarrius-settings.php'
    );
}
add_action( 'admin_menu', 'sagittarius_register_custom_menu_page' );

/**
 * Registers plugin settings, including endpoint
 */
function sagittarius_register_settings() {
    // endpoint registration/settings/field
    register_setting( 'sagittarius_plugin_options', 'sagittarius_plugin_options', 'sagittarius_plugin_options_validate' );
	add_settings_section('sagittarius_plugin_main', 'Endpoint Settings', 'sagittarius_plugin_section_text', 'sagittarius_plugin');
	add_settings_field('sagittarius_centaur_endpoint', 'Centaur Endpoint', 'centaur_endpoint_setting_string', 'sagittarius_plugin', 'sagittarius_plugin_main');

	// sync page registration/settings/field
	/*register_setting( 'sagittarius_sync_options', 'sagittarius_sync_options', 'sagittarius_sync_options_validate' );
	add_settings_section('sagittarius_plugin_main', 'Endpoint Settings', 'sagittarius_plugin_section_text', 'sagittarius_plugin');
	add_settings_field('sagittarius_centaur_endpoint', 'Centaur Endpoint', 'centaur_endpoint_setting_string', 'sagittarius_plugin', 'sagittarius_plugin_main');
*/
    // tour lookup by id
    register_setting( 'sagittarius_check_tour_options', 'sagittarius_check_tour_options', 'sagittarius_check_tour_options_validate' );
    add_settings_section('sagittarius_plugin_tour_main', '', 'sagittarius_plugin_centaur_id_text', 'sagittarius_plugin_tour');
    add_settings_field('sagittarius_check_tour_id', 'Centaur ID', 'sagittarius_check_tour_id_string', 'sagittarius_plugin_tour', 'sagittarius_plugin_tour_main');
} 
add_action( 'admin_init', 'sagittarius_register_settings' );

function sagittarius_plugin_section_text() {
	echo '<p>Please enter the full URL including "http" or https."</p>';
}
function centaur_endpoint_setting_string() {
	$options = get_option('sagittarius_plugin_options');
	echo "<input id='sagittarius_centaur_endpoint' name='sagittarius_plugin_options[text_string]' size='40' type='text' value='{$options['text_string']}' />";
}
function sagittarius_plugin_options_validate($input) {

	$newinput['text_string'] = trim($input['text_string']);

    if( filter_var($newinput['text_string'], FILTER_VALIDATE_URL) ) :
        return $newinput;
	else :
		add_settings_error( 'sagittarius_centaur_endpoint', 'invalid-endpoint', 'Invalid endpoint format', 'error' );
	endif;
}

function sagittarius_plugin_centaur_id_text() {
    echo '<p>Please enter a valid Centaur tour ID.</p>';
}
function sagittarius_check_tour_id_string() {
    $options = get_option('sagittarius_check_tour_options');
    echo "<input id='sagittarius_check_tour_id' name='sagittarius_plugin_options[text_string]' size='40' type='text' value='{$options['text_string']}' />";
}
function sagittarius_check_tour_options_validate($input) {

    $newinput['text_string'] = trim($input['text_string']);

    if( filter_var($newinput['text_string'], FILTER_SANITIZE_STRING) ) :
        return $newinput;
    else :
        add_settings_error( 'sagittarius_check_tour_id', 'invalid-centaur-id', 'Invalid Centaur ID format', 'error' );
    endif;
}
?>
