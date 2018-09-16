<?php
/**
 *
 * nwoclanmembers [English]
 *
 * @package Clan Members List
 * @version $Id: 1.0.0
 * @copyright (c) 2010 -[Nwo] fearless
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/*
<td class="gen row" align="center" <!-- IF nwodirectors.S_NWO_DIRECTOR_VACSTATUS -->style="color:lightred;"<!-- ENDIF -->>{nwodirectors.NWO_DIRECTOR_VACSTATUS}</td>
<td class="gen row" align="center" <!-- IF nwocouncil.S_NWO_COUNCIL_VACSTATUS -->style="color:lightred;"<!-- ENDIF -->>{nwocouncil.NWO_COUNCIL_VACSTATUS}</td>
<td class="gen row" align="center" <!-- IF nwomember.S_NWO_MEMBER_VACSTATUS -->style="color:lightred;"<!-- ENDIF -->>{nwomember.NWO_MEMBER_VACSTATUS}</td>
<td class="gen row" align="center" <!-- IF nwotrial.S_NWO_TRIAL_VACSTATUS -->style="color:lightred;"<!-- ENDIF -->>{nwotrial.NWO_TRIAL_VACSTATUS}</td>
*/

/**********************************************************************************
/ Basic Setup
/**********************************************************************************/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/steam.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('common');
$user->setup('mods/nwoclanmembers');


/**********************************************************************************
/ Get NWO Directors Block
/**********************************************************************************/
$nwodirectors = '3'; // NWO Directors Group
$sqlnwodirectors = 'SELECT DISTINCT u.user_id, u.username, u.user_steamid
			FROM ' . USERS_TABLE . ' u
			INNER JOIN ' . USER_GROUP_TABLE . ' ug ON (u.user_id = ug.user_id)
			WHERE ug.group_id = ' . "$nwodirectors" . '
			ORDER BY u.user_id';
$resultnwodirectors = $db->sql_query($sqlnwodirectors);
$i = 0;
while ($rownwodirectors = $db->sql_fetchrow($resultnwodirectors))
{
	$steamid = $rownwodirectors['user_steamid'];
    if (substr($steamid, 0, 6) != "STEAM_" && $steamid != "")
	{
		$steamid = "STEAM_" . $steamid;
	}	
	$friendid = getFriendId($steamid);

	//$vacstatus = getVacBannedStatus($steamid);
	$user_id = $rownwodirectors['user_id'];
	
	$template->assign_block_vars('nwodirectors', array(
		'ROW_NUMBER'				=> $i = $i + 1,
		'NWO_DIRECTOR_ID'			=> $user_id,
		'NWO_DIRECTOR_NAME'			=> $rownwodirectors['username'],
		'NWO_DIRECTOR_STEAMID'		=> $steamid,
		'NWO_DIRECTOR_FRIENDID'		=> $friendid,
		'U_NWO_DIRECTOR_FRIENDID'	=> $friendid == 'INVALID' ? '' : 'http://steamcommunity.com/profiles/' . $friendid,

		'IMG_NWO_DIRECTOR_AVATAR'	=> '', //$steamid !='' ? getSteamAvatarIcon($steamid) : '', //'', //getSteamAvatar($steamid),
		//'NWO_DIRECTOR_VACSTATUS'	=> $vacstatus==0 ? 'Clean' : 'Ban(s) on record',  // getVacBannedStatus($steamid)
		//'S_NWO_DIRECTOR_VACSTATUS'	=> $vacstatus,

		'U_NWO_DIRECTOR_ADDFRIEND'	=> $friendid == 'INVALID' ? '' : 'steam://friends/add/' . $friendid,
		'U_VIEW_PROFILE'			=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $user_id),
		'U_PM'			=> ($config['allow_privmsg'] && $auth->acl_get('u_sendpm') && ($data['user_allow_pm'] || $auth->acl_gets('a_', 'm_') || $auth->acl_getf_global('m_'))) ? append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=pm&amp;mode=compose&amp;u=' . $user_id) : '',		
	));		
}
$db->sql_freeresult($resultnwodirectors);


