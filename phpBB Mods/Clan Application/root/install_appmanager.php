<?php
/**
*
* @author -[Nwo]- fearless
* @package Clan Application Manager Installation (UMIL)
* @version $Id install_afkmanager.php 0.1.0
* @copyright (c) 2009 -[Nwo]- fearless
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('UMIL_AUTO', true);
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup('mods/umil_appmanager_install');

if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

// The name of the mod to be displayed during installation.
$mod_name = 'APPMANAGER';
$version_config_name = 'application_version';

/*
* The language file which will be included when installing
* Language entries that should exist in the language file for UMIL (replace $mod_name with the mod's name you set to $mod_name above)
* $mod_name
* 'INSTALL_' . $mod_name
* 'INSTALL_' . $mod_name . '_CONFIRM'
* 'UPDATE_' . $mod_name
* 'UPDATE_' . $mod_name . '_CONFIRM'
* 'UNINSTALL_' . $mod_name
* 'UNINSTALL_' . $mod_name . '_CONFIRM'
*/
/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/


// version 0.1.0
	$versions = array(

		'0.1.0' => array(
			// Now to add some permission settings
			'permission_add' => array(
				array('a_app_view', true),
				array('a_app_settings', true),
				array('a_app_form', true),
				array('a_app_status', true),
			),

			// Add tables, primary keys and other indexes
			'table_add' => array(

				// Application form table
				array('phpbb_app_form', array(
					'COLUMNS'				=> array(
						'section_id'		=> array('INT:8', NULL, 'auto_increment'),
						'section_title'		=> array('VCHAR_UNI:60', ''),
						'section_text'		=> array('TEXT', ''),
						'section_order'		=> array('INT:8', 0),
						'section_display'	=> array('INT:1', 1),
						'bbcode_uid'		=> array('VCHAR_UNI:8', ''),
						'bbcode_bitfield'	=> array('VCHAR_UNI:255', ''),
					),
					'PRIMARY_KEY'			=> 'section_id',
						'KEYS'					=> array(
							'section_order'		=> array('INDEX', 'section_order'),
						),
					),
				),

				// Application statuses table
				array('phpbb_app_status', array(
					'COLUMNS'				=> array(
						'status_id'			=> array('INT:8', NULL, 'auto_increment'),
						'status_value'		=> array('TINT:4', 0),
						'status_title'		=> array('VCHAR_UNI:60', ''),
						'status_text'		=> array('TEXT', ''),
						'status_color'		=> array('VCHAR_UNI:6', ''),
						'bbcode_uid'		=> array('VCHAR_UNI:8', ''),
						'bbcode_bitfield'	=> array('VCHAR_UNI:255', ''),
					),
					'PRIMARY_KEY'			=> 'status_id',
						'KEYS'					=> array(
							'status_value'		=> array('INDEX', 'status_value'),
						),
					),
				),

				// Applications table (users)
				array('phpbb_applications', array(
					'COLUMNS'					=> array(
						'app_id' 				=> array('INT:11', NULL, 'auto_increment'),
  						'app_user_id' 			=> array('INT:11', NULL),
  						'app_username' 			=> array('VCHAR_UNI:60', ''),
  						'app_date' 				=> array('VCHAR_UNI:19', ''),
 						'app_status' 			=> array('TINT:4', 0),
 					),
					'PRIMARY_KEY'				=> 'app_id',
					),
				),
			),

			// Add fields
			/*'table_column_add' => array(
				array(USERS_TABLE, 'user_afkstatus', array('TINT:1', '0')),
    			array(USERS_TABLE, 'user_afkdate', array('CHAR:19', '')),
    			array(USERS_TABLE, 'user_afkpmmsg', array('TEXT', '')),
    			array(USERS_TABLE, 'user_afktopicid', array('UINT', 0)),
    			array(USERS_TABLE, 'user_afkreasoncat', array('TINT:2', 0)),
    			array(USERS_TABLE, 'user_afkreason', array('VCHAR_UNI:60', '')),
    			array(USERS_TABLE, 'user_afkautodays', array('TINT:4', 0)),
			),*/

			// Add config settings
			'config_add' => array(
				array('application_clanname', 'Clan'),
				array('application_open','1'),
				array('application_questions','0'),
				array('application_post_private','0'),
				array('application_post_public','0'),
				array('application_post_topicicon','0'),
			),

			// Add ACP modules
			'module_add' => array(
				array('acp', 'ACP_CAT_DOT_MODS', 'ACP_APPMANAGER'),
			    array('acp', 'ACP_APPMANAGER', array(
            		'module_basename'   => 'appmanager',
            		'module_langname'   => 'ACP_APPMANAGER_LIST',
            		'module_mode'       => 'appviewlist',
            		'module_auth'       => 'acl_a_app_view',
       			)),
 			    array('acp', 'ACP_APPMANAGER', array(
            		'module_basename'   => 'appmanager',
            		'module_langname'   => 'ACP_APPMANAGER_SETTINGS',
            		'module_mode'       => 'appsettings',
            		'module_auth'       => 'acl_a_app_settings',
       			)),
       			array('acp', 'ACP_APPMANAGER', array(
            		'module_basename'   => 'appmanager',
            		'module_langname'   => 'ACP_APPMANAGER_FORM',
            		'module_mode'       => 'appform',
            		'module_auth'       => 'acl_a_app_form',
       			)),
       			array('acp', 'ACP_APPMANAGER', array(
            		'module_basename'   => 'appmanager',
            		'module_langname'   => 'ACP_APPMANAGER_STATUS',
            		'module_mode'       => 'appstatus',
            		'module_auth'       => 'acl_a_app_status',
       			)),


       		),

			// Purge Cache
			'cache_purge' => '',
		),
	);


// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

?>