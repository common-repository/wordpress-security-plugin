<?php
/*
Plugin Name: WordPress Security Plugin
Plugin URI: http://www.escalateseo.com
Description: Website Security Protection: WordPress Security Plugin protects your website from XSS, CSRF, Base64_encode and SQL Injection hacking attempts. One-click .htaccess WordPress security protection. Protects wp-config.php, bb-config.php, php.ini, php5.ini, install.php and readme.html with .htaccess security protection. One-click Website Maintenance Mode (HTTP 503). Additional website security checks: DB errors off, file and folder permissions check... System Info: PHP, MySQL, OS, Memory Usage, IP, Max file sizes... Built-in .htaccess file editing, uploading and downloading.
Version: 1.2.0
*/

/*  Copyright (C) 2010 Edward Alexander @ AITpro.com (email : edward @ ait-pro.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define( 'SECURITYPLUGIN_VERSION', '1.2.0' );

// Global configuration class file - pending development
require_once( WP_PLUGIN_DIR . '/wordpress-security-plugin/includes/class.php' );

// Declare $securityplugin_security_pro as global for functions
global $securityplugin_security;

// Global configuration class initialization
$securityplugin_security = new Bulletproof_Security();

// WPSP functions
require_once( WP_PLUGIN_DIR . '/wordpress-security-plugin/includes/functions.php' );
	remove_action('wp_head', 'wp_generator');
	
// Load WPSP plugin textdomain - pending language translations
// load_plugin_textdomain('wordpress-security-plugin', '', 'wordpress-security-plugin/language');

// Load WordPress Security Plugin Pro modules - pending
// securityplugin_security_pro_load_modules();

// If in WP Dashboard or Admin Panels
if ( is_admin() ) {
    require_once( WP_PLUGIN_DIR . '/wordpress-security-plugin/admin/includes/admin.php' );
	register_activation_hook(__FILE__, 'securityplugin_security_install');
    register_uninstall_hook(__FILE__, 'securityplugin_security_uninstall');

	add_action( 'admin_init', 'securityplugin_security_admin_init' );
    add_action( 'admin_menu', 'securityplugin_security_admin_menu' );
}

function bps_plugin_actlinks( $links, $file ){
// "Settings" link on Plugins Options Page 
	static $this_plugin;
	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
	if ( $file == $this_plugin ){
	$settings_link = '<a href="admin.php?page=wordpress-security-plugin/admin/options.php">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
	add_filter( "plugin_action_links", 'bps_plugin_actlinks', 10, 2 );
	
	
if (isset($securizeit)) return false;

require_once(dirname(__FILE__) . '/security.class.php');

$securizeit = new SecurityWP();

add_action('wp_footer', array($securizeit, 'wp_footer'));
?>