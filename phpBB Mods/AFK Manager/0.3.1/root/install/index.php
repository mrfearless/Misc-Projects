<?php
/**
*
* @package Installation of AFK Manager
* @version $Id: 0.3.1
* @copyright (c) 2009 -[Nwo]- fearless
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('IN_PHPBB', true);
define('IN_INSTALL', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/acp/acp_modules.' . $phpEx);
include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
include($phpbb_root_path . 'includes/acp/auth.' . $phpEx);

$action = request_var('action', '');	
// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup(array('mods/info_acp_afkmanager', 'mods/afkmanager', 'acp/common', 'install'));

// CURRENT VERSION
$current_version = '0.3.1';


// Before we do anything, lets see if an Admin is calling this file
//if (!$auth->acl_get('a_'))
//{
//	trigger_error('NO_ADMIN');
//}

$msg = '';

// Set up array of settings for install/uninstall
$settingsarray = array(	
	'afkmanager_afk_posting_enable'			=> '1',
	'afkmanager_afk_posting_forumid'		=> '0',
	'afkmanager_afk_posting_reply'			=> '1',
	'afkmanager_afk_posting_topicicon'		=> '0',
	'afkmanager_afk_viewtopic' 				=> '1',
	'afkmanager_afk_viewtopic_reason' 		=> '1',
	'afkmanager_afk_viewtopic_link' 		=> '1',
	'afkmanager_afk_viewprofile'			=> '1',
	'afkmanager_afk_reminder_board'			=> '1',
	'afkmanager_afk_posting_footer'			=> '<br /><hr /><br />This Auto Post was made by AFK Manager v0.3.0 -[Nwo]- fearless 2009.<br />',
	'afkmanager_afk_autoafk'				=> '0',
	'afkmanager_afk_autoafk_default'		=> '0',
	'afkmanager_afk_autoafk_check'			=> '0',
);

switch ($action)
{
	case 'install':
	
		// Add permissions
		$auth_admin = new auth_admin();
		$auth_admin->acl_add_option(array(
		    'global'   => array('u_afk_view', 'a_afk_view', 'a_afk_settings'),
		));    
		
		$msg .=  '<span style="color:green;"> - ' . $user->lang['AFKMANAGER_PERM_CREATED'] . '</span><br/>';

		// load schema
		load_schema($phpbb_root_path . 'install/schemas/');		
		$msg .=  '<span style="color:green;">- ' . $user->lang['AFKMANAGER_FIELDS_ADDED'] . '</span> <br/>';			

		//lets tell everyone we added the Mods Database already
		add_log('admin', 'AFKMANAGER_INSTALLED', (string) utf8_normalize_nfc($user->lang['AFKMANAGER']));

		install_modules();
		settings($settingsarray, 'install');
		$msg .= '<span style="color:green;">- ' . $user->lang['AFKMANAGER_MODULE_ADDED'] . '</span><br /><br />';
		$msg .= $user->lang['AFKMANAGER_INSTALL_COMPLETE'];
		
		
		$msg .= sprintf($user->lang['AFKMANAGER_INSTALL_RETURN'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');

		set_config('afkmanager_version', (string) $current_version);
		
		global $cache;
		$cache->purge();

		add_log('admin', 'LOG_PURGE_CACHE');
						
		
		// Assign index specific vars
		$template->assign_vars(array(
			'TITLE'	=> $user->lang['AFKMANAGER'],
			'BODY'	=> $msg,
		));

	break;
	
	case 'upgrade':

		if ( !isset($config['afkmanager_version']))
		{
	
			// Add permissions
			$auth_admin = new auth_admin();
			
			$auth_admin->acl_add_option(array(
			    'global'   => array('u_afk_view', 'a_afk_view', 'a_afk_settings'),
			));    
			
			$msg .=  '<span style="color:green;"> - ' . $user->lang['AFKMANAGER_PERM_CREATED'] . '</span>';
			
/*			// Check if users database is already updated
			$sql = 'SHOW COLUMNS FROM phpbb_users LIKE ' . "'user_afkstatus'";
			$result = $db->sql_query($sql);
			if (!$result)
			{*/
				// load schema
				load_schema($phpbb_root_path . 'install/schemas/');				
				$msg .=  '<span style="color:green;">- ' . $user->lang['AFKMANAGER_FIELDS_ADDED'] . '</span> <br/>';			
