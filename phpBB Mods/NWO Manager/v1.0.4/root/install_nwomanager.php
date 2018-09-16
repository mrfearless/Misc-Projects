<?php
/**
*
* @author -[Nwo]- fearless
* @package NWO Manager Installation (UMIL)
* @version $Id install_nwomanager.php 1.0.0
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
include($phpbb_root_path . 'includes/constants_nwomanager.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup('mods/umil_install_nwomanager');

if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

// The name of the mod to be displayed during installation.
$mod_name = 'NWOMANAGER';
$version_config_name = 'nwomanager_version';

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


// versions array
	$versions = array(

		// v 1.0.0
		'1.0.0' => array(
			// Now to add some permission settings
			/*'permission_add' => array(
				array('a_app_view', true),
				array('a_app_settings', true),
				array('a_app_form', true),
				array('a_app_status', true),
			),*/

			// Add tables, primary keys and other indexes
			'table_add' => array(

				// NWO Servers table
				array(NWOSERVERS_TABLE, array(
					'COLUMNS'					=> array(
						'server_id'				=> array('INT:8', NULL, 'auto_increment'),
						'server_name'			=> array('VCHAR_UNI:60', ''),
						'server_description'	=> array('VCHAR_UNI:255', ''),
						'server_map'			=> array('VCHAR_UNI:60', ''),
						'server_ip'				=> array('VCHAR_UNI:22', ''),
						'server_type'			=> array('TINT:4', 0),
						'server_private'		=> array('TINT:1', 0),
						'server_adminmod'		=> array('TINT:4', 0),
						'server_hlstatsx'		=> array('TINT:1', 0),
						'server_sourcebans'		=> array('TINT:1', 0),
						'server_steambans'		=> array('TINT:1', 0),
						'server_order'			=> array('INT:8', NULL),
						'server_visible'		=> array('TINT:1', 1),
					),
					'PRIMARY_KEY'				=> 'server_id',
						'KEYS'					=> array(
							'server_order'		=> array('INDEX', 'server_order'),
						),
					),
				),


				// NWO Menu Blocks table
				array(NWOMENUBLOCKS_TABLE, array(
					'COLUMNS'					=> array(
						'block_id'				=> array('INT:8', NULL, 'auto_increment'),
						'block_title'			=> array('VCHAR_UNI:60', ''),
						'block_text'			=> array('VCHAR_UNI:60', ''),
						'block_url'				=> array('VCHAR_UNI:255', ''),
						'block_class'			=> array('VCHAR_UNI:30', ''),
						'block_divid'			=> array('VCHAR_UNI:30', ''),
						'block_visible'			=> array('TINT:1', 1),
						'block_position'		=> array('TINT:3', 0),
						'block_type'			=> array('TINT:3', 0),
						'block_order'			=> array('INT:8', NULL),
					),
					'PRIMARY_KEY'				=> 'block_id',
						'KEYS'					=> array(
							'block_order'		=> array('INDEX', 'block_order'),
						),
					),
				),

				// Menu Items Table
				array(NWOMENUITEMS_TABLE, array(
					'COLUMNS'					=> array(
						'menu_id' 				=> array('INT:8', NULL, 'auto_increment'),
  						'menu_blockid' 			=> array('INT:8', NULL),
  						'menu_text' 			=> array('VCHAR_UNI:60', ''),
  						'menu_url' 				=> array('VCHAR_UNI:255', ''),
  						'menu_image' 			=> array('VCHAR_UNI:255', ''),
 						'menu_visible' 			=> array('TINT:1', 1),
 						'menu_order'			=> array('INT:8', NULL),
 					),
					'PRIMARY_KEY'				=> 'menu_id',
						'KEYS'					=> array(
							'menu_order'		=> array('INDEX', 'menu_order'),
							'menu_blockid'		=> array('INDEX', 'menu_blockid, menu_order'),
						),
					),
				),
			),
			// End of Add tables, primary keys and other indexes


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
				array('nwomanager_news_forumid', '0'),
				array('nwomanager_news_maxdisplayed','5'),
				array('nwomanager_topics_forumid','0'),
				array('nwomanager_topics_maxdisplayed','3'),
				array('nwomanager_welcome_box','Welcome to New World Order. The Multiplayer Gaming Community.'),
			),

			// Add ACP modules
			'module_add' => array(
				array('acp', 'ACP_CAT_DOT_MODS', 'ACP_NWOMANAGER'),
			    array('acp', 'ACP_NWOMANAGER', array(
            		'module_basename'   => 'nwomanager',
            		'module_langname'   => 'ACP_NWOMANAGER_SERVERLIST',
            		'module_mode'       => 'nwoserverlist',
            		'module_auth'       => 'acl_a_nwo_servers',
       			)),
 			    array('acp', 'ACP_NWOMANAGER', array(
            		'module_basename'   => 'nwomanager',
            		'module_langname'   => 'ACP_NWOMANAGER_SETTINGS',
            		'module_mode'       => 'nwosettings',
            		'module_auth'       => 'acl_a_nwo_settings',
       			)),
       			array('acp', 'ACP_NWOMANAGER', array(
            		'module_basename'   => 'nwomanager',
            		'module_langname'   => 'ACP_NWOMANAGER_MENULINKS',
            		'module_mode'       => 'nwomenulinks',
            		'module_auth'       => 'acl_a_nwo_menus',
       			)),
       		),

			// Purge Cache
			'cache_purge' => '',
		),


		// v 1.0.1 - Added center blocks table and ACP module
		'1.0.1' => array(

			// Add ACP modules
			'module_add' => array(
				array('acp', 'ACP_NWOMANAGER', array(
            		'module_basename'   => 'nwomanager',
            		'module_langname'   => 'ACP_NWOMANAGER_CENTERBLOCKS',
            		'module_mode'       => 'nwocenterblocks',
            		'module_auth'       => 'acl_a_nwo_centers',
       			)),
       		),

  			// Add tables, primary keys and other indexes
			'table_add' => array(

				// Center Blocks Table
				array(NWOCENTERBLOCKS_TABLE, array(
					'COLUMNS'					=> array(
						'center_id' 			=> array('INT:8', NULL, 'auto_increment'),
  						'center_title' 			=> array('VCHAR_UNI:60', ''),
  						'center_text' 			=> array('TEXT', ''),
 						'center_visible' 		=> array('TINT:1', 1),
 						'center_order'			=> array('INT:8', NULL),
						'bbcode_uid'			=> array('VCHAR_UNI:8', ''),
						'bbcode_bitfield'		=> array('VCHAR_UNI:255', ''),
 					),
					'PRIMARY_KEY'				=> 'center_id',
						'KEYS'					=> array(
							'center_order'		=> array('INDEX', 'center_order'),
						),
					),
				),
       		),

       		// Purge Cache
			'cache_purge' => '',
		),


		// v 1.0.2 - Added log table and ACP module
		'1.0.2' => array(

			// Add ACP modules
			'module_add' => array(
				array('acp', 'ACP_NWOMANAGER', array(
            		'module_basename'   => 'nwomanager',
            		'module_langname'   => 'ACP_NWOMANAGER_LOGLIST',
            		'module_mode'       => 'nwologlist',
            		'module_auth'       => 'acl_a_nwo_logs',
       			)),
       		),

			// Add config settings
			'config_add' => array(
				array('nwomanager_log_admins', ''),
			),

  			// Add tables, primary keys and other indexes
			'table_add' => array(

				// Center Blocks Table
				array(NWOLOGS_TABLE, array(
					'COLUMNS'					=> array(
						'log_id' 				=> array('INT:11', NULL, 'auto_increment'),
  						'log_datetime' 			=> array('VCHAR_UNI:19', ''),
 						'log_server'			=> array('INT:8', NULL),
 						'log_admin'				=> array('INT:11', NULL),
 						'log_category' 			=> array('TINT:4', 0),
 						'log_type'				=> array('TINT:4', 0),
 						'log_summary' 			=> array('VCHAR_UNI:255', ''),
  						'log_notes' 			=> array('TEXT', ''),
						'bbcode_uid'			=> array('VCHAR_UNI:8', ''),
						'bbcode_bitfield'		=> array('VCHAR_UNI:255', ''),
 					),
					'PRIMARY_KEY'				=> 'log_id',
						'KEYS'					=> array(
							'log_datetime'		=> array('INDEX', 'log_datetime'),
							'log_server'		=> array('INDEX', 'log_server'),
							'log_admin'			=> array('INDEX', 'log_admin'),
							'log_category'		=> array('INDEX', 'log_category'),
						),
					),
				),
       		),

       		// Purge Cache
			'cache_purge' => '',
		),

		// v 1.0.3 - Added permissions
		'1.0.3' => array(
			// Now to add some permission settings
			'permission_add' => array(
				array('a_nwo_servers', true),
				array('a_nwo_settings', true),
				array('a_nwo_menus', true),
				array('a_nwo_centers', true),
				array('a_nwo_logs', true),
			),

			// Purge Cache
			'cache_purge' => '',
		),

		// v 1.0.4 - Added Server Mods
		'1.0.4' => array(
			// Now to add some permission settings
			'permission_add' => array(
				array('a_nwo_servermoddb', true),
			),

  			// Add tables, primary keys and other indexes
			'table_add' => array(

				// Server ModDB Table
				array(NWOSERVERMODSDB_TABLE, array(
					'COLUMNS'					=> array(
						'mod_id' 				=> array('INT:11', NULL, 'auto_increment'),
  						'mod_name' 				=> array('VCHAR_UNI:50', ''),
 						'mod_description'		=> array('TEXT', ''),
 						'mod_type'				=> array('TINT:4', 0),
 						'mod_author'			=> array('VCHAR_UNI:50', ''),
 						'mod_website' 			=> array('VCHAR_UNI:255', ''),
 						'mod_downloadurl'		=> array('VCHAR_UNI:255', ''),
 						'mod_forum' 			=> array('VCHAR_UNI:255', ''),
  						'mod_notes' 			=> array('TEXT', ''),
						'bbcode_uid'			=> array('VCHAR_UNI:8', ''),
						'bbcode_bitfield'		=> array('VCHAR_UNI:255', ''),
 					),
					'PRIMARY_KEY'				=> 'mod_id',
						'KEYS'					=> array(
							'mod_name'			=> array('INDEX', 'mod_name'),
							'mod_type'			=> array('INDEX', 'mod_type'),
							'mod_typename'		=> array('INDEX', 'mod_type, mod_name'),
						),
					),
				),

				// Server Mods Table
				array(NWOSERVERMODS_TABLE, array(
					'COLUMNS'					=> array(
						'id' 					=> array('INT:11', NULL, 'auto_increment'),
						'server_id'				=> array('INT:8', NULL),
						'mod_id' 				=> array('INT:11', NULL),
 					),
					'PRIMARY_KEY'				=> 'id',
						'KEYS'					=> array(
							'server_id'			=> array('INDEX', 'server_id'),
							'server_mod'		=> array('INDEX', 'server_id, mod_id'),
						),
					),
				),
       		),

       		// Purge Cache
			'cache_purge' => '',

		),
	);


// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

?>