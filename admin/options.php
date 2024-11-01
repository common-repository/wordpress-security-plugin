<?php
// Direct calls to this file are Forbidden when core files are not present
if ( !function_exists('add_action') ){
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}
 
if ( !current_user_can('manage_options') ){ 
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}
?>

<div id="message" class="updated" style="border:1px solid #999999; margin-left:70px; margin-top:9px;">
<?php
// HUD - Heads Up Display - Warnings and Error messages
echo bps_check_php_version_error();
echo bps_check_permalinks_error();
echo bps_check_iis_supports_permalinks();
echo bps_hud_check_bpsbackup();
echo bps_check_safemode();
echo bps_w3tc_htaccess_check($plugin_var);
echo bps_wpsc_htaccess_check($plugin_var);

// Form - copy and rename htaccess file to root folder
// WordPress Security Plugin and Default Mode
$bpsecureroot = 'unchecked';
$bpdefaultroot = 'unchecked';
if (isset($_POST['submit12']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_root_copy' );
	
	$old = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/default.htaccess';
	$new = ABSPATH . '/.htaccess';
	$old1 = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/secure.htaccess';
	$new1 = ABSPATH . '/.htaccess';
	
	$selected_radio = $_POST['selection12'];
	if ($selected_radio == 'bpsecureroot') {
	$bpsecureroot = 'checked';
		copy($old1, $new1);
		chmod($new1, 0644);
		if (!copy($old1, $new1)) {
	_e('<font color="red"><strong>Failed to Activate WordPress Security Plugin Root Folder Protection! Your Website is NOT protected with WordPress Security Plugin!</strong></font><br>');
   	} else {
	_e('<font color="green"><strong>WordPress Security Plugin Root Folder Protection Activated. Your website Root folder is now protected with WordPress Security Plugin.</strong></font><br><font color="red"><strong>IMPORTANT!</strong></font><strong> BulletProof Mode for the wp-admin folder MUST also be activated when you have BulletProof Mode activated for the Root folder.</strong><br>');
    }
	}
	elseif ($selected_radio == 'bpdefaultroot') {
	$bpdefaultroot = 'checked';
		copy($old, $new);
		chmod($new, 0644);
		if (!copy($old, $new)) {
	_e('<font color="red"><strong>Failed to Activate Default .htaccess Mode!</strong></font><br>');
   	} else {
	_e('<font color="red"><strong>Warning: Default .htaccess Mode Is Activated In Your Website Root Folder. Your Website Is Not Protected With WordPress Security Plugin.</strong></font>');
	}
	}
}

// Form - copy and rename htaccess file to wp-admin folder
// WordPress Security Plugin wp-admin
$bpsecurewpadmin = 'unchecked';
$Removebpsecurewpadmin = 'unchecked';
if (isset($_POST['submit13']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_wpadmin_copy' );
	
	$oldadmin1 = ABSPATH . 'wp-content/plugins/wordpress-security-plugin/admin/htaccess/wpadmin-secure.htaccess';
	$newadmin1 = ABSPATH . 'wp-admin/.htaccess';
	$deleteWpadminHtaccess = ABSPATH . 'wp-admin/.htaccess';
	
	$selected_radio = $_POST['selection13'];
	if ($selected_radio == 'bpsecurewpadmin') {
	$bpsecurewpadmin = 'checked';
		copy($oldadmin1, $newadmin1);
		chmod($newadmin1, 0644);
		if (!copy($oldadmin1, $newadmin1)) {
	_e('<font color="red"><strong>Failed to Activate WordPress Security Plugin wp-admin Folder Protection! Your wp-admin folder is NOT protected with WordPress Security Plugin!</strong></font><br>');
   	} else {
	_e('<font color="green"><strong>WordPress Security Plugin wp-admin Folder Protection Activated. Your wp-admin folder is now protected with WordPress Security Plugin.</strong></font>');
	}
	}
	elseif ($selected_radio == 'Removebpsecurewpadmin') {
	$Removebpsecurewpadmin = 'checked';
	$fh = fopen($deleteWpadminHtaccess, 'a');
	fwrite($fh, 'delete');
	fclose($fh);
	unlink($deleteWpadminHtaccess);
	if (file_exists($deleteWpadminHtaccess)) {
	_e('<font color="red"><strong>Failed to Delete the wp-admin .htaccess file! The file does not exist. It may have been deleted or renamed already.</strong></font><br>');
   	} else {
	_e('<font color="green"><strong>The wp-admin .htaccess file has been Deleted. </strong></font><font color="red"><strong>Your wp-admin folder is no longer .htaccess protected.</strong></font> If you are testing then be sure to reactivate BulletProof Mode for your wp-admin folder when you are done testing. If you are removing WPSP from your website then be sure to also Activate Default Mode for your Root folder. The Root and wp-admin BulletProof Modes must be activated together or removed together.</strong></font><br>');
	}
	}
}

// Form rename Deny All htaccess file to .htaccess for the WPSP Master htaccess folder
$bps_rename_htaccess_files = 'unchecked';
if (isset($_POST['submit8']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_denyall_master' );
	
	$bps_rename_htaccess = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/deny-all.htaccess';
	$bps_rename_htaccess_renamed = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/.htaccess';
	
	$selected_radio = $_POST['selection8'];
	if ($selected_radio == 'bps_rename_htaccess_files') {
	$bps_rename_htaccess_files = 'checked';
		copy($bps_rename_htaccess, $bps_rename_htaccess_renamed);
		if (!copy($bps_rename_htaccess, $bps_rename_htaccess_renamed)) {
	_e('<font color="red"><strong>Failed to Activate WordPress Security Plugin Deny All Folder Protection! Your WPSP Master htaccess folder is NOT Protected with Deny All htaccess folder protection!</strong></font><br>');
   	} else {
	_e('WordPress Security Plugin Deny All Folder Protection <font color="green"><strong>Activated.</strong></font> Your WPSP Master htaccess folder is Now Protected with Deny All htaccess folder protection.');
	}
}
}

// Form copy and rename the Deny All htaccess file to the WPSP backup folder
// /wp-content/bps-backup
$bps_rename_htaccess_files_backup = 'unchecked';
if (isset($_POST['submit14']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_denyall_bpsbackup' );
	
	$bps_rename_htaccess_backup = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/deny-all.htaccess';
	$bps_rename_htaccess_backup_online = ABSPATH . '/wp-content/bps-backup/.htaccess';
	
	$selected_radio = $_POST['selection14'];
	if ($selected_radio == 'bps_rename_htaccess_files_backup') {
	$bps_rename_htaccess_files_backup = 'checked';
		copy($bps_rename_htaccess_backup, $bps_rename_htaccess_backup_online);
		if (!copy($bps_rename_htaccess_backup, $bps_rename_htaccess_backup_online)) {
	_e('<font color="red"><strong>Failed to Activate WordPress Security Plugin Deny All Folder Protection! Your WPSP /wp-content/bps-backup folder is NOT Protected with Deny All htaccess folder protection!</strong></font><br>');
   	} else {
	_e('WordPress Security Plugin Deny All Folder Protection <font color="green"><strong>Activated.</strong></font> Your WPSP /wp-content/bps-backup folder is Now Protected with Deny All htaccess folder protection.');
	}
	}
}

// Form - Backup and rename existing and / or currently active htaccess files from 
// the root and wpadmin folders to /wp-content/bps-backup
$backup_htaccess = 'unchecked';
if (isset($_POST['submit9']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_backup_active_htaccess_files' );
	
	$old_backroot = ABSPATH . '/.htaccess';
	$new_backroot = ABSPATH . '/wp-content/bps-backup/root.htaccess';
	$old_backwpadmin = ABSPATH . '/wp-admin/.htaccess';
	$new_backwpadmin = ABSPATH . '/wp-content/bps-backup/wpadmin.htaccess';
	
	$selected_radio = $_POST['selection9'];
	if ($selected_radio == 'backup_htaccess') {
	$backup_htaccess = 'checked';
	if ( !file_exists($old_backroot)) { 
	_e('<font color="red"><strong>You do not currently have an .htaccess file in your Root folder to backup.</strong></font><br>');
	} else {	
	if (file_exists($old_backroot)) { 
 		copy($old_backroot, $new_backroot);
		if (!copy($old_backroot, $new_backroot)) {
	_e('<font color="red"><strong>Failed to Backup Your Root .htaccess File! File copy function failed. Check the folder permissions for the /wp-content/bps-backup folder. Folder permissions should be set to 755.</strong></font><br><br>');
	} else {
	_e('<font color="green"><strong>Your currently active Root .htaccess file has been backed up successfully!</strong></font><br>Use the Restore feature to restore your .htaccess files if you run into a problem at any time. If you make additional changes or install a plugin that writes to the htaccess files then back them up again. This will overwrite the currently backed up htaccess files. Please read the <font color="red"><strong>CAUTION:</strong></font> Read Me ToolTip on the Backup & Restore Page for more detailed information.<br><br>');
	
	if ( !file_exists($old_backwpadmin)) { 
	_e('<font color="red"><strong>You do not currently have an .htaccess file in your wp-admin folder to backup.</strong></font><br>');
	} else {
	if (file_exists($old_backwpadmin)) { 	
		copy($old_backwpadmin, $new_backwpadmin);
		if (!copy($old_backwpadmin, $new_backwpadmin)) {
	_e('<font color="red"><strong>Failed to Backup Your wp-admin .htaccess File! File copy function failed. Check the folder permissions for the /wp-content/bps-backup folder. Folder permissions should be set to 755.</strong></font><br>');
	} else {
	_e('<font color="green"><strong>Your currently active wp-admin .htaccess file has been backed up successfully!</strong></font><br>');
	}
}}}}}}}

// Form - Restore backed up htaccess files
$restore_htaccess = 'unchecked';
if (isset($_POST['submit10']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_restore_active_htaccess_files' );
	
	$old_restoreroot = ABSPATH . '/wp-content/bps-backup/root.htaccess';
	$new_restoreroot = ABSPATH . '/.htaccess';
	$old_restorewpadmin = ABSPATH . '/wp-content/bps-backup/wpadmin.htaccess';
	$new_restorewpadmin = ABSPATH . '/wp-admin/.htaccess';
	
	$selected_radio = $_POST['selection10'];
	if ($selected_radio == 'restore_htaccess') {
		$restore_htaccess = 'checked';
	if (file_exists($old_restoreroot)) { 
 		copy($old_restoreroot, $new_restoreroot);
		if (!copy($old_restoreroot, $new_restoreroot)) {
	_e('<font color="red"><strong>Failed to Restore Your Root .htaccess File! This is most likely because you DO NOT currently have a Backed up Root .htaccess file.</strong></font><br>');
   	} else {
	_e('<font color="green"><strong>Your Root .htaccess file has been Restored successfully!</strong></font><br>');
	if (file_exists($old_restorewpadmin)) { 	
		copy($old_restorewpadmin, $new_restorewpadmin);
		if (!copy($old_restorewpadmin, $new_restorewpadmin)) {
	_e('<font color="red"><strong>Failed to Restore Your wp-admin .htaccess File! This is most likely because you DO NOT currently have a Backed up wp-admin .htaccess file.</strong></font><br>');
   	} else {
	_e('<font color="green"><strong>Your wp-admin .htaccess file has been Restored successfully!</strong></font><br>');
	}
}}}}}

// Form - Backup the WPSP Master Files to /wp-content/bps-backup/master-backups
$backup_master_htaccess_files = 'unchecked';
if (isset($_POST['submit11']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_backup_master_htaccess_files' );
	
$default_master = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/default.htaccess';
$default_master_backup = ABSPATH . '/wp-content/bps-backup/master-backups/backup_default.htaccess';
$secure_master = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/secure.htaccess';
$secure_master_backup = ABSPATH . '/wp-content/bps-backup/master-backups/backup_secure.htaccess';
$wpadmin_master = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/wpadmin-secure.htaccess';
$wpadmin_master_backup = ABSPATH . '/wp-content/bps-backup/master-backups/backup_wpadmin-secure.htaccess';
$maintenance_master = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/maintenance.htaccess';
$maintenance_master_backup = ABSPATH . '/wp-content/bps-backup/master-backups/backup_maintenance.htaccess';
$bp_maintenance_master = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/bp-maintenance.php';
$bp_maintenance_master_backup = ABSPATH . '/wp-content/bps-backup/master-backups/backup_bp-maintenance.php';
$bps_maintenance_values = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/bps-maintenance-values.php';
$bps_maintenance_values_backup = ABSPATH . '/wp-content/bps-backup/master-backups/backup_bps-maintenance-values.php';

	$selected_radio = $_POST['selection11'];
	if ($selected_radio == 'backup_master_htaccess_files') {
		$backup_master_htaccess_files = 'checked';
	if (file_exists($default_master)) { 
 		copy($default_master, $default_master_backup);
		if (!copy($default_master, $default_master_backup)) {
	_e('<font color="red"><strong>Failed to Backup Your default.htaccess File!</strong></font><br>');
   	} else {
	_e('<font color="green"><strong>The default.htaccess file has been backed up successfully!</strong></font><br>');
	}
	if (file_exists($secure_master)) { 	
		copy($secure_master, $secure_master_backup);
		if (!copy($secure_master, $secure_master_backup)) {
	_e('<font color="red"><strong>Failed to Backup Your secure.htaccess File!</strong></font><br>');
   	} else {
	_e('<font color="green"><strong>The secure.htaccess file has been backed up successfully!</strong></font><br>');
	}
	if (file_exists($wpadmin_master)) { 	
		copy($wpadmin_master, $wpadmin_master_backup);
		if (!copy($wpadmin_master, $wpadmin_master_backup)) {
	_e('<font color="red"><strong>Failed to Backup Your wpadmin-secure.htaccess File!</strong></font><br>');
   	} else {
	_e('<font color="green"><strong>The wpadmin-secure.htaccess file has been backed up successfully!</strong></font><br>');
	}
	if (file_exists($maintenance_master)) { 	
		copy($maintenance_master, $maintenance_master_backup);
		if (!copy($maintenance_master, $maintenance_master_backup)) {
	_e('<font color="red"><strong>Failed to Backup Your maintenance.htaccess File!</strong></font><br>');
   	} else {
	_e('<font color="green"><strong>The maintenance.htaccess file has been backed up successfully!</strong></font><br>');
	}
	if (file_exists($bp_maintenance_master)) { 	
		copy($bp_maintenance_master, $bp_maintenance_master_backup);
		if (!copy($bp_maintenance_master, $bp_maintenance_master_backup)) {
	_e('<font color="red"><strong>Failed to Backup Your bp-maintenance.php File!</strong></font><br>');
   	} else {
	_e('<font color="green"><strong>The bp-maintenance.php file has been backed up successfully!</strong></font><br>');
	}
	if (file_exists($bps_maintenance_values)) { 	
		copy($bps_maintenance_values, $bps_maintenance_values_backup);
		if (!copy($bps_maintenance_values, $bps_maintenance_values_backup)) {
	_e('<font color="red"><strong>Failed to Backup Your bps-maintenance-values.php File!</strong></font><br>');
   	} else {
	_e('<font color="green"><strong>The bps-maintenance-values.php file has been backed up successfully!</strong></font><br>');
	}
}}}}}}}}
	
// Form - Activate Maintenance Mode copy and rename maintenance htaccess, bp-maintenance.php and bps-maintenance-values.php to root
$bpmaintenance = 'unchecked';
if (isset($_POST['submit15']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_maintenance_copy' );

$oldmaint = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/maintenance.htaccess';
$newmaint = ABSPATH . '/.htaccess';
$oldmaint1 = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/bp-maintenance.php';
$newmaint1 = ABSPATH . '/bp-maintenance.php';
$oldmaint_values = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/bps-maintenance-values.php';
$newmaint_values = ABSPATH . '/bps-maintenance-values.php';

	$selected_radio = $_POST['selection15'];
	if ($selected_radio == 'bpmaintenance') {
	$bpmaintenance = 'checked';
		copy($oldmaint, $newmaint);
		copy($oldmaint1, $newmaint1);
		copy($oldmaint_values, $newmaint_values);
		if (!copy($oldmaint, $newmaint)) {
	_e('<font color="red"><strong>Failed to Activate Maintenance Mode! Your Website is NOT in Maintenance Mode!</strong></font><br>');
   	} else {
	_e('<font color="red"><strong>Warning: </strong></font>Maintenance Mode Is Activated. Your website is now displaying the Website Under Maintenance page to everyone except you. To switch out of Maintenance mode activate WordPress Security Plugin Mode. You can log in and out of your Dashboard / WordPress website in Maintenance Mode as long as your current IP address does not change. If your current IP address changes you will have to FTP to your website and delete the .htaccess file in your website root folder (or download the .htaccess file and add your new IP address and upload it back to your root website folder) to be able to log back into your WordPress Dashboard. Your ISP provides your current Public IP address. If you reboot your computer or disconnect from the Internet there is a good chance that you will get a new Public IP address from your ISP.');
	}
	}
}	

// Create maintenance htaccess file
if (isset($_POST['bps-auto-write-maint']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_auto_write_maint' );
	
$bps_string_replace_maint = array(".");
$bps_get_IP_maint = str_replace($bps_string_replace_maint, "\.", $_SERVER['REMOTE_ADDR']) . "$";
$bps_get_wp_root_maint = bps_wp_get_root_folder();
$bps_auto_write_maint_file = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/maintenance.htaccess';
$bps_maint_content = "#   SECURITYPLUGIN 1.2.0 MAINTENANCE  .HTACCESS     \n   
RewriteEngine On
RewriteBase $bps_get_wp_root_maint\n
RewriteCond %{REQUEST_METHOD} ^(HEAD|TRACE|DELETE|TRACK) [NC]
RewriteRule ^(.*)$ - [F,L]\n
RewriteCond %{QUERY_STRING} \.\.\/ [NC,OR]
RewriteCond %{QUERY_STRING} boot\.ini [NC,OR]
RewriteCond %{QUERY_STRING} tag\= [NC,OR]
RewriteCond %{QUERY_STRING} ftp\:  [NC,OR]
RewriteCond %{QUERY_STRING} http\:  [NC,OR]
RewriteCond %{QUERY_STRING} https\:  [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*iframe.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} mosConfig_[a-zA-Z_]{1,21}(=|%3D) [NC,OR]
RewriteCond %{QUERY_STRING} base64_encode.*\(.*\) [NC,OR]
RewriteCond %{QUERY_STRING} GLOBALS(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} _REQUEST(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} ^(.*)cPath=http://(.*)$ [NC,OR]
RewriteCond %{QUERY_STRING} ^(.*)/self/(.*)$ [NC,OR]
RewriteCond %{QUERY_STRING} ^.*(\[|\]|\(|\)|<|>).* [NC,OR]
RewriteCond %{QUERY_STRING} ^.*(globals|encode|localhost|loopback).* [NC,OR]
RewriteCond %{QUERY_STRING} ^.*(execute|exec|sp_executesql|request|select|insert|union|declare|drop|delete|create|alter|update|order|char|set|cast|convert|meta|script|truncate).* [NC]
RewriteRule ^(.*)$ - [F,L]\n
RewriteCond %{REMOTE_ADDR} !^$bps_get_IP_maint
RewriteCond %{REQUEST_URI} !^$bps_get_wp_root_maint"."bp-maintenance\.php$
RewriteCond %{REQUEST_URI} !^$bps_get_wp_root_maint"."wp-content/plugins/wordpress-security-plugin/abstract-blue-bg\.png$
RewriteRule ^(.*)$ $bps_get_wp_root_maint"."bp-maintenance.php [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . $bps_get_wp_root_maint"."index.php [L]";
	if (is_writable($bps_auto_write_maint_file)) {
	if (!$handle = fopen($bps_auto_write_maint_file, 'w+b')) {
         _e('<font color="red"><strong>Cannot open file' . "$bps_auto_write_maint_file" . '</strong></font>');
         exit;
    }
    if (fwrite($handle, $bps_maint_content) === FALSE) {
        _e('<font color="red"><strong>Cannot write to file' . "$bps_auto_write_maint_file" . '</strong></font>');
        exit;
    }
    _e('<font color="green"><strong>Success! Your Maintenance Mode htaccess file was created successfully! Select the Maintenance Mode radio button and click Activate to put your website in Maintenance Mode.</strong></font>');
    fclose($handle);
	} else {
    _e('<font color="red"><strong>The file ' . "$bps_auto_write_maint_file" . ' is not writable or does not exist.</strong></font><br><strong>Check that the file is named maintenance.htaccess and that the file exists in the /wordpress-security-plugin/admin/htaccess master folder. If this is not the problem click <a href="http://www.escalateseo.com/aitpro-blog/2566/wordpress-security-plugin-plugin-support/wordpress-security-plugin-error-messages" target="_blank">here</a> for more help info.</strong><br>');
	}
}

// Create Default htaccess file
if (isset($_POST['bps-auto-write-default']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_auto_write_default' );
		
$bps_get_wp_root_default = bps_wp_get_root_folder();
$bps_auto_write_default_file = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/default.htaccess';
$bps_default_content = "#   SECURITYPLUGIN .46.D >>>>>>> DEFAULT .HTACCESS     \n
# If you edit the line of code above you will see error messages on the WPSP status page
# WARNING!!! THE default.htaccess FILE DOES NOT PROTECT YOUR WEBSITE AGAINST HACKERS
# This is a standard generic htaccess file that does NOT provide any website security
# The DEFAULT .HTACCESS file should be used for testing and troubleshooting purposes only\n
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase $bps_get_wp_root_default
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . $bps_get_wp_root_default"."index.php [L]
</IfModule>\n
# END WordPress";
	if (is_writable($bps_auto_write_default_file)) {
	if (!$handle = fopen($bps_auto_write_default_file, 'w+b')) {
         _e('<font color="red"><strong>Cannot open file' . "$bps_auto_write_default_file" . '</strong></font>');
         exit;
    }
    if (fwrite($handle, $bps_default_content) === FALSE) {
        _e('<font color="red"><strong>Cannot write to file' . "$bps_auto_write_default_file" . '</strong></font>');
        exit;
    }
    _e('<font color="green"><strong>Success! Your Default Mode Master htaccess file was created successfully!</strong></font><br><font color="red"><strong>CAUTION: Default Mode should only be activated for testing or troubleshooting purposes. Default Mode does not protect your website with any security protection.</strong></font><br><font color="black"><strong>To activate Default Mode select the Default Mode radio button and click Activate to put your website in Default Mode.</strong></font>');
    fclose($handle);
	} else {
    _e('<font color="red"><strong>The file ' . "$bps_auto_write_default_file" . ' is not writable or does not exist.</strong></font><br><strong>Check that the file is named default.htaccess and that the file exists in the /wordpress-security-plugin/admin/htaccess master folder. If this is not the problem click <a href="http://www.escalateseo.com/aitpro-blog/2566/wordpress-security-plugin-plugin-support/wordpress-security-plugin-error-messages" target="_blank">here</a> for more help info.</strong><br>');
	}
}

// Create Secure htaccess Root file
if (isset($_POST['bps-auto-write-secure-root']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_auto_write_secure_root' );
	
$bps_get_wp_root_secure = bps_wp_get_root_folder();
$bps_auto_write_secure_file = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/secure.htaccess';
$bps_secure_content = "#   SECURITYPLUGIN 1.2.0 >>>>>>> SECURE .HTACCESS     \n
# If you edit the line of code above you will see error messages on the WPSP status page
# WPSP is reading the version number in the htaccess file to validate checks
# If you would like to change what is displayed above you
# will need to edit the WPSP functions.php file to match your changes
# For more info see the WPSP Guide at AIT-pro.com\n
# If you are getting 500 Errors when activating WPSP then comment out Options -Indexes
Options -Indexes\n
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase $bps_get_wp_root_secure
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . $bps_get_wp_root_secure"."index.php [L]
</IfModule>
# END WordPress\n
# If you want to add a custom 403 Forbidden page for your website uncomment the 
# ErrorDocument line of code below and copy the ait-pro.com example forbidden 
# HTML page to your correct website folder. See the WPSP Help and FAQ page for 
# detailed instructions on how to do this. If your Theme 404 template is named
# 404.php then you can uncomment the 404 line below now. If your 404 template is
# named some other file name then change 404.php to the name of your 404 template
# name and uncomment the 404 line of code below.
# ErrorDocument 403 /forbidden.html
# ErrorDocument 404 /404.php\n
# Plugin conflicts will be handled case by case
# You can leave the plugin fixes code intact just in case you install one of these plugins
# at a later time. Thousands of lines of htaccess code can be read in milliseconds
# so leaving the code intact does not slow down your website performance at all.
# Thousands of plugins have been tested with WPSP and the plugin conflict fixes 
# contained in this WPSP master file are permanent fixes for conflicts found with
# these plugins. If you use AutoMagic to create this file then your correct WordPress installation
# folder name will be automatically added to the plugin fixes that need a WP folder name.
# If you choose to manually edit this file instead of using AutoMagic be sure to add your 
# WordPress installation folder name to the fixes that require your WordPress folder name.
# Your WordPress installation folder name can be found on the System Info page. If you only see
# a forward slash then you have a root folder installation and do not need to add a folder name.\n
# redirect_to= string fix - fixes issues with plugins that use the redirect_to= string
RewriteCond %{QUERY_STRING} redirect_to=(.*) [NC]
RewriteRule . - [S=30]\n
# Login Plugins Password Reset And Redirect Conflicts Fix 1
RewriteCond %{QUERY_STRING} action=resetpass&key=(.*) [NC]
RewriteRule . - [S=30]\n
# Login Plugins Password Reset And Redirect Conflicts Fix 2
RewriteCond %{QUERY_STRING} action=rp&key=(.*) [NC]
RewriteRule . - [S=30]\n
# BuddyPress Logout Redirect fix - skip WPSP Filters on Logout link Redirect
# WordPress 3.0.4 or higher must be installed for this fix to work
RewriteCond %{QUERY_STRING} action=logout&redirect_to=http%3A%2F%2F(.*) [NC]
RewriteRule . - [S=30]\n
# Ozh' Admin Drop Down Menu Display Fix
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."wp-content/plugins/ozh-admin-drop-down-menu/ [NC] 
RewriteRule . - [S=30]\n
# ComicPress Manager ComicPress Theme Image Fix
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."wp-content/plugins/comicpress-manager/ [NC] 
RewriteRule . - [S=30]\n
# TimThumb and all other Thumbnailer Images not displaying - Red X instead of Images
# If your theme uses an image thumbnailer script file this fix will work to display images correctly
# as long as thumb is part of the file name like timthumb.php, thumb.php, thumbs.php or phpthumb.php
RewriteCond %{REQUEST_FILENAME} ^(.*)thumb(.*)$ [NC]
RewriteRule ^(.*)$ - [S=30]\n
# YAPB Image Display fix
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."wp-content/plugins/yet-another-photoblog/ [NC] 
RewriteRule . - [S=30]\n
# WordPress.com Stats Flash SWF Graph Does Not Load Fix
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."wp-content/plugins/stats/ [NC]
RewriteRule . - [S=30]\n
# Status Updater plugin fix
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."wp-content/plugins/fb-status-updater/ [NC] 
RewriteRule . - [S=30]\n
# wp-extplorer login fix
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."wp-content/plugins/wp-extplorer/ [NC] 
RewriteRule . - [S=30]\n
# Adminer MySQL management tool fix
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."wp-content/plugins/adminer/ [NC] 
RewriteRule . - [S=30]\n
# Peters Custom Anti-Spam Image fix
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."wp-content/plugins/peters-custom-anti-spam-image/ [NC] 
RewriteRule . - [S=30]\n
# Stream Video Player - Adding FLV Videos is Blocked By WPSP
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."wp-content/plugins/stream-video-player/ [NC]
RewriteRule . - [S=30]\n
# FeedWordPress - ?update_feedwordpress= String Blocked
RewriteCond %{QUERY_STRING} update_feedwordpress=(.*) [NC]
RewriteRule . - [S=30]\n
# XCloner 404 or 403 error when updating settings
RewriteCond %{REQUEST_URI} ^$bps_get_wp_root_secure"."wp-content/plugins/xcloner-backup-and-restore/ [NC]
RewriteRule . - [S=30]\n
# podPress rewrite ?feed=podcast as /feed/podcast
# If you are using a custom slug then add the slug name to the rewriterule
# RewriteRule (.*) /feed/custom-slug-name/$1? [R=301,L]
RewriteCond %{QUERY_STRING} feed=podcast [NC]
RewriteRule (.*) $bps_get_wp_root_secure"."feed/podcast/$1? [R=301,L]\n
# podPress rewrite ?feed=enhancedpodcast as /feed/enhancedpodcast
# If you are using a custom slug then add the slug name to the rewriterule
# RewriteRule (.*) /feed/custom-slug-name/$1? [R=301,L]
RewriteCond %{QUERY_STRING} feed=enhancedpodcast [NC]
RewriteRule (.*) $bps_get_wp_root_secure"."feed/enhancedpodcast/$1? [R=301,L]\n
# podPress rewrite ?feed=torrent as /feed/torrent
# If you are using a custom slug then add the slug name to the rewriterule
# RewriteRule (.*) /feed/custom-slug-name/$1? [R=301,L]
RewriteCond %{QUERY_STRING} feed=torrent [NC]
RewriteRule (.*) $bps_get_wp_root_secure"."feed/torrent/$1? [R=301,L]\n
# podPress rewrite ?feed=premium as /feed/premium
# If you are using a custom slug then add the slug name to the rewriterule
# RewriteRule (.*) /feed/custom-slug-name/$1? [R=301,L]
RewriteCond %{QUERY_STRING} feed=premimum [NC]
RewriteRule (.*) $bps_get_wp_root_secure"."feed/premium/$1? [R=301,L]\n
# FILTER REQUEST METHODS
RewriteCond %{REQUEST_METHOD} ^(HEAD|TRACE|DELETE|TRACK) [NC]
RewriteRule ^(.*)$ - [F,L]\n
# QUERY STRING EXPLOITS 
RewriteCond %{QUERY_STRING} \.\.\/ [NC,OR] 
RewriteCond %{QUERY_STRING} boot\.ini [NC,OR] 
RewriteCond %{QUERY_STRING} tag\= [NC,OR] 
RewriteCond %{QUERY_STRING} ftp\:  [NC,OR] 
RewriteCond %{QUERY_STRING} http\:  [NC,OR] 
RewriteCond %{QUERY_STRING} https\:  [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*iframe.*(\>|%3E) [NC,OR] 
RewriteCond %{QUERY_STRING} mosConfig_[a-zA-Z_]{1,21}(=|%3D) [NC,OR]
RewriteCond %{QUERY_STRING} base64_encode.*\(.*\) [NC,OR]
RewriteCond %{QUERY_STRING} GLOBALS(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} _REQUEST(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} ^(.*)cPath=http://(.*)$ [NC,OR] 
RewriteCond %{QUERY_STRING} ^(.*)/self/(.*)$ [NC,OR]
RewriteCond %{QUERY_STRING} ^.*(\[|\]|\(|\)|<|>).* [NC,OR] 
RewriteCond %{QUERY_STRING} ^.*(globals|encode|localhost|loopback).* [NC,OR] 
RewriteCond %{QUERY_STRING} ^.*(execute|exec|sp_executesql|request|select|insert|union|declare|drop|delete|create|alter|update|order|char|set|cast|convert|meta|script|truncate).* [NC] 
RewriteRule ^(.*)$ - [F,L]\n
# Deny Access to wp-config.php, bb-config.php, /wp-admin/install.php, all .htaccess files
# php.ini, php5.ini and the WordPress readme.html installation file.
# To allow ONLY yourself access to these files add your current IP address below to the 
# Allow from line of code and remove the # sign in front of Allow from to uncomment it
<FilesMatch ".'"'."^(wp-config\.php|install\.php|\.htaccess|php\.ini|php5\.ini|readme\.html|bb-config\.php)".'"'.">
 Deny from all
# Allow from 88.77.66.55
</FilesMatch>";
	if (is_writable($bps_auto_write_secure_file)) {
	if (!$handle = fopen($bps_auto_write_secure_file, 'w+b')) {
         _e('<font color="red"><strong>Cannot open file' . "$bps_auto_write_secure_file" . '</strong></font>');
         exit;
    }
    if (fwrite($handle, $bps_secure_content) === FALSE) {
        _e('<font color="red"><strong>Cannot write to file' . "$bps_auto_write_secure_file" . '</strong></font>');
        exit;
    }
    _e('<font color="green"><strong>Success! Your WordPress Security Plugin Root Master htaccess file was created successfully!</strong></font><br><font color="black"><strong>You can now Activate BulletProof Mode for your Root folder. Select the BulletProof Mode radio button and click Activate to put your website in BulletProof Mode.</strong></font>');
    fclose($handle);
	} else {
    _e('<font color="red"><strong>The file ' . "$bps_auto_write_secure_file" . ' is not writable or does not exist.</strong></font><br><strong>Check that the file is named secure.htaccess and that the file exists in the /wordpress-security-plugin/admin/htaccess master folder. If this is not the problem click <a href="http://www.escalateseo.com/aitpro-blog/2566/wordpress-security-plugin-plugin-support/wordpress-security-plugin-error-messages" target="_blank">here</a> for more help info.</strong><br>');
	}
}

// Create the Maintenance Mode Settings Values Form File - values from DB
if (isset($_POST['bps-maintenance-create-values_submit']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_create_values_form' );
	
	$options = get_option('securityplugin_security_options_maint');
	$bps_retry_after_write = $options['bps-retry-after'];
	$bps_site_title_write = $options['bps-site-title'];
	$bps_message1_write = $options['bps-message-1'];
	$bps_message2_write = $options['bps-message-2'];
	$bps_start_date_maintenance_write = $options['bps-start-date'];
	$bps_start_time_maintenance_write = $options['bps-start-time'];
	$bps_end_date_maintenance_write = $options['bps-end-date'];
	$bps_end_time_maintenance_write = $options['bps-end-time'];
	$bps_countdown_completed_popup_write = $options['bps-popup-message'];
	$bps_body_background_image_write = $options['bps-background-image'];
	$bps_auto_write_maint_file_form = ABSPATH . 'wp-content/plugins/wordpress-security-plugin/admin/htaccess/bps-maintenance-values.php';
$bps_maint_content_form = "<?php".'
$bps_retry_after'." = '$bps_retry_after_write';\n"
.'$bps_site_title'." = '$bps_site_title_write';\n"
.'$bps_message1'." = '$bps_message1_write';\n"
.'$bps_message2'." = '$bps_message2_write';\n"
.'$bps_start_maintenance_date'." = '$bps_start_date_maintenance_write';\n"
.'$bps_start_maintenance_time'." = '$bps_start_time_maintenance_write';\n"
.'$bps_end_maintenance_date'." = '$bps_end_date_maintenance_write';\n"
.'$bps_end_maintenance_time'." = '$bps_end_time_maintenance_write';\n"
.'$bps_countdown_completed_popup'." = '$bps_countdown_completed_popup_write';\n"
.'$bps_body_background_image'." = '$bps_body_background_image_write';
?>";
	if (is_writable($bps_auto_write_maint_file_form)) {
	if (!$handle = fopen($bps_auto_write_maint_file_form, 'w+b')) {
         _e('<font color="red"><strong>Cannot open file' . "$bps_auto_write_maint_file_form" . '</strong></font>');
         exit;
    }
    if (fwrite($handle, $bps_maint_content_form) === FALSE) {
        _e('<font color="red"><strong>Cannot write to file' . "$bps_auto_write_maint_file_form" . '</strong></font>');
        exit;
    }
    _e('<font color="green"><strong>Success! Your Maintenance Mode Form has been created successfully! Click the Preview button to preview your Website Under Maintenance page.</strong></font>');
    fclose($handle);
	} else {
    _e('<font color="red"><strong>The file ' . "$bps_auto_write_maint_file_form" . ' is not writable or does not exist.</strong></font><br><strong>Check that the bps-maintenance-values.php file exists in the /wordpress-security-plugin/admin/htaccess master folder. If this is not the problem click <a href="http://www.escalateseo.com/aitpro-blog/2566/wordpress-security-plugin-plugin-support/wordpress-security-plugin-error-messages" target="_blank">here</a> for more help info.</strong><br>');
	}
}

// Simple Secure Old School PHP file upload
if (isset($_POST['submit-bps-upload']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_upload' );
	
	$tmp_file = $_FILES['bps_file_upload']['tmp_name'];
	$folder_path = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/';
	$bps_uploaded_file =  str_replace('//','/',$folder_path) . $_FILES['bps_file_upload']['name'];
	if (!empty($_FILES)) {
	move_uploaded_file($tmp_file,$bps_uploaded_file);
		_e('<font color="black"><strong>File Upload Path and File Name: </strong></font><br>');
		echo "$bps_uploaded_file";
	} else {
		_e('<font color="red"><strong>File upload error. File was not successfully uploaded.</strong></font><br>');
	}
}

// Enable File Downloading for Master Files - writes a new denyall htaccess file with the current IP address
if (isset($_POST['bps-enable-download']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_enable_download' );
	
	$bps_get_IP = $_SERVER['REMOTE_ADDR'];
	$denyall_htaccess_file = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/.htaccess';
	$bps_denyall_content = "order deny,allow\ndeny from all\nallow from $bps_get_IP";
	
	if (is_writable($denyall_htaccess_file)) {
	if (!$handle = fopen($denyall_htaccess_file, 'w+b')) {
         _e('<font color="red"><strong>Cannot open file' . "$denyall_htaccess_file" . '</strong></font>');
         exit;
    }
    if (fwrite($handle, $bps_denyall_content) === FALSE) {
        _e('<font color="red"><strong>Cannot write to file' . "$denyall_htaccess_file" . '</strong></font>');
        exit;
    }
    _e('<font color="green"><strong>Success! File open, preview and downloading for your WPSP Master Files is enabled for your IP address only ===' . "$bps_get_IP." .'</strong></font>');
    fclose($handle);
	} else {
    _e('<font color="red"><strong>The file ' . "$denyall_htaccess_file" . ' is not writable or does not exist yet.</strong></font><br><strong>Check the WPSP Status page to see if Deny All protection has been activated. Activate Deny All htaccess Folder Protection For The WPSP Master htaccess Folder on the WPSP Security Modes page. If this is not the problem click <a href="http://www.escalateseo.com/aitpro-blog/2566/wordpress-security-plugin-plugin-support/wordpress-security-plugin-error-messages" target="_blank">here</a> for more help info.</strong><br>');
	}
}

// Enable File Downloading for WPSP Backup Folder - writes a new denyall htaccess file with the current IP address
if (isset($_POST['bps-enable-download-backup']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_enable_download-backup' );
	
	$bps_get_IP2 = $_SERVER['REMOTE_ADDR'];
	$denyall_htaccess_file_backup = ABSPATH . '/wp-content/bps-backup/.htaccess';
	$bps_denyall_content_backup = "order deny,allow\ndeny from all\nallow from $bps_get_IP2";
	
	if (is_writable($denyall_htaccess_file_backup)) {
	if (!$handle = fopen($denyall_htaccess_file_backup, 'w+b')) {
         _e('<font color="red"><strong>Cannot open file' . "$denyall_htaccess_file_backup" . '</strong></font>');
         exit;
    }
    if (fwrite($handle, $bps_denyall_content_backup) === FALSE) {
        _e('<font color="red"><strong>Cannot write to file' . "$denyall_htaccess_file_backup" . '</strong></font>');
        exit;
    }
    _e('<font color="green"><strong>Success! File open, preview and downloading for your Backed Up htaccess Files is enabled for your IP address only ===' . "$bps_get_IP2." .'</strong></font>');
    fclose($handle);
	} else {
    _e('<font color="red"><strong>The file ' . "$denyall_htaccess_file_backup" . ' is not writable or does not exist yet.</strong></font><br><strong>Check the WPSP Status page to see if Deny All protection has been activated. Activate Deny All htaccess Folder Protection For The WPSP Backup Folder on the WPSP Security Modes page. If this is not the problem click <a href="http://www.escalateseo.com/aitpro-blog/2566/wordpress-security-plugin-plugin-support/wordpress-security-plugin-error-messages" target="_blank">here</a> for more help info.</strong><br>');
	}
}

?>

</div>

<div class="wrap">
<?php $securityplugin_ver = '1.2.0'; ?>
<h2 style="margin-left:70px;"><?php _e('WordPress Security Plugin ~ htaccess Core'); ?></h2>

<!-- jQuery UI Tab Menu -->
<div id="bps-container">
	<div id="bps-tabs" class="bps-menu">
		<ul>
			<li><a href="#bps-tabs-1">Security Modes</a></li>
            <li><a href="#bps-tabs-2">Security Status</a></li>
			<li><a href="#bps-tabs-3">System Info</a></li>
			<li><a href="#bps-tabs-4">Backup &amp; Restore</a></li>
            <li><a href="#bps-tabs-5">Edit/Upload/Download</a></li>
			<li><a href="#bps-tabs-6">Maintenance Mode</a></li>
		</ul>
            
<div id="bps-tabs-1" class="bps-tab-page">
<h2><?php _e('WordPress Security Plugin Modes'); ?></h2>

<h3><?php _e('AutoMagic - Create Your htaccess Files Automatically'); ?> <a href="http://www.escalateseo.com/aitpro-blog/1166/wordpress-security-plugin-plugin-support/wordpress-security-plugin-plugin-guide-bps-version-45/" target="_blank"  onmouseover="Tip('<strong>Backup your existing htaccess files if you have any first by clicking on the Backup & Restore menu - click on the Backup htaccess files radio button to select it and click on the Backup Files button to back up your existing htaccess files.</strong><br><br><strong>AutoMagic - WPSP will create Your Master .htaccess Files For You Automatically</strong><br><br> -- Click the <strong>Create default.htaccess File</strong> button and click the <strong>Create secure.htaccess File</strong> button<br> -- Activate BulletProof Mode for your Root folder<br> -- Activate BulletProof Mode for your wp-admin folder<br> -- Activate BulletProof Mode for the WPSP Master htaccess folder and the WPSP Backup folder<br><br><strong>Explanation Of The Steps Above:</strong><br>If you see error messages when performing a first time backup do not worry about it. WPSP will backup whatever files should be or are available to backup for your website.<br>Clicking the <strong>Create default.htaccess File</strong> button and the <strong>Create secure.htaccess File</strong> button will create these two master htaccess files for you. The correct RewriteBase and RewriteRule for your website will be automatically added to these files. The default.htaccess file is used when you activate Default Mode. Default Mode should only be activated for testing and troubleshooting purposes - it does not provide any website security. The secure.htaccess file is used when you activate BulletProof Mode for your Root folder. The plugin conflict fixes will also have your correct WordPress folder name added to the secure.htaccess file. The htaccess file for your wp-admin folder does not require any editing nor do the Deny All htaccess files. This means that once you have created the default.htaccess file and the secure.htaccess file you can go ahead and activate all BulletProof Modes.<br><br><strong>Manual Control of htaccess Files Instead of Using AutoMagic</strong><br>If you want manual control and want to edit your htacess files using the built-in WPSP File Editor instead of having them automatically created for you then there is no need to click on the AutoMagic create files buttons. Personally I prefer full manual control but most people will probably just want to use AutoMagic.<br><br><strong>AutoMagic Instruction for WordPress Network (Multisite) Sites</strong><br>Because of the variations with htaccess coding for WordPress Network / MU sites and issues with Network SubDomains and SubFolder differences in WordPress Network / MU sites an AutoMagic feature will not be added to WPSP. You can use the AutoMagic buttons to create your WPSP Master htaccess files, but you will need to replace the WordPress single site htaccess code with the specific WordPress Network / MU htaccess code that you need for your particular site set up and purposes. This is a very simple and easy thing to do. The htaccess code you will be replacing with your Network / MU htaccess code will ONLY be the section of htaccess code that starts with <strong># BEGIN WordPress and ends with # END WordPress.</strong> When you create the WPSP Master htaccess files using the AutoMagic button you can edit them using the built-in WPSP File Editor. You will be able to copy and paste from your existing htaccess files to the WPSP Master htaccess files in the File Editor and save your changes. Once you have replaced the WordPress single site section of htaccess coding with you Network / MU section of htaccess code you can then activate BulletProof Modes.<br><br>For more information click this Read Me button link to view the <strong>WordPress Security Plugin Guide.</strong>', WIDTH, 600, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()">Read Me</a></h3>

<form name="bps-auto-write-default" action="admin.php?page=wordpress-security-plugin/admin/options.php" method="post">
<?php wp_nonce_field('securityplugin_security_auto_write_default'); ?>
<input type="hidden" name="filename" value="bps-auto-write-default_write" /><label for="bps-security-modes"><?php echo bps_multsite_check_smode(); ?></label>
<p class="submit">
<input type="submit" name="bps-auto-write-default" class="button-primary" value="<?php esc_attr_e('Create default.htaccess File') ?>" /></p>
</form>

<form name="bps-auto-write-secure-root" action="admin.php?page=wordpress-security-plugin/admin/options.php" method="post">
<?php wp_nonce_field('securityplugin_security_auto_write_secure_root'); ?>
<input type="hidden" name="filename" value="bps-auto-write-secure_write" />
<p class="submit">
<input type="submit" name="bps-auto-write-secure-root" class="button-primary" value="<?php esc_attr_e('Create secure.htaccess File') ?>" /></p>
</form>

<h3><?php _e('Activate Website Root Folder .htaccess Security Mode'); ?> <a href="http://www.escalateseo.com/aitpro-blog/1166/wordpress-security-plugin-plugin-support/wordpress-security-plugin-plugin-guide-bps-version-45/" target="_blank"  onmouseover="Tip('<strong>Installing the WordPress Security Plugin plugin does not activate any security modes.<br>If you activate BulletProof Mode for your Root folder you must also activate BulletProof Mode for your wp-admin folder.</strong><br>Perform a backup first before activating any WordPress Security Plugin modes (backs up both currently active root and wp-admin htaccess files at the same time). I also recommend using the WPSP File Downloader to backup your backed up files to your computer. Activating Default Mode or BulletProof Mode copies, renames and moves the master .htaccess files default.htaccess or secure.htaccess, depending on what radio button option you choose, from /plugins/wordpress-security-plugin/admin/htaccess/ to your root folder. Default Mode does not have any security protection - it is just a standard generic WordPress .htaccess file that you should only use for testing purposes.<br><br><strong>If you are installing WPSP for the first Help and FAQ links are available on the WPSP Help and FAQ page</strong><br><br><strong>Info for people who are upgrading WPSP</strong><br>When you upgrade WPSP your current root and wp-admin htaccess files are not affected. WPSP master htaccess files are replaced when you upgrade WPSP so if you have made changes to your WPSP master files that you want to keep make sure they are backed up first before upgrading. You can also download copies of the WPSP master files to your computer using the WPSP File Downloader if you want. When you backup your WPSP files it is an online backup so the files will be available to you to restore from if you run into any problems at any point. You should always be using the newest WPSP master htaccess files for the latest security protection updates and plugin conflict fixes. Before activating new WPSP master files you can use the WPSP File Editor to copy and paste any existing htaccess code that you want to keep from your current active htaccess files to the new WPSP master htaccess files and save your changes before activating the new WPSP htaccess files. Or you can copy any new htaccess code from the new WPSP master files to your existing currently active htaccess files. If you do this be sure to edit the WPSP  version number in your currently active htaccess files or you will get error messages.<br><br>For more information click this Read Me button link to view the <strong>WordPress Security Plugin Guide.</strong><br><br><strong>Testing or Removing / Uninstalling WPSP</strong><br>If you are testing WPSP to determine if there is a plugin conflict or other conflict then Activate Default Mode and select the Delete wp-admin htaccess File radio button and click the Activate button. This puts your site in a standard WordPress state with a default or generic Root .htaccess file and no .htaccess file in your wp-admin folder. After testing or troubleshooting is completed reactivate BulletProof Modes for both the Root and wp-admin folders. If you are removing / uninstalling WPSP then follow the same steps and then select Deactivate from the Wordpress Plugins page and then click Delete to uninstall the WPSP plugin.', WIDTH, 600, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()">Read Me</a></h3>

<form name="BulletProof-Root" action="admin.php?page=wordpress-security-plugin/admin/options.php" method="post">
<?php wp_nonce_field('securityplugin_security_root_copy'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection12" type="radio" value="bpsecureroot" class="tog" <?php checked('', $bpsecureroot); ?> /> <?php _e('BulletProof Mode'); ?></label></th>
	<td class="url-path"><?php echo get_site_url(); ?>/.htaccess<br /><?php _e('<font color="green">Copies the file secure.htaccess to your root folder and renames the file name to just .htaccess</font>'); ?></td>
   </tr>
  <tr>   
   <th><label><input name="selection12" type="radio" value="bpdefaultroot" class="tog" <?php checked('', $bpdefaultroot); ?> /><?php _e('<font color="red">Default Mode<br>WP Default htaccess File</font>'); ?></label></th>
	<td class="url-path"><?php echo get_site_url(); ?>/.htaccess<br /><?php _e('<font color="red"> CAUTION: </font>Your site will not be protected if you activate Default Mode. ONLY activate Default Mode for Testing and Troubleshooting.'); ?></td>
   </tr>
</table>
<p class="submit">
<input type="submit" name="submit12" class="button-primary" value="<?php esc_attr_e('Activate') ?>" />
</p></form>

<h3><?php _e('Activate Website wp-admin Folder .htaccess Security Mode'); ?> <a href="http://www.escalateseo.com/aitpro-blog/1166/wordpress-security-plugin-plugin-support/wordpress-security-plugin-plugin-guide-bps-version-45/" target="_blank"  onmouseover="Tip('<strong>Installing the WordPress Security Plugin plugin does not activate any security modes.<br>If you activate BulletProof Mode for your wp-admin folder you must also activate BulletProof Mode for your Root folder.</strong><br>Activating BulletProof Mode copies, renames and moves the master .htaccess file wpadmin-secure.htaccess from /plugins/wordpress-security-plugin/admin/htaccess/ to your /wp-admin folder. If you customize or modify the master .htaccess files then be sure to make an online backup and also download backups of these master .htaccess files to your computer using the WPSP File Downloader.<br><br>For more information click this Read Me button link to view the <strong>WordPress Security Plugin Guide.</strong><br><br><strong>Testing or Removing / Uninstalling WPSP</strong><br>If you are testing WPSP to determine if there is a plugin conflict or other conflict then Activate Default Mode and select the Delete wp-admin htaccess File radio button and click the Activate button. This puts your site in a standard WordPress state with a default or generic Root .htaccess file and no .htaccess file in your wp-admin folder. After testing or troubleshooting is completed reactivate BulletProof Modes for both the Root and wp-admin folders. If you are removing / uninstalling WPSP then follow the same steps and then select Deactivate from the Wordpress Plugins page and then click Delete to uninstall the WPSP plugin.', WIDTH, 400, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()">Read Me</a></h3>

<form name="BulletProof-WPadmin" action="admin.php?page=wordpress-security-plugin/admin/options.php" method="post">
<?php wp_nonce_field('securityplugin_security_wpadmin_copy'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection13" type="radio" value="bpsecurewpadmin" class="tog" <?php checked('', $bpsecurewpadmin); ?> /> <?php _e('BulletProof Mode'); ?></label></th>
	<td class="url-path"><?php echo get_site_url(); ?>/wp-admin/.htaccess<br /><?php _e('<font color="green"> Copies the file wpadmin-secure.htaccess to your /wp-admin folder and renames the file name to just .htaccess</font>'); ?></td>
   </tr>
   <tr>
	<th><label><input name="selection13" type="radio" value="Removebpsecurewpadmin" class="tog" <?php checked('', $Removebpsecurewpadmin); ?> /> <?php _e('<font color="red">Delete wp-admin<br>htaccess File</font>'); ?></label></th>
	<td class="url-path"><?php echo get_site_url(); ?>/wp-admin/.htaccess<br /><?php _e('<font color="red"> CAUTION: </font>Deletes the .htaccess file in your /wp-admin folder. ONLY delete For testing or WPSP removal.'); ?></td>
   </tr>
</table>
<p class="submit"><input type="submit" name="submit13" class="button-primary" value="<?php esc_attr_e('Activate') ?>" />
</p></form>

<h3><?php _e('Activate Deny All htaccess Folder Protection For The WPSP Master htaccess Folder'); ?> <a href="http://www.escalateseo.com/aitpro-blog/1166/wordpress-security-plugin-plugin-support/wordpress-security-plugin-plugin-guide-bps-version-45/" target="_blank"  onmouseover="Tip('Activating BulletProof Mode for Deny All htaccess Folder Protection copies and renames the deny-all.htaccess file located in the /plugins/wordpress-security-plugin/admin/htaccess/ folder and renames it to just .htaccess. The Deny All htaccess file blocks everyone, except for you, from accessing and viewing the WPSP Master htaccess files.<br><br>For more information click this Read Me button link to view the <strong>WordPress Security Plugin Guide.</strong>', WIDTH, 400, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()">Read Me</a></h3>

<form name="BulletProof-deny-all-htaccess" action="admin.php?page=wordpress-security-plugin/admin/options.php" method="post">
<?php wp_nonce_field('securityplugin_security_denyall_master'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection8" type="radio" value="bps_rename_htaccess_files" class="tog" <?php checked('', $bps_rename_htaccess_files); ?> /> <?php _e('BulletProof Mode'); ?></label></th>
	<td class="url-path"><?php echo get_site_url(); ?>/wp-content/plugins/wordpress-security-plugin/admin/htaccess/<br /><?php _e('<font color="green"> Copies the file deny-all.htaccess to the WPSP Master htaccess folder and renames the file name to just .htaccess</font>'); ?></td>
   </tr>
</table>
<p class="submit"><input type="submit" name="submit8" class="button-primary" value="<?php esc_attr_e('Activate') ?>" />
</p></form>

<h3><?php _e('Activate Deny All htaccess Folder Protection For The WPSP Backup Folder'); ?> <a href="http://www.escalateseo.com/aitpro-blog/1166/wordpress-security-plugin-plugin-support/wordpress-security-plugin-plugin-guide-bps-version-45/" target="_blank"  onmouseover="Tip('Activating BulletProof Mode for Deny All WPSP Backup Folder Protection copies and renames the deny-all.htaccess file located in the /wordpress-security-plugin/admin/htaccess/ folder to the WPSP Backup folder /wp-content/bps-backup and renames it to just .htaccess. The Deny All htaccess file blocks everyone, except for you, from accessing and viewing your backed up htaccess files.<br><br>For more information click this Read Me button link to view the <strong>WordPress Security Plugin Guide.</strong>', WIDTH, 400, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()">Read Me</a></h3>

<form name="BulletProof-deny-all-backup" action="admin.php?page=wordpress-security-plugin/admin/options.php" method="post">
<?php wp_nonce_field('securityplugin_security_denyall_bpsbackup'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection14" type="radio" value="bps_rename_htaccess_files_backup" class="tog" <?php checked('', $bps_rename_htaccess_files_backup); ?> /> <?php _e('BulletProof Mode'); ?></label></th>
	<td class="url-path"><?php echo get_site_url(); ?>/wp-content/bps-backup/<br /><?php _e('<font color="green"> Copies and the file deny-all.htaccess to the WPSP Backup folder and renames the file name to just .htaccess</font>'); ?></td>
   </tr>
</table>
<p class="submit"><input type="submit" name="submit14" class="button-primary" value="<?php esc_attr_e('Activate') ?>" />
</p></form>
</div>
            
<div id="bps-tabs-2" class="bps-tab-page">
<h2><?php _e('WordPress Security Plugin Status'); ?></h2>

<table width="100%" border="2" cellspacing="0" cellpadding="0" class="bps-status_table">
  <tr>
    <td width="49%" class="bps-table_title"><?php _e('Activated WordPress Security Plugin .htaccess Files'); ?> <a href="http://www.escalateseo.com/aitpro-blog/1166/wordpress-security-plugin-plugin-support/wordpress-security-plugin-plugin-guide-bps-version-45/#bps-advanced-coding-modfications" target="_blank"  onmouseover="Tip('<strong>Installing the WordPress Security Plugin plugin does not activate any security modes.<br>If you activate BulletProof Mode for your Root folder you must also activate BulletProof Mode for your wp-admin folder.</strong><br>Perform a backup first before activating any WordPress Security Plugin modes (backs up both currently active root and wp-admin htaccess files at the same time).<br><br><strong>If you are installing WPSP for the first Help and FAQ links are available on the WPSP Help and FAQ page</strong><br><br><strong>Info for people who are upgrading WPSP</strong><br>When you upgrade WPSP your current root and wp-admin htaccess files are not affected. WPSP master htaccess files are replaced when you upgrade WPSP so if you have made changes to your WPSP master files that you want to keep make sure they are backed up first before upgrading. You can also download copies of the WPSP master files to your computer using the WPSP File Downloader if you want. When you backup your WPSP files it is an online backup so the files will be available to you to restore from if you run into any problems at any point. You should always be using the newest WPSP master htaccess files for the latest security protection updates and plugin conflict fixes. Before activating new WPSP master files you can use the WPSP File Editor to copy and paste any existing htaccess code that you want to keep from your current active htaccess files to the new WPSP master htaccess files and save your changes before activating the new WPSP htaccess files. Or you can copy any new htaccess code from the new WPSP master files to your existing currently active htaccess files. If you do this be sure to edit the WPSP  version number in your currently active htaccess files or you will get error messages.<br><br>For more information click this Read Me button link to view the <strong>WordPress Security Plugin Guide.</strong><br><br>The Text Strings you see listed in the Activated WordPress Security Plugin Status window if you have an active BulletProof .htaccess file (or an existing .htaccess file) is reading and displaying the actual contents of any existing .htaccess files here. <strong>This is not just a displayed message - this is the actual first 45 string characters (text) of the contents of your .htaccess files.</strong><br><br>To change or modify the Text String that you see displayed here you would use the WPSP built in Text Editor to change the actual text content of the WordPress Security Plugin master .htaccess files. If the change the SECURITYPLUGIN SECURITY title shown here then you must also change the coding contained in the /wp-content/plugins/wordpress-security-plugin/includes/functions.php file to match your changes or you will get some error messages. The rest of the text content in the .htaccess files can be modified just like a normal post. Just this top line ot text in the .htaccess files contains version information that WPSP checks to do verifications and other file checking. For detailed instructions on modifying what text is displayed here click this Read Me button link.', WIDTH, 600, PADDING, 8, ABOVE, true, FADEIN, 500, FADEOUT, 300)" onmouseout="UnTip()">Read Me</a></td>
    <td width="2%">&nbsp;</td>
    <td width="49%" class="bps-table_title"><?php _e('Additional Website Security Measures'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell">
<?php 
	echo root_htaccess_status();
	echo denyall_htaccess_status_master();
	echo denyall_htaccess_status_backup();
	echo wpadmin_htaccess_status();
?>
    <td>&nbsp;</td>
    <td class="bps-table_cell">
<?php 
	echo bps_wpdb_errors_off();
	echo bps_wp_remove_version();
	echo check_admin_username();
	echo bps_filesmatch_check_readmehtml();
	echo bps_filesmatch_check_installphp();
	//echo check_bps_pro_mod();
?>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
</table>

<table width="100%" border="1" cellspacing="0" cellpadding="0" class="bps-perms_table">
  <tr>
    <td colspan="4" class="bps-table_title"><?php _e('File and Folder Permissions'); ?> <a href="http://www.escalateseo.com/aitpro-blog/1166/wordpress-security-plugin-plugin-support/wordpress-security-plugin-plugin-guide-bps-version-45/" target="_blank" onmouseover="Tip('Your current file and folder permissions are shown below with suggested file and folder permission settings that you should use for the best website security and functionality.<br><br>I recommend using FileZilla to change your file and folder permissions. FileZilla is a free FTP software that makes changing your file and folder permissions very simple and easy as well as many other very nice FTP features. With FileZilla you can right mouse click on your files or folders and set the permissions with a Numeric value like 755, 644, etc. Takes the confusion out of which attributes to check or uncheck.<br><br>For more information click this Read Me button link to view the <strong>WordPress Security Plugin Guide.</strong>', WIDTH, 400, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()">Read Me</a></td>
    <td width="2%">&nbsp;</td>
    <td width="49%" class="bps-table_title"><?php _e('General WordPress Security Plugin File Checks'); ?> <a href="http://www.escalateseo.com/aitpro-blog/1166/wordpress-security-plugin-plugin-support/wordpress-security-plugin-plugin-guide-bps-version-45/" target="_blank" onmouseover="Tip('This is a quick visual check to verify that you have active .htaccess files in your root and /wp-admin folders and that all the required WPSP files are in your WordPress Security Plugin plugin folder. The WordPress Security Plugin .htaccess master files (default.htaccess, secure.htaccess, wpadmin-secure.htaccess, maintenance.htaccess and bp-maintenance.php) are located in this folder /wp-content/plugins/wordpress-security-plugin/admin/htaccess/<br><br>For new installations and upgrades of WordPress Security Plugin you will see red warning messages. This is completely normal. These warnings are there to remind you to perform backups if they have not been performed yet. Also you may see warning messages if files do not exist yet.<br><br>You can also download backups of any existing .htaccess files using the WPSP File Downloader.<br><br>For more information click this Read Me button link to view the <strong>WordPress Security Plugin Guide.</strong>', WIDTH, 400, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()">Read Me</a></td>
  </tr>
  <tr>
    <td width="16%" class="bps-table_cell_perms_head_left"><?php _e('File Name -<br>Folder Name'); ?></td>
    <td width="13%" class="bps-table_cell_perms_head_middle"><?php _e('File Path -<br>Folder Path'); ?></td>
    <td width="10%" class="bps-table_cell_perms_head_middle"><?php _e('Suggested<br>Permissions'); ?></td>
    <td width="10%" class="bps-table_cell_perms_head_right"><?php _e('Current<br>Permissions'); ?></td>
    <td>&nbsp;</td>
    <td rowspan="4" class="bps-table_cell_file_checks">
<?php echo general_bps_file_checks(); ?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-file_checks_bottom_table" style="margin-top:50px;">
      <tr>
        <td class="bps-file_checks_bottom_bps-table_cell">&nbsp;</td>
      </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td height="100%" colspan="4" class="bps-table_cell_perms_blank">
	<?php  
	bps_check_perms("root folder","../","755");
	bps_check_perms("wp-includes/","../wp-includes","755");
	bps_check_perms(".htaccess","../.htaccess","644");
	bps_check_perms("wp-admin/.htaccess","../wp-admin/.htaccess","644");
	bps_check_perms("index.php","../index.php","644");
	bps_check_perms("wp-admin/index.php","../wp-admin/index.php","644");
	bps_check_perms("wp-admin/js/","../wp-admin/js/","755");
	bps_check_perms("wp-content/themes/","../wp-content/themes","755");
	bps_check_perms("wp-content/plugins/","../wp-content/plugins","755");
	bps_check_perms("wp-admin/","../wp-admin","755");
	bps_check_perms("wp-content/","../wp-content","755");
?>
</td>
    <td>&nbsp;</td>
    </tr>
 <tr>
    <td colspan="4">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-file_checks_bottom_table_special">
      <tr>
        <td class="bps-file_checks_bottom_bps-table_cell">&nbsp;</td>
      </tr>
    </table>
    </td>
    <td>&nbsp;</td>
    </tr>
</table>
</div>
            
<div id="bps-tabs-3">
<h2><?php _e('System Information'); ?></h2>

<table width="100%" border="2" cellspacing="0" cellpadding="0" class="bps-system_info_table">
  <tr>
    <td width="49%" class="bps-table_title"><?php _e('Website / Server / IP Info'); ?></td>
    <td width="2%">&nbsp;</td>
    <td width="49%" class="bps-table_title"><?php _e('SQL Database / Permalink Structure / WP Installation Folder'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('Website Root Folder'); ?>: <strong><?php echo get_site_url(); ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell"><?php _e('MySQL Database Version'); ?>: <?php $sqlversion = $wpdb->get_var("SELECT VERSION() AS version"); ?><strong><?php echo $sqlversion; ?></strong></td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('Document Root Path'); ?>: <strong><?php echo $_SERVER['DOCUMENT_ROOT']; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell"><?php _e('MySQL Client Version'); ?>: <strong><?php echo mysql_get_client_info(); ?></strong></td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('WP ABSPATH'); ?>: <strong><?php echo ABSPATH; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell"><?php _e('Database Host'); ?>: <strong><?php echo DB_HOST; ?></strong></td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('Server / Website IP Address'); ?>: <strong><?php echo $_SERVER['SERVER_ADDR']; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell"><?php _e('Database Name'); ?>: <strong><?php echo DB_NAME; ?></strong></td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('Public IP / Your Computer IP Address'); ?>: <strong><?php echo $_SERVER['REMOTE_ADDR']; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell"><?php _e('Database User'); ?>: <strong><?php echo DB_USER; ?></strong></td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('Server Type'); ?>: <strong><?php echo $_SERVER['SERVER_SOFTWARE']; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell"><?php _e('SQL Mode'); ?>: <?php $mysqlinfo = $wpdb->get_results("SHOW VARIABLES LIKE 'sql_mode'");
	if (is_array($mysqlinfo)) $sql_mode = $mysqlinfo[0]->Value;
    if (empty($sql_mode)) $sql_mode = __('Not Set');
	else $sql_mode = __('Off'); ?><strong><?php echo $sql_mode; ?></strong></td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('Operating System'); ?>: <strong><?php echo PHP_OS; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell"><?php _e('WordPress Installation Folder'); ?>: <strong><?php echo bps_wp_get_root_folder(); ?></strong></td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php echo bps_multsite_check(); ?></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell"><?php _e('WordPress Installation Type'); ?>: <strong><?php echo bps_wp_get_root_folder_display_type(); ?></strong></td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('Browser Compression Supported'); ?>: <strong><?php echo $_SERVER['HTTP_ACCEPT_ENCODING']; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell"><?php _e('WP Permalink Structure'); ?>: <strong><?php echo $permalink_structure; ?></strong></td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php echo bps_check_php_version (); ?></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell"><?php echo bps_check_permalinks(); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_title"><?php _e('PHP Server / PHP.ini Info'); ?></td>
    <td>&nbsp;</td>
    <td class="bps-table_title"><?php _e('WPSP Pro Security Modules Info'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP Version'); ?>: <strong><?php echo PHP_VERSION; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell"><?php _e('Not Installed - See WPSP Pro Features page'); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP Memory Usage'); ?>: <strong><?php echo round(memory_get_usage() / 1024 / 1024, 2) . __(' MB'); ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP Memory Limit'); ?>: <?php if(ini_get('memory_limit')) $memory_limit = ini_get('memory_limit');
        else $memory_limit = __('N/A'); ?><strong><?php echo $memory_limit; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP Max Upload Size'); ?>: <?php if(ini_get('upload_max_filesize')) $upload_max = ini_get('upload_max_filesize');
        else $upload_max = __('N/A'); ?><strong><?php echo $upload_max; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP Max Post Size'); ?>: <?php if(ini_get('post_max_size')) $post_max = ini_get('post_max_size');
        else $post_max = __('N/A'); ?><strong><?php echo $post_max; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP Safe Mode'); ?>: <?php if(ini_get('safe_mode')) $safe_mode = __('On');
        else $safe_mode = __('Off'); ?><strong><?php echo $safe_mode; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP Allow URL fopen'); ?>: <?php if(ini_get('allow_url_fopen')) $allow_url_fopen = __('On');
        else $allow_url_fopen = __('Off'); ?><strong><?php echo $allow_url_fopen; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
   <tr>
    <td class="bps-table_cell"><?php _e('PHP Allow URL Include'); ?>: <?php if(ini_get('allow_url_include')) $allow_url_include = __('On');
        else $allow_url_include = __('Off'); ?><strong><?php echo $allow_url_include; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP Display Errors'); ?>: <?php if(ini_get('display_errors')) $display_errors = __('On');
        else $display_errors = __('Off'); ?><strong><?php echo $display_errors; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP Display Startup Errors'); ?>: <?php if(ini_get('display_startup_errors')) $display_startup_errors = __('On');
        else $display_startup_errors = __('Off'); ?><strong><?php echo $display_startup_errors; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
   <tr>
    <td class="bps-table_cell"><?php _e('PHP Expose PHP'); ?>: <?php if(ini_get('expose_php')) $expose_php = __('On');
        else $expose_php = __('Off'); ?><strong><?php echo $expose_php; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP Register Globals'); ?>: <?php if(ini_get('register_globals')) $register_globals = __('On');
        else $register_globals = __('Off'); ?><strong><?php echo $register_globals; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP Max Script Execution Time'); ?>: <?php if(ini_get('max_execution_time')) $max_execute = ini_get('max_execution_time');
        else $max_execute = __('N/A'); ?><strong><?php echo $max_execute; ?> Seconds</strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP Magic Quotes GPC'); ?>: <?php if(ini_get('magic_quotes_gpc')) $magic_quotes_gpc = __('On');
        else $magic_quotes_gpc = __('Off'); ?><strong><?php echo $magic_quotes_gpc; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP open_basedir'); ?>: <?php if(ini_get('open_basedir')) $open_basedir = __('On');
        else $open_basedir = __('Off'); ?><strong><?php echo $open_basedir; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP XML Support'); ?>: <?php if (is_callable('xml_parser_create')) $xml = __('Yes');
        else $xml = __('No'); ?><strong><?php echo $xml; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP IPTC Support'); ?>: <?php if (is_callable('iptcparse')) $iptc = __('Yes');
        else $iptc = __('No'); ?><strong><?php echo $iptc; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php _e('PHP Exif Support'); ?>: <?php if (is_callable('exif_read_data')) $exif = __('Yes'). " ( V" . substr(phpversion('exif'),0,4) . ")" ;
        else $exif = __('No'); ?><strong><?php echo $exif; ?></strong></td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
    <td>&nbsp;</td>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
</div>
            
<div id="bps-tabs-4" class="bps-tab-page">
<h2><?php _e('WordPress Security Plugin Backup &amp; Restore'); ?></h2>

<h3><?php _e('Backup Your Currently Active .htaccess Files'); ?></h3>
<h3><?php _e('<font color="red"><strong>CAUTION: </strong></font>'); ?><a href="http://www.escalateseo.com/aitpro-blog/1166/wordpress-security-plugin-plugin-support/wordpress-security-plugin-plugin-guide-bps-version-45/#bps-backup-restore" target="_blank" onmouseover="Tip('Back up your existing .htaccess files first before activating any WordPress Security Plugin Modes in case of a problem when you first install and activate any WordPress Security Plugin Modes. Once you have backed up your original existing .htaccess files you will see the status listed in the <strong>Current Backed Up .htaccess Files Status</strong> window below. <br><br><strong>Backup files are stored in this folder /wp-content/bps-backup.</strong><br><br>In cases where you install a plugin that writes to your htaccess files you will want to perform another backup of your htaccess files. Each time you perform a backup you are overwriting older backed up htaccess files. Backed up files are stored in the /wp-content/bps-folder.<br><br>You could also use the WPSP File Downloader to download any existing .htaccess files, customized .htaccess files or other WPSP files that you have personally customized or modified just for an additional local backup.<br><br><strong>The WPSP Master .htaccess files are stored in your /plugins/wordpress-security-plugin/admin/htaccess folder and can also be backed up to the /wp-content/bps-backup/master-backups folder.</strong><br>Backed up files are stored online so they will be available to you after upgrading to a newer version of WPSP if you run into a problem. There is no Restore feature for the WPSP Master files because you should be using the latest versions of the WPSP master .htaccess files after you upgrade WPSP. You can manually download the files from this folder /wp-content/bps-backup/master-backups using FTP or your web host file downloader.<br><br>When you upgrade WPSP your current root and wp-admin htaccess files are not affected. WPSP master htaccess files are replaced when you upgrade WPSP so if you have made changes to your WPSP master files that you want to keep make sure they are backed up first before upgrading. You can also download copies of the WPSP master files to your computer using the WPSP File Downloader if you want. When you backup your WPSP files it is an online backup so the files will be available to you to restore from if you run into any problems at any point. You should always be using the newest WPSP master htaccess files for the latest security protection updates and plugin conflict fixes. Before activating new WPSP master files you can use the WPSP File Editor to copy and paste any existing htaccess code that you want to keep from your current active htaccess files to the new WPSP master htaccess files and save your changes before activating the new WPSP htaccess files. Or you can copy any new htaccess code from the new WPSP master files to your existing currently active htaccess files. If you do this be sure to edit the WPSP  version number in your currently active htaccess files or you will get error messages.<br><br><strong>If something goes wrong in the .htaccess file editing process or at any point you can restore your good .htaccess files with one click as long as you already backed them up.</strong><br><br>For more information click this Read Me button link to view the <strong>WordPress Security Plugin Guide.</strong>', WIDTH, 550, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()"><strong>Read Me</strong></a></h3>

<form name="BulletProof-Backup" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-4" method="post">
<?php wp_nonce_field('securityplugin_security_backup_active_htaccess_files'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection9" type="radio" value="backup_htaccess" class="tog" <?php echo checked('', $backup_htaccess); ?> />
<?php _e('Backup .htaccess Files'); ?></label></th>
	<td><?php _e('<font color="green"><strong>Backs up your currently active .htaccess files in your root and /wp-admin folders.</strong></font><br><strong>Backup your htaccess files for first time installations of WPSP or whenever new modifications have been made to your htaccess files. Read the <font color="red"><strong>CAUTION: </strong></font>Read Me ToolTip.</strong>'); ?></td>
	<td>
    </td>
   </tr>
</table>
<p class="submit">
<input type="submit" name="submit9" class="button-primary" value="<?php esc_attr_e('Backup Files') ?>" />
</p></form>

<h3><?php _e('Restore Your .htaccess Files From Backup'); ?><a href="http://www.escalateseo.com/aitpro-blog/1166/wordpress-security-plugin-plugin-support/wordpress-security-plugin-plugin-guide-bps-version-45/#bps-backup-restore" target="_blank" onmouseover="Tip('Restores your backed up .htaccess files that you backed up. Your backed up .htaccess files were renamed to root.htaccess and wpadmin.htaccess and copied to the /wp-content/bps-backup folder. Restoring your backed up .htaccess files will rename them back to .htaccess and copy them back to your root and /wp-admin folders respectively.<br><br><strong>If you did not have any original .htaccess files to begin with and / or you did not back up any files then you will not have any backed up .htaccess files.</strong><br><br>For more information click this Read Me button link to view the <strong>WordPress Security Plugin Guide.</strong>', WIDTH, 550, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()"><strong>Read Me</strong></a></h3>

<form name="BulletProof-Restore" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-4" method="post">
<?php wp_nonce_field('securityplugin_security_restore_active_htaccess_files'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection10" type="radio" value="restore_htaccess" class="tog" <?php checked('', $restore_htaccess); ?> />
<?php _e('Restore .htaccess Files'); ?></label></th>
	<td><?php _e('<font color="green"><strong>Restores your backed up .htaccess files to your root and /wp-admin folders.</strong></font><br><strong>Restore your backed up .htaccess files if you have any problems or for use between WPSP ugrades.</strong>'); ?></td>
	<td>
    </td>
   </tr>
</table>
<p class="submit">
<input type="submit" name="submit10" class="button-primary" value="<?php esc_attr_e('Restore Files') ?>" />
</p></form>

<h3><?php _e('Backup Your WPSP Master .htaccess Files'); ?><a href="http://www.escalateseo.com/aitpro-blog/1166/wordpress-security-plugin-plugin-support/wordpress-security-plugin-plugin-guide-bps-version-45/#bps-backup-restore" target="_blank" onmouseover="Tip('The WPSP Master .htaccess files are stored in your /plugins/wordpress-security-plugin/admin/htaccess folder and can also be backed up using this Master Backup feature. The backed up WPSP Master .htaccess files are copied to this folder /wp-content/bps-backup/master-backups folder. This way they will be available to you online after upgrading to a newer version of WPSP. There is no Restore feature for the WPSP Master files because you should be using the latest versions of the WPSP master .htaccess files after you upgrade WPSP. You can manually download the files from this folder /wp-content/bps-backup/master-backups using FTP or your web host file downloader.<br><br><strong>For more information click this Read Me button link to view the <strong>WordPress Security Plugin Guide.</strong>', WIDTH, 400, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()"><strong>Read Me</strong></a></h3>

<form name="WPSP-Master-Htaccess-Backup" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-4" method="post">
<?php wp_nonce_field('securityplugin_security_backup_master_htaccess_files'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection11" type="radio" value="backup_master_htaccess_files" class="tog" <?php checked('', $backup_master_htaccess_files); ?> />
<?php _e('Backup WPSP Master .htaccess Files'); ?></label></th>
	<td><?php _e('<font color="green"><strong>Backs up your WPSP Master .htaccess files to the /wp-content/bps-backup/master-backups folder.</strong></font><br><strong>There is no Restore feature for the WPSP Master .htaccess files because you should be using the latest most current WPSP Master .htaccess security coding and plugin fixes included in the most current version of the WPSP master .htacess files.</strong>'); ?></td>
	<td>
    </td>
   </tr>
</table>
<p class="submit">
<input type="submit" name="submit11" class="button-primary" value="<?php esc_attr_e('Backup Master Files') ?>" />
</p></form>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bps-backup_restore_table">
  <tr>
    <td class="bps-table_title"><?php _e('Current Backed Up .htaccess Files Status'); ?> <a href="http://www.escalateseo.com/aitpro-blog/1166/wordpress-security-plugin-plugin-support/wordpress-security-plugin-plugin-guide-bps-version-45/#bps-backup-restore" target="_blank" onmouseover="Tip('Help info has been moved to the <strong>WordPress Security Plugin Guide.</strong><br><br>If you see error messages here and / or would like to see more specific info on WordPress Security Plugin then click this Read Me button link to view the <strong>WordPress Security Plugin Guide.</strong>', WIDTH, 400, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()">Read Me</a></td>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><strong><?php general_bps_file_checks_backup_restore(); ?></strong></td>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php echo backup_restore_checks(); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell"><?php echo bps_master_file_backups(); ?></td>
  </tr>
  <tr>
    <td class="bps-table_cell">&nbsp;</td>
  </tr>
  <tr>
    <td class="bps-table_cell_bottom">&nbsp;</td>
  </tr>
</table>
<br /><br /> 
</div>
        
<div id="bps-tabs-5" class="bps-tab-page">
<table width="100%" border="0">
  <tr>
    <td width="33%"><h2><?php _e('WordPress Security Plugin File Editing'); ?></h2></td>
    <td width="21%"><h3><a href="http://www.escalateseo.com/aitpro-blog/2185/wordpress-security-plugin-plugin-support/wordpress-security-plugin-file-editing-editing-files-within-the-wordpress-dashboard/" target="_blank" onmouseover="Tip('<strong>You must be using WordPress Permalinks in order for WPSP to function correctly.</strong><br><br><strong>Automatically Generating The RewriteBase and RewriteRule Code</strong><br>WordPress will automatically write the correct RewriteBase and RewriteRule to <strong>Your Current Root htaccess File</strong> for you. For more information - click the Help & FAQ tab menu - click the <strong>WPSP Guide</strong> link - scroll to Step 2 - read <strong>The fast, simple and automated method of generating the correct WordPress .htaccess code for your website</strong> in the WPSP Guide. For more information on WordPress Permalinks - click the <strong>WP Permalinks - Custom Permalink Structure Help Info</strong> link.<br><br><strong>Manually Editing The htaccess Files</strong><br>If you are not sure whether you have a WordPress Root folder or Subfolder installation click on the System Info tab menu and check the WordPress Installation Folder and WordPress Installation Type. If you have a Root Folder Installation you do not need to edit any of the htaccess files and you can activate all BulletProof Modes now. If you have a Subfolder Installation you will need to add your subfolder name to the RewriteBase and RewriteRule manually or you can use the automated method above. Also for WordPress subfolder installations you will need to add your WordPress subfolder name to any plugin fixes that require a subfolder name - ONLY for the plugins that you are actually using. For more information on manually modifying WPSP master files - click the Help & FAQ tab menu - click the <strong>Modifying WPSP .htaccess Files for WordPress Subfolders</strong> or the <strong>WPSP Guide</strong> link.<br><br>Click this Read Me button link to view the WPSP File Editing Help Page. The help and info page will load a new browser window and will not leave your WordPress Dashboard. The WPSP File Editing Help page contains info on the File Editors full capabilities, limitations, best usages and error solutions.', WIDTH, 600, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()">Read Me</a></h3></td>
    <td width="19%" align="right">
    <h3 style="margin-right:0px;"><a href="http://www.escalateseo.com/aitpro-blog/2190/wordpress-security-plugin-plugin-support/wordpress-security-plugin-file-uploading-and-file-downloading-uploading-and-downloading-files-within-the-wordpress-dashboard/" target="_blank" onmouseover="Tip('<strong>File Uploading</strong><br>The file upload location is preset to the /wp-content/plugins/wordpress-security-plugin/admin/htaccess folder and the intended use is just for uploading the WPSP Master files: secure.htaccess, default.htaccess, wpadmin-secure.htaccess, maintenance.htaccess and bp-maintenance.php from your computer to the WPSP Master htaccess folder.<br><br><strong>File Downloading</strong><br><strong>Folder permissions must be set to a minimum of 705 for the /htaccess and /bps-backup folders in order to open and download files.</strong><br>Click the Enable Master File Downloading button to enable file downloading. This will write your current IP address to the deny all htaccess file and allow ONLY you access to the /plugins/wordpress-security-plugin/admin/htaccess folder to open and download files. To open and download your Backed up files click the Enable Backed Up File Downloading button. After clicking the Enable File Downloading buttons you can click the download buttons below to open or download files. If your IP address changes which it will do frequently then click the Enable File Downloading buttons again to write a new IP address to the deny all htaccess files.<br><br><strong>Current Active htaccess File Downloading<br>You will be blocked from downloading your Current Active htaccess Files until you add your IP address to Your Current Root htaccess File.</strong><br>Click on the Your Current Root htaccess File tab menu in the WPSP File Editor and scroll to the very bottom of that htaccess file. You will see Allow from 88.55.66.200. You will need to uncomment the Allow from line of code by removing the pound sign in front of Allow from and add your current IP address in order to allow ONLY yourself access to your current active htaccess files. Your current IP address can be found under the System Info tab menu. Your Computer IP Address displayed there is the IP address you will add to your currently active Root htaccess file.<br><br>For more detailed instructions click this Read Me button link. The File Downloading help and info page will load a new browser window and will not leave your WordPress Dashboard.', WIDTH, 600, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()">Read Me</a></h3>
    </td>
    <td width="27%" align="center"><h2><?php _e('Uploads - Downloads'); ?></h2></td>
  </tr>
</table>


<table width="100%" border="0">
  <tr>
    <td width="72%"><div id="bps_file_editor" class="bps_file_editor_update">
<?php
echo secure_htaccess_file_check();
echo default_htaccess_file_check();
echo maintenance_htaccess_file_check();
echo wpadmin_htaccess_file_check();
    
// Perform File Open and Write test first by appending a literal blank space
// or nothing at all to end of the htaccess files.
// If append write test is successful file is writable on submit
if (current_user_can('manage_options')) {
$secure_htaccess_file = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/secure.htaccess';
$write_test = "";
	if (is_writable($secure_htaccess_file)) {
    if (!$handle = fopen($secure_htaccess_file, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$secure_htaccess_file" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
	_e('<font color="red"><strong>Cannot write to file' . "$secure_htaccess_file" . '</strong></font><br>');
    exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! The secure.htaccess file is writable.</strong></font><br>');
	}
	}
	
	if (isset($_POST['submit1']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_save_settings_1' );
	$newcontent1 = stripslashes($_POST['newcontent1']);
	if ( is_writable($secure_htaccess_file) ) {
		$handle = fopen($secure_htaccess_file, 'w+b');
		fwrite($handle, $newcontent1);
	_e('<font color="green"><strong>Success! The secure.htaccess file has been updated.</strong></font><br>');	
    fclose($handle);
	}
}

if (current_user_can('manage_options')) {
$default_htaccess_file = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/default.htaccess';
$write_test = "";
	if (is_writable($default_htaccess_file)) {
    if (!$handle = fopen($default_htaccess_file, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$default_htaccess_file" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
	_e('<font color="red"><strong>Cannot write to file' . "$default_htaccess_file" . '</strong></font><br>');
    exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! The default.htaccess file is writable.</strong></font><br>');
	}
	}
	
	if (isset($_POST['submit2']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_save_settings_2' );
	$newcontent2 = stripslashes($_POST['newcontent2']);
	if ( is_writable($default_htaccess_file) ) {
		$handle = fopen($default_htaccess_file, 'w+b');
		fwrite($handle, $newcontent2);
	_e('<font color="green"><strong>Success! The default.htaccess file has been updated.</strong></font><br>');	
    fclose($handle);
	}
}

if (current_user_can('manage_options')) {
$maintenance_htaccess_file = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/maintenance.htaccess';
$write_test = "";
	if (is_writable($maintenance_htaccess_file)) {
    if (!$handle = fopen($maintenance_htaccess_file, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$maintenance_htaccess_file" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
	_e('<font color="red"><strong>Cannot write to file' . "$maintenance_htaccess_file" . '</strong></font><br>');
    exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! The maintenance.htaccess file is writable.</strong></font><br>');
	}
	}
	
	if (isset($_POST['submit3']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_save_settings_3' );
	$newcontent3 = stripslashes($_POST['newcontent3']);
	if ( is_writable($maintenance_htaccess_file) ) {
		$handle = fopen($maintenance_htaccess_file, 'w+b');
		fwrite($handle, $newcontent3);
	_e('<font color="green"><strong>Success! The maintenance.htaccess file has been updated.</strong></font><br>');	
    fclose($handle);
	}
}

if (current_user_can('manage_options')) {
$wpadmin_htaccess_file = ABSPATH . '/wp-content/plugins/wordpress-security-plugin/admin/htaccess/wpadmin-secure.htaccess';
$write_test = "";
	if (is_writable($wpadmin_htaccess_file)) {
    if (!$handle = fopen($wpadmin_htaccess_file, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$wpadmin_htaccess_file" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
	_e('<font color="red"><strong>Cannot write to file' . "$wpadmin_htaccess_file" . '</strong></font><br>');
    exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! The wpadmin-secure.htaccess file is writable.</strong></font><br>');
	}
	}
	
	if (isset($_POST['submit4']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_save_settings_4' );
	$newcontent4 = stripslashes($_POST['newcontent4']);
	if ( is_writable($wpadmin_htaccess_file) ) {
		$handle = fopen($wpadmin_htaccess_file, 'w+b');
		fwrite($handle, $newcontent4);
	_e('<font color="green"><strong>Success! The wpadmin-secure.htaccess file has been updated.</strong></font><br>');	
    fclose($handle);
	}
}

if (current_user_can('manage_options')) {
$root_htaccess_file = ABSPATH . '/.htaccess';
$write_test = "";
	if (is_writable($root_htaccess_file)) {
    if (!$handle = fopen($root_htaccess_file, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$root_htaccess_file" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
	_e('<font color="red"><strong>Cannot write to file' . "$root_htaccess_file" . '</strong></font><br>');
    exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! Your currently active root .htaccess file is writable.</strong></font><br>');
	}
	}
	
	if (isset($_POST['submit5']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_save_settings_5' );
	$newcontent5 = stripslashes($_POST['newcontent5']);
	if ( is_writable($root_htaccess_file) ) {
		$handle = fopen($root_htaccess_file, 'w+b');
		fwrite($handle, $newcontent5);
	_e('<font color="green"><strong>Success! Your currently active root .htaccess file has been updated.</strong></font><br>');	
    fclose($handle);
	}
}

if (current_user_can('manage_options')) {
$current_wpadmin_htaccess_file = ABSPATH . '/wp-admin/.htaccess';
$write_test = "";
	if (is_writable($current_wpadmin_htaccess_file)) {
    if (!$handle = fopen($current_wpadmin_htaccess_file, 'a+b')) {
	_e('<font color="red"><strong>Cannot open file' . "$current_wpadmin_htaccess_file" . '</strong></font><br>');
    exit;
    }
    if (fwrite($handle, $write_test) === FALSE) {
	_e('<font color="red"><strong>Cannot write to file' . "$current_wpadmin_htaccess_file" . '</strong></font><br>');
    exit;
    }
	_e('<font color="green"><strong>File Open and Write test successful! Your currently active wp-admin .htaccess file is writable.</strong></font><br>');
	}
	}
	
	if (isset($_POST['submit6']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_save_settings_6' );
	$newcontent6 = stripslashes($_POST['newcontent6']);
	if ( is_writable($current_wpadmin_htaccess_file) ) {
		$handle = fopen($current_wpadmin_htaccess_file, 'w+b');
		fwrite($handle, $newcontent6);
	_e('<font color="green"><strong>Success! Your currently active wp-admin .htaccess file has been updated.</strong></font><br>');	
    fclose($handle);
	}
}
?>
</div>
</td>
    <td width="28%" align="center" valign="top">
	<?php _e("<div class=\"bps-file_upload_title\"><strong>File Uploads<br></strong></div>"); ?>
<form name="WPSP-upload" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-5" method="post" enctype="multipart/form-data"><?php wp_nonce_field('securityplugin_security_upload'); ?>
<p class="submit">
<input id="bps_file_upload" name="bps_file_upload" type="file" />
</p>
<p class="submit" style="margin:-5px 0px 0px -12px;">
<input type="submit" name="submit-bps-upload" class="button-primary" value="<?php esc_attr_e('Upload File') ?>" />
</p>
</form>
</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    
    <!-- jQuery UI File Editor Tab Menu -->
<div id="bps-edittabs" class="bps-edittabs-class">
		<ul>
			<li><a href="#bps-edittabs-1">secure.htaccess</a></li>
			<li><a href="#bps-edittabs-2">default.htaccess</a></li>
			<li><a href="#bps-edittabs-3">maintenance.htaccess</a></li>
			<li><a href="#bps-edittabs-4">wpadmin-secure.htaccess</a></li>
            <li><a href="#bps-edittabs-5">Your Current Root htaccess File</a></li>
            <li><a href="#bps-edittabs-6">Your Current wp-admin htaccess File</a></li>
        </ul>
       
<div id="bps-edittabs-1" class="bps-edittabs-page-class">
<form name="template1" id="template1" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-5" method="post">
<?php wp_nonce_field('securityplugin_security_save_settings_1'); ?>
    <div>
    <textarea cols="115" rows="27" name="newcontent1" id="newcontent1" tabindex="1"><?php echo get_secure_htaccess(); ?></textarea>
	<input type="hidden" name="action" value="update" />
    <input type="hidden" name="filename" value="<?php echo esc_attr($secure_htaccess_file) ?>" />
	<input type="hidden" name="scrollto1" id="scrollto1" value="<?php echo $scrollto; ?>" />
    <p class="submit">
	<input type="submit" name="submit1" class="button-primary" value="<?php esc_attr_e('Update File') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#template1').submit(function(){ $('#scrollto1').val( $('#newcontent1').scrollTop() ); });
	$('#newcontent1').scrollTop( $('#scrollto1').val() ); 
});
/* ]]> */
</script>     
</div>

<div id="bps-edittabs-2" class="bps-edittabs-page-class">
<form name="template2" id="template2" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-5" method="post">
<?php wp_nonce_field('securityplugin_security_save_settings_2'); ?>
	<div>
    <textarea cols="115" rows="27" name="newcontent2" id="newcontent2" tabindex="2"><?php echo get_default_htaccess(); ?></textarea>
	<input type="hidden" name="action" value="update" />
    <input type="hidden" name="filename" value="<?php echo esc_attr($default_htaccess_file) ?>" />
	<input type="hidden" name="scrollto2" id="scrollto2" value="<?php echo $scrollto; ?>" />
    <p class="submit">
	<input type="submit" name="submit2" class="button-primary" value="<?php esc_attr_e('Update File') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#template2').submit(function(){ $('#scrollto2').val( $('#newcontent2').scrollTop() ); });
	$('#newcontent2').scrollTop( $('#scrollto2').val() );
});
/* ]]> */
</script>     
</div>

<div id="bps-edittabs-3" class="bps-edittabs-page-class">
<form name="template3" id="template3" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-5" method="post">
<?php wp_nonce_field('securityplugin_security_save_settings_3'); ?>
	<div>
    <textarea cols="115" rows="27" name="newcontent3" id="newcontent3" tabindex="3"><?php echo get_maintenance_htaccess(); ?></textarea>
	<input type="hidden" name="action" value="update" />
    <input type="hidden" name="filename" value="<?php echo esc_attr($maintenance_htaccess_file) ?>" />
	<input type="hidden" name="scrollto3" id="scrollto3" value="<?php echo $scrollto; ?>" />
    <p class="submit">
	<input type="submit" name="submit3" class="button-primary" value="<?php esc_attr_e('Update File') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#template3').submit(function(){ $('#scrollto3').val( $('#newcontent3').scrollTop() ); });
	$('#newcontent3').scrollTop( $('#scrollto3').val() );
});
/* ]]> */
</script>     
</div>

<div id="bps-edittabs-4" class="bps-edittabs-page-class">
<form name="template4" id="template4" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-5" method="post">
<?php wp_nonce_field('securityplugin_security_save_settings_4'); ?>
	<div>
    <textarea cols="115" rows="27" name="newcontent4" id="newcontent4" tabindex="4"><?php echo get_wpadmin_htaccess(); ?></textarea>
	<input type="hidden" name="action" value="update" />
    <input type="hidden" name="filename" value="<?php echo esc_attr($wpadmin_htaccess_file) ?>" />
	<input type="hidden" name="scrollto4" id="scrollto4" value="<?php echo $scrollto; ?>" />
    <p class="submit">
	<input type="submit" name="submit4" class="button-primary" value="<?php esc_attr_e('Update File') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#template4').submit(function(){ $('#scrollto4').val( $('#newcontent4').scrollTop() ); });
	$('#newcontent4').scrollTop( $('#scrollto4').val() );
});
/* ]]> */
</script>     
</div>

<div id="bps-edittabs-5" class="bps-edittabs-page-class">
<form name="template5" id="template5" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-5" method="post">
<?php wp_nonce_field('securityplugin_security_save_settings_5'); ?>
	<div>
    <textarea cols="115" rows="27" name="newcontent5" id="newcontent5" tabindex="5"><?php echo get_root_htaccess(); ?></textarea>
	<input type="hidden" name="action" value="update" />
    <input type="hidden" name="filename" value="<?php echo esc_attr($root_htaccess_file) ?>" />
	<input type="hidden" name="scrollto5" id="scrollto5" value="<?php echo $scrollto; ?>" />
    <p class="submit">
	<input type="submit" name="submit5" class="button-primary" value="<?php esc_attr_e('Update File') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#template5').submit(function(){ $('#scrollto5').val( $('#newcontent5').scrollTop() ); });
	$('#newcontent5').scrollTop( $('#scrollto5').val() );
});
/* ]]> */
</script>     
</div>

<div id="bps-edittabs-6" class="bps-edittabs-page-class">
<form name="template6" id="template6" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-5" method="post">
<?php wp_nonce_field('securityplugin_security_save_settings_6'); ?>
	<div>
    <textarea cols="115" rows="27" name="newcontent6" id="newcontent6" tabindex="6"><?php echo get_current_wpadmin_htaccess_file(); ?></textarea>
	<input type="hidden" name="action" value="update" />
    <input type="hidden" name="filename" value="<?php echo esc_attr($current_wpadmin_htaccess_file) ?>" />
	<input type="hidden" name="scrollto6" id="scrollto6" value="<?php echo $scrollto; ?>" />
    <p class="submit">
	<input type="submit" name="submit6" class="button-primary" value="<?php esc_attr_e('Update File') ?>" /></p>
</div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function($){
	$('#template6').submit(function(){ $('#scrollto6').val( $('#newcontent6').scrollTop() ); });
	$('#newcontent6').scrollTop( $('#scrollto6').val() );
});
/* ]]> */
</script>     
</div>
</div>
</td>
    <td align="center" valign="top">
<?php _e("<div class=\"bps-file_download_title\"><strong>File Downloads</strong></div>"); ?>

<form name="bps-enable-download" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-5" method="post">
<?php wp_nonce_field('securityplugin_security_enable_download'); ?>
<input type="hidden" name="filename" value="bps-enable-download-edit" />
<p class="submit">
<input type="submit" name="bps-enable-download" class="button-primary" value="<?php esc_attr_e('Enable Master File Downloading') ?>" /></p>
</form>

<div id="bps-enable_bu_file_dl_button">
<form name="bps-enable-download-backup" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-5" method="post">
<?php wp_nonce_field('securityplugin_security_enable_download-backup'); ?>
<input type="hidden" name="filename" value="bps-enable-download-edit-backup" />
<p class="submit">
<input type="submit" name="bps-enable-download-backup" class="button-primary" value="<?php esc_attr_e('Enable Backed Up File Downloading') ?>" /></p>
</form>
</div>
<div id="bps-download_buttons_table">
<?php _e("<p class=\"bps-download_titles\">WPSP Master Files</p>");
	
if (isset($_POST['bps-master-secure-download']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_download_secure' );	
	header("Content-Description: File Transfer");
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=secure.htaccess");
	}
if (isset($_POST['bps-master-default-download']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_download_default' );	
	header("Content-Description: File Transfer");
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=default.htaccess");
	}	
if (isset($_POST['bps-master-maintenance-download']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_download_maintenance' );	
	header("Content-Description: File Transfer");
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=maintenance.htaccess");
	}
// pending - loads file instead of forcing download 
// if (isset($_POST['bps-master-bp-maintenance-download']) && current_user_can('manage_options')) {
//	check_admin_referer( 'securityplugin_security_download_bp-maintenance' );	
//	header("Content-Description: File Transfer");
//	header("Content-type: application/force-download");
//	header("Content-Disposition: attachment; filename=bp-maintenance.php");
//	}	
if (isset($_POST['bps-master-wpadmin-secure-download']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_download_wpadmin-secure' );	
	header("Content-Description: File Transfer");
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=wpadmin-secure.htaccess");
	}
if (isset($_POST['bps-master-current-root-htaccess-download']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_download_current-root-htaccess' );	
	header("Content-Description: File Transfer");
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=root.htaccess");
	}
if (isset($_POST['bps-master-current-wp-admin-htaccess-download']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_download_current-wp-admin-htaccess' );	
	header("Content-Description: File Transfer");
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=wpadmin.htaccess");
	}	
if (isset($_POST['bps-master-root-backup-htaccess-download']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_download_root-backup-htaccess' );	
	header("Content-Description: File Transfer");
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=root.htaccess_backup");
	}	
if (isset($_POST['bps-master-wpadmin-backup-htaccess-download']) && current_user_can('manage_options')) {
	check_admin_referer( 'securityplugin_security_download_wpadmin-backup-htaccess' );	
	header("Content-Description: File Transfer");
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=wpadmin.htaccess_backup");
	}	
?> 

<form name="bps-master-secure-download" action="<?php echo get_site_url() .'/wp-content/plugins/wordpress-security-plugin/admin/htaccess/secure.htaccess'; ?>" method="post">
<?php wp_nonce_field('securityplugin_security_download_secure'); ?>
<input type="hidden" name="filename" value="bps-secure-download" />
<input type="submit" name="bps-master-secure-download" class="button-primary" value="<?php esc_attr_e('secure.htaccess') ?>" /></p>
</form>

<form name="bps-master-default-download" action="<?php echo get_site_url() .'/wp-content/plugins/wordpress-security-plugin/admin/htaccess/default.htaccess'; ?>" method="post">
<?php wp_nonce_field('securityplugin_security_download_default'); ?>
<input type="hidden" name="filename" value="bps-default-download" />
<input type="submit" name="bps-master-default-download" class="button-primary" value="<?php esc_attr_e('default.htaccess') ?>" /></p>
</form>

<form name="bps-master-maintenance-download" action="<?php echo get_site_url() .'/wp-content/plugins/wordpress-security-plugin/admin/htaccess/maintenance.htaccess'; ?>" method="post">
<?php wp_nonce_field('securityplugin_security_download_maintenance'); ?>
<input type="hidden" name="filename" value="bps-maintenance-download" />
<input type="submit" name="bps-master-maintenance-download" class="button-primary" value="<?php esc_attr_e('maintenance.htaccess') ?>" /></p>
</form>

<!-- pending may or may not go this route -->
<!-- <form name="bps-master-bp-maintenance-download" action="<?php echo get_site_url() .'/wp-content/plugins/wordpress-security-plugin/admin/htaccess/bp-maintenance.php'; ?>" method="post">
<?php wp_nonce_field('securityplugin_security_download_bp-maintenance'); ?>
<input type="hidden" name="filename" value="bps-bp-maintenance-download" />
<input type="submit" name="bps-master-bp-maintenance-download" class="button-primary" value="<?php esc_attr_e('bp-maintenance.php') ?>" /></p>
</form> -->

<form name="bps-master-wpadmin-secure-download" action="<?php echo get_site_url() .'/wp-content/plugins/wordpress-security-plugin/admin/htaccess/wpadmin-secure.htaccess'; ?>" method="post">
<?php wp_nonce_field('securityplugin_security_download_wpadmin-secure'); ?>
<input type="hidden" name="filename" value="bps-wpadmin-secure-download" />
<input type="submit" name="bps-master-wpadmin-secure-download" class="button-primary" value="<?php esc_attr_e('wpadmin-secure.htaccess') ?>" /></p>
</form>
	
	<?php _e("<p class=\"bps-download_titles\">Current Active htaccess Files</p>"); ?>
    <?php _e("<div id=\"bps-download_titles_small\">*View the Read Me ToolTip button first*</div>"); ?>
    
<form name="bps-master-current-root-htaccess-download" action="<?php echo get_site_url() .'/.htaccess'; ?>" method="post">
<?php wp_nonce_field('securityplugin_security_download_current-root-htaccess'); ?>
<input type="hidden" name="filename" value="bps-current-root-htaccess-download" />
<input type="submit" name="bps-master-current-root-htaccess-download" class="button-primary" value="<?php esc_attr_e('Current Root htaccess File') ?>" /></p>
</form>

<form name="bps-master-current-wp-admin-htaccess-download" action="<?php echo get_site_url() .'/wp-admin/.htaccess'; ?>" method="post">
<?php wp_nonce_field('securityplugin_security_download_current-wp-admin-htaccess'); ?>
<input type="hidden" name="filename" value="bps-current-wp-admin-htaccess-download" />
<input type="submit" name="bps-master-current-wp-admin-htaccess-download" class="button-primary" value="<?php esc_attr_e('Current wp-admin htaccess File') ?>" /></p>
</form>

	<?php _e("<p class=\"bps-download_titles\">Backed Up htaccess Files</p>"); ?>
    
<form name="bps-master-root-backup-htaccess-download" action="<?php echo get_site_url() . '/wp-content/bps-backup/root.htaccess'; ?>" method="post">
<?php wp_nonce_field('securityplugin_security_download_root-backup-htaccess'); ?>
<input type="hidden" name="filename" value="bps-root-backup-htaccess-download" />
<input type="submit" name="bps-master-root-backup-htaccess-download" class="button-primary" value="<?php esc_attr_e('root.htaccess Backup File') ?>" /></p>
</form>

<form name="bps-master-wpadmin-backup-htaccess-download" action="<?php echo get_site_url() . '/wp-content/bps-backup/wpadmin.htaccess'; ?>" method="post">
<?php wp_nonce_field('securityplugin_security_download_wpadmin-backup-htaccess'); ?>
<input type="hidden" name="filename" value="bps-wpadmin-backup-htaccess-download" />
<input type="submit" name="bps-master-wpadmin-backup-htaccess-download" class="button-primary" value="<?php esc_attr_e('wpadmin.htaccess Backup File') ?>" /></p>
</form>
</div>
    </td>
  </tr>
</table>
</div>

<div id="bps-tabs-6" class="bps-tab-page">
<h2><?php _e('WordPress Security Plugin Maintenance Mode'); ?></h2>

<div id="bps-maintenance_form_table">
<h3><?php _e('Website Maintenance Mode Settings'); ?></h3>
<h3><?php _e('<font color="red"><strong>CAUTION: </strong></font>'); ?> <a href="http://www.escalateseo.com/aitpro-blog/2585/wordpress-security-plugin-plugin-support/wordpress-website-maintenance-wordpress-maintenance-mode" target="_blank" onmouseover="Tip('<strong>Your Maintenance Mode Form data is saved to the WordPress Database and will remain permanently until you delete it. When you upgrade WPSP your form data will still be saved in your database.</strong><br><br><strong>Maintenance Mode Activation Steps</strong><br><br><strong>Filling In The Maintenance Mode Settings Form</strong><br><strong>1. Fill out the Website Maintenance Mode Form</strong><br> -- There are 4 important text fields that must be entered in the exact format shown in the example AIT-pro descriptions to the right of the text form fields in order for the countdown timer to display correctly. They are - Start Date -  Start Time - End Date - End Time. Military time must be used, spaces must be used and commas must be used - the format should be identical to the example description. All other text fields do not require following a specific format.<br> -- For the Retry-After text field I recommend using 259200. 259200 is 72 hours in seconds. 3600 = 1hr 43200 = 12hrs 86400 = 24hrs<br> -- You can copy and paste the example Background Image URL into the Background Image text field if you want to use the background image file that comes with WPSP. If you have another background image file that you want to use then just name it with the same name as the example image file and copy it to the /wordpress-security-plugin folder. If you do not want a background image then leave this text field blank. The background color will be white. If you want to customize the Website Under Maintenance template then download this file located in this folder /wordpress-security-plugin/admin/htaccess/bp-maintenance.php.<br><strong>2. Click the Save Form Settings button to save your form data to your database.</strong><br><strong>3. Click the Create Form button to create your Website Under Maintenance form.</strong><br><strong>4. Click the Preview Form button to preview your Website Under Maintenance page.</strong><br> -- If you see a 404 or 403 Forbidden message in the popup preview window refresh the popup preview window or just close the popup window and click the Preview button again.<br> -- You can use the Preview button at any time to preview how your site will be displayed to everyone else except you when your website is in Maintenance Mode.<br><br><strong>Create Your Maintenance Mode .htaccess File</strong><br>After you have finished previewing your Website Under Maintenance page, click the Create htaccess File button. This creates your Maintenance Mode .htaccess file for your website. Your current Public IP address and correct RewriteBase and RewriteRule are included when this new Maintenance Mode .htaccess file is created.<br><br><strong>Activate Website Under Maintenance Mode</strong><br>Select the Maintenance Mode radio button and click the Activate Maintenance Mode button. Your website is now in Maintenance Mode. Everyone else will see your Website Under Maintenance page while you can still view and work on your site as you normally would.<br><br>For more help information and detailed instructions click this Read Me button link to view the <strong>WordPress Security Plugin Guide.</strong>', WIDTH, 600, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()">Read Me</a></h3>

<form name="bps-maintenance-values" action="options.php" method="post">
<?php settings_fields('securityplugin_security_options_maint'); ?>
			<?php $options = get_option('securityplugin_security_options_maint'); ?>
<table class="form-table">
<tr valign="top">
<th scope="row"><label for="bps-site-title"><?php _e('Site Title:') ?></label></th>
<td><input name="securityplugin_security_options_maint[bps-site-title]" type="text" value="<?php echo $options['bps-site-title']; ?>" class="regular-text" /><span class="description"><?php _e('AIT-pro is Temporarily Closed for Maintenance') ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="bps-message-1"><?php _e('Message 1:') ?></label></th>
<td><input name="securityplugin_security_options_maint[bps-message-1]" type="text" value="<?php echo $options['bps-message-1']; ?>" class="regular-text" /><span class="description"><?php _e('The AIT-pro Website Is Performing Maintenance') ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="bps-message-2"><?php _e('Message 2:') ?></label></th>
<td><input name="securityplugin_security_options_maint[bps-message-2]" type="text" value="<?php echo $options['bps-message-2']; ?>" class="regular-text" /><span class="description"><?php _e('The AIT-pro Website Will Be Online In...') ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="bps-start-date"><?php _e('Start Date:') ?></label></th>
<td><input name="securityplugin_security_options_maint[bps-start-date]" type="text" value="<?php echo $options['bps-start-date']; ?>" class="regular-text" /><span class="description"><?php _e('April 05, 2011') ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="bps-start-time"><?php _e('Start Time:') ?></label></th>
<td><input name="securityplugin_security_options_maint[bps-start-time]" type="text" value="<?php echo $options['bps-start-time']; ?>" class="regular-text" /><span class="description"><?php _e('16:25:00') ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="bps-end-date"><?php _e('End Date:') ?></label></th>
<td><input name="securityplugin_security_options_maint[bps-end-date]" type="text" value="<?php echo $options['bps-end-date']; ?>" class="regular-text" /><span class="description"><?php _e('June 06') ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="bps-end-time"><?php _e('End Time:') ?></label></th>
<td><input name="securityplugin_security_options_maint[bps-end-time]" type="text" value="<?php echo $options['bps-end-time']; ?>" class="regular-text" /><span class="description"><?php _e('12:00:00') ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="bps-popup-message"><?php _e('Popup Message:') ?></label></th>
<td><input name="securityplugin_security_options_maint[bps-popup-message]" type="text" value="<?php echo $options['bps-popup-message']; ?>" class="regular-text" /><span class="description"><?php _e('Countdown completed! AIT-pro will resume normal website operation shortly.') ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="bps-retry-after"><?php _e('Retry-After:') ?></label></th>
<td><input name="securityplugin_security_options_maint[bps-retry-after]" type="text" value="<?php echo $options['bps-retry-after']; ?>" class="regular-text" /><span class="description"><?php _e('259200') ?></span></td>
</tr>
<tr valign="top">
<th scope="row"><label for="bps-background-image"><?php _e('Background Image') ?></label></th>
<td><input name="securityplugin_security_options_maint[bps-background-image]" type="text" value="<?php echo $options['bps-background-image']; ?>" class="regular-text" /><span class="description"><?php echo get_site_url(); ?>/wp-content/plugins/wordpress-security-plugin/abstract-blue-bg.png</span></td>
</tr>
</table>
<p class="submit">
<input type="submit" name="bps-maintenance-values_submit" class="button-primary" value="<?php esc_attr_e('Save Form Settings') ?>" />
</p>
</form>

<form name="bps-maintenance-create-values" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-6" method="post">
<?php wp_nonce_field('securityplugin_security_create_values_form'); ?>
<input type="hidden" name="mmfilename" value="bps-maintenance-create-valuesH" />
<p class="submit">
<input type="submit" name="bps-maintenance-create-values_submit" class="button-primary" value="<?php esc_attr_e('Create Form') ?>" /></p>
</form>

<!-- this is the Enable Download form reused for maintenance mode Preview -->
<form name="bps-enable-download" method="POST" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-6" target="" onSubmit="window.open('<?php echo get_site_url(); ?>/wp-content/plugins/wordpress-security-plugin/admin/htaccess/bp-maintenance.php','','scrollbars=yes,menubar=yes,width=800,height=600,resizable=yes,status=yes,toolbar=yes')">
<?php wp_nonce_field('securityplugin_security_enable_download'); ?>
<input type="hidden" name="filename" value="bps-enable-download-edit" />
<p class="submit">
<input type="submit" name="bps-enable-download" class="button-primary" value="<?php esc_attr_e('Preview Form') ?>" /></p>
</form>
</div>

<h3><?php _e('Activate Website Under Maintenance Mode'); ?></h3>
<h3><?php _e('<font color="red"><strong>CAUTION: </strong></font>'); ?> <a href="http://www.escalateseo.com/aitpro-blog/2585/wordpress-security-plugin-plugin-support/wordpress-website-maintenance-wordpress-maintenance-mode" target="_blank" onmouseover="Tip('<strong>You must click the Create htaccess File button FIRST to create your Maintenance Mode htaccess file before activating Maintenance Mode if you want to be able to continue working on your website while everyone else sees the Website Under Maintenance page</strong><br>After you have created your Maintenance Mode .htaccess file - Select the Maintenance Mode radio button and click Activate.<br><br><strong>To switch out of or exit Maintenance Mode just activate WordPress Security Plugin Mode for your Root folder on the Security Modes page.</strong> You can see what everyone is seeing except for you by clicking on the Preview Form button at any time.<br><br>When you activate Maintenance Mode your website will be put in HTTP 503 Service Temporarily Unavailable status and display a Website Under Maintenance page to everyone except you. Your current Public IP address was automatically added to the Maintenance Mode file as well as the correct .htaccess RewriteRule and RewriteBase for your website when you clicked the Create File button.<br><br>To manually add additional IP addresses that are allowed to view your website normally use the WPSP File Editor to add them. To view your current Public IP address click on the System Info tab menu.<br><br><strong>Your current Public IP address is also displayed on the Website Under Maintenance page itself.</strong><br><br>Your SERPs (website or web page ranking) will not be affected by putting your website in Maintenance Mode for several days for existing websites. To manually add additional IP addresses that can view your website you would add them using the WPSP File Editor.<br><br>If you are unable to log back into your WordPress Dashboard and are also seeing the Website Under Maintenance page then you will need to FTP to your website and either delete the .htaccess file in your website root folder or download the .htaccess file - add your correct current Public IP address and upload it back to your website.<br><br>For more help information and detailed instructions click this Read Me button link to view the <strong>WordPress Security Plugin Guide.</strong>', WIDTH, 600, PADDING, 8, ABOVE, true, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()">Read Me</a></h3>

<form name="bps-auto-write-maint" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-6" method="post">
<?php wp_nonce_field('securityplugin_security_auto_write_maint'); ?>
<input type="hidden" name="filename" value="bps-auto-write-maint_write" />
<p class="submit">
<input type="submit" name="bps-auto-write-maint" class="button-primary" value="<?php esc_attr_e('Create htaccess File') ?>" /></p>
</form>

<form name="BulletProof-Maintenance" action="admin.php?page=wordpress-security-plugin/admin/options.php#bps-tabs-6" method="post">
<?php wp_nonce_field('securityplugin_security_maintenance_copy'); ?>
<table class="form-table">
   <tr>
	<th><label><input name="selection15" type="radio" value="bpmaintenance" class="tog" <?php checked('', $bpmaintenance); ?> />
	<?php _e('Maintenance Mode'); ?></label></th>
	<td class="url-path"><?php _e('<font color="green">Click the Create htaccess File button first to create your Maintenance Mode .htaccess file. To switch out of or exit Maintenance Mode just activate WordPress Security Plugin Mode for your Root Folder.</font><strong> Read the <font color="red">CAUTION:</font> Read Me ToolTip for more detailed information.</strong>'); ?></td>
   </tr>
</table>
<p class="submit">
<input type="submit" name="submit15" class="button-primary" value="<?php esc_attr_e('Activate Maintenance Mode') ?>" />
</p></form>
</div>
        
</div>
<!-- this script needs to be on the options.php page - will not register or enqueue correctly due to tt_aElt.0.style issue -->
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/wordpress-security-plugin/admin/js/wz_tooltip.js"></script>
</div>