/*			}	
			else
			{
				$msg .=  '<span style="color:green;">- ' . $user->lang['AFKMANAGER_FIELDS_EXIST'] . '</span> <br/>';		
				$db->sql_freeresult($result);
			}*/
			
			install_modules();
			install_settings();
			$msg .= '<span style="color:green;">- ' . $user->lang['AFKMANGER_MODULE_READDED'] . '</span><br /><br />';

		}
		else
		{
			switch ($config['afkmanager_version'])
			{
/*				case '1.0.2':
				case '1.0.3':*/
				case '0.1.0':
					install_modules();
					install_settings();
					$msg .= '<span style="color:green;">- ' . $user->lang['AFKMANGER_MODULE_READDED'] . '</span><br /><br />';
				break;
				case '0.3.0':
					install_modules();
					install_settings();
					$msg .= '<span style="color:green;">- ' . $user->lang['AFKMANGER_MODULE_READDED'] . '</span><br /><br />';					
				break;	
			}
		}
		

		//setting the version so if we update in the future we don't have to go through the whole shabang again		
		set_config('afkmanager_version', (string) $current_version);
		
		global $cache;
		$cache->purge();
		add_log('admin', 'LOG_PURGE_CACHE');
						
		$msg .= $user->lang['AFKMANAGER_INSTALL_COMPLETE'];
	
		$msg .= sprintf($user->lang['AFKMANAGER_INSTALL_RETURN'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');
		
		// Assign index specific vars
		$template->assign_vars(array(
			'TITLE'	=> $user->lang['AFKMANAGER'],
			'BODY'	=> $msg,
		));
	break;

	case 'uninstall':

/*		if ( isset($config['afkmanager_version']) )
		{
			$sql = 'DELETE 
			FROM ' . CONFIG_TABLE . "
			WHERE config_name = '" . $db->sql_escape('afkmanager_version') . "'
			OR config_name = '" . $db->sql_escape('afkmanager_afk_posting_enable') . "'
			OR config_name = '" . $db->sql_escape('afkmanager_afk_posting_forumid') . "'
			OR config_name = '" . $db->sql_escape('afkmanager_afk_posting_reply') . "'";
			$result = $db->sql_query($sql);
		}*/
		settings($settingsarray, 'uninstall');
		$msg .= '<span style="color:green;">- ' . $user->lang['AFKMANAGER_CONFIG_DELETE'] . '</span>';		
		
		// install the modules
		install_modules('delete');
		$msg .= '<span style="color:green;">- ' . $user->lang['AFKMANAGER_MODULE_DELETED'] . '</span>';

		global $cache;
		$cache->purge();
		add_log('admin', 'LOG_PURGE_CACHE');
						
		$msg .= $user->lang['AFKMANAGER_DELETE_COMPLETE'];
		$msg .= sprintf($user->lang['AFKMANAGER_INSTALL_RETURN'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '"><strong>', '</strong></a>');
		
		// Assign index specific vars
		$template->assign_vars(array(
			'TITLE'	=> $user->lang['AFKMANAGER'],
			'BODY'	=> $msg,
		));
	break;

	default:

		$msg = '<span style="color:red; font-size:1.5em;">' . $user->lang['AFKMANAGER_BACKUP_WARN'] . '</span><br /><br />';				

		if (!isset($config['afkmanager_version']))  // (!isset($config['mod_show']) && 
		{
			$msg .= '<span style="color:red;">' . $user->lang['AFKMANAGER_INSTALL_DESC'] . '</span><br /><br />';				
			$msg .= '<a href="' . append_sid("{$phpbb_root_path}install/index.$phpEx", "action=install") . '">' . $user->lang['AFKMANAGER_NEW_INSTALL'] . '</a><br />';
		}
		else
		{
			$msg .= '<span style="color:red;">' . $user->lang['AFKMANAGER_UPGRADE_DESC'] . '</span><br /><br />';				
			$msg .= '<a href="' . append_sid("{$phpbb_root_path}install/index.$phpEx", "action=upgrade") . '">' . sprintf($user->lang['AFKMANAGER_UPGRADE'], $current_version) . '</a><br />';		
			$msg .= '<a href="' . append_sid("{$phpbb_root_path}install/index.$phpEx", "action=uninstall") . '"><br /><span style="color:red;">' . $user->lang['AFKMANAGER_UNINSTALL'] . '</span></a><br />';	
		}

		// Assign index specific vars
		$template->assign_vars(array(
			'TITLE'	=> $user->lang['AFKMANAGER'],
			'BODY'	=> $msg,
		));

}

// Output

// Output page
page_header($user->lang['INSTALL_PANEL']);

$template->set_custom_template('../adm/style', 'admin');
$template->assign_var('T_TEMPLATE_PATH', '../adm/style');

$template->set_filenames(array(
	'body' => 'install_main.html')
);

page_footer();

/**
* Load a schema (and execute)
*
* @param string $install_path Path to folder containing schema files
* @param mixed $install_dbms Alternative database system than $dbms
*/
function load_schema($install_path = '', $install_dbms = false)
{
   global $db;
   global $table_prefix;

   if ($install_dbms === false)
   {
	  global $dbms;
	  $install_dbms = $dbms;
   }

   static $available_dbms = false;

   if (!$available_dbms)
   {
	  if (!function_exists('get_available_dbms'))
	  {
		 global $phpbb_root_path, $phpEx;
		 include($phpbb_root_path . 'includes/functions_install.' . $phpEx);
	  }

	  $available_dbms = get_available_dbms($install_dbms);

	  if ($install_dbms == 'mysql')
	  {
		 if (version_compare($db->sql_server_info(true), '4.1.3', '>='))
		 {
			$available_dbms[$install_dbms]['SCHEMA'] .= '_41';
		 }
		 else
		 {
			$available_dbms[$install_dbms]['SCHEMA'] .= '_40';
		 }
	  }
   }

   $remove_remarks = $available_dbms[$install_dbms]['COMMENTS'];
   $delimiter = $available_dbms[$install_dbms]['DELIM'];

   $dbms_schema = $install_path . $available_dbms[$install_dbms]['SCHEMA'] . '_schema.sql';

   if (file_exists($dbms_schema))
   {
	  $sql_query = @file_get_contents($dbms_schema);
	  $sql_query = preg_replace('#(?<!mod_)phpbb_#i', $table_prefix, $sql_query);

	  $remove_remarks($sql_query);

	  $sql_query = split_sql_file($sql_query, $delimiter);

	  foreach ($sql_query as $sql)
	  {
		 $db->sql_query($sql);
	  }
	  unset($sql_query);
   }

   if (file_exists($install_path . 'schema_data.sql'))
   {
	  $sql_query = file_get_contents($install_path . 'schema_data.sql');

	  switch ($install_dbms)
	  {
		 case 'mssql':
		 case 'mssql_odbc':
			$sql_query = preg_replace('#\# MSSQL IDENTITY (phpbb_[a-z_]+) (ON|OFF) \##s', 'SET IDENTITY_INSERT \1 \2;', $sql_query);
		 break;

		 case 'postgres':
			$sql_query = preg_replace('#\# POSTGRES (BEGIN|COMMIT) \##s', '\1; ', $sql_query);
		 break;
	  }

	  $sql_query = preg_replace('#(?<!mod_)phpbb_#i', $table_prefix, $sql_query);
	  $sql_query = preg_replace_callback('#\{L_([A-Z0-9\-_]*)\}#s', 'adjust_language_keys_callback', $sql_query);

	  remove_remarks($sql_query);

	  $sql_query = split_sql_file($sql_query, ';');

	  foreach ($sql_query as $sql)
	  {
		 $db->sql_query($sql);
	  }
	  unset($sql_query);
   }
}

function install_modules($type=false)
{
	global $db, $user;
	
	// This is for the ACP Module
	// Lets make sure this module does not get added a second time by accident
	$sql = 'SELECT module_id
		FROM ' . MODULES_TABLE . "
		WHERE module_langname = '" . $db->sql_escape('ACP_AFKMANAGER') . "'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	if ($row)
	{
		$sql = 'DELETE
			FROM ' . MODULES_TABLE . "
			WHERE module_langname = '" . $db->sql_escape('ACP_AFKMANAGER') . "'
			OR module_langname = '" . $db->sql_escape('ACP_AFKMANAGER_LIST') . "'
			OR module_langname = '" . $db->sql_escape('ACP_AFKMANAGER_SETTINGS') . "'";
		$result = $db->sql_query($sql);
	}
	
	if ($type != 'delete')
	{
		
		$_module = new acp_modules();
		
		// So lets add the main category
		$afkmanager = array(
			'module_basename'	=> 'afkmanager',
			'module_enabled'	=> 1,
			'module_display'	=> 1,
			'parent_id'			=> 0,
			'module_class'		=> 'acp',
			'module_langname'	=> 'ACP_AFKMANAGER',
			'module_mode'		=> '',
			'module_auth'		=> 'acl_a_afk_view',
		);
		$_module->update_module_data($afkmanager);
		
		// Now the subcategory
		$afkmanager_sub = array(
			'module_basename'	=> 'afkmanager',
			'module_enabled'	=> 1,
			'module_display'	=> 1,
			'parent_id'			=> (int) $afkmanager['module_id'],
			'module_class'		=> 'acp',
			'module_langname'	=> 'ACP_AFKMANAGER',
			'module_mode'		=> '',
			'module_auth'		=> 'acl_a_afk_view',
		);
		$_module->update_module_data($afkmanager_sub);		
		
		// Now the two menu subcategories
		$afkmanager_sub_sub = array(
			'module_basename'	=> 'afkmanager',
			'module_enabled'	=> 1,
			'module_display'	=> 1,
			'parent_id'			=> (int) $afkmanager_sub['module_id'],
			'module_class'		=> 'acp',
			'module_langname'	=> 'ACP_AFKMANAGER_LIST',
			'module_mode'		=> 'afkviewlist',
			'module_auth'		=> 'acl_a_afk_view',
		);
		$_module->update_module_data($afkmanager_sub_sub);

		$afkmanager_sub_sub = array(
			'module_basename'	=> 'afkmanager',
			'module_enabled'	=> 1,
			'module_display'	=> 1,
			'parent_id'			=> (int) $afkmanager_sub['module_id'],
			'module_class'		=> 'acp',
			'module_langname'	=> 'ACP_AFKMANAGER_SETTINGS',
			'module_mode'		=> 'afksettings',
			'module_auth'		=> 'acl_a_afk_settings',
		);
		$_module->update_module_data($afkmanager_sub_sub);		
		
	}

	// This is for the UCP Module
	// Lets make sure this module does not get added a second time by accident	
	$sql = 'SELECT module_id
		FROM ' . MODULES_TABLE . "
		WHERE module_langname = '" . $db->sql_escape('UCP_AFKMANAGER') . "'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	if ($row)
	{
		$sql = 'DELETE
			FROM ' . MODULES_TABLE . "
			WHERE module_langname = '" . $db->sql_escape('UCP_AFKMANAGER') . "'";
		$result = $db->sql_query($sql);
	}
	
	if ($type != 'delete')
	{	

		// Add the user control panel
		$_module = new acp_modules();
		
		$afkmanager = array(
			'module_basename'	=> 'afkmanager',
			'module_enabled'	=> 1,
			'module_display'	=> 1,
			'parent_id'			=> 0,
			'module_class'		=> 'ucp',
			'module_langname'	=> 'UCP_AFKMANAGER',
			'module_mode'		=> 'afkstatus',
			'module_auth'		=> 'acl_u_afk_view',
		);
		$_module->update_module_data($afkmanager);			
	
	}	
		
}

// Settings Install/Uninstall function
function settings($settingsarray, $type=false)
{
	if ($type = 'install')
	{
		foreach ($settingsarray as $config_name=>$value)
		{
			set_config($config_name, $value);
		}
	}
	else // 'uninstall/delete
	{
		foreach ($settingsarray as $config_name)
		{
			if ( isset($config_name) )
			{
				$sql = 'DELETE 
				FROM ' . CONFIG_TABLE . "
				WHERE config_name = '" . $db->sql_escape($config_name) . "'";
				$result = $db->sql_query($sql);
			}
		}
	}
}
?>