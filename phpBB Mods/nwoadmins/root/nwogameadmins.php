<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/steam.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('common');
$user->setup('mods/nwoadmins');

$page = $_REQUEST['page'];

$adminlist = '3,287,483,494,496'; // NWO Server Admins & Directors Group
$adminlistarray = explode(",", $adminlist);
$sqladmins = 'SELECT DISTINCT u.user_id, u.username, u.user_steamid
			FROM ' . USERS_TABLE . ' u
			INNER JOIN ' . USER_GROUP_TABLE . ' ug ON (u.user_id = ug.user_id)
			WHERE ' . $db->sql_in_set('ug.group_id', $adminlistarray) .'
			ORDER BY u.user_id';
$resultadmins = $db->sql_query($sqladmins);

$i = 0;
$cellid = 0;
while ($rowadmins = $db->sql_fetchrow($resultadmins))
{
	$steamid = $rowadmins['user_steamid'];
    if (substr($steamid, 0, 6) != "STEAM_" && $steamid != "")
	{
		$steamid = "STEAM_" . $steamid;
	}		
	$user_id = $rowadmins['user_id'];
	$friendid = getFriendId($steamid);
	
	//$steamarray = getSteamArray($steamid);
	$cellid = $cellid + 1;
	if ($cellid==4)
	{
		$cellid = 1;
	}
	//echo $rowadmins['username'];
	$template->assign_block_vars('nwoadminlist', array(
		'ROW_NUMBER'			=> $i = $i + 1,
		'CELL_ID'				=> $cellid,
		'ADMIN_ID'				=> $user_id,
		'ADMIN_NAME'			=> $rowadmins['username'],
		'ADMIN_STEAMID'			=> $steamid,
		'ADMIN_FRIENDID'		=> $friendid,//$steamarray['FriendId'],//getFriendId($steamid),
		'U_ADMIN_FRIENDID'		=> 'http://steamcommunity.com/profiles/' . $friendid, //getFriendId($steamid),
		// steam://friends/add/76561197987219955
		//'IMG_ADMIN_AVATAR'		=> $steamarray['SteamAvatar'], //getSteamAvatar($steamid),
		//'ADMIN_VACSTATUS'		=> $steamarray['VacStatus']==0 ? 'Clean' : 'Ban(s) on record',  // getVacBannedStatus($steamid)
		//'S_ADMIN_VACSTATUS'		=> $steamarray['VacStatus'],
		//'ADMIN_ONLINE'			=> $steamarray['OnlineStatus'],
		//'S_ADMIN_ONLINE'		=> $steamarray['OnlineStatus']=='offline' ? 0 : 1,
		//'ADMIN_STATUS'			=> $steamarray['StateMessage'],
		//'ADMIN_IMAGE'			=> '<img src="' . $phpbb_root_path . 'images/avatars/steamprofiles/' . getSteamImage($steamid) . '" alt="">',
		'U_ADMIN_ADDFRIEND'		=> 'steam://friends/add/' . $friendid, //$steamarray['FriendId'],
		'U_VIEW_PROFILE'		=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $user_id),
	));		
}
$db->sql_freeresult($resultadmins);

// Output page
$page_title = $user->lang['NWOADMINS'];
page_header($page_title);

		//$message = $message . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');
		//trigger_error($message);


$template->assign_vars(array(
	'PAGE_TITLE'			=> $page_title,
	));

$template->set_filenames(array(
    'body' => 'nwogameadmins_body.html',
));

page_footer();

?>