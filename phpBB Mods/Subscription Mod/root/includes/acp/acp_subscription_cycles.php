<?php
/***************************************************************************
*
* @package Medals Mod for phpBB3
* @version $Id: medals.php,v 0.7.0 2008/01/14 Gremlinn$
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
* @package acp
*/
class acp_subscription_cycles
{
	var $u_action;
	var $new_config;
	
	function main($id, $mode)
    {
	
        global $config, $db, $user, $auth, $template;
		global $phpbb_root_path, $phpbb_admin_path, $phpEx, $table_prefix;

		include($phpbb_root_path . 'includes/acp/info/acp_subscription_cycles.' . $phpEx);

		$user->add_lang('acp/modules');

		$action	= request_var('action', '');
		$submode = request_var('submode', '');
		$submit = (isset($_POST['submit'])) ? true : false;

		$version = new acp_subscription_cycles_info();
		
		$subscription_cycles_mod_version = $version->module();
		$subscription_cycles_mod_version = $subscription_cycles_mod_version['version'];
		
		if (!isset($config['subscription_cycles_mod_version']))
		{
			$version->install();
		}
		else if (version_compare($config['subscription_cycles_mod_version'], $subscription_cycles_mod_version, '<'))
		{
			$version->update($config['subscription_cycles_mod_version']);
		}

		switch($mode)
		{
			case 'management':
				$sql = 'SELECT *
					FROM ' . $table_prefix . 'subscription_cycles
					ORDER BY order_id ASC';
				$result = $db->sql_query($sql);
				$cyles = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$cycles[$row['id']] = array( 
						'cyclename' 	=> $row['cycle_name'], 
						'cycleamount'	=> $row['cycle_amount'], 
						'cycletime'	=> $row['cycle_time'], 
						'cyleid'		=> $row['id'],
						'order_id'	=> $row['order_id'],
					);
				}
				$db->sql_freeresult($result);
				
				$cycleid = request_var('cycleid', -1);
				$move_id = request_var('moveid', -1);
				$move_type = request_var('movetype', -1);
				$submode = request_var('submode', '');
				
				$submode = '';
				// if (isset($_POST['addcycle']))
				// {
					// $submode = 'addcycle';
				// }
				// else if (isset($_POST['addsubscription']))
				// {
					// $submode = 'addsubscription';
				// }
				// else if (isset($_POST['cancelcycle']))
				// {
					// $submode = '';
				// }
				// else if (isset($_POST['cancelsubscription']))
				// {
					// $submode = 'subscriptionview';
				// }
				// break;
		}

