<?php
/**
*
* @author -[Nwo]- fearless
* @package AFK Manager Installation (UMIL)
* @version $Id install_afkmanager.php 0.3.1
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
$user->setup('mods/afkmanager');

if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

// The name of the mod to be displayed during installation.
$mod_name = 'AFKMANAGER';
$version_config_name = 'afkmanager_version';

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
//$language_file = 'mods/afkmanager';

/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/


// version 0.3.1
	$versions = array(

		'0.3.1' => array(
			// Now to add some permission settings
			'permission_add' => array(
				array('u_afk_view', true),
				array('a_afk_view', true),
				array('a_afk_settings', true),
			),

			// Add fields
			'table_column_add' => array(
				array(USERS_TABLE, 'user_afkstatus', array('TINT:1', '0')),
    			array(USERS_TABLE, 'user_afkdate', array('CHAR:19', '')),
    			array(USERS_TABLE, 'user_afkpmmsg', array('TEXT', '')),
    			array(USERS_TABLE, 'user_afktopicid', array('UINT', 0)),
    			array(USERS_TABLE, 'user_afkreasoncat', array('TINT:2', 0)),
    			array(USERS_TABLE, 'user_afkreason', array('VCHAR_UNI:60', '')),
    			array(USERS_TABLE, 'user_afkautodays', array('TINT:4', 0)),
			),

			// Add config settings
			'config_add' => array(
				array('afkmanager_afk_posting_enable', '1'),
				array('afkmanager_afk_posting_forumid','0'),
				array('afkmanager_afk_posting_reply','1'),
				array('afkmanager_afk_posting_topicicon','0'),
				array('afkmanager_afk_viewtopic','1'),
				array('afkmanager_afk_viewtopic_reason','1'),
				array('afkmanager_afk_viewtopic_link','1'),
				array('afkmanager_afk_viewprofile','1'),
				array('afkmanager_afk_reminder_board','1'),
				array('afkmanager_afk_posting_default', 'My status is marked as Away From Keyboard (AFK) at the moment. Any PMs sent to me in the meantime wont be read until my return.'),
				array('afkmanager_afk_posting_footer','<br /><hr /><br />This Auto Post was made by [b]AFK Manager v0.3.1[/b] -[Nwo]- fearless 2009.<br />'),
				array('afkmanager_afk_pmmsg_enable','1'),
				array('afkmanager_afk_autoafk','0'),
				array('afkmanager_afk_autoafk_default','0'),
				array('afkmanager_afk_autoafk_check','0'),
			),

			// Add ACP & UCP modules
			'module_add' => array(
				array('acp', 'ACP_CAT_DOT_MODS', 'ACP_AFKMANAGER'),
			    array('acp', 'ACP_AFKMANAGER', array(
            		'module_basename'   => 'afkmanager',
            		'module_langname'   => 'ACP_AFKMANAGER_LIST',
            		'module_mode'       => 'afkviewlist',
            		'module_auth'       => 'acl_a_afk_view',
       			)),
 			    array('acp', 'ACP_AFKMANAGER', array(
            		'module_basename'   => 'afkmanager',
            		'module_langname'   => 'ACP_AFKMANAGER_SETTINGS',
            		'module_mode'       => 'afksettings',
            		'module_auth'       => 'acl_a_afk_settings',
       			)),
 			    array('acp', 'ACP_CAT_USERS', array(
            		//'module_display'    => 1,
 			    	'module_basename'   => 'users',
            		'module_langname'   => 'ACP_USER_AFKMANAGER',
            		'module_mode'       => 'afkmanager',
            		'module_auth'       => 'acl_a_board',
       			)),
				array('ucp', '', array(
       				'module_basename'   => 'afkmanager',
					'module_langname'	=> 'UCP_AFKMANAGER',
					'module_mode'		=> 'afkstatus',
					'module_auth'		=> 'acl_u_afk_view',
				))
			),

			// Purge Cache
			'cache_purge' => '',
		),
	);


// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

?>