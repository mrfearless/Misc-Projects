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
class acp_subscription
{
	var $u_action;
	var $new_config;
	
	function main($id, $mode)
    {
	
        global $config, $db, $user, $auth, $template;
		global $phpbb_root_path, $phpbb_admin_path, $phpEx, $table_prefix;

		include($phpbb_root_path . 'includes/acp/info/acp_subscription.' . $phpEx);

		$user->add_lang('acp/modules');

		$action	= request_var('action', '');
		$submode = request_var('submode', '');
		$submit = (isset($_POST['submit'])) ? true : false;

		$version = new acp_subscription_info();
		
		$subscription_mod_version = $version->module();
		$subscription_mod_version = $subscription_mod_version['version'];
		
		if (!isset($config['subscription_mod_version']))
		{
			$version->install();
		}
		else if (version_compare($config['subscription_mod_version'], $subscription_mod_version, '<'))
		{
			$version->update($config['subscription_mod_version']);
		}

		switch($mode)
		{
			case 'config':
				$display_vars = array(
					'title'	=> 'ACP_SUBSCRIPTION_INDEX',
					'vars'	=> array(
						'legend1'				=> 'ACP_SUBSCRIPTION_CONF_SETTINGS',
						'subscription_active' 	=> array('lang' => 'ACP_SUBSCRIPTION_ACTIVATE',		'validate' => 'int',	'type' => 'radio:yes_no', 'explain' => false),
						//'medal_small_img_width' => array('lang' => 'ACP_MEDALS_SM_IMG_WIDTH',	'validate' => 'int',	'type' => 'text:3:3', 'explain' => true),
						//'medal_small_img_ht'	=> array('lang' => 'ACP_MEDALS_SM_IMG_HT',		'validate' => 'int',	'type' => 'text:3:3', 'explain' => true),
						//'medal_profile_across'	=> array('lang' => 'ACP_MEDALS_PROFILE_ACROSS', 'validate' => 'int',	'type' => 'text:2:2', 'explain' => true),
						//'legend2'				=> 'ACP_MEDALS_VT_SETTINGS',
						//'medal_display_topic'	=> array('lang' => 'ACP_MEDALS_TOPIC_DISPLAY',	'validate' => 'bool',	'type' => 'radio:yes_no', 'explain' => false),
						//'medal_topic_row' 		=> array('lang' => 'ACP_MEDALS_TOPIC_ROW',		'validate' => 'int',	'type' => 'text:1:1', 'explain' => true),
						//'medal_topic_col'		=> array('lang' => 'ACP_MEDALS_TOPIC_COL',		'validate' => 'int',	'type' => 'text:1:1', 'explain' => true),
					)
				);
				if (isset($display_vars['lang']))
				{
					$user->add_lang($display_vars['lang']);
				}
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
					trigger_error(sprintf($user->lang['ACP_SUBSCRIPTION_CONF_SAVED'], append_sid('index.php?i=' . $id . '&mode=config')));
					break ;
				}
				$this->tpl_name = 'acp_subscription_config';
                $this->page_title = $user->lang['ACP_SUBSCRIPTION_INDEX'];

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

			case 'management':
				
				// SELECT 
  // phpbb_subscription.id,
  // phpbb_subscription.user_id,
  // phpbb_subscription.username,
  // phpbb_subscription.reg_date,
  // phpbb_subscription.payment_initial,
  // phpbb_subscription.payment_cycle,
  // phpbb_subscription.payment_amount,
  // phpbb_subscription.payment_nextdate,
  // phpbb_subscription.payment_overdue,
  // phpbb_subscription.`status`,
  // phpbb_subscription.order_id,
  // phpbb_subscription.notes,
  // phpbb_subscription_cycles.id,
  // phpbb_subscription_cycles.cycle_name,
  // phpbb_subscription_cycles.cycle_amount
// FROM
  // phpbb_subscription_cycles
  // INNER JOIN phpbb_subscription ON (phpbb_subscription_cycles.id = phpbb_subscription.id)
				
				
				
				$sql = 'SELECT *
					FROM ' . $table_prefix . 'subscription
					ORDER BY order_id ASC';
				$result = $db->sql_query($sql);
				$subscription = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$subscription[$row['id']] = array( 
						'userid' 			=> $row['user_id'], 
						'username' 			=> $row['username'], 
						'regdate' 			=> $row['reg_date'], 
						'paymentinitial'	=> $row['payment_initial'], 
						'paymentcycle' 		=> $row['payment_cycle'], 
						'paymentamount'		=> $row['payment_amount'],
						'paymentnextdate'	=> $row['payment_nextdate'],
						'paymentoverdue'	=> $row['payment_overdue'],
						'status' 			=> $row['status'], 
						'subscriptionid'	=> $row['id'],
						'order_id'			=> $row['order_id'],
					);
				}
				$db->sql_freeresult($result);
				
