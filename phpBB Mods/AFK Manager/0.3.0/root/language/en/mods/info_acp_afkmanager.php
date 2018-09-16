<?php
/**
 *
 * acp_afkmanager [English]
 *
 * @package AFK Manager
 * @version $Id: 0.3.0
 * @copyright (c) 2009 -[Nwo] fearless
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

/**
 * DO NOT CHANGE
 */
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

// AFK Manager

$lang['permission_cat']['afkmanager']   = 'AFK Manager';

$lang = array_merge($lang, array(
	'ACP_AFKMANAGER'						=>	'AFK Manager',
	'ACP_AFKMANAGER_LIST'					=>	'AFK Manager User List',
	'ACP_AFKMANAGER_TITLE'					=>	'AFK Manager',
	'ACP_AFKMANAGER_DESC'					=>	'The Away From Keyboard (AFK) Manager shows a list of users that have toggled their AFK status to on.',
	'AFKMANAGER_INSTALLED'					=>	'AFK Manager Successfully Installed. Remember to assign admin permissions to view the AFK list and user permissions to use the UCP module.',

	'ACP_AFKER_ID'							=>	'ID',
	'ACP_AFKER_NAME'						=>	'User Name',
	'ACP_AFKER_DATE'						=>	'AFK Since',
	'ACP_AFKER_AFKTIME' 					=>	'Time AFK',
	'ACP_AFKER_REASON'						=>	'AFK Reason',
	'ACP_AFKER_REASONCATEGORY'				=> 'AFK Category',
	'ACP_AFKER_TOPICID'						=>	'AFK Topic',
	'ACP_NO_AFKERS'							=>	'No Users Are Presently Marked As Away From Keyboard (AFK).',
	'ACP_NOTOPICID_NOTE'					=>	'<strong>Note:</strong> if auto posting has been disabled, no afk topic id link will be shown. Also if a user changes their status to AFK when the auto posting is disabled, when it is re-enabled they will not have an afk topic id.',
	
	// AFK Manager Settings
	'ACP_AFKMANAGER_SETTINGS' 							=> 'AFK Manager Settings',
	'ACP_AFKMANAGER_SETTINGS_DESC' 						=> 'Here you can set the AFK Manager Settings',	
	'ACP_AFKMANAGER_SETTINGS_POSTING_ENABLE'			=> 'Enable AFK Auto Posting',
	'ACP_AFKMANAGER_SETTINGS_POSTING_FORUMID'			=> 'AFK Auto Posting Forum',
	'ACP_AFKMANAGER_SETTINGS_POSTING_TOPICICON'			=> 'AFK Auto Posting Topic Icon',
	'ACP_AFKMANAGER_SETTINGS_POSTING_REPLY'				=> 'AFK Auto Posting Reply',
	'ACP_AFKMANAGER_SETTINGS_POSTING_FOOTER'			=> 'AFK Auto Posting Footer',
	'ACP_AFKMANAGER_SETTINGS_POSTING_ENABLE_EXPLAIN'	=> 'This will turn on the auto post feature and submit a message to a specified forum to tell other users that the user status is now AFK.',
	'ACP_AFKMANAGER_SETTINGS_POSTING_FORUMID_EXPLAIN'	=> 'This is the forum that the AFK Auto Posts will be submitted to.',
	'ACP_AFKMANAGER_SETTINGS_POSTING_TOPICICON_EXPLAIN'	=> 'This is the default Topic Icon to use with the Auto Post.',
	'ACP_AFKMANAGER_SETTINGS_POSTING_REPLY_EXPLAIN'		=> 'Updates the original AFK Auto Post with a reply when a user returns from AFK status. Setting this to off will submit a new post instead.',
	'ACP_AFKMANAGER_SETTINGS_POSTING_FOOTER_EXPLAIN'	=> 'You can define some text that will be appended to the end of the AFK Auto Post message. Leave blank if you do not wish to use this.',
	
	// AFK Manager User Settings
	'ACP_AFKMANAGER_SETTINGS_USER'						=> 'AFK Manager User Settings',
	'ACP_AFKMANAGER_SETTINGS_SHOW_STATUS_VIEWTOPIC'		=> 'User AFK Viewtopic',
	'ACP_AFKMANAGER_SETTINGS_SHOW_STATUS_VIEWPROFILE'	=> 'User AFK Viewprofile',
	'ACP_AFKMANAGER_SETTINGS_SHOW_REMINDER_BOARD'		=> 'User AFK Reminder',
	'ACP_AFKMANAGER_SETTINGS_SHOW_STATUS_VIEWTOPIC_EXPLAIN'		=> "If a user is marked as AFK, enabling this option this will show 'User Is Currently AFK' when viewing a forum topic that the user has posted in.",
	'ACP_AFKMANAGER_SETTINGS_SHOW_STATUS_VIEWPROFILE_EXPLAIN'	=> "If a user is marked as AFK, enabling this option will show 'User Is Currently AFK' when viewing the users profile.",
	'ACP_AFKMANAGER_SETTINGS_SHOW_REMINDER_BOARD_EXPLAIN'		=> 'If a user is marked as AFK, this will show a reminder at the top of the main forum page only to the AFK user.',
	'ACP_AFKMANAGER_SETTINGS_SAVED' 					=> 'AFK Manager Settings Successfully Saved. <br /><br /> Click <a href="%s">here</a> to go back',
   		
	// Admin perms
	'acl_a_afk_view'      					=> array('lang' => 'Admin Can View The AFK List', 'cat' => 'afkmanager'),  
	'acl_a_afk_settings'  					=> array('lang' => 'Admin Can Modify AFK Manager Settings', 'cat' => 'afkmanager'), 
	
	
	// For Admin User ACP Page
	'ACP_USER_AFKMANAGER'					=> 'AFK Manager',
	'ACP_USER_AFKSTATUS_UPDATED'			=> 'User AFK status successfully updated.',
	'ACP_USER_AFKMANAGER_SETTINGS'			=> 'User AFK Settings',
	'ACP_USER_AFKSTATUS'					=> 'User is AFK?',
	'ACP_USER_AFKREASON'					=> 'AFK Reason',
	'ACP_USER_AFKREASONCATEGORY'			=> 'AFK Reason Category',
	
));



?>

