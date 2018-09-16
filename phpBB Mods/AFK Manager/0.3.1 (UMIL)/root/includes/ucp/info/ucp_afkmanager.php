<?php
/**
 * 
 * @package AFK Manager
 * @version $Id: 0.3.0
 * @copyright (c) 2009 -[Nwo]- fearless
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License 
 */

if (!defined('IN_PHPBB'))
{
	exit;
}


class ucp_afkmanager_info
{
	function module()
	{
		return array(
		'filename'   => 'ucp_afkmanager', // The module's filename
		'title'      => 'UCP_AFKMANAGER', // The title (language string)
		'version'    => '0.1.0', // The module's version
		'modes'      => array( // This is where you add the mode(s)
		'afkstatus'	 => array('title' => 'UCP_AFKMANAGER', 'auth' => 'acl_u_afk_view', 'cat' => 'UCP_AFKMANAGER')
		),
		);
	}

	function install()
	{

/*		$user->session_begin();
		$auth->acl($user->data);
		$user->setup();		
		
		include($phpbb_root_path . 'includes/acp/auth.' . $phpEx);
		$auth_admin = new auth_admin();	
		
		// Add permissions
		$auth_admin->acl_add_option(array(
		    'global'   => array('u_afk_view'),
		));   		
		
		global $phpbb_root_path, $phpEx, $db, $user;
		
		// Setup $auth_admin class so we can add permission options
		include($phpbb_root_path . 'includes/acp/auth.' . $phpEx);
		$auth_admin = new auth_admin();

		// Add permission for manage cvsdb
		$auth_admin->acl_add_option(array(
			'local'		=> array(),
			'global'	=> array('a_afk_usemanager')
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
		
		$sql_ary[] = 'UPDATE ' . MODULES_TABLE . " SET module_auth = 'acl_a_afk_usemanager' WHERE module_id = $module_id";
		*/

	}

	function uninstall()
	{
	}
}
?>