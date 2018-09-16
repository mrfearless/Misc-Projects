<?php
/**
 * 
 * @package AFK Manager
 * @version $Id: 0.1.0
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
 * @package ACP
 */

 
/**
 * If you are adding a full file, you'll need to make your own info file too. 
 * If you are adding mode, you'll need to edit the existing info file. 
 * The info file should be placed in the includes/{MODULECLASS}/info folder, and have the same filename as the module file. 
 * Example: includes/acp/info/acp_foobar.php.
 */

class acp_afkmanager
{
	var $u_action;
	var $tpl_path;
	var $page_title;

	function main($id, $mode)
	{
        global $config, $db, $user, $auth, $template;
		global $phpbb_root_path, $phpbb_admin_path, $phpEx, $table_prefix;
		
		include($phpbb_root_path . 'includes/acp/info/acp_afkmanager.' . $phpEx);

		$user->add_lang('mods/info_acp_afkmanager');

		$action	= request_var('action', '');
		//$submode = request_var('submode', '');
		$submit = (isset($_POST['submit'])) ? true : false;

		switch($mode)
		{
			// View AFK List in ACP
			case 'afkadmin':		
		
				$forumid = $config['afkmanager_afk_posting_forumid'];

				$afkreasonsarray = array(
					'0'		=>	' ', 
					'1'		=>	'Holiday', 
					'2'		=>	'School/College/University', 
					'3'		=>	'Illness',
					'4'		=>	'Family Matter',
					'5'		=>	'Real Life Issues', 
					'6'		=>	'Work/Training', 
					'7'		=>	'Party/Celebration',
					'7'		=>	'Night Out/Social Event',
					'8'		=>	'Marriage/Engagement',
					'9'		=>	'Internet/Computer Problem',
					'10'	=>	'Inactive',			
				);						

/*				$sql = 'SELECT config_name, config_value
						FROM ' . CONFIG_TABLE . "
						WHERE '" . $db->sql_escape('afkmanager_afk_posting_enable') . "'";
				$result = $db->sql_query($sql);
				$afkmanager_afk_posting_enable = $row['config_value'];	*/			
				
				$sql = 'SELECT user_id, username, user_afkdate, user_afkstatus, user_afktopicid, user_afkreason
						FROM ' . $table_prefix . 'users
						WHERE user_afkstatus = 1 
						ORDER BY user_id ASC';
				$result = $db->sql_query($sql);
		
				//echo $config['afkmanager_afk_posting_enable'];
				
				while ($row = $db->sql_fetchrow($result))
				{
					// calc time afk
					$afktime = ucwords(ezDate(date('Y-m-d H:m:s',strtotime($row['user_afkdate'])))); 
					$afkreason = $row['user_afkreason'];
				
					$template->assign_block_vars('afkers', array(
						'AFKER_ID'				=> $row['user_id'], 
						'AFKER_LINK'			=> append_sid('../memberlist.php?mode=viewprofile&u=' . $row['user_id']),
						'AFKER_LINK'			=> append_sid('../adm/index.php?i=users&icat=13&mode=afkmanager&u=' . $row['user_id']),	
						'AFKER_NAME'			=> $row['username'],
						'AFKER_DATE'			=> date('d-m-Y',strtotime($row['user_afkdate'])),
						'AFKER_AFKTIME'			=> $afktime, 
						'AFKER_STATUS'			=> $row['user_afkstatus'],
						'AFKER_REASON'			=> ($afkreason > 0) ? $afkreasonsarray[$afkreason] : 'AFK',
						'AFKER_TOPIC_LINK'		=> append_sid('../viewtopic.php?f=' . $forumid . '&t=' . $row['user_afktopicid'], false, false, false),	
						//http://127.0.0.1/forum/adm/index.php?i=users&sid=6f1d06179d444546c94230712e33fe55&icat=13&mode=afkmanager&u=53					
						// "../viewtopic.php?f={afkers.AFKER_FORUMID}&t={afkers.AFKER_TOPICID}"
						'AFKER_TOPICID'			=> $row['user_afktopicid'],	
						'AFKER_FORUMID'			=> $forumid,
						'S_AFKER_TOPICID'		=> ($row['user_afktopicid'] == 0) ? false : true,
						'S_AUTO_POST'			=> ($config['afkmanager_afk_posting_enable']==1) ? true : false,
					));	
				}
				$db->sql_freeresult($result);
				
				//$template->assign_vars(array(
				//	'S_AUTO_POST'	=> ($afkmanager_afk_posting_enable) ? true : false,
				//));						
				
				$this->tpl_name = 'acp_afkmanager';
				$this->page_title = 'ACP_AFKMANAGER_LIST';	
				
				break;
		
			
			// View & Change AFK Settings in ACP
			case 'afksettings':

				$display_vars = array(
				    'title' => 'ACP_AFKMANAGER_SETTINGS',
				    'vars'    => array(
				        'legend1'             				=> 'ACP_AFKMANAGER_SETTINGS',
				        'afkmanager_afk_posting_enable'		=> array('lang' => 'ACP_AFKMANAGER_SETTINGS_POSTING_ENABLE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				        'afkmanager_afk_posting_forumid'	=> array('lang' => 'ACP_AFKMANAGER_SETTINGS_POSTING_FORUMID', 'validate' => 'int', 'type' => 'select', 'function' => 'forum_select', 'params' => array('{CONFIG_VALUE}', false), 'explain' => true),
				        'afkmanager_afk_posting_reply'		=> array('lang' => 'ACP_AFKMANAGER_SETTINGS_POSTING_REPLY', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true)
				    ),
				);
				
				$this->new_config = $config;
						$cfg_array = (isset($_REQUEST['config'])) ? utf8_normalize_nfc(request_var('config', array('' => ''), true)) : $this->new_config;
				$error = array();

				// We validate the complete config if whished
				validate_config_vars($display_vars['vars'], $cfg_array, $error);

				// Do not write values if there is an error
				if (sizeof($error))
				{
					$submit = false;
				}

				// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
				foreach ($display_vars['vars'] as $config_name => $null)
				{
					if (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false)
					{
						continue;
					}

					$this->new_config[$config_name] = $config_value = $cfg_array[$config_name];

					if ($submit)
					{
						set_config($config_name, $config_value);
					}
				}

				if ($submit)
				{
					trigger_error(sprintf($user->lang['ACP_AFKMANAGER_SETTINGS_SAVED'], append_sid('index.php?i=' . $id . '&mode=afksettings')));
					break ;
				}
				$this->tpl_name = 'acp_afkmanager_settings';
                $this->page_title = $user->lang['ACP_AFKMANGER_SETTINGS'];

				$template->assign_vars(array(
					'L_TITLE'			=> $user->lang[$display_vars['title']],
					'L_TITLE_EXPLAIN'	=> $user->lang[$display_vars['title'] . '_EXPLAIN'],

					'S_ERROR'			=> (sizeof($error)) ? true : false,
					'ERROR_MSG'			=> implode('<br />', $error),

					'U_ACTION'			=> $this->u_action,
				));

				// Output relevant page
				foreach ($display_vars['vars'] as $config_key => $vars)
				{
					if (!is_array($vars) && strpos($config_key, 'legend') === false)
					{
						continue;
					}

					if (strpos($config_key, 'legend') !== false)
					{
						$template->assign_block_vars('options', array(
							'S_LEGEND'		=> true,
							'LEGEND'		=> (isset($user->lang[$vars])) ? $user->lang[$vars] : $vars)
						);

						continue;
					}

					$type = explode(':', $vars['type']);

					$l_explain = '';
					if ($vars['explain'] && isset($vars['lang_explain']))
					{
						$l_explain = (isset($user->lang[$vars['lang_explain']])) ? $user->lang[$vars['lang_explain']] : $vars['lang_explain'];
					}
					else if ($vars['explain'])
					{
						$l_explain = (isset($user->lang[$vars['lang'] . '_EXPLAIN'])) ? $user->lang[$vars['lang'] . '_EXPLAIN'] : '';
					}

					$template->assign_block_vars('options', array(
						'KEY'			=> $config_key,
						'TITLE'			=> (isset($user->lang[$vars['lang']])) ? $user->lang[$vars['lang']] : $vars['lang'],
						'S_EXPLAIN'		=> $vars['explain'],
						'TITLE_EXPLAIN'	=> $l_explain,
						'CONTENT'		=> build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars),
					));
				
					unset($display_vars['vars'][$config_key]);
				}
				break;
		}	
	}
}



