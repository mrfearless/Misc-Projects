<?php
/**
 * 
 * @package AFK Manager
 * @version $Id: 0.3.0
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

				$afkreasoncategoriesarray = array(
					'0'		=>	' ', 
					'1'		=>	'Holiday', 
					'2'		=>	'School/College/University', 
					'3'		=>	'Illness',
					'4'		=>	'Family Matter',
					'5'		=>	'Real Life Issues', 
					'6'		=>	'Work/Training', 
					'7'		=>	'Party/Celebration',
					'8'		=>	'Night Out/Social Event',
					'9'		=>	'Marriage/Engagement',
					'10'	=>	'Internet/Computer Problem',
					'11'	=>	'Inactive',			
				);						

/*				$sql = 'SELECT config_name, config_value
						FROM ' . CONFIG_TABLE . "
						WHERE '" . $db->sql_escape('afkmanager_afk_posting_enable') . "'";
				$result = $db->sql_query($sql);
				$afkmanager_afk_posting_enable = $row['config_value'];	*/			
				
				$sql = 'SELECT user_id, username, user_afkdate, user_afkstatus, user_afktopicid, user_afkreasoncat, user_afkreason
						FROM ' . $table_prefix . 'users
						WHERE user_afkstatus = 1 
						ORDER BY user_id ASC';
				$result = $db->sql_query($sql);
		
				//echo $config['afkmanager_afk_posting_enable'];
				
				while ($row = $db->sql_fetchrow($result))
				{
					// calc time afk
					$afktime = ucwords(ezDate(date('Y-m-d H:m:s',strtotime($row['user_afkdate'])))); 
					$afkreasoncategory = $row['user_afkreasoncat'];
					$afkreason = $row['user_afkreason'];
				
					$template->assign_block_vars('afkers', array(
						'AFKER_ID'					=> $row['user_id'], 
						'AFKER_LINK'				=> append_sid('../memberlist.php?mode=viewprofile&u=' . $row['user_id']),
						//'AFKER_LINK'				=> append_sid('../adm/index.php?i=users&icat=13&mode=afkmanager&u=' . $row['user_id']),	
						'AFKER_NAME'				=> $row['username'],
						'AFKER_DATE'				=> date('d-m-Y',strtotime($row['user_afkdate'])),
						'AFKER_AFKTIME'				=> $afktime, 
						'AFKER_STATUS'				=> $row['user_afkstatus'],
						'AFKER_REASON'				=> !empty($afkreason) ? $afkreason : '-',
						'AFKER_REASONCATEGORIES'	=> ($afkreasoncategory > 0) ? $afkreasoncategoriesarray[$afkreasoncategory] : 'AFK',
						'AFKER_TOPIC_LINK'			=> append_sid('../viewtopic.php?f=' . $forumid . '&t=' . $row['user_afktopicid'], false, false, false),	
						'AFKER_TOPICID'				=> $row['user_afktopicid'],	
						'AFKER_FORUMID'				=> $forumid,
						'S_AFKER_TOPICID'			=> ($row['user_afktopicid'] == 0) ? false : true,
						'S_AUTO_POST'				=> ($config['afkmanager_afk_posting_enable']==1) ? true : false,
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
				        'legend1'             					=> 'ACP_AFKMANAGER_SETTINGS',
				        'afkmanager_afk_posting_enable'			=> array('lang' => 'ACP_AFKMANAGER_SETTINGS_POSTING_ENABLE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				        'afkmanager_afk_posting_forumid'		=> array('lang' => 'ACP_AFKMANAGER_SETTINGS_POSTING_FORUMID', 'validate' => 'int', 'type' => 'select', 'function' => 'forum_select', 'params' => array('{CONFIG_VALUE}'), 'explain' => true),
						'afkmanager_afk_posting_topicicon'		=> array('lang' => 'ACP_AFKMANAGER_SETTINGS_POSTING_TOPICICON', 'validate' => 'int', 'type' => 'select', 'function' => 'topicicon_select', 'params' => array('{CONFIG_VALUE}'), 'explain' => true),
				        'afkmanager_afk_posting_reply'			=> array('lang' => 'ACP_AFKMANAGER_SETTINGS_POSTING_REPLY', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'afkmanager_afk_posting_footer'			=> array('lang' => 'ACP_AFKMANAGER_SETTINGS_POSTING_FOOTER', 'validate' => 'string', 'type' => 'textarea', 'explain' => true),
				        'legend2'								=> 'ACP_AFKMANAGER_SETTINGS_USER',
				        'afkmanager_afk_show_status_viewtopic'	=> array('lang' => 'ACP_AFKMANAGER_SETTINGS_SHOW_STATUS_VIEWTOPIC', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'afkmanager_afk_show_status_viewprofile'=> array('lang' => 'ACP_AFKMANAGER_SETTINGS_SHOW_STATUS_VIEWPROFILE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'afkmanager_afk_show_reminder_board'	=> array('lang' => 'ACP_AFKMANAGER_SETTINGS_SHOW_REMINDER_BOARD', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				        
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



function forum_select($default = '')
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


function topicicon_select($default = '')
{    
	global $db;

    $sql = 'SELECT icons_id, icons_url
        FROM ' . ICONS_TABLE . "
        ORDER BY icons_id";
    $result = $db->sql_query($sql);

    //$topicicon_options = '';
    $topicicon_options = '<option value="0"' . (($row['icons_id'] == $default) ? ' selected="selected"' : '') . '>' . 'No Topic Icon' . '</option>';
    while ($row = $db->sql_fetchrow($result))
    {
        $selected = ($row['icons_id'] == $default) ? ' selected="selected"' : '';
        $topicicon_options .= '<option value="' . $row['icons_id'] . '"' . $selected . '>' . $row['icons_url'] . '</option>';
    }
    $db->sql_freeresult($result);

    return $topicicon_options;
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