				$sql = 'SELECT *
					FROM ' . $table_prefix . 'subscription_cycles
					ORDER BY order_id ASC';
				$result = $db->sql_query($sql);
				$cycles = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$cycles[$row['id']] = array( 
						'cyclename' 	=> $row['cycle_name'], 
						'cycleamount'	=> $row['cycle_amount'], 
						'cycletime'	=> $row['cycle_time'], 
						'cycleid'		=> $row['id'],
						'order_id'	=> $row['order_id'],
					);
				}
				$db->sql_freeresult($result);
				
				$cycle_id = request_var('cycleid', -1);
				$subscription_id = request_var('subscriptionid', -1);
				$move_id = request_var('moveid', -1);
				$move_type = request_var('movetype', -1);
				$submode = request_var('submode', '');
				
				//$submode = '';
				// if (isset($_POST['addcycle']))
				// {
					// $submode = 'addcycle';
				// }
				if (isset($_POST['addsubscription']))
				{
					$submode = 'addsubscription';
				}
				// else if (isset($_POST['cancelcycle']))
				// {
					// $submode = '';
				// }
				else if (isset($_POST['cancelsubscription']))
				{
					$submode = 'subscriptionview';
				}
				break;
		}

		switch($submode)
		{
			case 'subscriptionview':
			
				// if ($subscription_id < 0)
				// {
					// trigger_error('NO_SUBSCRIPTION_ID');
				// }
				
				$this->tpl_name = 'acp_subscription';
				$this->page_title = $user->lang['ACP_SUBSCRIPTION_INDEX'];
				foreach($subscription as $key2 => $value2)
				{
					$template->assign_block_vars('subscription', array(
						'U_EDIT'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=editsubscription&subscriptionid=' . $value2['subscriptionid']),
						'U_DELETE'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=deletesubscription&subscriptionid=' . $value2['subscriptionid']),
						'U_MOVE_UP'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=movesubscription&movetype=0&moveid=' . $value2['subscriptionid']),
						'U_MOVE_DOWN'		=> append_sid('index.php?i=' . $id . '&mode=management&submode=movesubscription&movetype=1&moveid=' . $value2['subscriptionid']),
						//'MEDAL_IMAGE'		=> '<img src="images/icon_subfolder.gif" alt="' . $user->lang['ACP_MEDAL_LEGEND_CAT'] . '" title="' . $user->lang['ACP_MEDAL_LEGEND_CAT'] . '" />',
						'SUBSCRIPTION_TITLE'		=> '<a href="' . append_sid('index.php?i=' . $id . '&mode=management&submode=editsubscription&subscriptionid=' . $value2['subscriptionid']) . '" class="title">' . $value2['username'] . '</a>',
						'SUBSCRIPTION_REGDATE' 		=> $value2['regdate'],
						'SUBSCRIPTION_INITIALPAY' 	=> $value2['paymentinitial'],
						'SUBSCRIPTION_CYCLE'		=> $value2['paymentcycle'],
						'SUBSCRIPTION_AMOUNT'		=> $value2['paymentamount'],
						'SUBSCRIPTION_NEXTDATE'		=> $value2['paymentnextdate'],
						'SUBSCRIPTION_OVERDUE'		=> $value2['paymentoverdue'],
						'SUBSCRIPTION_STATUS'		=> $value2['status'],
					));
				}
				break;

				
			case 'deletesubscription':
				if (!isset($_POST['confirm']))
				{
					trigger_error('ACP_CONFIRM_MSG_1');
				}
				if ($subscription_id < 0)
				{
					trigger_error('ACP_NO_SUBSCRIPTION_ID');
				}

				$sql = 'DELETE FROM ' . $table_prefix . 'subscription WHERE id = ' . $subscription_id;
				$db->sql_query($sql);

				trigger_error(sprintf($user->lang['ACP_SUBSCRIPTION_DELETE_GOOD'], append_sid('index.php?i=' . $id . '&mode=management&submode=subscriptionview&subscriptionid=' . $subscription_id)));
				break;
			
			case 'editsubscription':
				
				if ($subcription_id < 0)
				{
					trigger_error('ACP_NO_SUBSCRIPTION_ID');
				}
	
				
                $this->tpl_name = 'acp_subscription_new';
                $this->page_title = $user->lang['ACP_SUBSCRIPTION_INDEX'];
				
				
				$options2 = '';
				foreach($cycles as $key => $value)
				{
					if ($subscription[$subscription_id]['paymentcycle'] == $value['cycleid'])
					{
						$options2 .= '<option value="' . $value['cycleid'] . '" selected="selected">' . $value['cyclename'] . '</option>';
					}
					else
					{
						$options2 .= '<option value="' . $value['cycleid'] . '">' . $value['cyclename'] . '</option>';
					}
				}
				
				$options3 .= '<option value="1">Active</option><option value="2">Cancelled</option><option value="3">On Hold</option><option value="4">Overdue</option><option value="5">-</option>';
				
				
				//$options3 = '<option value="1">Microsoft</option><option value="2">Google</option><option value="3">Apple</option>';

				
				
                $template->assign_vars(array(
					'SUBCRIPTION_TITLE'			=> $user->lang['ACP_SUBSCRIPTION_TITLE_EDIT'],
					'SUBSCRIPTION_TEXT'			=> $user->lang['ACP_SUBSCRIPTION_TEXT_EDIT'],
					'NAME_VALUE'			=> $subscription[$subscription_id]['username'],
					// 'DESC_VALUE'			=> $medals[$medal_id]['description'],
					// 'MEDAL_IMAGE'			=> '<br /><img src="' . $phpbb_root_path . 'images/medals/' . $medals[$medal_id]['image'] . '" alt="" style="max-width: 60px; max-height: 60px;" />',
					// 'IMAGE_OPTIONS'			=> $options,
					// 'DYNAMIC_CHECKED_NO'	=> ($medals[$medal_id]['dynamic']) ? '' : 'checked="checked"',
					// 'DYNAMIC_CHECKED_YES'	=> ($medals[$medal_id]['dynamic']) ? 'checked="checked"' : '',
					// 'DEVICE_VALUE'			=> $medals[$medal_id]['device'],
					// 'NUMBER_VALUE'			=> $medals[$medal_id]['number'],
					// 'POINTS_VALUE'			=> $medals[$medal_id]['points'],
					'SUBSCRIPTION_REGDATE' 		=> $subscription[$subscription_id]['regdate'],
					'SUBSCRIPTION_INITIALPAY' 	=> $subscription[$subscription_id]['paymentinitial'],
					'SUBSCRIPTION_CYCLE'		=> $options2,
					'SUBSCRIPTION_AMOUNT'		=> $subscription[$subscription_id]['paymentamount'],
					'SUBSCRIPTION_NEXTDATE'		=> $subscription[$subscription_id]['paymentnextdate'],		
					//'SUBSCRIPTION_STATUS'		=> $subscription[$subscription_id]['status'],		
					'SUBSCRIPTION_STATUS'		=> $options3,
					'OVERDUE_CHECKED_NO'		=> ($subscription[$subscription_id]['paymentoverdue']) ? '' : 'checked="checked"',
					'OVERDUE_CHECKED_YES'		=> ($subscription[$subscription_id]['paymentoverdue']) ? 'checked="checked"' : '',
					'SUBSCRIPTION_ACTION'		=> 'changesubscription',
					'SUBSCRIPTION_SUBMIT'		=> append_sid('index.php?i=' . $id . '&mode=management&submode=editsubscriptionsql&subscriptionid=' . $subscription_id),
					'PHPBB_ROOT_PATH'			=> $phpbb_root_path,
				));
				break;
			
			case 'editsubscriptionsql':
				
				$this_id = $subscription[$subscription_id]['order_id'];
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
				$paymentcycleselection 	= $_POST['paymentcyclecats'];
				$statuselection			= $_POST['accountstatus'];
				$paymentoverdue=$_REQUEST['paymentoverdue'];
				
				$sql = 'UPDATE ' . $table_prefix . 'subscription SET ' . $db->sql_build_array('UPDATE', array(
					'username'			=> utf8_normalize_nfc(request_var('username', '', true)),
					'reg_date' 			=> $_POST['regdate'],
					'payment_initial'	=> $_POST['paymentinitial'],
					'payment_cycle'		=> $paymentcycleselection,
					//'payment_cycle'		=> $_POST['paymentcycle'],
					'payment_amount'	=> $_POST['paymentamount'],
					'payment_nextdate'	=> $_POST['paymentnextdate'],
					'payment_overdue'	=> $paymentoverdue,
					//'status'			=> $_POST['status'],
					'status'			=> $statuselection,
					'order_id'			=> $this_id,
				)) . ' WHERE id = ' . $subscription_id;
				$db->sql_query($sql);

				trigger_error(sprintf($user->lang['ACP_SUBSCRIPTION_EDIT_GOOD'], append_sid('index.php?i=' . $id . '&mode=management&submode=subscriptionview&subscriptionid=' . $subscription_id)));
				break;
				
			
			case 'addsubscriptionsql':
				

				$this_id = 1;
				foreach ($subscription as $key => $value)
				{
					$this_id++;
				}
				
				// $this_id = 1;
				// foreach ($subscription as $key => $value)
				// {
					// $this_id = $value['order_id'] + 1;
				// }
				$username 		= request_var('username','');
				$namesql = 'SELECT username, user_id  
							FROM ' . USERS_TABLE .
							" WHERE username =  '$username' "; 
				$nameresult = $db->sql_query($namesql);
				$namerow = $db->sql_fetchrow($nameresult);
				$userid	   = $namerow['user_id'];
				
				$db->sql_freeresult($nameresult);
				
				if (!isset($namerow['user_id']))
				{	
					trigger_error(sprintf($user->lang['ACP_NO_SUBCRIPTION_USERID'], append_sid('index.php?i=' . $id . '&mode=management')));
				}				
				
				$paymentcycleselection = $_POST['paymentcyclecats'];
				$paymentoverdue=$_REQUEST['paymentoverdue'];
				$sql = 'INSERT INTO ' . $table_prefix . 'subscription ' . $db->sql_build_array('INSERT', array(
					'user_id'			=> $namerow['user_id'],
					'username'			=> utf8_normalize_nfc(request_var('username', '', true)),
					//'username'		=> utf8_normalize_nfc(request_var('username', '', true)),
					'reg_date' 			=> $_POST['regdate'],
					'payment_initial'	=> $_POST['paymentinitial'],
					'payment_cycle'		=> $paymentcycleselection,
					//'payment_cycle'		=> $_POST['paymentcycle'],
					'payment_amount'	=> $_POST['paymentamount'],
					'payment_nextdate'	=> $_POST['paymentnextdate'],
					'payment_overdue'	=> $paymentoverdue,
					'status'			=> $_POST['status'],
					'order_id'			=> $this_id,
				));
				$db->sql_query($sql);

				// foreach ($subscription as $key => $value)
				// {
				// //	if ($value['id'] == $_POST['parent'])
				// //	{
						// $newsub = $key;
				// //		break;
				// //	}
				// }
				//trigger_error(sprintf($user->lang['ACP_SUBSCRIPTION_ADD_GOOD'], append_sid('index.php?i=' . $id . '&mode=management&submode=subscriptionview&subscriptionid=' . $newsub)));
				trigger_error(sprintf($user->lang['ACP_SUBSCRIPTION_ADD_GOOD'], append_sid('index.php?i=' . $id . '&mode=management&submode=subscriptionview')));
				break;
				
			case 'addsubscription':
			
 				if (empty($_POST['subscription_name']))
				{
					trigger_error(sprintf($user->lang['ACP_SUBSCRIPTION_ADD_FAIL'], append_sid('index.php?i=' . $id . '&mode=management')));
				}

				if (!$cycleid < 0)
				{
					trigger_error(sprintf($user->lang['ACP_SUBSCRIPTION_CYCLES_REQUIRED'], append_sid('index.php?i=' . $id . '&mode=management')));
				}
				
				$this->tpl_name = 'acp_subscription_new';
                $this->page_title = $user->lang['ACP_SUBSCRIPTION_INDEX'];
		
				$options2 = '';
				foreach($cycles as $key => $value)
				{
					// if ($key == $paymentcycle)
					// {
						// $options2 .= '<option value="' . $value['id'] . '" selected="selected">' . $value['name'] . '</option>';
					// }
					// else
					// {
						$options2 .= '<option value="' . $value['cycleid'] . '">' . $value['cyclename'] . '</option>';
					// }
				}
                $template->assign_vars(array(
					'SUBCRIPTION_TITLE'			=> $user->lang['ACP_SUBSCRIPTION_TITLE_EDIT'],
					'SUBSCRIPTION_TEXT'			=> $user->lang['ACP_SUBSCRIPTION_TEXT_EDIT'],
					//'NAME_VALUE'				=> utf8_normalize_nfc(request_var('subscription_name', '', true)),
					'NAME_VALUE'				=> utf8_normalize_nfc((isset($_POST['subscription_name'])) ? request_var('subscription_name', '', true) : ''),
					// 'DESC_VALUE'			=> $medals[$medal_id]['description'],
					// 'MEDAL_IMAGE'			=> '<br /><img src="' . $phpbb_root_path . 'images/medals/' . $medals[$medal_id]['image'] . '" alt="" style="max-width: 60px; max-height: 60px;" />',
					// 'IMAGE_OPTIONS'			=> $options,
					// 'DYNAMIC_CHECKED_NO'	=> ($medals[$medal_id]['dynamic']) ? '' : 'checked="checked"',
					// 'DYNAMIC_CHECKED_YES'	=> ($medals[$medal_id]['dynamic']) ? 'checked="checked"' : '',
					// 'DEVICE_VALUE'			=> $medals[$medal_id]['device'],
					// 'NUMBER_VALUE'			=> $medals[$medal_id]['number'],
					// 'POINTS_VALUE'			=> $medals[$medal_id]['points'],
					// 'PARENT_OPTIONS'		=> $options2,
					'SUBSCRIPTION_REGDATE' 		=> '28/08/2009',
					'SUBSCRIPTION_INITIALPAY' 	=> '0',
					'SUBSCRIPTION_CYCLE'		=>  $options2,
					'SUBSCRIPTION_AMOUNT'		=> '15',
					'SUBSCRIPTION_NEXTDATE'		=> '28/09/2009',
					'SUBSCRIPTION_STATUS'		=> '1',
					//'SUBSCRIPTION_REGDATE' 		=> $subscription[$subscription_id]['regdate'],
					//'SUBSCRIPTION_INITIALPAY' 	=> $subscription[$subscription_id]['paymentinitial'],
					//'SUBSCRIPTION_CYCLE'		=> $subscription[$subscription_id]['paymentcycle'],
					//'SUBSCRIPTION_AMOUNT'		=> $subscription[$subscription_id]['paymentamount'],
					//'SUBSCRIPTION_NEXTDATE'		=> $subscription[$subscription_id]['paymentnextdate'],
					
					
					//'OVERDUE_CHECKED_NO'		=> ($subscription[$subscription_id]['paymentoverdue']) ? '' : 'checked="checked"',
					'OVERDUE_CHECKED_NO'		=> 'checked="checked"',
					//'OVERDUE_CHECKED_YES'		=> ($subscription[$subscription_id]['paymentoverdue']) ? 'checked="checked"' : '',
					'SUBSCRIPTION_ACTION'		=> 'newsubscription',
					'SUBSCRIPTION_SUBMIT'		=> append_sid('index.php?i=' . $id . '&mode=management&submode=addsubscriptionsql'),
					'PHPBB_ROOT_PATH'			=> $phpbb_root_path,
				));
				break;

				
			case 'movesubscription':
			
				if ($move_type)
				{
					$swap_diff = 1;
				}
				else
				{
					$swap_diff = -1;
				}
				$sql = 'UPDATE ' . $table_prefix . 'subscription
							SET order_id = ' . $subscription[$move_id]['order_id'] . '
							WHERE order_id = ' . $subscription[$move_id]['order_id'] . '+' . $swap_diff;
				$db->sql_query($sql);
				$sql = 'UPDATE ' . $table_prefix . 'subscription
							SET order_id = ' . $subscription[$move_id]['order_id'] . '+' . $swap_diff . '
							WHERE id = ' . $subscription[$move_id]['subscriptionid'];
				$db->sql_query($sql);
				$submode = '';
				$sql = 'SELECT *
							FROM ' . $table_prefix . 'subscription
							ORDER BY order_id ASC';
				$result = $db->sql_query($sql);
				$subscription = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$subscription[$row['id']] = array( 
						'userid' 			=> $row['user_id'], 
						'username' 			=> $row['username'], 
						'regdate' 			=> $row['reg_date'], 
						'paymentinitial'	=> $row['payment_initial'], 
						'paymentcycle' 		=> $row['payment_cycle'], 
						'paymentamount'		=> $row['payment_amount'],
						'paymentnextdate'	=> $row['payment_nextdate'],
						'paymentoverdue'	=> $row['payment_overdue'],
						'status' 			=> $row['status'], 
						'subscriptionid'	=> $row['id'],
						'order_id'			=> $row['order_id'],
					);
				}

				$db->sql_freeresult($result);
				break;
			

		}
		if (empty($submode))
		{
			switch($mode)
			{
				case 'config':
					$this->tpl_name = 'acp_subscription_config';
					$this->page_title = $user->lang['ACP_SUBSCRIPTION_INDEX'];
					break;

				case 'management':
					$this->tpl_name = 'acp_subscription';
					$this->page_title = $user->lang['ACP_SUBSCRIPTION_INDEX'];
					foreach($subscription as $key2 => $value2)
					{
						$template->assign_block_vars('subscription', array(
							'U_EDIT'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=editsubscription&subscriptionid=' . $value2['subscriptionid']),
							'U_DELETE'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=deletesubscription&subscriptionid=' . $value2['subscriptionid']),
							'U_MOVE_UP'			=> append_sid('index.php?i=' . $id . '&mode=management&submode=movesubscription&movetype=0&moveid=' . $value2['subscriptionid']),
							'U_MOVE_DOWN'		=> append_sid('index.php?i=' . $id . '&mode=management&submode=movesubscription&movetype=1&moveid=' . $value2['subscriptionid']),
							//'MEDAL_IMAGE'		=> '<img src="images/icon_subfolder.gif" alt="' . $user->lang['ACP_MEDAL_LEGEND_CAT'] . '" title="' . $user->lang['ACP_MEDAL_LEGEND_CAT'] . '" />',
							'SUBSCRIPTION_TITLE'		=> '<a href="' . append_sid('index.php?i=' . $id . '&mode=management&submode=editsubscription&subscriptionid=' . $value2['subscriptionid']) . '" class="title">' . $value2['username'] . '</a>',
							'SUBSCRIPTION_REGDATE' 		=> $value2['regdate'],
							'SUBSCRIPTION_INITIALPAY' 	=> $value2['paymentinitial'],
							'SUBSCRIPTION_CYCLE'		=> $value2['paymentcycle'],
							'SUBSCRIPTION_AMOUNT'		=> $value2['paymentamount'],
							'SUBSCRIPTION_NEXTDATE'		=> $value2['paymentnextdate'],
							'SUBSCRIPTION_OVERDUE'		=> $value2['paymentoverdue'],
							'SUBSCRIPTION_STATUS'		=> $value2['status'],
						));
					}
					break;

					default:
						trigger_error('NO_MODE', E_USER_ERROR);
					break;
			}
		}
		$template->assign_vars(array(
			'U_SUBSCRIPTION_CONFIG' => append_sid('index.php?i=' . $id . '&mode=config'),
			'U_SUBSCRIPTION_INDEX'  => append_sid('index.php?i=' . $id . '&mode=management'),
		));
    }
}
?>