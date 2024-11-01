<?php
// Direct calls to this file are Forbidden when core files are not present
if (!function_exists ('add_action')) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}

function securityplugin_security_admin_init() {
	// whitelist WPSP DB options 
	register_setting('securityplugin_security_options', 'securityplugin_security_options', 'securityplugin_security_options_validate');
	register_setting('securityplugin_security_options_maint', 'securityplugin_security_options_maint', 'securityplugin_security_options_validate_maint');
	register_setting('securityplugin_security_options_mynotes', 'securityplugin_security_options_mynotes', 'securityplugin_security_options_validate_mynotes');
		
	// Register WPSP js
	wp_register_script( 'bps-js', WP_PLUGIN_URL . '/wordpress-security-plugin/admin/js/wordpress-security-plugin-admin.js');
	//wp_register_script( 'bps-js-hover', WP_PLUGIN_URL . '/wordpress-security-plugin/admin/js/wz_tooltip.js');
				
	// Register WPSP stylesheet
	wp_register_style('bps-css', plugins_url('/wordpress-security-plugin/admin/css/wordpress-security-plugin-admin.css'));

	// Create WPSP Backup Folder structure - suppressing errors on activation - errors displayed in HUD
	if( !is_dir (WP_CONTENT_DIR . '/bps-backup')) {
		@mkdir (WP_CONTENT_DIR . '/bps-backup/master-backups', 0755, true);
		@chmod (WP_CONTENT_DIR . '/bps-backup/', 0755);
		@chmod (WP_CONTENT_DIR . '/bps-backup/master-backups/', 0755);
	}
	
	// Load scripts and styles only on WPSP specified pages
	add_action('load-wordpress-security-plugin/admin/options.php', 'securityplugin_security_load_settings_page');

}

// WPSP Menu
function securityplugin_security_admin_menu() {
	//if (function_exists('add_menu_page')){
	add_menu_page(__('WordPress Security Plugin ~ htaccess Core', 'wordpress-security-plugin'), __('WPSP Security', 'wordpress-security-plugin'), 'manage_options', 'wordpress-security-plugin/admin/options.php', '', plugins_url('wordpress-security-plugin/admin/images/bps-icon-small.png'));
	add_submenu_page('wordpress-security-plugin/admin/options.php', __('WordPress Security Plugin ~ htaccess Core', 'wordpress-security-plugin'), __('WPSP Settings', 'wordpress-security-plugin'), 'manage_options', 'wordpress-security-plugin/admin/options.php' );
}

// Loads Settings for H-Core and P-Security
// Enqueue WPSP scripts and styles
function securityplugin_security_load_settings_page() {
	global $securityplugin_security;
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-form');
	//wp_enqueue_script('swfobject');
	wp_enqueue_script('bps-js');
	//wp_enqueue_script('bps-js-hover');
	  	
	// Engueue WPSP stylesheet
	wp_enqueue_style('bps-css', plugins_url('/wordpress-security-plugin/admin/css/wordpress-security-plugin-admin.css'));
}

function securityplugin_security_install() {
	global $securityplugin_security;
	$previous_install = get_option('securityplugin_security_options');
	if ( $previous_install ) {
	if ( version_compare($previous_install['version'], '1.2.0', '<') )
	remove_role('denied');
	}
}

// unregister_setting( $option_group, $option_name, $sanitize_callback );

function securityplugin_security_uninstall() {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php');
	$options = get_option('securityplugin_security_options');
	delete_option('securityplugin_security_options');
}

// Validate WPSP options 
function securityplugin_security_options_validate($input) {  
	$options = get_option('securityplugin_security_options');  
	$options['bps_blank'] = wp_filter_nohtml_kses($input['bps_blank']);
			
	return $options;  
}

// Validate WPSP options - Maintenance Mode Form 
function securityplugin_security_options_validate_maint($input) {  
	$options = get_option('securityplugin_security_options_maint');  
	$options['bps-site-title'] = wp_filter_nohtml_kses($input['bps-site-title']);
	$options['bps-message-1'] = wp_filter_nohtml_kses($input['bps-message-1']);
	$options['bps-message-2'] = wp_filter_nohtml_kses($input['bps-message-2']);
	$options['bps-start-date'] = wp_filter_nohtml_kses($input['bps-start-date']);
	$options['bps-start-time'] = wp_filter_nohtml_kses($input['bps-start-time']);
	$options['bps-end-date'] = wp_filter_nohtml_kses($input['bps-end-date']);
	$options['bps-end-time'] = wp_filter_nohtml_kses($input['bps-end-time']);
	$options['bps-popup-message'] = wp_filter_nohtml_kses($input['bps-popup-message']);
	$options['bps-retry-after'] = wp_filter_nohtml_kses($input['bps-retry-after']);
	$options['bps-background-image'] = wp_filter_nohtml_kses($input['bps-background-image']);
		
	return $options;  
}

// Validate WPSP options - WPSP "My Notes" settings 
function securityplugin_security_options_validate_mynotes($input) {  
	$options = get_option('securityplugin_security_options_mynotes');  
	$options['bps_my_notes'] = htmlspecialchars($input['bps_my_notes']);
		
	return $options;  
}

?>