function forum_select($default = '', $all = false)
{
    global $db;

    //$sql_where = (!$all) ? 'WHERE style_active = 1 ' : '';$sql_where
    $sql = 'SELECT forum_id, forum_name
        FROM ' . FORUMS_TABLE . "
        ORDER BY forum_id";
    $result = $db->sql_query($sql);

    $forum_options = '';
    while ($row = $db->sql_fetchrow($result))
    {
        $selected = ($row['forum_id'] == $default) ? ' selected="selected"' : '';
        $forum_options .= '<option value="' . $row['forum_id'] . '"' . $selected . '>' . $row['forum_name'] . '</option>';
    }
    $db->sql_freeresult($result);

    return $forum_options;
}		


function ezDate($d) {
        $ts = time() - strtotime(str_replace("-","/",$d));
       
        if($ts>31536000) $val = round($ts/31536000,0).' year';
        else if($ts>2419200) $val = round($ts/2419200,0).' month';
        else if($ts>604800) $val = round($ts/604800,0).' week';
        else if($ts>86400) $val = round($ts/86400,0).' day';
        else if($ts>3600) $val = round($ts/3600,0).' hour';
        else if($ts>60) $val = round($ts/60,0).' minute';
        else $val = $ts.' second';
       
        if($val>1) $val .= 's';
        return $val;
    } 

?>