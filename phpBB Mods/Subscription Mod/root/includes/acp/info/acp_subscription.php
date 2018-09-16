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
class acp_subscription_info
{
	var $u_action;

    function module()
    {
        return array(
            'filename'		=> 'acp_subscription',
            'title'			=> 'ACP_SUBSCRIPTION_INDEX',
            'version'		=> '1.0.0',
            'modes'			=> array(
				'config'		=> array(
					'title' 		=> 'ACP_SUBSCRIPTION_SETTINGS',
					'auth' 			=> 'acl_a_board',
					'cat' 			=> array('ACP_CAT_USERS'),
				),
                'management'	=> array(
					'title'			=> 'ACP_SUBSCRIPTION_TITLE',
					'auth'			=> 'acl_a_manage_subscription',
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
			'global'	=> array('a_manage_subscription')
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
		
		$sql_ary[] = 'UPDATE ' . MODULES_TABLE . " SET module_auth = 'acl_a_manage_subscription' WHERE module_id = $module_id";

		$sql_ary[] = "CREATE TABLE {$table_prefix}subscription (
				  id tinyint(10) NOT NULL auto_increment,
				  user_id mediumint(8) unsigned NOT NULL default '0',
				  username varchar(30) collate utf8_bin NOT NULL default '',
				  reg_date varchar(32) collate utf8_bin NOT NULL,
				  payment_initial decimal(8,2) NOT NULL,
				  payment_cycle tinyint(5) NOT NULL default '0',
				  payment_amount decimal(8,2) NOT NULL,
				  payment_nextdate varchar(32) collate utf8_bin NOT NULL,
				  payment_overdue tinyint(1) NOT NULL default '0',
                  status tinyint(2) NOT NULL default '1',                             
				  order_id tinyint(5) NOT NULL default '0',
				  notes varchar(256) NULL COLLATE utf8_bin,
				  PRIMARY KEY  (`id`),
				  KEY `order_id` (`order_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

		$sql_ary[] = "CREATE TABLE {$table_prefix}subscription_cycles (
				  id tinyint(5) NOT NULL auto_increment,
				  cycle_name varchar(30) collate utf8_bin NOT NULL default '',
				  cycle_amount decimal(8,2) NOT NULL,
				  cycle_time varchar(32) collate utf8_bin NOT NULL,
				  order_id tinyint(5) NOT NULL default '0',
				  PRIMARY KEY  (`id`),
				  KEY `order_id` (`order_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";


		//$sqlinsert = array(
		//		'image_name'		=> 'icon_post_approve',
		//		'image_filename'	=> 'icon_post_approve.gif',
		//		'image_height'		=> '20',
		//		'image_width'		=> '20',
		//		'imageset_id'		=> '1',
		//	);
		//$sql_ary[]  = 'INSERT INTO ' . STYLES_IMAGESET_DATA_TABLE . ' ' . $db->sql_build_array('INSERT', $sqlinsert);

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

		set_config('subscription_mod_version', $module_data['version']);
		set_config('subscription_activate', 1);
		//set_config('medal_small_img_width', 0);
		//set_config('medal_small_img_ht', 0);
		//set_config('medal_profile_across', 5);
		//set_config('medal_display_topic', 0);
		//set_config('medal_topic_row', 1);
		//set_config('medal_topic_col', 1);
		//set_config('medal_profile_across', 0);

		$message .= sprintf($user->lang['SUBSCRIPTION_MOD_INSTALLED'], $module_data['version']) . adm_back_link($this->u_action) ;

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
			//case '0.9.11':
			//case '0.10.0':
			//case '0.10.1':
			case '1.0.0':
				set_config('subscription_activate', 1);

	            $update = true;
	            // No Database changes
			break;

	         default:
				$message = ($user->lang['SUBCRIPTION_MOD_MANUAL']);
				$update = false;
			break;
		}
		
		if ($update)
		{
			set_config('subscription_mod_version', $module_data['version']);
			
			$message .= sprintf($user->lang['SUBCRIPTION_MOD_UPDATED'], $module_data['version']) . adm_back_link($this->u_action) ;
		}

	    msg_handler(E_USER_WARNING, $message, '', '');
	}
}

?>