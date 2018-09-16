<?php
/**
 * 
 * @package AFK Manager
 * @version $Id: 0.3.1
 * @copyright (c) 2009 -[Nwo]- fearless
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License 
 */

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
 * @package module_install
 */

 
/**
 * If you are adding a full file, you'll need to make your own info file too. 
 * If you are adding mode, you'll need to edit the existing info file. 
 * The info file should be placed in the includes/{MODULECLASS}/info folder, and have the same filename as the module file. 
 * Example: includes/acp/info/acp_foobar.php.
 */

class acp_afkmanager_info
{
	function module()
	{
		return array(
		'filename'   => 'acp_afkmanager', // The module's filename
		'title'      => 'ACP_AFKMANAGER', // The title (language string)
		'version'    => '0.1.0', // The module's version
		'modes'      => array( // This is where you add the mode(s)
		'afkviewlist'	 => array('title' => 'ACP_AFKMANAGER_LIST', 'auth' => 'acl_a_afk_view', 'cat' => 'ACP_AFKMANAGER'),
		'afksettings'	 => array('title' => 'ACP_AFKMANAGER_SETTINGS', 'auth' => 'acl_a_afk_settings', 'cat' => 'ACP_AFKMANAGER'),
		'afkmanager'	 => array('title' => 'ACP_USER_AFKMANAGER', 'auth' => 'acl_a_board', 'cat' => 'ACP_CAT_USERS')
		),
		);
	}

	function install()
	{

/*		global $phpbb_root_path, $phpEx, $db, $user, $table_prefix;
		
		include($phpbb_root_path . 'includes/acp/auth.' . $phpEx);
		$auth_admin = new auth_admin();	
		
		// Add permissions
		$auth_admin->acl_add_option(array(
		    'global'   => array('u_afk_view', 'a_afk_view'),
		));   		
		
		$module_data = $this->module();
		$module_basename = substr(strchr($module_data['filename'], '_'), 1);
		$sql_ary = array();
		$message = '';
		$db->sql_return_on_error(true);

		$sql = 'SELECT module_id
				FROM ' . MODULES_TABLE . "
				WHERE module_basename = '$module_basename'";
		$result = $db->sql_query($sql);
		$module_id = $db->sql_fetchfield('module_id');
		$db->sql_freeresult($result);
		
		$sql_ary[] = 'UPDATE ' . MODULES_TABLE . " SET module_auth = 'acl_a_afk_view' WHERE module_id = $module_id";
		
		//$sql_ary[] = "ALTER TABLE {$table_prefix}users ADD user_afkstatus TINYINT(1) NOT NULL default '0';";

		foreach ($sql_ary as $sql)
		{
			$message .= '<p style="font-weight: bold;">'.$sql.'</p>';
			$result = $db->sql_query($sql);
			if ($result)
			{
				$message .= '<p style="color: yellow;">Query processed succesfully.</p>';
			}
			else
			{
				$error = true;
				$message .= '<p style="color: red;">There was an error while processing this query.</p>';
			}
			$message .= '<br />';
		}

		set_config('afkmanager_version', $module_data['version']);
		//set_config('afkmanager_activate', 1);

		$message .= sprintf($user->lang['AFKMANAGER_INSTALLED'], $module_data['version']) . adm_back_link($this->u_action) ;

	    msg_handler(E_USER_WARNING, $message, '', '');*/

	}

	function uninstall()
	{
	}
}
?>