/**********************************************************************************
/ Get NWO Council Block
/**********************************************************************************/
$nwocouncil = '483'; // NWO Council Group
$sqlnwocouncil = 'SELECT DISTINCT u.user_id, u.username, u.user_steamid
			FROM ' . USERS_TABLE . ' u
			INNER JOIN ' . USER_GROUP_TABLE . ' ug ON (u.user_id = ug.user_id)
			WHERE ug.group_id = ' . "$nwocouncil" .'
			ORDER BY u.user_id';
$resultnwocouncil = $db->sql_query($sqlnwocouncil);
$i = 0;
while ($rownwocouncil = $db->sql_fetchrow($resultnwocouncil))
{
	$steamid = $rownwocouncil['user_steamid'];
    if (substr($steamid, 0, 6) != "STEAM_" && $steamid != "")
	{
		$steamid = "STEAM_" . $steamid;
	}		
	$friendid = getFriendId($steamid);

	//$vacstatus = getVacBannedStatus($steamid);
	$user_id = $rownwocouncil['user_id'];
	
	$template->assign_block_vars('nwocouncil', array(
		'ROW_NUMBER'				=> $i = $i + 1,
		'NWO_COUNCIL_ID'			=> $user_id,
		'NWO_COUNCIL_NAME'			=> $rownwocouncil['username'],
		'NWO_COUNCIL_STEAMID'		=> $steamid,
		'NWO_COUNCIL_FRIENDID'		=> $friendid == 'INVALID' ? '' : 'http://steamcommunity.com/profiles/' . $friendid,
		'U_NWO_COUNCIL_FRIENDID'	=> 'http://steamcommunity.com/profiles/' . $friendid,

		'IMG_NWO_COUNCIL_AVATAR'	=> '', //$steamid !='' ? getSteamAvatarIcon($steamid) : '', // //getSteamAvatar($steamid),
		//'NWO_COUNCIL_VACSTATUS'	=> $vacstatus==0 ? 'Clean' : 'Ban(s) on record',  // getVacBannedStatus($steamid)
		//'S_NWO_COUNCIL_VACSTATUS'	=> $vacstatus,

		'U_NWO_COUNCIL_ADDFRIEND'	=> $friendid == 'INVALID' ? '' : 'steam://friends/add/' . $friendid,
		'U_VIEW_PROFILE'			=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $user_id),
		'U_PM'			=> ($config['allow_privmsg'] && $auth->acl_get('u_sendpm') && ($data['user_allow_pm'] || $auth->acl_gets('a_', 'm_') || $auth->acl_getf_global('m_'))) ? append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=pm&amp;mode=compose&amp;u=' . $user_id) : '',		
	));		
}
$db->sql_freeresult($resultnwocouncil);


/**********************************************************************************
/ Get NWO Members Block
/**********************************************************************************/
$nwomembers = '4'; // NWO Members Group
$sqlnwomembers = 'SELECT DISTINCT u.user_id, u.username, u.user_steamid
			FROM ' . USERS_TABLE . ' u
			INNER JOIN ' . USER_GROUP_TABLE . ' ug ON (u.user_id = ug.user_id)
			WHERE ug.group_id = ' . "$nwomembers" .'
			ORDER BY UPPER(u.username)';
$resultnwomembers = $db->sql_query($sqlnwomembers);
$i = 0;
while ($rownwomembers = $db->sql_fetchrow($resultnwomembers))
{
	$steamid = $rownwomembers['user_steamid'];
    if (substr($steamid, 0, 6) != "STEAM_" && $steamid != "")
	{
		$steamid = "STEAM_" . $steamid;
	}		
	$friendid = getFriendId($steamid);

	//$vacstatus = getVacBannedStatus($steamid);
	$user_id = $rownwomembers['user_id'];
	
	$template->assign_block_vars('nwomember', array(
		'ROW_NUMBER'				=> $i = $i + 1,
		'NWO_MEMBER_ID'				=> $user_id,
		'NWO_MEMBER_NAME'			=> $rownwomembers['username'],
		'NWO_MEMBER_STEAMID'		=> $steamid,
		'NWO_MEMBER_FRIENDID'		=> $friendid == 'INVALID' ? '' : 'http://steamcommunity.com/profiles/' . $friendid,
		'U_NWO_MEMBER_FRIENDID'	=> 'http://steamcommunity.com/profiles/' . $friendid,

		'IMG_NWO_MEMBER_AVATAR'		=> '', //$steamid !='' ? getSteamAvatarIcon($steamid) : '', // //getSteamAvatar($steamid),
		//'NWO_MEMBER_VACSTATUS'	=> $vacstatus==0 ? 'Clean' : 'Ban(s) on record',  // getVacBannedStatus($steamid)
		//'S_NWO_MEMBER_VACSTATUS'	=> $vacstatus,

		'U_NWO_MEMBER_ADDFRIEND'	=> $friendid == 'INVALID' ? '' : 'steam://friends/add/' . $friendid,
		'U_VIEW_PROFILE'			=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $user_id),
		'U_PM'			=> ($config['allow_privmsg'] && $auth->acl_get('u_sendpm') && ($data['user_allow_pm'] || $auth->acl_gets('a_', 'm_') || $auth->acl_getf_global('m_'))) ? append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=pm&amp;mode=compose&amp;u=' . $user_id) : '',		
	));		
}
$db->sql_freeresult($resultnwomembers);