		switch($submode)
		{
			// case 'move':
				// if ($move_type)
				// {
					// $swap_diff = 1;
				// }
				// else
				// {
					// $swap_diff = -1;
				// }
				// $sql = 'UPDATE ' . $table_prefix . 'medals
							// SET order_id = ' . $medals[$move_id]['order_id'] . '
							// WHERE order_id = ' . $medals[$move_id]['order_id'] . '+' . $swap_diff . '
								// AND parent = ' . $cat_id;
				// $db->sql_query($sql);
				// $sql = 'UPDATE ' . $table_prefix . 'medals
							// SET order_id = ' . $medals[$move_id]['order_id'] . '+' . $swap_diff . '
							// WHERE id = ' . $move_id;
				// $db->sql_query($sql);
				// $sql = 'SELECT *
						// FROM ' . $table_prefix . 'medals
						// ORDER BY order_id ASC';
				// $result = $db->sql_query($sql);
				// $subscriptions = array();
				// while ($row = $db->sql_fetchrow($result))
				// {
					// $subscriptions[$row['id']] = array( 
						// 'name' 		=> $row['name'], 
						// 'image'	 	=> $row['image'], 
						// 'device'	=> $row['device'], 
						// 'dynamic'	=> $row['dynamic'],
						// 'number'	=> $row['number'],
						// 'points'	=> $row['points'],
						// 'parent' 	=> $row['parent'], 
						// 'id'		=> $row['id'],
						// 'nominated'	=> $row['nominated'],
						// 'order_id'	=> $row['order_id'],
					// );
				// }
				// $db->sql_freeresult($result);
				
			// case 'subscriptionview':
			
				// if ($subscription_id < 0)
				// {
					// trigger_error('NO_SUBSCRIPTION_ID');
				// }
				
                // //$this->tpl_name = 'acp_medals_cat';
                // $this->page_title = $user->lang['ACP_SUBSCRIPTION_INDEX'];
				// foreach($subscriptions as $key => $value)
				// {
					// //if ($value['parent'] != $cat_id)
					// //{
					// //	continue;
					// //}
					// $template->assign_block_vars('subscription', array(
						// 'U_EDIT'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=editsubscription&subscriptionid=' . $value['subscriptionid'] ),
						// 'U_DELETE'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=deletesubscription&subscriptionid=' . $value['subscriptionid'] ,
						// 'U_MOVE_UP'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=move&moveid=' . $value['subscriptionid'] . '&movetype=0&subscriptionid=' . $id),
						// 'U_MOVE_DOWN'		=> append_sid('index.php?i=' . $id . '&mode=management&submode=move&moveid=' . $value['subscriptionid'] . '&movetype=1&subscriptionid=' . $id),
						// //'MEDAL_NOMINATED'	=> ($value['nominated']) ? $user->lang['YES'] : $user->lang['NO'],
						// //'MEDAL_NUMBER'		=> $value['number'],
						// //'MEDAL_IMAGE'		=> '<img src="' . $phpbb_root_path . 'images/medals/' . $value['image'] . '" title="' . $value['name'] . '" style="max-width: 60px; max-height: 60px;"/>',
						// //'MEDAL_TITLE'		=> $value['name'],
						// 'S_IS_SUBSCRIPTION'	=> true,
					// ));
				// }
                // //$template->assign_vars(array(
				// //	'CAT_TITLE' => $cats[$cat_id]['name'],
				// ));
                // break;
			
			case 'deletecycle':
				if (!isset($_POST['confirm']))
				{
					trigger_error('ACP_CONFIRM_MSG_1');
				}
				if ($cycleid < 0)
				{
					trigger_error('ACP_NO_SUBSCRIPTION_CYCLE_ID');
				}

				$sql = 'DELETE FROM ' . $table_prefix . 'subscription_cycles WHERE id = ' . $cycleid;
				$db->sql_query($sql);
				trigger_error(sprintf($user->lang['ACP_SUBSCRIPTION_CYCLE_DELETE_GOOD'], append_sid('index.php?i=' . $id . '&mode=management&submode=cycleview&cycleid=' . $cycleid)));
				break;
			
			// case 'editsubscription':
				
				// if ($subcription_id < 0)
				// {
					// trigger_error('NO_SUBCRIPTION_ID');
				// }
				// //if ($cat_id < 0)
				// //{
				// //	trigger_error('NO_CAT_ID');
				// //}
				
                // $this->tpl_name = 'acp_subscription_new';
                // $this->page_title = $user->lang['ACP_SUBSCRIPTION_INDEX'];
				
				// $dir = $phpbb_root_path . 'images/medals/';
				// $options = '<option value=""></option>';
				// if ($dh = opendir($dir))
				// {
					// while (($file = readdir($dh)) !== false)
					// {
						// if (strlen($file) >= 3 && ( strpos($file, '.gif',1) || strpos($file, '.jpg',1) || strpos($file, '.png',1) ))
						// {
							// if ($medals[$medal_id]['image'] == $file)
							// {
								// $options .= '<option value="' . $file . '" selected="selected">' . $file . '</option>';
							// }
							// else
							// {
								// $options .= '<option value="' . $file . '">' . $file . '</option>';
							// }
						// }
					// }
					// closedir($dh);
				// }
				
				// $options2 = '';
				// foreach($cats as $key => $value)
				// {
					// if ($medals[$medal_id]['parent'] == $value['id'])
					// {
						// $options2 .= '<option value="' . $value['id'] . '" selected="selected">' . $value['name'] . '</option>';
					// }
					// else
					// {
						// $options2 .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
					// }
				// }

                // $template->assign_vars(array(
					// 'MEDAL_TITLE'			=> $user->lang['ACP_MEDAL_TITLE_EDIT'],
					// 'MEDAL_TEXT'			=> $user->lang['ACP_MEDAL_TEXT_EDIT'],
					// 'NAME_VALUE'			=> $medals[$medal_id]['name'],
					// 'DESC_VALUE'			=> $medals[$medal_id]['description'],
					// 'MEDAL_IMAGE'			=> '<br /><img src="' . $phpbb_root_path . 'images/medals/' . $medals[$medal_id]['image'] . '" alt="" style="max-width: 60px; max-height: 60px;" />',
					// 'IMAGE_OPTIONS'			=> $options,
					// 'DYNAMIC_CHECKED_NO'	=> ($medals[$medal_id]['dynamic']) ? '' : 'checked="checked"',
					// 'DYNAMIC_CHECKED_YES'	=> ($medals[$medal_id]['dynamic']) ? 'checked="checked"' : '',
					// 'DEVICE_VALUE'			=> $medals[$medal_id]['device'],
					// 'NUMBER_VALUE'			=> $medals[$medal_id]['number'],
					// 'POINTS_VALUE'			=> $medals[$medal_id]['points'],
					// 'PARENT_OPTIONS'		=> $options2,
					// 'NOMINATED_CHECKED_NO'	=> ($medals[$medal_id]['nominated']) ? '' : 'checked="checked"',
					// 'NOMINATED_CHECKED_YES'	=> ($medals[$medal_id]['nominated']) ? 'checked="checked"' : '',
					// 'MEDAL_ACTION'			=> 'changemedal',
					// 'MEDAL_SUBMIT'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=editmedalsql&medalid=' . $medal_id . '&catid=' . $cat_id),
					// 'PHPBB_ROOT_PATH'		=> $phpbb_root_path,
				// ));
				// break;
			
			// case 'editsubscriptionsql':
				
				// $this_id = $medals[$medal_id]['order_id'];
				// if ($medals[$medal_id]['parent'] != $_POST['parent'])
				// {
					// $this_id = 1;
					// foreach ($medals as $key => $value)
					// {
						// if ($value['parent'] == $_POST['parent'])
						// {
							// $this_id = $value['order_id'] + 1;
						// }
					// }
				// }
				// $sql = 'UPDATE ' . $table_prefix . 'medals SET ' . $db->sql_build_array('UPDATE', array(
					// 'name'		=> utf8_normalize_nfc(request_var('name', '', true)),
					// 'description' => utf8_normalize_nfc(request_var('description', '', true)),
					// 'image'		=> $_POST['image'],
					// 'device'	=> utf8_normalize_nfc(request_var('device', '', true)),
					// 'dynamic'	=> $_POST['dynamic'],
					// 'number'	=> $_POST['number'],
					// 'parent'	=> $_POST['parent'],
					// 'nominated'	=> $_POST['nominated'],
					// 'points'	=> $_POST['points'],
					// 'order_id'	=> $this_id,
				// )) . ' WHERE id = ' . $medal_id;
				// $db->sql_query($sql);
				// foreach ($cats as $key => $value)
				// {
					// if ($value['id'] == $_POST['parent'])
					// {
						// $newcat = $key;
						// break;
					// }
				// }	
				// trigger_error(sprintf($user->lang['ACP_MEDAL_EDIT_GOOD'], append_sid('index.php?i=' . $id . '&mode=management&submode=catview&catid=' . $newcat)));
				// break;
				
			// case 'addsubscription':
			
				// if (empty($_POST['subscription_name']))
				// {
					// trigger_error(sprintf($user->lang['ACP_SUBSCRIPTION_ADD_FAIL'], append_sid('index.php?i=' . $id . '&mode=management')));
				// }
				// $this_id = 1;
				// foreach ($cats as $key => $value)
				// {
					// $this_id++;
				// }
				// $sql = 'INSERT INTO ' . $table_prefix . 'subscription ' . $db->sql_build_array('INSERT', array(
					// 'name'		=> utf8_normalize_nfc(request_var('subscription_name', '', true)),
					// 'order_id'	=> $this_id,
				// ));
				// $db->sql_query($sql);
				// trigger_error(sprintf($user->lang['ACP_SUBSCRIPTION_ADD_GOOD'], append_sid('index.php?i=' . $id . '&mode=management')));
				// break;
				
			// case 'addsubscriptionsql':
				
				// $this_id = 1;
				// foreach ($subscriptions as $key => $value)
				// {
					// if ($value['parent'] == $_POST['parent'])
					// {
						// $this_id = $value['order_id'] + 1;
					// }
				// }
				// $sql = 'INSERT INTO ' . $table_prefix . 'subscription ' . $db->sql_build_array('INSERT', array(
					// 'name'		=> utf8_normalize_nfc(request_var('name', '', true)),
					// 'image'		=> $_POST['image'],
					// 'device'	=> utf8_normalize_nfc(request_var('device', '', true)),
					// 'dynamic'	=> $_POST['dynamic'],
					// 'number'	=> $_POST['number'],
					// 'parent'	=> $_POST['parent'],
					// 'nominated'	=> $_POST['nominated'],
					// 'order_id'	=> $this_id,
					// 'description' => utf8_normalize_nfc(request_var('description', '', true)),
					// 'points'	=> $_POST['points'],
				// ));
				// $db->sql_query($sql);
				// foreach ($cats as $key => $value)
				// {
					// if ($value['id'] == $_POST['parent'])
					// {
						// $newcat = $key;
						// break;
					// }
				// }
				// trigger_error(sprintf($user->lang['ACP_MEDAL_ADD_GOOD'], append_sid('index.php?i=' . $id . '&mode=management&submode=catview&catid=' . $newcat)));
				// break;
				
			// case 'addmedal':
			
				// if ($cat_id < 0)
				// {
					// trigger_error('ACP_NO_CAT_ID');
				// }
				
                // $this->tpl_name = 'acp_medals_new';
                // $this->page_title = $user->lang['ACP_MEDALS_INDEX'];
				
				// $dir = $phpbb_root_path . 'images/medals/';
				// $options = '<option value=""></option>';
				// if ($dh = opendir($dir))
				// {
					// while (($file = readdir($dh)) !== false)
					// {
						// if (strlen($file) >= 3 && ( strpos($file, '.gif',1) || strpos($file, '.jpg',1) || strpos($file, '.png',1) ))
						// {
							// $options .= '<option value="' . $file . '">' . $file . '</option>';
						// }
					// }
					// closedir($dh);
				// }
				
				// $options2 = '';
				// foreach($cats as $key => $value)
				// {
					// if ($key == $cat_id)
					// {
						// $options2 .= '<option value="' . $value['id'] . '" selected="selected">' . $value['name'] . '</option>';
					// }
					// else
					// {
						// $options2 .= '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
					// }
				// }

                // $template->assign_vars(array(
					// 'MEDAL_TITLE'			=> $user->lang['ACP_MEDAL_TITLE_ADD'],
					// 'MEDAL_TEXT'			=> $user->lang['ACP_MEDAL_TEXT_ADD'],
					// 'NAME_VALUE'			=> utf8_normalize_nfc((isset($_POST['medal_name'])) ? request_var('medal_name', '', true) : ''),
					// 'IMAGE_OPTIONS'			=> $options,
					// 'PARENT_OPTIONS'		=> $options2,
					// 'DYNAMIC_CHECKED_NO'	=> 'checked="checked"',
					// 'DEVICE_VALUE'			=> 'device',
					// 'NUMBER_VALUE'			=> 1,
					// 'POINTS_VALUE'			=> 0,
					// 'NOMINATED_CHECKED_NO'	=> 'checked="checked"',
					// 'MEDAL_ACTION'			=> 'newmedal',
					// 'MEDAL_SUBMIT'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=addmedalsql&catid=' . $cat_id),
					// 'PHPBB_ROOT_PATH'		=> $phpbb_root_path,
				// ));
				// break;
			
			// case 'movesubscription':
			
				// if ($move_type)
				// {
					// $swap_diff = 1;
				// }
				// else
				// {
					// $swap_diff = -1;
				// }
				// $sql = 'UPDATE ' . $table_prefix . 'medals_cats
							// SET order_id = ' . $cats[$move_id]['order_id'] . '
							// WHERE order_id = ' . $cats[$move_id]['order_id'] . '+' . $swap_diff;
				// $db->sql_query($sql);
				// $sql = 'UPDATE ' . $table_prefix . 'medals_cats
							// SET order_id = ' . $cats[$move_id]['order_id'] . '+' . $swap_diff . '
							// WHERE id = ' . $cats[$move_id]['id'];
				// $db->sql_query($sql);
				// $submode = '';
				// $sql = 'SELECT *
							// FROM ' . $table_prefix . 'medals_cats
							// ORDER BY order_id ASC';
				// $result = $db->sql_query($sql);
				// $cats = array();
				// while ($row = $db->sql_fetchrow($result))
				// {
					// $cats[$row['id']] = array( 
						// 'name' 		=> $row['name'], 
						// 'id'		=> $row['id'],
						// 'order_id'	=> $row['order_id'],
					// );
				// }
				// $db->sql_freeresult($result);
				// break;
			
			case 'editcycle':
				
                $this->tpl_name = 'acp_subscription_cycle_edit';
                $this->page_title = $user->lang['ACP_SUBSCRIPTION_INDEX'];

                $template->assign_vars(array(
					'SUBSCRIPTION_TITLE'		=> $user->lang['ACP_SUBSCRIPTION_TITLE'],
					'SUBSCRIPTION_TEXT'			=> $user->lang['ACP_SUBSCRIPTION_TEXT'],
					'SUBSCRIPTION_LEGEND'		=> $user->lang['ACP_SUBSCRIPTION_LEGEND'],
					'NAME_VALUE'				=> $cats[$cat_id]['name'],
					'NAME_TITLE'				=> $user->lang['ACP_NAME_TITLE_SUBSCRIPTION'],
					'SUBSCRIPTION_ACTION'		=> 'changesubscription',
					'SUBSCIPTION_SUBMIT'		=> append_sid('index.php?i=' . $id . '&mode=management&submode=editcyclesql&subscitptionid=' . $subscription_id),
				));
				break;
			
			case 'editcyclesql':
				$sql = 'UPDATE ' . $table_prefix . 'subscription_cyles 
						SET ' . $db->sql_build_array('UPDATE', array(
									'cycle_name'		=> utf8_normalize_nfc(request_var('cyclename', '', true)),
								)) . '
						WHERE id = ' . $cycles[$cycleid]['cycleid'];
				$db->sql_query($sql);
				trigger_error(sprintf($user->lang['ACP_SUBSCRIPTION_CYCLE_EDIT_GOOD'], append_sid('index.php?i=' . $id . '&mode=management')));
				break;
			

		}
		if (empty($submode))
		{
			switch($mode)
			{
				case 'management':
					$this->tpl_name = 'acp_subscription_cycles';
					$this->page_title = $user->lang['ACP_SUBSCRIPTION_CYCLES_INDEX'];
					foreach($cycles as $key2 => $value2)
					{
						$template->assign_block_vars('cycles', array(
							'U_EDIT'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=editcycle&cycleid=' . $value2['id']),
							'U_DELETE'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=deletecycle&cycleid=' . $value2['id']),
							'U_MOVE_UP'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=movecycle&movetype=0&moveid=' . $value2['id']),
							'U_MOVE_DOWN'		=> append_sid('index.php?i=' . $id . '&mode=management&submode=movecycle&movetype=1&moveid=' . $value2['id']),
							'SUBSCRIPTION_CYCLES_TITLE'		=> '<a href="' . append_sid('index.php?i=' . $id . '&mode=management&submode=cycleview&cycleid=' . $value2['cycleid']) . '" class="title">' . $value2['cyclename'] . '</a>',
							'SUBSCRIPTION_CYCLES_AMOUNT' 		=> $value2['cycleamount'],
							'SUBSCRIPTION_CYCLES_TIME' 	=> $value2['cycletime'],
						));
					}
					break;

					default:
						trigger_error('NO_MODE', E_USER_ERROR);
					break;
			}
		}
		$template->assign_vars(array(
			'U_SUBSCRIPTION_CYCLES_INDEX'  => append_sid('index.php?i=' . $id . '&mode=management'),
		));
    }
}
?>