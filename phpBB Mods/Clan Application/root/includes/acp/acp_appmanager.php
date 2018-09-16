<?php
/**
 *
 * @package Clan Application Manager
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


/**********************************************************
 * Main ACP Class for App Manager contains:
 * - App Manager User List
 * - App Manager Settings
 * - App Manager Form
 **********************************************************/
class acp_appmanager
{
	var $u_action;
	var $tpl_path;
	var $page_title;

	function main($id, $mode)
	{
        global $config, $db, $user, $auth, $template;
		global $phpbb_root_path, $phpbb_admin_path, $phpEx, $table_prefix;

		include($phpbb_root_path . 'includes/acp/info/acp_appmanager.' . $phpEx);
		//include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
		include($phpbb_root_path . 'includes/message_parser.'.$phpEx);
		include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

		$user->add_lang('mods/info_acp_appmanager');
		$user->add_lang('posting');

		$action	= request_var('action', '');
		$submode = request_var('submode', '');
		$submit = (isset($_POST['submit'])) ? true : false;

		switch($mode)
		{
			/**********************
			 * View App User List *
			 **********************/
			case 'appviewlist':

				$start   = request_var('start', 0);
				$limit   = request_var('limit', 25);

				$this->tpl_name = 'acp_appmanager_list';
				$this->page_title = 'ACP_APPMANAGER_LIST';
			break;


			/*****************************************
			 * View & Change Application Form in ACP *
			 *****************************************/
			case 'appform':

				$submode = request_var('submode', '');
				$sectionid = request_var('sectionid', '');
				$addsection = request_var('addsection', '');

				// Handle some specials cases first for submode. Like add section
				if (!empty($addsection))
				{
					$submode = 'addsection';
				}

				/*if (!empty($addsection))
				{
					//$submode = 'addsection';
					redirect(append_sid('index.php?i=' . $id . '&mode=appform&submode=addsection&section_name=' . request_var('section_name','New Section')));

				}*/

				/******************************************
				 * Check for submode to process:          *
				 *                                        *
				 * - Add New Section                      *
				 * - Edit Section                         *
				 * - Delete Section                       *
				 * - Move Section Up                      *
				 * - Move Section Down                    *
				 * - View All App Form Sections (Default) *
				 *                                        *
				 ******************************************/

				/*******************
				 * Add New Section *
				 *******************/
				if ($submode == 'addsection')
				{
					if ($submit)
					{
						$sectiontitle = utf8_normalize_nfc(request_var('sectiontitle', ''), true);
						$sectiontext = utf8_normalize_nfc(request_var('sectiontext', ''), true);
						$sectiondisplay = request_var('sectiondisplay', 1);

						$uid = $bitfield = $options = '';
						generate_text_for_storage($sectiontitle, $uid, $bitfield, $options, false, false, true);
						generate_text_for_storage($sectiontext, $uid, $bitfield, $options, true, true, true);

						// Get total sections. This for the order id, new entry is added with total sections+1
						$sql = 'SELECT COUNT(section_id) AS total_sections FROM phpbb_app_form';
						$result = $db->sql_query($sql);
						$total_sections = $db->sql_fetchfield('total_sections');
						$db->sql_freeresult($result);

						// Create new app section array for sql insert
						$sql_appsection = array(
							'section_title'		=> $sectiontitle,
							'section_text'		=> $sectiontext,
							'section_order'		=> (int)  $total_sections -1 +2,
							'bbcode_uid'		=> $uid,
							'bbcode_bitfield'	=> $bitfield,
						);
						$db->sql_query('INSERT INTO phpbb_app_form' . $db->sql_build_array('INSERT', $sql_appsection));
						trigger_error(sprintf($user->lang['ACP_APPMANAGER_SECTION_SAVED'], append_sid('index.php?i=' . $id . '&mode=appform')));
					}
					else
					{
						$sectiontitle = utf8_normalize_nfc(request_var('sectionname','New Section'), true);

						$template->assign_vars(array(
							'SECTION_TITLE'		=> $sectiontitle,
							'SECTION_TEXT'		=> '',
							'S_SECTION_DISPLAY'	=> true,
							'U_ACP_APPMANAGER_FORM'	=> append_sid('index.php?i=' . $id . '&mode=appform'),
							'U_ACTION'				=> append_sid('index.php?i=' . $id . '&mode=appform&submode=addsection')
						));

						$this->tpl_name = 'acp_appmanager_section';
						$this->page_title = 'ACP_APPMANAGER_SECTION';
					}
				}

				/****************
				 * Edit section *
				 ****************/
				elseif ($submode == 'editsection')
				{

					if ($sectionid < 0)
					{
						trigger_error('ACP_NO_SECTION_ID');
					}

					$sqlsection = 'SELECT *
						FROM phpbb_app_form
						WHERE section_id= ' . $sectionid .'';
					$resultsection = $db->sql_query($sqlsection);
					$rowsection = $db->sql_fetchrow($resultsection);

					$sectiontitle = utf8_normalize_nfc(request_var('sectiontitle', $rowsection['section_title']), true);
					$sectiontext = utf8_normalize_nfc(request_var('sectiontext', $rowsection['section_text']), true);
					$sectiondisplay = request_var('sectiondisplay', (int) $rowsection['section_display']);

					if ($submit)
					{
						$uid = $bitfield = $options = '';
						generate_text_for_storage($sectiontitle, $uid, $bitfield, $options, false, false, true);
						generate_text_for_storage($sectiontext, $uid, $bitfield, $options, true, true, true);

						$sql = 'UPDATE phpbb_app_form
							SET
							section_title 		= "' . $sectiontitle . '",
							section_text 		= "' . $sectiontext . '",
							section_display		= "' . $sectiondisplay . '",
							bbcode_uid        	= "' . $uid . '",
    						bbcode_bitfield  	= "' . $bitfield . '"
							WHERE section_id='. $sectionid;
						$results = $db->sql_query($sql);
						$row = $db->sql_fetchrow($results);
						$db->sql_freeresult($results);
						trigger_error(sprintf($user->lang['ACP_APPMANAGER_SECTION_SAVED'], append_sid('index.php?i=' . $id . '&mode=appform')));
					}

					decode_message($rowsection['section_text'], $rowsection['bbcode_uid']);

					$template->assign_vars(array(
						'SECTION_TITLE'		=> $sectiontitle,
						'SECTION_TEXT'		=> $sectiontext,
						'S_SECTION_DISPLAY'	=> ($sectiondisplay==1) ? true : false,
						'U_ACP_APPMANAGER_FORM'	=> append_sid('index.php?i=' . $id . '&mode=appform'),
					));
					$db->sql_freeresult($resultsection);

					$this->tpl_name = 'acp_appmanager_section';
					$this->page_title = 'ACP_APPMANAGER_SECTION';
				}

				/******************
				 * Delete section *
				 ******************/
				elseif ($submode == 'deletesection')
				{
					$cancelsection = request_var('cancelsection', '');
					$confirmsection = request_var('confirmsection', '');

					if (!empty($cancelsection))
					{
						redirect(append_sid('index.php?i=' . $id . '&mode=appform'));
					}
					if (empty($confirmsection))
					{
						trigger_error('ACP_CONFIRM_SECTION_DELETE');
					}
					if ($sectionid < 0)
					{
						trigger_error('ACP_NO_SECTION_ID');
					}

					// reorder sections for the delete
					$sqlsection = 'SELECT section_id, section_order
						FROM phpbb_app_form
						ORDER BY section_order ASC';
					$resultsection = $db->sql_query($sqlsection);

					while ($rowsection = $db->sql_fetchrow($resultsection))
					{
						if ($rowsection['section_id'] > $sectionid)
						{
							$sql = 'UPDATE phpbb_app_form
								SET
								section_order = ' . ($rowsection['section_order'] -1) .'
								WHERE section_id='. $rowsection['section_id'];
							$results = $db->sql_query($sql);
							$db->sql_freeresult($results);
						}
					}
					$db->sql_freeresult($resultsection);

					// delete actual section
					$sql = 'DELETE FROM phpbb_app_form
							WHERE section_id = ' . $sectionid;
					$db->sql_query($sql);
					trigger_error(sprintf($user->lang['ACP_SECTION_DELETED'], append_sid('index.php?i=' . $id . '&mode=appform')));
				}

				/*******************
				 * Move Section Up *
				 *******************/
				elseif ($submode == 'moveup')
				{
					$sqlsection = 'SELECT section_order
						FROM phpbb_app_form
						WHERE section_id= ' . $sectionid .'';
					$resultsection = $db->sql_query($sqlsection);
					$rowsection = $db->sql_fetchrow($resultsection);

					$sectionorder = $rowsection['section_order'];

					$sql = 'UPDATE phpbb_app_form
							SET
							section_order = ' . $sectionorder . '
							WHERE section_order = ' . $sectionorder . '-2 +1';

					$db->sql_query($sql);
					$sql = 'UPDATE phpbb_app_form
							SET
							section_order = ' . $sectionorder . '-2 +1
							WHERE section_id = ' . $sectionid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultsection);

					redirect(append_sid('index.php?i=' . $id . '&mode=appform'));
				}

				/*********************
				 * Move Section Down *
				 *********************/
				elseif ($submode == 'movedn')
				{
					$sqlsection = 'SELECT section_order
						FROM phpbb_app_form
						WHERE section_id= ' . $sectionid .'';
					$resultsection = $db->sql_query($sqlsection);
					$rowsection = $db->sql_fetchrow($resultsection);

					$sectionorder = $rowsection['section_order'];

					$sql = 'UPDATE phpbb_app_form
							SET
							section_order = ' . $sectionorder . '
							WHERE section_order = ' . $sectionorder . '-1 +2';

					$db->sql_query($sql);
					$sql = 'UPDATE phpbb_app_form
							SET
							section_order = ' . $sectionorder . '-1 +2
							WHERE section_id = ' . $sectionid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultsection);

					redirect(append_sid('index.php?i=' . $id . '&mode=appform'));
				}

				elseif ($submode == 'toggledisplay')
				{
					$sqlsection = 'SELECT section_display
						FROM phpbb_app_form
						WHERE section_id= ' . $sectionid .'';
					$resultsection = $db->sql_query($sqlsection);
					$rowsection = $db->sql_fetchrow($resultsection);

					$sectiondisplay = $rowsection['section_display'];
					$sql = 'UPDATE phpbb_app_form
							SET
							section_display = ' . ($sectiondisplay ? "0" : "1") .'
							WHERE section_id = ' . $sectionid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultsection);

					redirect(append_sid('index.php?i=' . $id . '&mode=appform'));
				}


				/*********************************
				 * View All Sections In App Form *
				 *********************************/
				else
				{
					// Get existing section details to put into form
					$sql = 'SELECT *
							FROM phpbb_app_form
							ORDER BY section_order ASC';
					$result = $db->sql_query($sql);

					while ($row = $db->sql_fetchrow($result))
					{
						//$sectiontext = htmlentities($row['section_text']);

						$flags = (($config['allow_bbcode']) ? 1 : 0) + (($config['allow_smilies']) ? 2 : 0) + ((true) ? 4 : 0);
						$sectiontext = generate_text_for_display($row['section_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $flags);

						//$sectiontext = $row['section_text'];
					/*	if (strlen($sectiontext) < 250)
						{
							$sectiontext = substr(str_pad($sectiontext, 254, " ", STR_PAD_RIGHT), 0, 254);
						}
						else
						{
							$sectiontext = substr($sectiontext, 0, 250) . ' ...';
						}*/
						$template->assign_vars(array(
							'ICON_BULB_ON'		=> '<img src="' . $phpbb_admin_path . 'images/icon_lightbulb_on.gif" alt="Visible" title="Visible: click to switch off" />',
							'ICON_BULB_OFF'		=> '<img src="' . $phpbb_admin_path . 'images/icon_lightbulb_off.gif" alt="Not visible" title="Not visible: Click to switch on" />',
						));

						$template->assign_block_vars('appform',array(
							'U_EDIT'			=> append_sid('index.php?i=' . $id . '&mode=appform&submode=editsection&sectionid=' . $row['section_id']),
							'U_DELETE'			=> append_sid('index.php?i=' . $id . '&mode=appform&submode=deletesection&sectionid=' . $row['section_id']),
							'U_MOVE_UP'			=> append_sid('index.php?i=' . $id . '&mode=appform&submode=moveup&sectionid=' . $row['section_id']),
							'U_MOVE_DOWN'		=> append_sid('index.php?i=' . $id . '&mode=appform&submode=movedn&sectionid=' . $row['section_id']),
							'U_DISPLAY'			=> append_sid('index.php?i=' . $id . '&mode=appform&submode=toggledisplay&sectionid=' . $row['section_id']),
							'SECTION_ID'		=> $row['section_id'],
							'SECTION_TITLE'		=> $row['section_title'],
							'SECTION_TEXT'		=> $sectiontext,
							'SECTION_ORDER'		=> $row['section_order'],
							'S_DISPLAY'			=> $row['section_display'] ? true : false,
						));
					}
					$db->sql_freeresult($result);

					$this->tpl_name = 'acp_appmanager_form';
					$this->page_title = 'ACP_APPMANAGER_FORM';
 				}
			break;


			/*************************************
			 * View & Change App Settings in ACP *
			 *************************************/
			case 'appsettings':

				$display_vars = array(
				    'title' => 'ACP_APPMANAGER_SETTINGS',
				    'vars'    => array(
				        'legend1'             					=> 'ACP_APPMANAGER_SETTINGS',
				        'application_clanname'					=> array('lang' => 'ACP_APPMANAGER_SETTINGS_CLANNAME', 'validate' => 'string', 'type' => 'text', 'explain' => true),
				        'application_open'						=> array('lang' => 'ACP_APPMANAGER_SETTINGS_OPEN_ENABLE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
				        'application_questions'					=> array('lang' => 'ACP_APPMANAGER_SETTINGS_QUESTIONS_ENABLE', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						 'legend2'								=> 'ACP_APPMANAGER_SETTINGS_AUTOPOST',
				        'application_post_private'				=> array('lang' => 'ACP_APPMANAGER_SETTINGS_POST_PRIVATE', 'validate' => 'int', 'type' => 'select', 'function' => 'forum_select', 'params' => array('{CONFIG_VALUE}'), 'explain' => true),
				        'application_post_public'				=> array('lang' => 'ACP_APPMANAGER_SETTINGS_POST_PUBLIC', 'validate' => 'int', 'type' => 'select', 'function' => 'forum_select', 'params' => array('{CONFIG_VALUE}'), 'explain' => true),
				        'application_post_topicicon'			=> array('lang' => 'ACP_APPMANAGER_SETTINGS_POST_TOPICICON', 'validate' => 'int', 'type' => 'select', 'function' => 'topicicon_select', 'params' => array('{CONFIG_VALUE}'), 'explain' => true),
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
					trigger_error(sprintf($user->lang['ACP_APPMANAGER_SETTINGS_SAVED'], append_sid('index.php?i=' . $id . '&mode=appsettings')));
					break ;
				}
				$this->tpl_name = 'acp_appmanager_settings';
                $this->page_title = $user->lang['ACP_APPMANGER_SETTINGS'];

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

			/*************************************
			 * View & Change App Statuses in ACP *
			 *************************************/
			case 'appstatus':
				$submode = request_var('submode', '');
				$statusid = request_var('statusid', '');
				$addstatus = request_var('addstatus', '');


				// Handle some specials cases first for submode. Like add server
				if (!empty($addstatus))
				{
					$submode = 'addstatus';
				}

				// Handle some specials cases first for submode. Like add status
				/*if (!empty($addstatus))
				{
					redirect(append_sid('index.php?i=' . $id . '&mode=appstatus&submode=addstatus&status_name=' . request_var('status_name','New Status')));
				}*/

				/**************
				 * Add Status *
				 **************/
				if ($submode == 'addstatus')
				{

					if ($submit)
					{
						$statusvalue = (int) request_var('statusvalue', 0);
						$statustitle = utf8_normalize_nfc(request_var('statustitle', 'New Status'), true);
						$statustext = utf8_normalize_nfc(request_var('statustext', ''), true);
						$statuscolor = utf8_normalize_nfc(request_var('statuscolor', ''), true);
						$statusreapply = (int) request_var('statusreapply', 1);
						$statuspm = (int) request_var('statuspm', 0);
						$statuspmmsg = utf8_normalize_nfc(request_var('statuspmmsg', ''), true);


						$uid = $bitfield = $options = '';
						generate_text_for_storage($statustitle, $uid, $bitfield, $options, false, false, true);
						generate_text_for_storage($statustext, $uid, $bitfield, $options, true, true, true);
						generate_text_for_storage($statuspmmsg, $uid, $bitfield, $options, true, true, true);

						// Create new app status array for sql insert
						$sql_appstatus = array(
							'status_value'		=> $statusvalue,
							'status_title'		=> $statustitle,
							'status_text'		=> $statustext,
							'status_color'		=> $statuscolor,
							'status_reapply'	=> $statusreapply,
							'status_pm'			=> $statuspm,
							'status_pmmsg'		=> $statuspmmsg,
							'bbcode_uid'		=> $uid,
							'bbcode_bitfield'	=> $bitfield,
						);
						$db->sql_query('INSERT INTO phpbb_app_status' . $db->sql_build_array('INSERT', $sql_appstatus));
						trigger_error(sprintf($user->lang['ACP_APPMANAGER_STATUS_SAVED'], append_sid('index.php?i=' . $id . '&mode=appstatus')));
					}
					else
					{
						$statustitle = utf8_normalize_nfc(request_var('statusname','New Status'), true);

						$template->assign_vars(array(
							'STATUS_VALUE'		=> 0,
							'STATUS_TITLE'		=> $statustitle,
							'STATUS_TEXT'		=> '',
							'STATUS_COLOR'		=> '',
							'S_STATUS_COLOR'	=> false,
							'S_STATUS_REAPPLY'	=> true,
							'S_STATUS_PM'		=> false,
							'STATUS_PMMSG'		=> '',
							'U_ACP_APPMANAGER_STATUSES'	=> append_sid('index.php?i=' . $id . '&mode=appstatus'),
							'U_ACTION'				=> append_sid('index.php?i=' . $id . '&mode=appstatus&submode=addstatus')
						));

						$this->tpl_name = 'acp_appmanager_status';
						$this->page_title = 'ACP_APPMANAGER_STATUS';
					}
				}

				/***************
				 * Edit Status *
				 ***************/
				elseif  ($submode == 'editstatus')
				{
					echo 'editstatus!';

					if ($statusid < 0)
					{
						trigger_error('ACP_NO_STATUS_ID');
					}

					$sqlstatus = 'SELECT *
						FROM phpbb_app_status
						WHERE status_id= ' . $statusid .'';
					$resultstatus = $db->sql_query($sqlstatus);
					$rowstatus = $db->sql_fetchrow($resultstatus);

					$statusvalue = (int) request_var('statusvalue', $rowstatus['status_value']);
					$statustitle = utf8_normalize_nfc(request_var('statustitle', $rowstatus['status_title']), true);
					$statustext = utf8_normalize_nfc(request_var('statustext', $rowstatus['status_text']), true);
					$statuscolor = utf8_normalize_nfc(request_var('statuscolor', $rowstatus['status_color']), true);
					$statusreapply = (int) request_var('statusreapply', $rowstatus['status_reapply']);
					$statuspm = (int) request_var('statuspm', $rowstatus['status_pm']);
					$statuspmmsg = utf8_normalize_nfc(request_var('statuspmmsg', $rowstatus['status_pmmsg']), true);

					if ($submit)
					{
						$uid = $bitfield = $options = '';
						generate_text_for_storage($statustitle, $uid, $bitfield, $options, false, false, true);
						generate_text_for_storage($statustitle, $uid, $bitfield, $options, true, true, true);
						generate_text_for_storage($statuspmmsg, $uid, $bitfield, $options, true, true, true);

						$sql = 'UPDATE phpbb_app_status
							SET
							status_value 		= "' . $statusvalue . '",
							status_title 		= "' . $statustitle . '",
							status_text 		= "' . $statustext . '",
							status_color 		= "' . $statuscolor . '",
							status_reapply 		= "' . $statusreapply . '",
							status_pm 			= "' . $statuspm . '",
							status_pmmsg 		= "' . $statuspmmsg . '",
							bbcode_uid        	= "' . $uid . '",
    						bbcode_bitfield  	= "' . $bitfield . '"
							WHERE status_id='. $statusid;
						$results = $db->sql_query($sql);
						$row = $db->sql_fetchrow($results);
						$db->sql_freeresult($results);
						trigger_error(sprintf($user->lang['ACP_APPMANAGER_STATUS_SAVED'], append_sid('index.php?i=' . $id . '&mode=appstatus')));
					}

					decode_message($rowstatus['status_text'], $rowstatus['bbcode_uid']);
					decode_message($rowstatus['status_pmmsg'], $rowstatus['bbcode_uid']);

					$template->assign_vars(array(
						'STATUS_VALUE'		=> $statusvalue,
						'STATUS_TITLE'		=> $statustitle,
						'STATUS_TEXT'		=> $statustext,
						'STATUS_COLOR'		=> $statuscolor,
						'S_STATUS_COLOR'	=> !empty($statuscolor) ? true : false,
						'S_STATUS_REAPPLY'	=> $statusreapply ? true : false,
						'S_STATUS_PM'		=> $statuspm ? true : false,
						'STATUS_PMMSG'		=> $statuspmmsg,
						'U_ACP_APPMANAGER_STATUSES'	=> append_sid('index.php?i=' . $id . '&mode=appstatus'),
					));

					$db->sql_freeresult($resultstatus);

					$this->tpl_name = 'acp_appmanager_status';
					$this->page_title = 'ACP_APPMANAGER_STATUS';
				}

				/*****************
				 * Delete Status *
				 *****************/
				elseif ($submode == 'deletestatus')
				{
					echo 'deletestatus!';

					$cancelstatus = request_var('cancelstatus', '');
					$confirmstatus = request_var('confirmstatus', '');

					if (!empty($cancelstatus))
					{
						redirect(append_sid('index.php?i=' . $id . '&mode=appstatus'));
					}
					if (empty($confirmstatus))
					{
						trigger_error('ACP_CONFIRM_STATUS_DELETE');
					}
					if ($statusid < 0)
					{
						trigger_error('ACP_NO_STATUS_ID');
					}

					// delete actual status
					$sql = 'DELETE FROM phpbb_app_status
							WHERE status_id = ' . $statusid;
					$db->sql_query($sql);
					trigger_error(sprintf($user->lang['ACP_STATUS_DELETED'], append_sid('index.php?i=' . $id . '&mode=appstatus')));
				}

				/*********************
				 * View all Statuses *
				 *********************/
				else
				{

					// Get existing section details to put into form
					$sqlstatus = 'SELECT *
							FROM phpbb_app_status
							ORDER BY status_id ASC';
					$resultstatus = $db->sql_query($sqlstatus);

					while ($rowstatus = $db->sql_fetchrow($resultstatus))
					{

						$flags = (($config['allow_bbcode']) ? 1 : 0) + (($config['allow_smilies']) ? 2 : 0) + ((true) ? 4 : 0);
						$statustext = generate_text_for_display($rowstatus['status_text'], $rowstatus['bbcode_uid'], $rowstatus['bbcode_bitfield'], $flags);
						$statuspmmsg = generate_text_for_display($rowstatus['status_pmmsg'], $rowstatus['bbcode_uid'], $rowstatus['bbcode_bitfield'], $flags);

						$template->assign_block_vars('appstatus',array(
							'U_EDIT'			=> append_sid('index.php?i=' . $id . '&mode=appstatus&submode=editstatus&statusid=' . $rowstatus['status_id']),
							'U_DELETE'			=> append_sid('index.php?i=' . $id . '&mode=appstatus&submode=deletestatus&statusid=' . $rowstatus['status_id']),
							'STATUS_ID'			=> $rowstatus['status_id'],
							'STATUS_TITLE'		=> $rowstatus['status_title'],
							'STATUS_TEXT'		=> $statustext,
							'STATUS_COLOR'		=> $rowstatus['status_color'],
							'S_STATUS_COLOR'	=> !empty($rowstatus['status_color']) ? true : false,
							'S_STATUS_REAPPLY'	=> $rowstatus['status_reapply'] ? true : false,
							'S_STATUS_PM'		=> $rowstatus['status_pm'] ? true : false,
							'STATUS_PMMSG'		=> $statuspmmsg,
						));
					}
					$db->sql_freeresult($resultstatus);
					$this->tpl_name = 'acp_appmanager_statuses';
					$this->page_title = 'ACP_APPMANAGER_STATUSES';
				}
			break;
		}
	}
}


/**********************************************************
 * Create a list of forums for the listbox in the
 * App settings ACP
 **********************************************************/
function forum_select($default = '')
{
    global $db;

    $sql = 'SELECT forum_id, forum_name, forum_type
        FROM ' . FORUMS_TABLE . "
        ORDER BY left_id";
    $result = $db->sql_query($sql);

    //$forum_options = '';
    $forum_options = '<option value="0"' . (($row['forum_id'] == $default) ? ' selected="selected"' : '') . '>' . '- No Forum -' . '</option>';
    while ($row = $db->sql_fetchrow($result))
    {
        $selected = ($row['forum_id'] == $default) ? ' selected="selected"' : '';

    	$forumtype = $row['forum_type'];
        if ($forumtype == 1) // forum types: 0 = section, 1 = forum, 2 = link
        {
        	//$forum_options .= '<option value="' . $row['forum_id'] . 'disabled="disabled">' . $row['forum_name'] . '</option>';
			//echo 'Forum: ' . $row['forum_name'];
			$forum_options .= '<option value="' . $row['forum_id'] . '"' . $selected . '>' . $row['forum_name'] . '</option>';
    	}
    }
    $db->sql_freeresult($result);

    return $forum_options;
}


/**********************************************************
 * Create a list of topic icons for the listbox in the
 * App settings ACP
 **********************************************************/
function topicicon_select($default = '')
{
	global $db;

    $sql = 'SELECT icons_id, icons_url
        FROM ' . ICONS_TABLE . "
        ORDER BY icons_order";
    $result = $db->sql_query($sql);

    //$topicicon_options = '';
    $topicicon_options = '<option value="0"' . (($row['icons_id'] == $default) ? ' selected="selected"' : '') . '>' . '- No Topic Icon -' . '</option>';
    while ($row = $db->sql_fetchrow($result))
    {
        $selected = ($row['icons_id'] == $default) ? ' selected="selected"' : '';
        $topicicon_options .= '<option value="' . $row['icons_id'] . '"' . $selected . '>' . $row['icons_url'] . '</option>';
    }
    $db->sql_freeresult($result);

    return $topicicon_options;
}


?>