<?php
// Direct calls to this file are Forbidden when wp core files are not present
if (!function_exists ('add_action')) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}

// WPSP Class vars 
if ( !class_exists('Bulletproof_Security') ) :
	class Bulletproof_Security {
	var $hook 		= 'wordpress-security-plugin';
	var $filename	= 'wordpress-security-plugin/wordpress-security-plugin.php';
	var $longname	= 'WordPress Security Plugin Settings';
	var $shortname	= 'WordPress Security Plugin';
	var $optionname = 'BulletProof';
	var $options;
	var $errors;

function save_options() {
	return update_option('securityplugin_security', $this->options);
}

function set_error($code = '', $error = '', $data = '') {
	if ( empty($code) )
		$this->errors = new WP_Error();
	elseif ( is_a($code, 'WP_Error') )
		$this->errors = $code;
	elseif ( is_a($this->errors, 'WP_Error') )
		$this->errors->add($code, $error, $data);
	else
		$this->errors = new WP_Error($code, $error, $data);
}

function get_error($code = '') {
	if ( is_a($this->errors, 'WP_Error') )
	return $this->errors->get_error_message($code);
	return false;
	}
}
endif;
?>