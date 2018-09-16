<?php
/***************************************************************************
*
* @package Medals Mod for phpBB3
* @version $Id: medals.php,v 0.9.1 2008/02/19 Gremlinn$
* @copyright (c) 2008 Nathan DuPra (mods@dupra.net)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
***************************************************************************/

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
class acp_subscription_cycles_info
{
	var $u_action;

    function module()
    {
        return array(
            'filename'		=> 'acp_subscription_cycles',
            'title'			=> 'ACP_SUBSCRIPTION_CYCLES_INDEX',
            'version'		=> '1.0.0',
            'modes'			=> array(
                'management'	=> array(
					'title'			=> 'ACP_SUBSCRIPTION_CYCLES_TITLE',
					'auth'			=> 'acl_a_manage_subscription_cycles',
					'cat' 			=> array('ACP_CAT_USERS'),
				),
			),
        );
    }

    function install()
    {
		global $phpbb_root_path, $phpEx, $db, $user, $table_prefix;
		
		// Setup $auth_admin class so we can add permission options
		include($phpbb_root_path . 'includes/acp/auth.' . $phpEx);
		$auth_admin = new auth_admin();

		// Add permission for manage cvsdb
		$auth_admin->acl_add_option(array(
			'local'		=> array(),
			'global'	=> array('a_manage_subscription_cycles')
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
		
		$sql_ary[] = 'UPDATE ' . MODULES_TABLE . " SET module_auth = 'acl_a_manage_subscription_cycles' WHERE module_id = $module_id";

		// $sql_ary[] = "CREATE TABLE {$table_prefix}subscription_cycles (
				  // id tinyint(5) NOT NULL auto_increment,
				  // cycle_name varchar(30) collate utf8_bin NOT NULL default '',
				  // cycle_amount decimal(8,2) NOT NULL,
				  // cycle_time varchar(32) collate utf8_bin NOT NULL,
				  // order_id tinyint(5) NOT NULL default '0',
				  // PRIMARY KEY  (`id`),
				  // KEY `order_id` (`order_id`)
				// ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

		// foreach ($sql_ary as $sql)
		// {
			// $message .= '<p style="font-weight: bold;">'.$sql.'</p>';
			// $result = $db->sql_query($sql);
			// if ($result)
			// {
				// $message .= '<p style="color: yellow;">Query processed succesfully.</p>';
			// }
			// else
			// {
				// $error = true;
				// $message .= '<p style="color: red;">There was an error while processing this query.</p>';
			// }
			// $message .= '<br />';
		// }

		set_config('subscription_cycles_mod_version', $module_data['version']);
		set_config('subscription_cycles_activate', 1);
		
		$message .= sprintf($user->lang['SUBSCRIPTION_CYCLES_MOD_INSTALLED'], $module_data['version']) . adm_back_link($this->u_action) ;

	    msg_handler(E_USER_WARNING, $message, '', '');
	}

    function uninstall()
    {
    }
	
	function update($mod_version)
	{
		global $phpbb_root_path, $phpEx, $db, $user, $table_prefix;
		
		$module_data = $this->module();
		$sql_ary = array();
		$message = '';
		$db->sql_return_on_error(true);

		switch ($mod_version)
		{
			case '1.0.0':
				set_config('subscription_cycles_activate', 1);

	            $update = true;
	            // No Database changes
			break;

	         default:
				$message = ($user->lang['SUBCRIPTION_CYCLES_MOD_MANUAL']);
				$update = false;
			break;
		}
		
		if ($update)
		{
			set_config('subscription_cycles_mod_version', $module_data['version']);
			
			$message .= sprintf($user->lang['SUBCRIPTION_CYCLES_MOD_UPDATED'], $module_data['version']) . adm_back_link($this->u_action) ;
		}

	    msg_handler(E_USER_WARNING, $message, '', '');
	}
}

?>