/**********************************************************************************
/ Get NWO Trial Members Block
/**********************************************************************************/
$nwotrial = '279'; // NWO Trial Members Group
$sqlnwotrial = 'SELECT DISTINCT u.user_id, u.username, u.user_steamid
			FROM ' . USERS_TABLE . ' u
			INNER JOIN ' . USER_GROUP_TABLE . ' ug ON (u.user_id = ug.user_id)
			WHERE ug.group_id = ' . "$nwotrial" .'
			ORDER BY u.user_id';
$resultnwotrial = $db->sql_query($sqlnwotrial);
$i = 0;
while ($rownwotrial = $db->sql_fetchrow($resultnwotrial))
{
	$steamid = $rownwotrial['user_steamid'];
    if (substr($steamid, 0, 6) != "STEAM_" && $steamid != "")
	{
		$steamid = "STEAM_" . $steamid;
	}		
	$friendid = getFriendId($steamid);

	//$vacstatus = getVacBannedStatus($steamid);
	$user_id = $rownwotrial['user_id'];
	
	$template->assign_block_vars('nwotrial', array(
		'ROW_NUMBER'				=> $i = $i + 1,
		'NWO_TRIAL_ID'				=> $user_id,
		'NWO_TRIAL_NAME'			=> $rownwotrial['username'],
		'NWO_TRIAL_STEAMID'			=> $steamid,
		'NWO_TRIAL_FRIENDID'		=> $friendid == 'INVALID' ? '' : 'http://steamcommunity.com/profiles/' . $friendid,
		'U_NWO_TRIAL_FRIENDID'		=> 'http://steamcommunity.com/profiles/' . $friendid,

		'IMG_NWO_TRIAL_AVATAR'		=> '', //$steamid !='' ? getSteamAvatarIcon($steamid) : '', // //getSteamAvatar($steamid),
		//'NWO_TRIAL_VACSTATUS'		=> $vacstatus==0 ? 'Clean' : 'Ban(s) on record',  // getVacBannedStatus($steamid)
		//'S_NWO_TRIAL_VACSTATUS'	=> $vacstatus,

		'U_NWO_TRIAL_ADDFRIEND'		=> $friendid == 'INVALID' ? '' : 'steam://friends/add/' . $friendid,
		'U_VIEW_PROFILE'			=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $user_id),
		'U_PM'			=> ($config['allow_privmsg'] && $auth->acl_get('u_sendpm') && ($data['user_allow_pm'] || $auth->acl_gets('a_', 'm_') || $auth->acl_getf_global('m_'))) ? append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=pm&amp;mode=compose&amp;u=' . $user_id) : '',		
	));		
}
$db->sql_freeresult($resultnwotrial);


/**********************************************************************************
/ Output Page to Template
/**********************************************************************************/

// Output page
$page_title = $user->lang['NWOCLANMEMBERS'];
page_header($page_title);

		//$message = $message . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');
		//trigger_error($message);


$template->assign_vars(array(
	'PAGE_TITLE'	=> $page_title,
	'PM_IMG'		=> $user->img('icon_contact_pm', $user->lang['SEND_PRIVATE_MESSAGE']),
	//'ADD_FRIEND_IMG'	=> $user->img('contrib/icon_addsteamfriend', $user->lang['SEND_PRIVATE_MESSAGE']), //$user->lang['ADD_STEAM_FRIEND']),
));

$template->set_filenames(array(
    'body' => 'nwoclanmembers_body.html',
));

page_footer();



?>