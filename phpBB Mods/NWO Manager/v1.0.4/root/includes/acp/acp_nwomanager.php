<?php
/**
 *
 * @package NWO Manager
 * @version $Id: 1.0.0
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
 * Main ACP Class for NWO Manager contains:
 * - NWO Manager Server List
 * - NWO Manager Settings
 * - NWO Manager Links
 **********************************************************/
class acp_nwomanager
{
	var $u_action;
	var $tpl_path;
	var $page_title;

	function main($id, $mode)
	{
        global $config, $db, $user, $auth, $template;
		global $phpbb_root_path, $phpbb_admin_path, $phpEx, $table_prefix;

		include($phpbb_root_path . 'includes/acp/info/acp_nwomanager.' . $phpEx);
		include($phpbb_root_path . 'includes/constants_nwomanager.' . $phpEx);

		$user->add_lang('mods/info_acp_nwomanager');

		$action	= request_var('action', '');
		$submit = (isset($_POST['submit'])) ? true : false;



		switch($mode)
		{
			/*********************************************************************************************************************************************************
			 * Log List in ACP
			 *********************************************************************************************************************************************************/
			case 'nwologlist':

				$submode = request_var('submode', '');
				$logid = request_var('logid', '');
				$addlog = request_var('addlog', '');


				// Setup arrays for our use
				$logcategoryarray = array(
					'0'		=>	'-',
					'1'		=>	'GHP',
					'2'		=>	'WHP',
					'3'		=>	'GSCP',
					'4'		=>	'WSCP',
					'5'		=>	'GS',
					'6'		=>	'WS',
					'7'		=>	'FRM',
					'8'		=>	'MA',
					'9'		=>	'SM',
					'10'	=>	'MOD',
					'11'	=>	'Other',
				);

				$logcategoryexpandedarray = array(
					'0'		=>	'-',
					'1'		=>	'Game Host Provider',
					'2'		=>	'Web Host Provider',
					'3'		=>	'Game Server Control Panel',
					'4'		=>	'Web Server Control Panel',
					'5'		=>	'Game Server',
					'6'		=>	'Web Server',
					'7'		=>	'Mani Admin',
					'8'		=>	'SourceMod',
					'9'		=>	'Mod/Plugin/Script',
					'10'	=>	'MySQL Database',
					'11'	=>	'PhpBB Forums',
					'12'	=>	'PhpBB Mod',
					'13'	=>	'SourceMod WebAdmin',
					'14'	=>	'SourceBans',
					'15'	=>	'SteamBans',
					'16'	=>	'HLStatsX',
					'17'	=>	'Other',
				);


				$logcategoryfilterarray = array(
					'0'		=>	'All Log Categories',
					'1'		=>	'Game Host Provider',
					'2'		=>	'Web Host Provider',
					'3'		=>	'Game Server Control Panel',
					'4'		=>	'Web Server Control Panel',
					'5'		=>	'Game Server',
					'6'		=>	'Web Server',
					'7'		=>	'Mani Admin',
					'8'		=>	'SourceMod',
					'9'		=>	'Mod/Plugin/Script',
					'10'	=>	'MySQL Database',
					'11'	=>	'PhpBB Forums',
					'12'	=>	'PhpBB Mod',
					'13'	=>	'SourceMod WebAdmin',
					'14'	=>	'SourceBans',
					'15'	=>	'SteamBans',
					'16'	=>	'HLStatsX',
					'17'	=>	'Other',
				);

				$logtypearray = array(
					'0'		=>	'INF',
					'1'		=>	'NEW',
					'2'		=>	'CHG',
					'3'		=>	'UPD',
					'4'		=>	'DEL',
					'5'		=>	'MIN',
					'6'		=>	'MAJ',
					'7'		=>	'CRT',
					'8'		=>	'MNT',
					'9'		=>	'Other',
				);

				$logtypeexpandedarray = array(
					'0'		=>	'-',
					'1'		=>	'Information/Notification',
					'2'		=>	'Installation/Addition',
					'3'		=>	'Change/Modification',
					'4'		=>	'Update/Upgrade',
					'5'		=>	'Removal/Delete',
					'6'		=>	'Minor Issue',
					'7'		=>	'Major Issue',
					'8'		=>	'Critical Issue',
					'9'		=>	'Maintenance',
					'10'	=>	'Other',
				);

				$logtypefilterarray = array(
					'0'		=>	'All Log Types',
					'1'		=>	'Information/Notification',
					'2'		=>	'Installation/Addition',
					'3'		=>	'Change/Modification',
					'4'		=>	'Update/Upgrade',
					'5'		=>	'Removal/Delete',
					'6'		=>	'Minor Issue',
					'7'		=>	'Major Issue',
					'8'		=>	'Critical Issue',
					'9'		=>	'Maintenance',
					'10'	=>	'Other',
				);

				// Put all servers into array for later use
				$sqlservers = 'SELECT server_id, server_name
								FROM ' . NWOSERVERS_TABLE . '
								ORDER BY server_order';
				$resultservers = $db->sql_query($sqlservers);
				$serversarray = array();
				$serversarray[-4] = 'Website Server';
				$serversarray[-3] = 'Teamspeak Server';
				$serversarray[-2] = 'Ventrolli Server';
				$serversarray[-1] = 'All Game Servers';
				$serversarray[0] = '- N/A -';
				while ($rowservers = $db->sql_fetchrow($resultservers))
				{
					$serversarray[$rowservers['server_id']] = $rowservers['server_name'];
				}
				$db->sql_freeresult($resultservers);

				// Put admins into array for later use
				//$adminlist = '3, 287, 483, 494, 496';
				$adminlist = (!empty($config['nwomanager_log_admins']) && isset($config['nwomanager_log_admins'])) ? $config['nwomanager_log_admins'] : '5'; // if empty use admins from forum board
				$adminlistarray = explode(",", $adminlist);
				$sqladmins = 'SELECT DISTINCT u.user_id, u.username
							FROM ' . USERS_TABLE . ' u
							INNER JOIN ' . USER_GROUP_TABLE . ' ug ON (u.user_id = ug.user_id)
							WHERE ' . $db->sql_in_set('ug.group_id', $adminlistarray) .'
							ORDER BY u.user_id';
				$resultadmins = $db->sql_query($sqladmins);
				$adminsarray = array();
				while ($rowadmins = $db->sql_fetchrow($resultadmins))
				{
					$adminsarray[$rowadmins['user_id']] = $rowadmins['username'];
				}
				$db->sql_freeresult($resultadmins);

				// Put all users into array for later use
				$sqlusers = 'SELECT DISTINCT user_id, username
							FROM ' . USERS_TABLE . '
							WHERE user_type != 2
							ORDER BY user_id';
				$resultusers = $db->sql_query($sqlusers);
				$usersarray = array();
				while ($rowusers = $db->sql_fetchrow($resultusers))
				{
					$usersarray[$rowusers['user_id']] = $rowusers['username'];
				}
				$db->sql_freeresult($resultusers);


				// Handle some specials cases first for submode. Like add log
				if (!empty($addlog))
				{
					$submode = 'addlog';
				}

				/******************************************
				 * Check for submode to process:          *
				 *                                        *
				 * - Add New Log Entry                    *
				 * - Edit Log Entry                       *
				 * - Delete Log Entry                     *
				 * - View All Log Entries (Default)       *
				 *                                        *
				 ******************************************/

				/*********************
				 * Add New Log Entry *
				 *********************/
				if ($submode == 'addlog')
				{
					if ($submit)
					{
						$logdatetime = request_var('logdatetime', gmdate('Y-m-d H:i:s'));
						// Validate date input
						$date_format = 'Y-m-d H:i:s';
						$input = $logdatetime;
						$input = trim($input);
						$time = strtotime($input);
						$isdatevalid = date($date_format, $time) == $input;

						// if it is wrong we just set date to today
						if (!$isdatevalid)
						{
							$logdatetime = gmdate('Y-m-d H:i:s');
						}

						$logserver = request_var('logserver', 0);
						//$logadmin  = request_var('logadmin', $rowcenter['log_admin']);
						$logadmin  = request_var('logadmin', $user->data['user_id']);
						$logcategory  = request_var('logcategory', 0);
						$logtype  = request_var('logtype', 0);
						$logsummary = utf8_normalize_nfc(request_var('logsummary', ''), true);
						$lognotes = utf8_normalize_nfc(request_var('lognotes', ''), true);

						$uid = $bitfield = $options = '';
						$allow_bbcode = $allow_smilies = $allow_urls = true;
						generate_text_for_storage($lognotes, $uid, $bitfield, $options, true, true, true);

						// Create new server array for sql insert
						$sql_logentry = array(
							'log_datetime'				=> $logdatetime,
							'log_server'				=> $logserver,
							'log_admin'					=> $logadmin,
							'log_category'				=> $logcategory,
							'log_type'					=> $logtype,
							'log_summary'				=> $logsummary,
							'log_notes'					=> $lognotes,
							'bbcode_uid'				=> $uid,
							'bbcode_bitfield'			=> $bitfield,
						);
						$db->sql_query('INSERT INTO ' . NWOLOGS_TABLE . $db->sql_build_array('INSERT', $sql_logentry));

						// Log entry to tell everyone that a nwo log entry has been made
						add_log('admin', 'LOG_NWOMANAGER_LOGENTRY', !empty($logsummary) ? $logsummary : 'NWO Log Entry');

						trigger_error(sprintf($user->lang['ACP_NWOMANAGER_LOGENTRY_SAVED'], append_sid('index.php?i=' . $id . '&mode=nwologlist')));
					}
					else
					{

						$logentrytitle = 'New Log Entry';

						$template->assign_vars(array(
							'LOGENTRY_TITLE'			=> $logentrytitle,
							'LOG_DATETIME'				=> gmdate('Y-m-d H:i:s'),
							'LOG_SERVER'				=> build_options_select($serversarray, 0),
							'LOG_ADMIN'					=> build_options_select($adminsarray, $user->data['user_id']),
							'LOG_CATEGORY'				=> build_options_select($logcategoryexpandedarray, 0),
							'LOG_TYPE'					=> build_options_select($logtypeexpandedarray, 0),
							'LOG_SUMMARY'				=> '',
							'LOG_NOTES'					=> '',
							'U_ACP_NWOMANAGER_LOGLIST'	=> append_sid('index.php?i=' . $id . '&mode=nwologlist'),
							'U_ACTION'				=> append_sid('index.php?i=' . $id . '&mode=nwologlist&submode=addlog'),
							'LOGENTRY_PAGETITLE'		=> $user->lang['ACP_NWOMANAGER_LOGENTRY'],
						));

						$this->tpl_name = 'acp_nwomanager_logentry';
						$this->page_title = 'ACP_NWOMANAGER_LOGENTRY';
					}
				}

				/*******************
				 * Edit Log Entry  *
				 *******************/
				elseif ($submode == 'editlog')
				{
					if ($logid < 0)
					{
						trigger_error('ACP_NO_LOGENTRY_ID');
					}

					$sqllog = 'SELECT *
						FROM ' . NWOLOGS_TABLE . '
						WHERE log_id= ' . $logid .'';
					$resultlog = $db->sql_query($sqllog);
					$rowlog = $db->sql_fetchrow($resultlog);

					//$logdatetime = request_var('logdatetime', $rowcenter['log_datetime']);

					$logdatetime = request_var('logdatetime', ((!is_null($rowlog['log_datetime']) && !empty($rowlog['log_datetime']) ? $rowlog['log_datetime'] : gmdate('Y-m-d H:i:s'))));
					// Validate date input
					$date_format = 'Y-m-d H:i:s';
					$input = $logdatetime;
					$input = trim($input);
					$time = strtotime($input);
					$isdatevalid = date($date_format, $time) == $input;

					// if it is wrong we just set date to today
					if (!$isdatevalid)
					{
						$logdatetime = gmdate('Y-m-d H:i:s');
					}

					$logserver = request_var('logserver', $rowlog['log_server']);
					//$logadmin  = request_var('logadmin', $rowcenter['log_admin']);
					$logadmin  = request_var('logadmin', ((!is_null($rowlog['log_admin']) && !empty($rowlog['log_admin']) ? $rowlog['log_admin'] : $user->data['user_id'])));
					$logcategory  = request_var('logcategory', $rowlog['log_category']);
					$logtype  = request_var('logtype', $rowlog['log_type']);
					$logsummary = utf8_normalize_nfc(request_var('logsummary', $rowlog['log_summary']), true);
					$lognotes = utf8_normalize_nfc(request_var('lognotes', $rowlog['log_notes']), true);

					if ($submit)
					{

						$uid = $bitfield = $options = '';
						$allow_bbcode = $allow_smilies = $allow_urls = true;
						generate_text_for_storage($lognotes, $uid, $bitfield, $options, true, true, true);

						$sql = 'UPDATE ' . NWOLOGS_TABLE . '
							SET
							log_datetime 		= "' . $logdatetime . '",
							log_server 			= "' . $logserver . '",
							log_admin			= "' . $logadmin . '",
							log_category       	= "' . $logcategory . '",
    						log_type  			= "' . $logtype . '",
    						log_summary			= "' . $logsummary . '",
    						log_notes  			= "' . $lognotes . '",
							bbcode_uid			= "' . $uid . '",
							bbcode_bitfield		= "' . $bitfield . '"
							WHERE log_id='. $logid;
						$results = $db->sql_query($sql);
						$row = $db->sql_fetchrow($results);
						$db->sql_freeresult($results);
						trigger_error(sprintf($user->lang['ACP_NWOMANAGER_LOGENTRY_SAVED'], append_sid('index.php?i=' . $id . '&mode=nwologlist')));
					}

					decode_message($rowlog['log_notes'], $rowlog['bbcode_uid']);

					$pagetitle = $user->lang['ACP_NWOMANAGER_LOGENTRY'] . (!empty($logdatetime) ? ' [' . $logdatetime . ']' : '');

					$template->assign_vars(array(
						'LOG_DATETIME'				=> $logdatetime,
						'LOG_SERVER'				=> build_options_select($serversarray, $logserver),
						'LOG_ADMIN'					=> build_options_select($adminsarray, $logadmin),
						'LOG_CATEGORY'				=> build_options_select($logcategoryexpandedarray, $logcategory),
						'LOG_TYPE'					=> build_options_select($logtypeexpandedarray, $logtype),
						'LOG_SUMMARY'				=> $logsummary,
						'LOG_NOTES'					=> $rowlog['log_notes'],
						'LOGENTRY_TITLE'			=> $logdatetime,
						'U_ACP_NWOMANAGER_LOGLIST'	=> append_sid('index.php?i=' . $id . '&mode=nwologlist'),
						'LOGENTRY_PAGETITLE'		=> $pagetitle,
					));
					$db->sql_freeresult($resultlog);

					$this->tpl_name = 'acp_nwomanager_logentry';
					$this->page_title = $pagetitle;
				}


				/********************
				 * Delete Log Entry *
				 ********************/
				elseif ($submode == 'deletelog')
				{
					$cancellogentry = request_var('cancellogentry', '');
					$confirmlogentry = request_var('confirmlogentry', '');

					if (!empty($cancellogentry))
					{
						redirect(append_sid('index.php?i=' . $id . '&mode=nwologlist'));
					}
					if (empty($confirmlogentry))
					{
						trigger_error('ACP_CONFIRM_LOGENTRY_DELETE');
					}
					if ($logid < 0)
					{
						trigger_error('ACP_NO_LOGENTRY_ID');
					}

					$sql= 'SELECT log_summary FROM ' . NWOLOGS_TABLE . '
							WHERE log_id = ' . $logid;
					$result = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					$logsummary = $row['log_summary'];
					$db->sql_freeresult($result);
					// delete actual log entry
					$sql = 'DELETE FROM ' . NWOLOGS_TABLE . '
							WHERE log_id = ' . $logid;
					$db->sql_query($sql);
					add_log('admin', 'LOG_NWOMANAGER_LOGDELETED', !empty($logsummary) ? $logsummary : 'NWO Log Entry');
					trigger_error(sprintf($user->lang['ACP_LOGENTRY_DELETED'], append_sid('index.php?i=' . $id . '&mode=nwologlist')));
				}


				/************************
				 * View All Log Entries *
				 ************************/
				else
				{

					$start   = request_var('start', 0);
					$limit   = request_var('limit', 25);

					// Start of log filtering option. If specified then we need to adjust sql command, otherwise we show all entries
					$resetfilter = request_var('resetfilter', '');
					if (!empty($resetfilter))
					{
						$logfiltercategory = 0;
						$logfiltertype = 0;
					}

					$dofilter = request_var('dofilter', '');
					if (!empty($dofilter))
					{
						$logfiltercategory = (int) request_var('logfiltercategory', 0);
						$logfiltertype = (int) request_var('logfiltertype', 0);
						//echo $logfiltercategory;
						//echo ' | ' .$logfiltertype;
					}
					else
					{
						$logfiltercategory = 0;
						$logfiltertype = 0;
					}

					if ($logfiltercategory == 0 && $logfiltertype == 0)
					{
						$sql = 'SELECT *
								FROM ' . NWOLOGS_TABLE . '
								ORDER BY log_datetime DESC';
					}
					else
					{
						if ($logfiltercategory != 0 && $logfiltertype == 0)
						{
							$sql_where = "log_category=$logfiltercategory";
						}
						elseif ($logfiltertype != 0  && $logfiltercategory == 0)
						{
							$sql_where = "log_type=$logfiltertype";
						}
						else
						{
							$sql_where =  "log_category=$logfiltercategory AND log_type=$logfiltertype";
						}
						//echo $sql_where;
						$sql = 'SELECT *
								FROM ' . NWOLOGS_TABLE . '
								WHERE ' . $sql_where . '
								ORDER BY log_datetime DESC';
					}

					//$result = $db->sql_query($sql);
					$result = $db->sql_query_limit($sql, $limit, $start);
					while($row = $db->sql_fetchrow($result, $limit, $start))
					//while($row = $db->sql_fetchrow($result))
					{

						$flags = (($config['allow_bbcode']) ? 1 : 0) + (($config['allow_smilies']) ? 2 : 0) + ((true) ? 4 : 0);
						$lognotes = generate_text_for_display($row['log_notes'], $row['bbcode_uid'], $row['bbcode_bitfield'], $flags);

						//$centerlink = !empty($row['center_title']) ? $row['center_title'] : '- No Center Block Title -';

						$template->assign_block_vars('logs', array(
							'U_EDIT'				=> append_sid('index.php?i=' . $id . '&mode=nwologlist&submode=editlog&logid=' . $row['log_id']),
							'U_DELETE'				=> append_sid('index.php?i=' . $id . '&mode=nwologlist&submode=deletelog&logid=' . $row['log_id']),
							'U_NEW'					=> append_sid('index.php?i=' . $id . '&mode=nwologlist&submode=addlog'),
							//'CENTER_TITLE'		=> '<a href="' . append_sid('index.php?i=' . $id . '&mode=nwocenterblocks&submode=editcenter&centerid=' . $row['center_id']) . '" class="title">' . $centerlink . '</a>',
							'LOG_DATETIME'			=> '<a href="' . append_sid('index.php?i=' . $id . '&mode=nwologlist&submode=editlog&logid=' . $row['log_id']) . '" class="title">' . $row['log_datetime'] . '</a>',
							//'LOG_DATETIME'			=> $row['log_datetime'],
							'LOG_SERVER'			=> $serversarray[$row['log_server']],
							'LOG_ADMIN'				=> $usersarray[$row['log_admin']],
							//'LOG_CATEGORY'			=> $logcategoryarray[$row['log_category']],
							'LOG_CATEGORY'			=> $logcategoryexpandedarray[$row['log_category']],
							//'LOG_TYPE'				=> $logtypearray[$row['log_type']],
							'LOG_TYPE'				=> $logtypeexpandedarray[$row['log_type']],
							'LOG_SUMMARY'			=> $row['log_summary'],
							'LOG_NOTES'				=> $lognotes,
							'LOG_SUBMIT'			=> append_sid('index.php?i=' . $id . '&mode=nwologlist&submode=addlog'),
						));
					}
					$db->sql_freeresult($result);

					$template->assign_vars(array(
						'ICON_NEW'		=> '<img src="' . $phpbb_admin_path . 'images/file_new.gif" alt="Add New Entry" title="Add New Entry" />',
					));

					$sql = 'SELECT COUNT(log_id) AS log_count FROM ' . NWOLOGS_TABLE;
					$result = $db->sql_query($sql);
					$total_logentries = $db->sql_fetchfield('log_count');
					$db->sql_freeresult($result);

					$action = $this->u_action;
					$template->assign_vars(array(
	    				'PAGINATION'		=> generate_pagination($action, $total_logentries, $limit, $start),
		    			'PAGE_NUMBER'		=> on_page($total_logentries, $limit, $start),
	    				'TOTAL_LOGENTRIES'	=> $total_logentries,
	    				'LOG_FILTERCATEGORY'=> build_options_select($logcategoryfilterarray, !empty($logfiltercategory) ? $logfiltercategory : 0),
	    				'LOG_FILTERTYPE'	=> build_options_select($logtypefilterarray, !empty($logfiltertype) ? $logfiltertype : 0),
					));

					$this->tpl_name = 'acp_nwomanager_loglist';
					$this->page_title = 'ACP_NWOMANAGER_LOGLIST';

				}



			break;


			/*********************************************************************************************************************************************************
			 * Center Blocks in ACP
			 *********************************************************************************************************************************************************/
			case 'nwocenterblocks':

				$submode = request_var('submode', '');
				$centerid = request_var('centerid', '');
				$addcenter = request_var('addcenter', '');

				// Handle some specials cases first for submode. Like add center
				if (!empty($addcenter))
				{
					$submode = 'addcenter';
				}

				/******************************************
				 * Check for submode to process:          *
				 *                                        *
				 * - Add New Center                       *
				 * - Edit Center                          *
				 * - Center Server                        *
				 * - Move Center Up                       *
				 * - Move Center Dn                       *
				 * - Toggle Center                        *
				 * - View All Centers (Default)           *
				 *                                        *
				 ******************************************/

				/******************
				 * Add New Center *
				 ******************/
				if ($submode == 'addcenter')
				{
					if ($submit)
					{
						$centertitle = utf8_normalize_nfc(request_var('centertitle', 'New Center Block'), true);
						$centertext = utf8_normalize_nfc(request_var('centertext', ''), true);
						$centervisible = (int) request_var('centervisible', 1);

						$uid = $bitfield = $options = '';
						$allow_bbcode = $allow_smilies = $allow_urls = true;
						generate_text_for_storage($centertext, $uid, $bitfield, $options, true, true, true);

						// Get total center blocks. This for the order id, new entry is added with total center block+1
						$sql = 'SELECT COUNT(center_id) AS total_centers FROM ' . NWOCENTERBLOCKS_TABLE;
						$result = $db->sql_query($sql);
						$total_centers = $db->sql_fetchfield('total_centers');
						$db->sql_freeresult($result);

						// Create new server array for sql insert
						$sql_centerblock = array(
							'center_title'				=> $centertitle,
							'center_text'				=> $centertext,
							'center_visible'			=> $centervisible,
							'center_order'				=> (int) $total_centers -1 +2,
							'bbcode_uid'				=> $uid,
							'bbcode_bitfield'			=> $bitfield,
						);
						$db->sql_query('INSERT INTO ' . NWOCENTERBLOCKS_TABLE . $db->sql_build_array('INSERT', $sql_centerblock));
						trigger_error(sprintf($user->lang['ACP_NWOMANAGER_CENTERBLOCK_SAVED'], append_sid('index.php?i=' . $id . '&mode=nwocenterblocks')));
					}
					else
					{

						$centertitle = utf8_normalize_nfc(request_var('centertitle','New Center Block'), true);

						$template->assign_vars(array(
							'CENTER_TITLE'			=> !empty($centertitle) ? $centertitle : 'New Center Block',
							'CENTER_TEXT'			=> '',
							'S_CENTER_VISIBLE'		=> true,
							'U_ACP_NWOMANAGER_CENTERBLOCKS'	=> append_sid('index.php?i=' . $id . '&mode=nwocenterblocks'),
							'U_ACTION'				=> append_sid('index.php?i=' . $id . '&mode=nwocenterblocks&submode=addcenter'),
							'CENTERBLOCK_PAGETITLE'		=> $user->lang['ACP_NWOMANAGER_CENTERBLOCK'],
							'CNTR_TITLE'				=> $user->lang['ACP_NWOMANAGER_CENTERBLOCK'],
						));

						$this->tpl_name = 'acp_nwomanager_centerblock';
						$this->page_title = 'ACP_NWOMANAGER_CENTERBLOCK';
					}
				}

				/****************
				 * Edit Center  *
				 ****************/
				elseif ($submode == 'editcenter')
				{
					if ($centerid < 0)
					{
						trigger_error('ACP_NO_CENTERBLOCK_ID');
					}

					$sqlcenter = 'SELECT *
						FROM ' . NWOCENTERBLOCKS_TABLE . '
						WHERE center_id= ' . $centerid .'';
					$resultcenter = $db->sql_query($sqlcenter);
					$rowcenter = $db->sql_fetchrow($resultcenter);

					$centertitle = utf8_normalize_nfc(request_var('centertitle', $rowcenter['center_title']), true);
					$centertext = utf8_normalize_nfc(request_var('centertext', $rowcenter['center_text']), true);
					$centervisible = (int) request_var('centervisible', $rowcenter['center_visible']);

					if ($submit)
					{

						$uid = $bitfield = $options = '';
						$allow_bbcode = $allow_smilies = $allow_urls = true;
						//generate_text_for_storage($centertext, $uid, $bitfield, $flags);
						generate_text_for_storage($centertext, $uid, $bitfield, $options, true, true, true);
						//generate_text_for_storage($centertext, $uid, $bitfield, $flags);

						$sql = 'UPDATE ' . NWOCENTERBLOCKS_TABLE . '
							SET
							center_title 		= "' . $centertitle . '",
							center_text 		= "' . $centertext . '",
							center_visible		= "' . $centervisible . '",
							bbcode_uid        	= "' . $uid . '",
    						bbcode_bitfield  	= "' . $bitfield . '"
							WHERE center_id='. $centerid;
						$results = $db->sql_query($sql);
						$row = $db->sql_fetchrow($results);
						$db->sql_freeresult($results);
						trigger_error(sprintf($user->lang['ACP_NWOMANAGER_CENTERBLOCK_SAVED'], append_sid('index.php?i=' . $id . '&mode=nwocenterblocks')));
					}

					//decode_message($centertext, $uid);
					decode_message($rowcenter['center_text'], $rowcenter['bbcode_uid']);

					$pagetitle = $user->lang['ACP_NWOMANAGER_CENTERBLOCK'] . (!empty($centertitle) ? ' [' . $centertitle . ']' : '');

					$template->assign_vars(array(
						'CENTER_TITLE'			=> $centertitle,
						'CENTER_TEXT'			=> $rowcenter['center_text'], //$centertext,
						'S_CENTER_VISIBLE'		=> $centervisible,
						'U_ACP_NWOMANAGER_CENTERBLOCKS'	=> append_sid('index.php?i=' . $id . '&mode=nwocenterblocks'),
						'CENTERBLOCK_PAGETITLE'		=> $pagetitle,
						'CNTR_TITLE'			=> !empty($servername) ? $servername : $user->lang['ACP_NWOMANAGER_CENTERBLOCK'],
					));
					$db->sql_freeresult($resultserver);

					$this->tpl_name = 'acp_nwomanager_centerblock';
					$this->page_title = $pagetitle;
				}

				/****************
				 * Delete Center*
				 ****************/
				elseif ($submode == 'deletecenter')
				{
					$cancelcenter = request_var('cancelcenterblock', '');
					$confirmcenter = request_var('confirmcenterblock', '');

					if (!empty($cancelcenter))
					{
						redirect(append_sid('index.php?i=' . $id . '&mode=nwocenterblocks'));
					}
					if (empty($confirmcenter))
					{
						trigger_error('ACP_CONFIRM_CENTERBLOCK_DELETE');
					}
					if ($centerid < 0)
					{
						trigger_error('ACP_NO_CENTERBLOCK_ID');
					}

					// delete actual center
					$sql = 'DELETE FROM ' . NWOCENTERBLOCKS_TABLE . '
							WHERE center_id = ' . $centerid;
					$db->sql_query($sql);
					trigger_error(sprintf($user->lang['ACP_CENTERBLOCK_DELETED'], append_sid('index.php?i=' . $id . '&mode=nwocenterblocks')));
				}


				/****************
				 * Mv Center Up *
				 ****************/
				elseif ($submode == 'movecenterup')
				{
					$sqlcenter = 'SELECT center_order
						FROM ' . NWOCENTERBLOCKS_TABLE . '
						WHERE center_id= ' . $centerid .'';
					$resultcenter = $db->sql_query($sqlcenter);
					$rowcenter = $db->sql_fetchrow($resultcenter);

					$centerorder = $rowcenter['center_order'];

					$sql = 'UPDATE ' . NWOCENTERBLOCKS_TABLE . '
							SET
							center_order = ' . $centerorder . '
							WHERE center_order = ' . $centerorder . '-2 +1';
					$db->sql_query($sql);

					$sql = 'UPDATE ' . NWOCENTERBLOCKS_TABLE . '
							SET
							center_order = ' . $centerorder . '-2 +1
							WHERE center_id = ' . $centerid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultcenter);

					redirect(append_sid('index.php?i=' . $id . '&mode=nwocenterblocks'));
				}

				/****************
				 * Mv Center Dn *
				 ****************/
				elseif ($submode == 'movecenterdn')
				{
					$sqlcenter = 'SELECT center_order
						FROM ' . NWOCENTERBLOCKS_TABLE . '
						WHERE center_id= ' . $centerid .'';
					$resultcenter = $db->sql_query($sqlcenter);
					$rowcenter = $db->sql_fetchrow($resultcenter);

					$centerorder = $rowcenter['center_order'];

					$sql = 'UPDATE ' . NWOCENTERBLOCKS_TABLE . '
							SET
							center_order = ' . $centerorder . '
							WHERE center_order = ' . $centerorder . '-1 +2';
					$db->sql_query($sql);

					$sql = 'UPDATE ' . NWOCENTERBLOCKS_TABLE . '
							SET
							center_order = ' . $centerorder . '-1 +2
							WHERE center_id = ' . $centerid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultcenter);

					redirect(append_sid('index.php?i=' . $id . '&mode=nwocenterblocks'));
				}


				/*****************
				 * Toggle Center *
				 *****************/
				elseif ($submode == 'togglecenter')
				{
					$sqlcenter = 'SELECT center_visible
						FROM ' . NWOCENTERBLOCKS_TABLE . '
						WHERE center_id= ' . $centerid .'';
					$resultcenter = $db->sql_query($sqlcenter);
					$rowcenter = $db->sql_fetchrow($resultcenter);

					$centervisible = $rowcenter['center_visible'];
					$sql = 'UPDATE ' . NWOCENTERBLOCKS_TABLE . '
							SET
							center_visible = ' . ($centervisible ? "0" : "1") .'
							WHERE center_id = ' . $centerid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultcenter);

					redirect(append_sid('index.php?i=' . $id . '&mode=nwocenterblocks'));
				}


				/********************
				 * View All Centers *
				 ********************/
				else
				{
					//$start   = request_var('start', 0);
					//$limit   = request_var('limit', 25);
					$sql = 'SELECT *
							FROM ' . NWOCENTERBLOCKS_TABLE . '
							ORDER BY center_order ASC';
					$result = $db->sql_query($sql);
					//$result = $db->sql_query_limit($sql, $limit, $start);
					//while($row = $db->sql_fetchrow($result, $limit, $start))
					while($row = $db->sql_fetchrow($result))
					{

						$flags = (($config['allow_bbcode']) ? 1 : 0) + (($config['allow_smilies']) ? 2 : 0) + ((true) ? 4 : 0);
						$centertext = generate_text_for_display($row['center_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $flags);

						$centerlink = !empty($row['center_title']) ? $row['center_title'] : '- No Center Block Title -';

						$template->assign_block_vars('centerblocks', array(
							'U_EDIT'				=> append_sid('index.php?i=' . $id . '&mode=nwocenterblocks&submode=editcenter&centerid=' . $row['center_id']),
							'U_DELETE'				=> append_sid('index.php?i=' . $id . '&mode=nwocenterblocks&submode=deletecenter&centerid=' . $row['center_id']),
							'U_MOVE_DOWN'			=> append_sid('index.php?i=' . $id . '&mode=nwocenterblocks&submode=movecenterdn&centerid=' . $row['center_id']),
							'U_MOVE_UP'				=> append_sid('index.php?i=' . $id . '&mode=nwocenterblocks&submode=movecenterup&centerid=' . $row['center_id']),
							'U_VISIBLE'				=> append_sid('index.php?i=' . $id . '&mode=nwocenterblocks&submode=togglecenter&centerid=' . $row['center_id']),
							'CENTER_TITLE'			=> '<a href="' . append_sid('index.php?i=' . $id . '&mode=nwocenterblocks&submode=editcenter&centerid=' . $row['center_id']) . '" class="title">' . $centerlink . '</a>',
							'CENTER_TEXT'			=> $centertext, //$row['center_text'],
							'S_CENTER_VISIBLE'		=> $row['center_visible'] ? true : false,
							'CENTER_SUBMIT'			=> append_sid('index.php?i=' . $id . '&mode=nwocenterblocks&submode=addcenter'),
						));
					}
					$db->sql_freeresult($result);

					$template->assign_vars(array(
						'ICON_BULB_ON'		=> '<img src="' . $phpbb_admin_path . 'images/icon_lightbulb_on.gif" alt="Visible" title="Visible: click to switch off" />',
						'ICON_BULB_OFF'		=> '<img src="' . $phpbb_admin_path . 'images/icon_lightbulb_off.gif" alt="Not visible" title="Not visible: Click to switch on" />',
					));

					$sql = 'SELECT COUNT(center_id) AS center_count FROM ' . NWOCENTERBLOCKS_TABLE;
					$result = $db->sql_query($sql);
					$total_centers = $db->sql_fetchfield('center_count');
					$db->sql_freeresult($result);

					$action = $this->u_action;
					$template->assign_vars(array(
	    				'PAGINATION'		=> generate_pagination($action, $total_centers, $limit, $start),
		    			'PAGE_NUMBER'		=> on_page($total_centers, $limit, $start),
	    				'TOTAL_CENTERS'		=> $total_centers,
					));

					$this->tpl_name = 'acp_nwomanager_centerblocks';
					$this->page_title = 'ACP_NWOMANAGER_CENTERBLOCKS';

				}

			break;






			/*********************************************************************************************************************************************************
			 * Server List in ACP
			 *********************************************************************************************************************************************************/
			case 'nwoserverlist':

				$submode = request_var('submode', '');
				$serverid = request_var('serverid', '');
				$addserver = request_var('addserver', '');

				$servertypearray = array(
					'0'		=>	'CSS',
					'1'		=>	'TF2',
					'2'		=>	'L4D',
					'3'		=>	'L4D2',
					'4'		=>	'COD4',
					'5'		=>	'COD5',
					'6'		=>	'INS',
					'7'		=>	'HL2',
					'8'		=>	'Mod',
					'9'		=>	'Other',
				);

				$adminmodarray = array(
					'0' 	=>	'Mani Admin',
					'1'		=>	'SourceMod Admin',
					'2'		=>	'eXtensible Admin',
					'3'		=>	'RCON Only',
				);

				// Handle some specials cases first for submode. Like add server
				if (!empty($addserver))
				{
					$submode = 'addserver';
				}

				/******************************************
				 * Check for submode to process:          *
				 *                                        *
				 * - Add New Server                       *
				 * - Edit Server                          *
				 * - Delete Server                        *
				 * - Move Server Up                       *
				 * - Move Server Dn                       *
				 * - Toggle Server                        *
				 * - View All Servers (Default)           *
				 *                                        *
				 ******************************************/

				/******************
				 * Add New Server *
				 ******************/
				if ($submode == 'addserver')
				{
					if ($submit)
					{

						$servername = utf8_normalize_nfc(request_var('servername', 'New Server'), true);
						$serverdescription = utf8_normalize_nfc(request_var('serverdescription', ''), true);
						$serverip = request_var('serverip', '');
						$servermap = utf8_normalize_nfc(request_var('servermap', ''), true);
						$servertype = (int) request_var('servertype', 0);
						$serveradminmod = (int) request_var('serveradminmod', 0);
						$serverprivate = (int) request_var('serverprivate', 0);
						$serverhlstatsx = (int) request_var('serverhlstatsx', 0);
						$serversourcebans = (int) request_var('serversourcebans', 0);
						$serversteambans = (int) request_var('serversteambans', 0);
						$servervisible = (int) request_var('servervisible', 1);

						// Get total server. This for the order id, new entry is added with total servers+1
						$sql = 'SELECT COUNT(server_id) AS total_servers FROM ' . NWOSERVERS_TABLE;
						$result = $db->sql_query($sql);
						$total_servers = $db->sql_fetchfield('total_servers');
						$db->sql_freeresult($result);

						// Create new server array for sql insert
						$sql_nwoserver = array(
							'server_name'			=> $servername,
							'server_description'	=> $serverdescription,
							'server_ip'				=> $serverip,
							'server_map'			=> $servermap,
							'server_type'			=> $servertype,
							'server_adminmod'		=> $serveradminmod,
							'server_private'		=> $serverprivate,
							'server_hlstatsx'		=> $serverhlstatsx,
							'server_sourcebans'		=> $serversourcebans,
							'server_steambans'		=> $serversteambans,
							'server_order'			=> (int) $total_servers -1 +2,
							'server_visible'		=> $servervisible,
						);
						$db->sql_query('INSERT INTO ' . NWOSERVERS_TABLE . $db->sql_build_array('INSERT', $sql_nwoserver));
						trigger_error(sprintf($user->lang['ACP_NWOMANAGER_SERVER_SAVED'], append_sid('index.php?i=' . $id . '&mode=nwoserverlist')));
					}
					else
					{

						$servername = utf8_normalize_nfc(request_var('servername','New Server'), true);

						$template->assign_vars(array(
							'SERVER_NAME'			=> $servername,
							'SERVER_DESCRIPTION'	=> '',
							'SERVER_IP'				=> '',
							'SERVER_MAP'			=> '',
							'SERVER_TYPE'			=> build_options_select($servertypearray, 0),
							'SERVER_ADMINMOD'		=> build_options_select($adminmodarray, 0),
							'S_SERVER_PRIVATE'		=> false,
							'S_SERVER_HLSTATSX'		=> false,
							'S_SERVER_SOURCEBANS'	=> false,
							'S_SERVER_STEAMBANS'	=> false,
							'S_SERVER_VISIBLE'		=> true,
							'U_ACP_NWOMANAGER_SERVERLIST'	=> append_sid('index.php?i=' . $id . '&mode=nwoserverlist'),
							'U_ACTION'				=> append_sid('index.php?i=' . $id . '&mode=nwoserverlist&submode=addserver'),
							'SERVER_PAGETITLE'		=> $user->lang['ACP_NWOMANAGER_SERVER'],
							'SERVER_TITLE'			=> $user->lang['ACP_NWOMANAGER_SERVER'],
						));

						$this->tpl_name = 'acp_nwomanager_server';
						$this->page_title = 'ACP_NWOMANAGER_SERVER';
					}
				}


				/****************
				 * Edit Server  *
				 ****************/
				elseif ($submode == 'editserver')
				{
					if ($serverid < 0)
					{
						trigger_error('ACP_NO_SERVER_ID');
					}

					$sqlserver = 'SELECT *
						FROM ' . NWOSERVERS_TABLE . '
						WHERE server_id= ' . $serverid .'';
					$resultserver = $db->sql_query($sqlserver);
					$rowserver = $db->sql_fetchrow($resultserver);

					$servername = utf8_normalize_nfc(request_var('servername', $rowserver['server_name']), true);
					$serverdescription = utf8_normalize_nfc(request_var('serverdescription', $rowserver['server_description']), true);
					$serverip = request_var('serverip', $rowserver['server_ip']);
					$servermap = utf8_normalize_nfc(request_var('servermap', $rowserver['server_map']), true);
					$servertype = (int) request_var('servertype', $rowserver['server_type']);
					$serveradminmod = (int) request_var('serveradminmod', $rowserver['server_adminmod']);
					$serverprivate = (int) request_var('serverprivate', $rowserver['server_private']);
					$serverhlstatsx = (int) request_var('serverhlstatsx', $rowserver['server_hlstatsx']);
					$serversourcebans = (int) request_var('serversourcebans', $rowserver['server_sourcebans']);
					$serversteambans = (int) request_var('serversteambans', $rowserver['server_steambans']);
					$servervisible = (int) request_var('servervisible', $rowserver['server_visible']);

					if ($submit)
					{
						$sql = 'UPDATE ' . NWOSERVERS_TABLE . '
							SET
							server_name 		= "' . $servername . '",
							server_description 	= "' . $serverdescription . '",
							server_ip			= "' . $serverip . '",
							server_map			= "' . $servermap . '",
							server_type			= "' . $servertype . '",
							server_adminmod     = "' . $serveradminmod . '",
    						server_private  	= "' . $serverprivate . '",
    						server_hlstatsx		= "' . $serverhlstatsx . '",
    						server_sourcebans	= "' . $serversourcebans . '",
    						server_steambans	= "' . $serversteambans . '",
							server_visible		= "' . $servervisible . '"
							WHERE server_id='. $serverid;
						$results = $db->sql_query($sql);
						$row = $db->sql_fetchrow($results);
						$db->sql_freeresult($results);
						trigger_error(sprintf($user->lang['ACP_NWOMANAGER_SERVER_SAVED'], append_sid('index.php?i=' . $id . '&mode=nwoserverlist')));
					}

					$pagetitle = $user->lang['ACP_NWOMANAGER_SERVER'] . (!empty($servername) ? ' [' . $servername . ']' : '');

					$template->assign_vars(array(
						'SERVER_NAME'			=> $servername,
						'SERVER_DESCRIPTION'	=> $serverdescription,
						'SERVER_IP'				=> $serverip,
						'SERVER_MAP'			=> $servermap,
						'SERVER_TYPE'			=> build_options_select($servertypearray, $servertype),
						'SERVER_ADMINMOD'		=> build_options_select($adminmodarray, $serveradminmod),
						'S_SERVER_PRIVATE'		=> $serverprivate,
						'S_SERVER_HLSTATSX'		=> $serverhlstatsx,
						'S_SERVER_SOURCEBANS'	=> $serversourcebans,
						'S_SERVER_STEAMBANS'	=> $serversteambans,
						'S_SERVER_VISIBLE'		=> $servervisible,
						'U_ACP_NWOMANAGER_SERVERLIST'	=> append_sid('index.php?i=' . $id . '&mode=nwoserverlist'),
						'SERVER_PAGETITLE'		=> $pagetitle,
						'SERVER_TITLE'			=> !empty($servername) ? $servername : $user->lang['ACP_NWOMANAGER_SERVER'],
					));
					$db->sql_freeresult($resultserver);

					$this->tpl_name = 'acp_nwomanager_server';
					$this->page_title = $pagetitle;
				}


				/******************
				 * Delete Server  *
				 ******************/
				elseif ($submode == 'deleteserver')
				{
					$cancelserver = request_var('cancelserver', '');
					$confirmserver = request_var('confirmserver', '');

					if (!empty($cancelserver))
					{
						redirect(append_sid('index.php?i=' . $id . '&mode=nwoserverlist'));
					}
					if (empty($confirmsection))
					{
						trigger_error('ACP_CONFIRM_SERVER_DELETE');
					}
					if ($serverid < 0)
					{
						trigger_error('ACP_NO_SERVER_ID');
					}

					// delete actual server
					$sql = 'DELETE FROM ' . NWOSERVERS_TABLE . '
							WHERE server_id = ' . $serverid;
					$db->sql_query($sql);
					trigger_error(sprintf($user->lang['ACP_SERVER_DELETED'], append_sid('index.php?i=' . $id . '&mode=nwoserverlist')));
				}


				/******************
				 * Move Server Dn *
				 ******************/
				elseif ($submode == 'moveserverdn')
				{
					$sqlserver = 'SELECT server_order
						FROM ' . NWOSERVERS_TABLE . '
						WHERE server_id= ' . $serverid .'';
					$resultserver = $db->sql_query($sqlserver);
					$rowserver = $db->sql_fetchrow($resultserver);

					$serverorder = $rowserver['server_order'];

					$sql = 'UPDATE ' . NWOSERVERS_TABLE . '
							SET
							server_order = ' . $serverorder . '
							WHERE server_order = ' . $serverorder . '-1 +2';
					$db->sql_query($sql);

					$sql = 'UPDATE ' . NWOSERVERS_TABLE . '
							SET
							server_order = ' . $serverorder . '-1 +2
							WHERE server_id = ' . $serverid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultserver);

					redirect(append_sid('index.php?i=' . $id . '&mode=nwoserverlist'));
				}


				/******************
				 * Move Server Up *
				 ******************/
				elseif ($submode == 'moveserverup')
				{
					$sqlserver = 'SELECT server_order
						FROM ' . NWOSERVERS_TABLE . '
						WHERE server_id= ' . $serverid .'';
					$resultserver = $db->sql_query($sqlserver);
					$rowserver = $db->sql_fetchrow($resultserver);

					$serverorder = $rowserver['server_order'];

					$sql = 'UPDATE ' . NWOSERVERS_TABLE . '
							SET
							server_order = ' . $serverorder . '
							WHERE server_order = ' . $serverorder . '-2 +1';
					$db->sql_query($sql);

					$sql = 'UPDATE ' . NWOSERVERS_TABLE . '
							SET
							server_order = ' . $serverorder . '-2 +1
							WHERE server_id = ' . $serverid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultserver);

					redirect(append_sid('index.php?i=' . $id . '&mode=nwoserverlist'));
				}


				/******************
				 * Toggle Server  *
				 ******************/
				elseif ($submode == 'toggleserver')
				{
					$sqlserver = 'SELECT server_visible
						FROM ' . NWOSERVERS_TABLE . '
						WHERE server_id= ' . $serverid .'';
					$resultserver = $db->sql_query($sqlserver);
					$rowserver = $db->sql_fetchrow($resultserver);

					$servervisible = $rowserver['server_visible'];
					$sql = 'UPDATE ' . NWOSERVERS_TABLE . '
							SET
							server_visible = ' . ($servervisible ? "0" : "1") .'
							WHERE server_id = ' . $serverid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultserver);

					redirect(append_sid('index.php?i=' . $id . '&mode=nwoserverlist'));
				}


				/********************
				 * View All Servers *
				 ********************/
				else
				{
					//$start   = request_var('start', 0);
					//$limit   = request_var('limit', 25);
					$sql = 'SELECT *
							FROM ' . NWOSERVERS_TABLE . '
							ORDER BY server_order ASC';
					$result = $db->sql_query($sql);
					//$result = $db->sql_query_limit($sql, $limit, $start);
					//while($row = $db->sql_fetchrow($result, $limit, $start))
					while($row = $db->sql_fetchrow($result))
					{
						$template->assign_block_vars('servers', array(
							'U_EDIT'				=> append_sid('index.php?i=' . $id . '&mode=nwoserverlist&submode=editserver&serverid=' . $row['server_id']),
							'U_DELETE'				=> append_sid('index.php?i=' . $id . '&mode=nwoserverlist&submode=deleteserver&serverid=' . $row['server_id']),
							'U_MOVE_DOWN'			=> append_sid('index.php?i=' . $id . '&mode=nwoserverlist&submode=moveserverdn&serverid=' . $row['server_id']),
							'U_MOVE_UP'				=> append_sid('index.php?i=' . $id . '&mode=nwoserverlist&submode=moveserverup&serverid=' . $row['server_id']),
							'U_VISIBLE'				=> append_sid('index.php?i=' . $id . '&mode=nwoserverlist&submode=toggleserver&serverid=' . $row['server_id']),
							'SERVER_NAME'		=> '<a href="' . append_sid('index.php?i=' . $id . '&mode=nwoserverlist&submode=editserver&serverid=' . $row['server_id']) . '" class="title">' . $row['server_name'] . '</a>',
							//'SERVER_NAME'			=> $row['server_name'],
							'SERVER_DESCRIPTION'	=> $row['server_description'],
							'SERVER_IP'				=> $row['server_ip'],
							'SERVER_MAP'			=> $row['server_map'],
							'SERVER_TYPE'			=> $servertypearray[$row['server_type']],
							'SERVER_ADMINMOD'		=> $adminmodarray[$row['server_adminmod']],
							'S_SERVER_PRIVATE'		=> $row['server_private'] ? true : false,
							'S_SERVER_HLSTATSX'		=> $row['server_hlstatsx'] ? true : false,
							'S_SERVER_SOURCEBANS'	=> $row['server_sourcebans'] ? true : false,
							'S_SERVER_STEAMBANS'	=> $row['server_steambans'] ? true : false,
							'S_SERVER_VISIBLE'		=> $row['server_visible'] ? true : false,
							'SERVER_SUBMIT'			=> append_sid('index.php?i=' . $id . '&mode=nwoserver&submode=addserver'),
						));
					}
					$db->sql_freeresult($result);

					$template->assign_vars(array(
						'ICON_BULB_ON'		=> '<img src="' . $phpbb_admin_path . 'images/icon_lightbulb_on.gif" alt="Visible" title="Visible: click to switch off" />',
						'ICON_BULB_OFF'		=> '<img src="' . $phpbb_admin_path . 'images/icon_lightbulb_off.gif" alt="Not visible" title="Not visible: Click to switch on" />',
					));

					$sql = 'SELECT COUNT(server_id) AS server_count FROM ' . NWOSERVERS_TABLE;
					$result = $db->sql_query($sql);
					$total_servers = $db->sql_fetchfield('server_count');
					$db->sql_freeresult($result);

					$action = $this->u_action;
					$template->assign_vars(array(
	    				'PAGINATION'		=> generate_pagination($action, $total_servers, $limit, $start),
		    			'PAGE_NUMBER'		=> on_page($total_servers, $limit, $start),
	    				'TOTAL_SERVERS'		=> $total_servers,
					));

					$this->tpl_name = 'acp_nwomanager_serverlist';
					$this->page_title = 'ACP_NWOMANAGER_SERVERLIST';
				}
			break;

			/*********************************************************************************************************************************************************
			 * Settings in ACP
			 *********************************************************************************************************************************************************/
			case 'nwosettings':
				$display_vars = array(
				    'title' => 'ACP_NWOMANAGER_SETTINGS',
				    'vars'    => array(
				        'legend1'								=> 'ACP_NWOMANAGER_SETTINGS_NEWS',
				        'nwomanager_news_forumid'				=> array('lang' => 'ACP_NWOMANAGER_SETTINGS_NEWS_FORUMID', 'validate' => 'int', 'type' => 'select', 'function' => 'forum_select', 'params' => array('{CONFIG_VALUE}'), 'explain' => true),
				        'nwomanager_news_maxdisplayed'			=> array('lang' => 'ACP_AFKMANAGER_SETTINGS_NEWS_MAX', 'validate' => 'string', 'type' => 'text', 'explain' => true),
						'legend2'								=> 'ACP_NWOMANAGER_SETTINGS_TOPICS',
						'nwomanager_topics_forumid'				=> array('lang' => 'ACP_NWOMANAGER_SETTINGS_TOPICS_FORUMID', 'validate' => 'int', 'type' => 'select', 'function' => 'forum_select', 'params' => array('{CONFIG_VALUE}'), 'explain' => true),
						//'nwomanager_topics_forumid'				=> array('lang' => 'ACP_NWOMANAGER_SETTINGS_TOPICS_FORUMID', 'validate' => 'string', 'type' => 'select_multiple', 'function' => 'forum_multi_select', 'params' => array('{CONFIG_VALUE}', &$mf), 'explain' => true),
						'nwomanager_topics_maxdisplayed'		=> array('lang' => 'ACP_AFKMANAGER_SETTINGS_TOPICS_MAX', 'validate' => 'string', 'type' => 'text', 'explain' => true),
						'legend3'								=> 'ACP_NWOMANAGER_SETTINGS_WELCOME',
						'nwomanager_welcome_box'				=> array('lang' => 'ACP_NWOMANAGER_SETTINGS_WELCOME_BOX', 'validate' => 'string', 'type' => 'textarea', 'explain' => true),
						'legend4'								=> 'ACP_NWOMANAGER_SETTINGS_LOG',
						'nwomanager_log_admins'					=> array('lang' => 'ACP_AFKMANAGER_SETTINGS_LOG_ADMINS', 'validate' => 'string', 'type' => 'text', 'explain' => true),
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
					trigger_error(sprintf($user->lang['ACP_NWOMANAGER_SETTINGS_SAVED'], append_sid('index.php?i=' . $id . '&mode=nwosettings')));
					break ;
				}
				$this->tpl_name = 'acp_nwomanager_settings';
                $this->page_title = $user->lang['ACP_NWOMANAGER_SETTINGS'];

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

			/*********************************************************************************************************************************************************
			 * Links in ACP
			 *********************************************************************************************************************************************************/
			case 'nwomenulinks':
				$submode = request_var('submode', '');
				$blockid = request_var('blockid', 0);
				$menuid = request_var('menuid', '');
				$addblock = request_var('addblock', '');
				$addmenu = request_var('addmenu', '');

				$blockpositionsarray = array(
					'0'		=>	'Left',
					'1'		=>	'Top',
					'2'		=>	'Sub-Top',
					'3'		=>	'Bottom',
					'4'		=>	'Sub-Bottom',
					'5'		=>	'Right',
					//'6'		=>	'Center',
				);

				$blocktypesarray = array(
					'0' 	=>	'Text',
					'1'		=>	'Image',
					//'2'		=>	'Both',
				);

				// Get blocks into array for later
				$sql = 'SELECT *
						FROM ' . NWOMENUBLOCKS_TABLE . '
						ORDER BY block_order ASC';
				$result = $db->sql_query($sql);
				$blocksarray = array();

				while ($row = $db->sql_fetchrow($result))
				{
					$blocksarray[$row['block_id']] = $row['block_title'];
				}
				$db->sql_freeresult($result);

				// Get block title
				if ($blockid < 0)
				{
					$blocktitle = '';
				}
				else
				{
					$sql = 'SELECT block_title
							FROM ' . NWOMENUBLOCKS_TABLE . '
							WHERE block_id=' . $blockid;
					$result = $db->sql_query($sql);
					if($row = $db->sql_fetchrow($result))
					{
						$blocktitle = trim($row['block_title']);
						$db->sql_freeresult($result);
					}
					else
					{
						$blocktitle = '';
					}
				}

				// Handle some specials cases first for submode. Like add server
				if (!empty($addblock))
				{
					$submode = 'addblock';
				}
				if (!empty($addmenu))
				{
					$submode = 'addmenu';
				}
				if (empty($submode))
				{
					$submode = 'viewblocks';
				}

				/******************************************
				 * Categories
				 ******************************************/

				/******************************************
				 * Check for submode to process:          *
				 *                                        *
				 * - Add New Block                     *
				 * - Edit Category                        *
				 * - Delete Category                      *
				 * - Move Category Up                     *
				 * - Move Category Down                   *
				 * - Toggle Category Visible              *
				 * - View All Category (Default)          *
				 *                                        *
				 ******************************************/

				/**********************
				 * Add New Menu Block *
				 **********************/
				if ($submode == 'addblock')
				{
					if ($submit)
					{
						$blocktitle = utf8_normalize_nfc(request_var('blocktitle', 'New Block'), true);
						$blocktext = utf8_normalize_nfc(request_var('blocktext', ''), true);
						$blockurl = utf8_normalize_nfc(request_var('blockurl', ''), true);
						$blockclass	= utf8_normalize_nfc(request_var('blockclass', ''), true);
						$blockdivid = utf8_normalize_nfc(request_var('blockdivid', ''), true);
						$blockposition = (int) request_var('blockposition', 0);
						$blocktype = (int) request_var('blocktype', 0);
						$blockvisible = (int) request_var('blockvisible', 0);

						// Get total menus. This for the order id, new entry is added with total menus+1
						$sql = 'SELECT COUNT(block_id) AS total_blocks FROM ' . NWOMENUBLOCKS_TABLE;
						$result = $db->sql_query($sql);
						$total_blocks = $db->sql_fetchfield('total_blocks');
						$db->sql_freeresult($result);

						// Create new server array for sql insert
						$sql_block = array(
							'block_title'				=> $blocktitle,
							'block_text'				=> $blocktext,
							'block_url'					=> $blockurl,
							'block_class'				=> $blockclass,
							'block_divid'				=> $blockdivid,
							'block_position'			=> $blockposition,
							'block_type'				=> $blocktype,
							'block_visible'				=> $blockvisible,
							'block_order'				=> (int) $total_blocks -1 +2,
						);
						$db->sql_query('INSERT INTO ' . NWOMENUBLOCKS_TABLE . $db->sql_build_array('INSERT', $sql_block));
						trigger_error(sprintf($user->lang['ACP_NWOMANAGER_MENUBLOCK_SAVED'], append_sid('index.php?i=' . $id . '&mode=nwomenulinks')));
					}
					else
					{

						$blocktitle = utf8_normalize_nfc(request_var('blocktitle','New Block'), true);

						$template->assign_vars(array(
							'BLOCK_TITLE'			=> !empty($blocktitle) ? $blocktitle : 'New Block',
							'BLOCK_TEXT'			=> '',
							'BLOCK_URL'				=> '',
							'BLOCK_CLASS'			=> '',
							'BLOCK_DIVID'			=> '',
							'BLOCK_POSITION'		=> build_options_select($blockpositionsarray, 0),
							'BLOCK_TYPE'			=> build_options_select($blocktypesarray, 0),
							'S_BLOCK_VISIBLE'		=> true,
							'U_ACP_NWOMANAGER_MENULINKS'	=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks'),
							'U_ACTION'				=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=addblock'),
							'BLOCK_PAGETITLE'		=> $user->lang['ACP_NWOMANAGER_MENUBLOCK'],
							'BLK_TITLE'				=> $user->lang['ACP_NWOMANAGER_MENUBLOCK'],
						));

						$this->tpl_name = 'acp_nwomanager_menublock';
						$this->page_title = 'ACP_NWOMANAGER_MENUBLOCK';
					}
				}


				/********************
				 * Edit Menu Block  *
				 ********************/
				elseif ($submode == 'editblock')
				{
					if ($blockid < 0)
					{
						trigger_error('ACP_NO_BLOCK_ID');
					}

					$sqlblock = 'SELECT *
						FROM ' . NWOMENUBLOCKS_TABLE . '
						WHERE block_id= ' . $blockid .'';
					$resultblock = $db->sql_query($sqlblock);
					$rowblock = $db->sql_fetchrow($resultblock);

					$blocktitle = utf8_normalize_nfc(request_var('blocktitle', !empty($rowblock['block_title']) ? $rowblock['block_title'] : 'New Block'), true);
					$blocktext = utf8_normalize_nfc(request_var('blocktext', $rowblock['block_text']), true);
					$blockurl = utf8_normalize_nfc(request_var('blockurl', $rowblock['block_url']), true);
					$blockclass	= utf8_normalize_nfc(request_var('blockclass', $rowblock['block_class']), true);
					$blockdivid = utf8_normalize_nfc(request_var('blockdivid', $rowblock['block_divid']), true);
					$blockposition = (int) request_var('blockposition', $rowblock['block_position']);
					$blocktype = (int) request_var('blocktype', $rowblock['block_type']);
					$blockvisible = (int) request_var('blockvisible', $rowblock['block_visible']);

					if ($submit)
					{
						$sql = 'UPDATE ' . NWOMENUBLOCKS_TABLE . '
							SET
							block_title 		= "' . $blocktitle . '",
							block_text 			= "' . $blocktext . '",
							block_url 			= "' . $blockurl . '",
							block_class 		= "' . $blockclass . '",
							block_divid 		= "' . $blockdivid . '",
							block_position		= "' . $blockposition . '",
							block_type			= "' . $blocktype . '",
							block_visible		= "' . $blockvisible . '"
							WHERE block_id='. $blockid;
						$results = $db->sql_query($sql);
						$row = $db->sql_fetchrow($results);
						$db->sql_freeresult($results);
						trigger_error(sprintf($user->lang['ACP_NWOMANAGER_MENUBLOCK_SAVED'], append_sid('index.php?i=' . $id . '&mode=nwomenulinks')));
					}

					$pagetitle = $user->lang['ACP_NWOMANAGER_MENUBLOCK'] . (!empty($blocktitle) ? ' [' . $blocktitle . ']' : '');

					$template->assign_vars(array(
						'BLOCK_TITLE'			=> $blocktitle,
						'BLOCK_TEXT'			=> $blocktext,
						'BLOCK_URL'				=> $blockurl,
						'BLOCK_CLASS'			=> $blockclass,
						'BLOCK_DIVID'			=> $blockdivid,
						'BLOCK_POSITION'		=> build_options_select($blockpositionsarray, $blockposition),
						'BLOCK_TYPE'			=> build_options_select($blocktypesarray, $blocktype),
						'S_BLOCK_VISIBLE'		=> ($blockvisible==1) ? true : false,
						'U_ACP_NWOMANAGER_MENULINKS'	=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks'),
						'BLOCK_PAGETITLE'		=> $pagetitle,
						'BLK_TITLE'				=> !empty($blocktitle) ? $blocktitle : $user->lang['ACP_NWOMANAGER_MENUBLOCK'],

					));
					$db->sql_freeresult($resultblock);

					$this->tpl_name = 'acp_nwomanager_menublock';
					$this->page_title = $pagetitle;
				}


				/********************
				 * Delete Menu Block  *
				 ********************/
				elseif ($submode == 'delblock')
				{
					$cancelblock = request_var('cancelblock', '');
					$confirmblock = request_var('confirmblock', '');

					if (!empty($cancelblock))
					{
						redirect(append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewblocks'));
					}
					if (empty($confirmblock))
					{
						trigger_error('ACP_CONFIRM_MENUBLOCK_DELETE');
					}
					if ($blockid < 0)
					{
						trigger_error('ACP_NO_MENUBLOCK_ID');
					}

					// reorder blocks for the delete
					$sqlblock = 'SELECT block_id, block_order
						FROM ' . NWOMENUBLOCKS_TABLE . '
						ORDER BY block_order ASC';
					$resultblock = $db->sql_query($sqlblock);

					while ($rowblock = $db->sql_fetchrow($resultblock))
					{
						if ($rowblock['block_id'] > $blockid)
						{
							$sql = 'UPDATE ' . NWOMENUBLOCKS_TABLE . '
								SET
								block_order = ' . ($rowblock['block_order'] -1) .'
								WHERE block_id='. $rowblock['block_id'];
							$results = $db->sql_query($sql);
							$db->sql_freeresult($results);
						}
					}
					$db->sql_freeresult($resultblock);

					// delete actual category
					$sql = 'DELETE FROM ' . NWOMENUBLOCKS_TABLE . '
							WHERE block_id = ' . $blockid;
					$db->sql_query($sql);

					// Delete menus that are in this category
					$sql = 'DELETE FROM ' . NWOMENUITEMS_TABLE . '
							WHERE menu_blockid = ' . $blockid;
					$db->sql_query($sql);

					// reorder menus after the delete
					$sqlmenu = 'SELECT menu_id, menu_blockid, menu_order
						FROM ' . NWOMENUITEMS_TABLE . '
						ORDER BY menu_blockid ASC';
					$resultmenu = $db->sql_query($sqlmenu);
					$ct=1;
					while ($rowmenu = $db->sql_fetchrow($resultmenu))
					{
						$sql = 'UPDATE ' . NWOMENUITEMS_TABLE . '
							SET
							menu_order = ' . $ct .'
							WHERE menu_id='. $rowmenu['menu_id'];
						$results = $db->sql_query($sql);
						$db->sql_freeresult($results);
						$ct++;
					}
					$db->sql_freeresult($resultmenu);


					trigger_error(sprintf($user->lang['ACP_MENUBLOCK_DELETED'], append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewblocks')));
				}


				/********************
				 * Move Category Dn *
				 ********************/
				elseif ($submode == 'moveblockdn')
				{
					$sqlblock = 'SELECT block_order
						FROM ' . NWOMENUBLOCKS_TABLE . '
						WHERE block_id= ' . $blockid .'';
					$resultblock = $db->sql_query($sqlblock);
					$rowblock = $db->sql_fetchrow($resultblock);

					$blockorder = $rowblock['block_order'];

					$sql = 'UPDATE ' . NWOMENUBLOCKS_TABLE . '
							SET
							block_order = ' . $blockorder . '
							WHERE block_order = ' . $blockorder . '-1 +2';

					$db->sql_query($sql);
					$sql = 'UPDATE ' . NWOMENUBLOCKS_TABLE . '
							SET
							block_order = ' . $blockorder . '-1 +2
							WHERE block_id = ' . $blockid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultblock);

					redirect(append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewblocks'));
				}


				/********************
				 * Move Category Up *
				 ********************/
				elseif ($submode == 'moveblockup')
				{
					$sqlblock = 'SELECT block_order
						FROM ' . NWOMENUBLOCKS_TABLE . '
						WHERE block_id= ' . $blockid .'';
					$resultblock = $db->sql_query($sqlblock);
					$rowblock = $db->sql_fetchrow($resultblock);

					$blockorder = $rowblock['block_order'];

					$sql = 'UPDATE ' . NWOMENUBLOCKS_TABLE . '
							SET
							block_order = ' . $blockorder . '
							WHERE block_order = ' . $blockorder . '-2 +1';

					$db->sql_query($sql);
					$sql = 'UPDATE ' . NWOMENUBLOCKS_TABLE . '
							SET
							block_order = ' . $blockorder . '-2 +1
							WHERE block_id = ' . $blockid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultblock);

					redirect(append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewblocks'));
				}


				/*******************************
				 * Toggle Menu Block Visible   *
				 *******************************/
				elseif ($submode == 'toggleblock')
				{
					$sqlblock = 'SELECT block_visible
						FROM ' . NWOMENUBLOCKS_TABLE . '
						WHERE block_id= ' . $blockid .'';
					$resultblock = $db->sql_query($sqlblock);
					$rowblock = $db->sql_fetchrow($resultblock);

					$blockvisible = $rowblock['block_visible'];
					$sql = 'UPDATE ' . NWOMENUBLOCKS_TABLE . '
							SET
							block_visible = ' . ($blockvisible ? "0" : "1") .'
							WHERE block_id = ' . $blockid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultblock);

					redirect(append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewblocks'));
				}


				/********************
				 * View Menu Blocks *
				 ********************/
				elseif ($submode == 'viewblocks')
				{
					//$start   = request_var('start', 0);
					//$limit   = request_var('limit', 25);
					$sqlblocks = 'SELECT *
							FROM ' . NWOMENUBLOCKS_TABLE . '
							ORDER BY block_order ASC';
					$resultblocks = $db->sql_query($sqlblocks);
					//$result = $db->sql_query_limit($sql, $limit, $start);
					//while($row = $db->sql_fetchrow($result, $limit, $start))
					while($rowblocks = $db->sql_fetchrow($resultblocks))
					{

						// Get total amount of menus in each category
						$sql = 'SELECT COUNT(menu_id) AS menu_count
							FROM ' . NWOMENUITEMS_TABLE . '
							WHERE menu_blockid=' . $rowblocks['block_id'];
						$result = $db->sql_query($sql);
						$menu_count = $db->sql_fetchfield('menu_count');
						$db->sql_freeresult($result);


						$blocklink = !empty($rowblocks['block_title']) ? $rowblocks['block_title'] : '- No Block Title -';

						$template->assign_block_vars('blocks', array(
							'U_EDIT'				=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=editblock&blockid=' . $rowblocks['block_id']),
							'U_DELETE'				=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=delblock&blockid=' . $rowblocks['block_id']),
							'U_MOVE_DOWN'			=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=moveblockdn&blockid=' . $rowblocks['block_id']),
							'U_MOVE_UP'				=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=moveblockup&blockid=' . $rowblocks['block_id']),
							'U_VISIBLE'				=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=toggleblock&blockid=' . $rowblocks['block_id']),
							'BLOCK_IMAGE'			=> '<img src="images/icon_subfolder.gif" alt="' . $user->lang['ACP_NWOMANAGER_BLOCK'] . '" title="' . $user->lang['ACP_NWOMANAGER_BLOCK'] . '" />',
							//'MEDAL_TITLE'			=> '<a href="' . append_sid('index.php?i=' . $id . '&mode=management&submode=catview&blockid=' . $value2['id']) . '" class="title">' . $value2['name'] . '</a>',
							'BLOCK_TITLE'			=> '<a href="' . append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewmenus&blockid=' . $rowblocks['block_id']) . '" class="title">' . $blocklink . '</a>',
							//'BLOCK_TITLE'			=> $rowblocks['block_title'],
							//'BLOCK_TEXT'			=> $blocktext,
							'BLOCK_TEXT'			=> $rowblocks['block_text'],
							'BLOCK_URL'				=> $rowblocks['block_url'],
							'BLOCK_CLASS'			=> $rowblocks['block_class'],
							'BLOCK_DIVID'			=> $rowblocks['block_divid'],
							'BLOCK_POSITION'		=> $blockpositionsarray[$rowblocks['block_position']],
							'BLOCK_TYPE'			=> $blocktypesarray[$rowblocks['block_type']],
							'BLOCK_MENUS'			=> $menu_count,
							'S_BLOCK_VISIBLE'		=> $rowblocks['block_visible'] ? true : false,
						));
					}
					$db->sql_freeresult($resultblocks);

					$template->assign_vars(array(
						'ICON_BULB_ON'		=> '<img src="' . $phpbb_admin_path . 'images/icon_lightbulb_on.gif" alt="Visible" title="Visible: click to switch off" />',
						'ICON_BULB_OFF'		=> '<img src="' . $phpbb_admin_path . 'images/icon_lightbulb_off.gif" alt="Not visible" title="Not visible: Click to switch on" />',
					));

					$sql = 'SELECT COUNT(block_id) AS block_count FROM ' . NWOMENUBLOCKS_TABLE;
					$result = $db->sql_query($sql);
					$total_blocks = $db->sql_fetchfield('block_count');
					$db->sql_freeresult($result);

					$action = $this->u_action;
					$template->assign_vars(array(
	    				//'PAGINATION'		=> generate_pagination($action, $total_blocks, $limit, $start),
		    			//'PAGE_NUMBER'		=> on_page($total_blocks, $limit, $start),
	    				'TOTAL_BLOCKS'		=> $total_blocks,
					));

					$this->tpl_name = 'acp_nwomanager_menublocks';
					$this->page_title = 'ACP_NWOMANAGER_MENULINKS';
				}


				/******************************************
				 * Menus
				 ******************************************/

				/******************************************
				 * Check for submode to process:          *
				 *                                        *
				 * - Add New Menu                         *
				 * - Edit Menu                            *
				 * - Delete Menu                          *
				 * - Move Menu Up                         *
				 * - Move Menu Down                       *
				 * - Toggle Menu Visible                  *
				 * - View All Menus                       *
				 *                                        *
				 ******************************************/

				/******************
				 * Add Menu       *
				 ******************/
				elseif ($submode == 'addmenu')
				{
					if ($submit)
					{
						//$menublockid = (int) request_var('menublockid', $blockid);
						$menublockid = $blockid;
						$menutext = utf8_normalize_nfc(request_var('menutext', 'New Menu Item'), true);
						$menuurl = utf8_normalize_nfc(request_var('menuurl', ''), true);
						$menuimage = utf8_normalize_nfc(request_var('menuimage', ''), true);
						$menuvisible = (int) request_var('menuvisible', 1);

						// Get total menus. This for the order id, new entry is added with total menus+1
						$sql = 'SELECT COUNT(menu_id) AS total_menus FROM ' . NWOMENUITEMS_TABLE;
						$result = $db->sql_query($sql);
						$total_menus = $db->sql_fetchfield('total_menus');
						$db->sql_freeresult($result);

						// Create new menu array for sql insert
						$sql_menu = array(
							'menu_blockid'			=> $menublockid,
							'menu_text'				=> $menutext,
							'menu_url'				=> $menuurl,
							'menu_image'			=> $menuimage,
							'menu_visible'			=> $menuvisible,
							'menu_order'			=> (int)  $total_menus -1 +2,
						);
						$db->sql_query('INSERT INTO ' . NWOMENUITEMS_TABLE . $db->sql_build_array('INSERT', $sql_menu));
						trigger_error(sprintf($user->lang['ACP_NWOMANAGER_MENUITEM_SAVED'], append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewmenus&blockid=' . $blockid)));
					}
					else
					{

						$menutext = utf8_normalize_nfc(request_var('menutext','New Menu Item'), true);

						$template->assign_vars(array(
							//'MENU_blockid'			=> build_options_select($blocksarray, $blockid),
							'MENU_BLOCKID'			=> $blockid,
							'MENU_TEXT'				=> $menutext,
							'MENU_URL'				=> '',
							'MENU_IMAGE'			=> '',
							'S_MENU_VISIBLE'		=> true,
							'U_ACP_NWOMANAGER_MENULINKS'	=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks'),
							//'U_CATEGORIES'			=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewblocks'),
							'U_BLOCK_TITLE'			=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewmenus&blockid='. $blockid),
							'BLOCK_TITLE'			=> !empty($blocktitle) ? $blocktitle : $user->lang['ACP_NWOMANAGER_MENUITEMS'],
							'MENU_TITLE'			=> $user->lang['ACP_NWOMANAGER_MENUITEM'],
							'MENU_PAGETITLE'		=> $user->lang['ACP_NWOMANAGER_MENUITEM'],
							'U_ACTION'				=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=addmenu&blockid=' . $blockid)
						));

						$this->tpl_name = 'acp_nwomanager_menuitem';
						$this->page_title = 'ACP_NWOMANAGER_MENUITEM';
					}
				}

				/******************
				 * Edit Menu      *
				 ******************/
				elseif ($submode == 'editmenu')
				{
					if ($menuid < 0)
					{
						trigger_error('ACP_NO_MENUITEM_ID');
					}

					$sqlmenu = 'SELECT *
						FROM ' . NWOMENUITEMS_TABLE . '
						WHERE menu_id= ' . $menuid .'';
					$resultmenu = $db->sql_query($sqlmenu);
					$rowmenu = $db->sql_fetchrow($resultmenu);

					$menublockid = (int) request_var('menublockid', $rowmenu['menu_blockid']);
					$menutext = utf8_normalize_nfc(request_var('menutext', $rowmenu['menu_text']), true);
					$menuurl = substr(utf8_normalize_nfc(request_var('menuurl', $rowmenu['menu_url']), true),0, 255);
					$menuimage = substr(utf8_normalize_nfc(request_var('menuimage', $rowmenu['menu_image']), true),0, 255);
					$menuvisible = (int) request_var('menuvisible', $rowmenu['menu_visible']);

					if ($submit)
					{
						$sql = 'UPDATE ' . NWOMENUITEMS_TABLE . '
							SET
							menu_blockid			= "' . $menublockid . '",
							menu_text 				= "' . $menutext . '",
							menu_url				= "' . $menuurl . '",
							menu_image				= "' . $menuimage . '",
							menu_visible			= "' . $menuvisible . '"
							WHERE menu_id='. $menuid;
						$results = $db->sql_query($sql);
						$row = $db->sql_fetchrow($results);
						$db->sql_freeresult($results);
						trigger_error(sprintf($user->lang['ACP_NWOMANAGER_MENUITEM_SAVED'], append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewmenus&blockid=' . $blockid)));
					}

					$pagetitle = $user->lang['ACP_NWOMANAGER_MENUITEM'] . (!empty($menutext) ? ' [' . $menutext . ']' : '');

					$template->assign_vars(array(
						'MENU_BLOCKID'			=> build_options_select($blocksarray, $menublockid),
						'MENU_TEXT'				=> $menutext,
						'MENU_URL'				=> $menuurl,
						'MENU_IMAGE'			=> $menuimage,
						'S_MENU_VISIBLE'		=> ($menuvisible==1) ? true : false,
						'U_ACP_NWOMANAGER_MENULINKS'	=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks'),
						'U_BLOCK_TITLE'			=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewmenus&blockid='. $blockid),
						'BLOCK_TITLE'			=> !empty($blocktitle) ? $blocktitle : $user->lang['ACP_NWOMANAGER_MENUITEMS'],
						'MENU_TITLE'			=> !empty($menutext) ? $menutext : $user->lang['ACP_NWOMANAGER_MENUITEM'],
						'MENU_PAGETITLE'		=> $pagetitle,
					));
					$db->sql_freeresult($resultmenu);

					$this->tpl_name = 'acp_nwomanager_menuitem';
					$this->page_title = $pagetitle;
				}



				/******************
				 * Delete Menu    *
				 ******************/
				elseif ($submode == 'delmenu')
				{
					$cancelmenu = request_var('cancelmenu', '');
					$confirmmenu = request_var('confirmmenu', '');

					if (!empty($cancelmenu))
					{
						redirect(append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewmenus&blockid='. $blockid));
					}
					if (empty($confirmmenu))
					{
						trigger_error('ACP_CONFIRM_MENUITEM_DELETE');
					}
					if ($menuid < 0)
					{
						trigger_error('ACP_NO_MENUITEM_ID');
					}

					// reorder sections for the delete
					$sqlmenu = 'SELECT menu_id, menu_order
						FROM ' . NWOMENUITEMS_TABLE . '
						ORDER BY menu_order ASC';
					$resultmenu = $db->sql_query($sqlmenu);

					while ($rowmenu = $db->sql_fetchrow($resultmenu))
					{
						if ($rowmenu['menu_id'] > $menuid)
						{
							$sql = 'UPDATE ' . NWOMENUITEMS_TABLE . '
								SET
								menu_order = ' . ($rowmenu['menu_order'] -1) .'
								WHERE menu_id='. $rowmenu['menu_id'];
							$results = $db->sql_query($sql);
							$db->sql_freeresult($results);
						}
					}
					$db->sql_freeresult($resultmenu);

					// delete actual section
					$sql = 'DELETE FROM ' . NWOMENUITEMS_TABLE . '
							WHERE menu_id = ' . $menuid;
					$db->sql_query($sql);
					trigger_error(sprintf($user->lang['ACP_MENUITEM_DELETED'], append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewmenus&blockid='. $blockid)));
				}



				/******************
				 * Move Menu Dn   *
				 ******************/
				elseif ($submode == 'movemenudn')
				{
					$sqlmenu = 'SELECT menu_order
						FROM ' . NWOMENUITEMS_TABLE . '
						WHERE menu_id= ' . $menuid .'';
					$resultmenu = $db->sql_query($sqlmenu);
					$rowmenu = $db->sql_fetchrow($resultmenu);

					$menuorder = $rowmenu['menu_order'];

					$sql = 'UPDATE ' . NWOMENUITEMS_TABLE . '
							SET
							menu_order = ' . $menuorder . '
							WHERE menu_order = ' . $menuorder . '-1 +2';

					$db->sql_query($sql);
					$sql = 'UPDATE ' . NWOMENUITEMS_TABLE . '
							SET
							menu_order = ' . $menuorder . '-1 +2
							WHERE menu_id = ' . $menuid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultmenu);

					redirect(append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewmenus&blockid='. $blockid));
				}


				/******************
				 * Move Menu Up   *
				 ******************/
				elseif ($submode == 'movemenuup')
				{
					$sqlmenu = 'SELECT menu_order
						FROM ' . NWOMENUITEMS_TABLE . '
						WHERE menu_id= ' . $menuid .'';
					$resultmenu = $db->sql_query($sqlmenu);
					$rowmenu = $db->sql_fetchrow($resultmenu);

					$menuorder = $rowmenu['menu_order'];

					$sql = 'UPDATE ' . NWOMENUITEMS_TABLE . '
							SET
							menu_order = ' . $menuorder . '
							WHERE menu_order = ' . $menuorder . '-2 +1';

					$db->sql_query($sql);
					$sql = 'UPDATE ' . NWOMENUITEMS_TABLE . '
							SET
							menu_order = ' . $menuorder . '-2 +1
							WHERE menu_id = ' . $menuid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultmenu);

					redirect(append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewmenus&blockid='. $blockid));
				}


				/******************
				 * Toggle Menu    *
				 ******************/
				elseif ($submode == 'togglemenu')
				{
					$sqlmenu = 'SELECT menu_visible
						FROM ' . NWOMENUITEMS_TABLE . '
						WHERE menu_id= ' . $menuid .'';
					$resultmenu = $db->sql_query($sqlmenu);
					$rowmenu = $db->sql_fetchrow($resultmenu);

					$menuvisible = $rowmenu['menu_visible'];
					$sql = 'UPDATE ' . NWOMENUITEMS_TABLE . '
							SET
							menu_visible = ' . ($menuvisible ? "0" : "1") .'
							WHERE menu_id = ' . $menuid;
					$db->sql_query($sql);
					$db->sql_freeresult($resultmenu);

					redirect(append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=viewmenus&blockid='. $blockid));
				}


				/******************
				 * View Menu      *
				 ******************/
				elseif ($submode == 'viewmenus')
				{

					$sqlmenus = 'SELECT *
							FROM ' . NWOMENUITEMS_TABLE . '
							WHERE menu_blockid=' . $blockid . '
							ORDER BY menu_order ASC';
					$resultmenus = $db->sql_query($sqlmenus);
					//$result = $db->sql_query_limit($sql, $limit, $start);
					//while($row = $db->sql_fetchrow($result, $limit, $start))
					while($rowmenus = $db->sql_fetchrow($resultmenus))
					{

						//$flags = (($config['allow_bbcode']) ? 1 : 0) + (($config['allow_smilies']) ? 2 : 0) + ((true) ? 4 : 0);
						//$menutext = generate_text_for_display($rowmenus['menu_text'], $rowmenus['bbcode_uid'], $rowmenus['bbcode_bitfield'], $flags);
						$template->assign_block_vars('menus', array(
							'U_EDIT'				=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=editmenu&blockid=' . $blockid . '&menuid=' . $rowmenus['menu_id']),
							'U_DELETE'				=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=delmenu&blockid=' . $blockid . '&menuid=' . $rowmenus['menu_id']),
							'U_MOVE_DOWN'			=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=movemenudn&blockid=' . $blockid . '&menuid=' . $rowmenus['menu_id']),
							'U_MOVE_UP'				=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=movemenuup&blockid=' . $blockid . '&menuid=' . $rowmenus['menu_id']),
							'U_VISIBLE'				=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=togglemenu&blockid=' . $blockid . '&menuid=' . $rowmenus['menu_id']),
							'MENU_TEXT'				=> '<a href="' . append_sid('index.php?i=' . $id . '&mode=nwomenulinks&submode=editmenu&blockid=' . $blockid . '&menuid=' . $rowmenus['menu_id']) . '" class="title">' . $rowmenus['menu_text'] . '</a>',
							'MENU_URL'				=> $rowmenus['menu_url'],
							'MENU_IMAGE'			=> !empty($rowmenus['menu_image']) ? '<img src="' . $rowmenus['menu_image'] . '" alt="Menu Image" title="Menu Image">' : '',
							'S_MENU_VISIBLE'		=> $rowmenus['menu_visible'] ? true : false,
						));
					}
					$db->sql_freeresult($resultmenus);

					// Assign Lightbulbs
					$template->assign_vars(array(
						'ICON_BULB_ON'		=> '<img src="' . $phpbb_admin_path . 'images/icon_lightbulb_on.gif" alt="Visible" title="Visible: click to switch off" />',
						'ICON_BULB_OFF'		=> '<img src="' . $phpbb_admin_path . 'images/icon_lightbulb_off.gif" alt="Not visible" title="Not visible: Click to switch on" />',
					));

					$pagetitle = $user->lang['ACP_NWOMANAGER_MENUITEMS'] . (!empty($blocktitle) ? ' [' . $blocktitle . ']' : '');

					$template->assign_vars(array(
						'U_ACP_NWOMANAGER_MENULINKS'	=> append_sid('index.php?i=' . $id . '&mode=nwomenulinks'),
						'BLOCK_TITLE'				=> !empty($blocktitle) ? $blocktitle : $user->lang['ACP_NWOMANAGER_MENUITEMS'],
						'BLOCK_PAGETITLE'			=> $pagetitle,
					));

					//$sql = 'SELECT COUNT(menu_id) AS menu_count FROM ' . NWOMENUITEMS_TABLE;
					$sql = 'SELECT COUNT(menu_id) AS menu_count
							FROM ' . NWOMENUITEMS_TABLE . '
							WHERE menu_blockid=' . $blockid;
					$result = $db->sql_query($sql);
					$total_menus = $db->sql_fetchfield('menu_count');
					$db->sql_freeresult($result);

					$action = $this->u_action;
					$template->assign_vars(array(
	    				//'PAGINATION'		=> generate_pagination($action, $total_menus, $limit, $start),
		    			//'PAGE_NUMBER'		=> on_page($total_menus, $limit, $start),
	    				'TOTAL_MENUS'		=> $total_menus,
					));

					$this->tpl_name = 'acp_nwomanager_menuitems';
					$this->page_title = $pagetitle;
				}


			break;

		}
	}
}


/**********************************************************
 * Create a list of forums for the listbox in the
 * Settings ACP
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
 * Create a list of forums for the listbox in the
 * Settings ACP
 **********************************************************/
function forum_multi_select($default = '', &$mf)
{
    global $db;

    $sql = 'SELECT forum_id, forum_name, forum_type
        FROM ' . FORUMS_TABLE . "
        ORDER BY left_id";
    $result = $db->sql_query($sql);



  // $forumsarray = implode("`:`", $default);

   $forumsarray = explode(":", $default);

   //foreach ($forumsarray as $forumid => $key)
 // {
  //		echo $forumid . ' | ' . $key;
  //}
   // echo '<br />';
   // echo $forumsarray;

    $forum_options = '';
    //$forum_options = '<option value="0"' . (($row['forum_id'] == $default) ? ' selected="selected"' : '') . '>' . '- No Forum -' . '</option>';
    while ($row = $db->sql_fetchrow($result))
    {
        //$selected = ($row['forum_id'] == $default) ? ' selected="selected"' : '';


		//$multiselectresult = array_key_exists($row['forum_id'], $forumsarray);
		$multiselectresult = in_array((int) $row['forum_id'], $forumsarray);
		//if ($result)
		//{
			//echo 'found';
			//echo $row['forum_id'] .'<br />';
			//$selected = ' selected="selected"';
		//}
       	$selected = $multiselectresult ? ' selected="selected"' : '';


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
 * Create listbox from array, and set the selected option
 **********************************************************/
function build_options_select($optionsarray, $selected = "")
{
	$returnval = '';
	foreach ($optionsarray as $field=>$value)
	{
		if ($field == $selected)
  		{
	 		$returnval .= '<option selected value="' . $field . '">' . $value . '</option>\n';
		}
	    else
	    {
			$returnval .= '<option value="' . $field . '">' . $value . '</option>\n';
		}
	}
	return $returnval;
}


?>