<?php
/**
 *
 * @package NWO Manager
 * @version $Id: 1.0.0
 * @copyright (c) 2009 -[Nwo]- fearless
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License
 */

/**
 * DO NOT CHANGE
 */
define('IN_PHPBB', true);

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

// NWO Manager

$lang = array_merge($lang, array(
	'ACP_NWOMANAGER'									=> 'NWO Manager',

	// NWO Manager Modes, Categories & Main Section Titles
	'ACP_NWOMANAGER_SERVERLIST'							=> 'NWO Manager Server List',
	'ACP_NWOMANAGER_SERVER'								=> 'Server Details',
	'ACP_NWOMANAGER_SETTINGS'							=> 'NWO Manager Settings',
	'ACP_NWOMANAGER_MENULINKS'							=> 'NWO Manager Menus',
	'ACP_NWOMANAGER_MENUBLOCKS'							=> 'Menu Blocks',
	'ACP_NWOMANAGER_MENUBLOCK'							=> 'Menu Block Details',
	'ACP_NWOMANAGER_MENUITEMS'							=> 'Menu Items',
	'ACP_NWOMANAGER_MENUITEM'							=> 'Menu Details',
	'ACP_NWOMANAGER_CENTERBLOCKS'						=> 'NWO Manager Center Blocks',
	'ACP_NWOMANAGER_CENTERBLOCK'						=> 'Center Block Details',
	'ACP_NWOMANAGER_LOGLIST'							=> 'NWO Manager Log',
	'ACP_NWOMANAGER_LOGENTRY'							=> 'Log Entry Details',
	'ACP_NWOMANAGER_SERVERMODDB'						=> 'NWO Manager Server Modsdb',
	'ACP_NWOMANAGER_SERVERMODS'							=> 'NWO Manager Server Mods',
	'ACP_NWOMANAGER_SERVERMOD'							=> 'Server Mod Details',


	// NWO Manager Server List
	'ACP_NWOMANAGER_SERVER_NAME'						=> 'Server Name',
	'ACP_NWOMANAGER_SERVER_DESCRIPTION'					=> 'Server Description',
	'ACP_NWOMANAGER_SERVER_MAP'							=> 'Map',
	'ACP_NWOMANAGER_SERVER_IP'							=> 'IP Address',
	'ACP_NWOMANAGER_SERVER_TYPE'						=> 'Type',
	'ACP_NWOMANAGER_SERVER_ADMINMOD'					=> 'Admin',
	'ACP_NWOMANAGER_SERVER_PRIVATE'						=> 'Private',
	'ACP_NWOMANAGER_SERVER_HLSTATSX'					=> 'HLStatsX',
	'ACP_NWOMANAGER_SERVER_HLX'							=> 'HLX',
	'ACP_NWOMANAGER_SERVER_SOURCEBANS'					=> 'SourceBans',
	'ACP_NWOMANAGER_SERVER_STEAMBANS'					=> 'SteamBans',
	'ACP_NWOMANAGER_SERVER_SBSRC'						=> 'SBSRC',
	'ACP_NWOMANAGER_SERVER_VISIBLE'						=> 'Visible',
	'ACP_NWOMANAGER_SERVERS'							=> '</strong>) NWO Servers.',
	'ACP_NWOMANAGER_NO_SERVERS'							=> 'No servers are currently defined. Click <em>Add Server</em> to create a new server entry.',
	'ACP_NWOMANAGER_ADD_SERVER'							=> 'Add Server',
	'ACP_NWOMANAGER_SERVER_SAVED'						=> 'Server details successfully saved. <br /><br /> Click <a href="%s">here</a> to go back to the NWO Server List.',
	'ACP_CONFIRM_SERVER_DELETE'							=> 'Are you sure you wish to delete this server? <br /><br /><form method="post"><fieldset class="submit-buttons"><input class="button1" type="submit" name="confirmserver" value="Yes" />&nbsp;<input type="submit" class="button2" name="cancelserver" value="No" /></fieldset></form>',
	'ACP_NO_SERVER_ID'									=> 'No server has been specified for deletion.',
	'ACP_SERVER_DELETED'								=> 'Server deleted succesfully. <br /><br /> Click <a href="%s">here</a> to go back to the NWO Server List.',

	// NWO Manager Server List: Server Subform
	'ACP_NWOMANAGER_SERVER_DESC'						=> 'Here you can edit the server details.',
	'ACP_NWOMANAGER_SERVER_NAME_EXPLAIN'				=> 'Short name for the server.',
	'ACP_NWOMANAGER_SERVER_DESCRIPTION_EXPLAIN'			=> 'More detailed explanation or description of the server. This can include details such as tick rate, server location, map rotations, mod installed or other information.',
	'ACP_NWOMANAGER_SERVER_MAP_EXPLAIN'					=> 'The main maps that run on the game server.',
	'ACP_NWOMANAGER_SERVER_IP_EXPLAIN'					=> 'The IP Address of the game server.',
	'ACP_NWOMANAGER_SERVER_TYPE_EXPLAIN'				=> 'The game type that the server is running.',
	'ACP_NWOMANAGER_SERVER_ADMINMOD_EXPLAIN'			=> 'The administration mod used to manage the server.',
	'ACP_NWOMANAGER_SERVER_PRIVATE_EXPLAIN'				=> 'If the server is a private server set to yes otherwise set to no. ',
	'ACP_NWOMANAGER_SERVER_HLSTATSX_EXPLAIN'			=> 'If the server uses HLStatsX set to yes otherwise set to no.',
	'ACP_NWOMANAGER_SERVER_SOURCEBANS_EXPLAIN'			=> 'If the server uses SourceBans set to yes, otherwise set to no',
	'ACP_NWOMANAGER_SERVER_STEAMBANS_EXPLAIN'			=> 'If the server uses SteamBans set to yes, otherwise set to no',
	'ACP_NWOMANAGER_SERVER_VISIBLE_EXPLAIN'				=> 'If the server is visible in the serverlist set to yes.',


	// NWO Manager Settings
	'ACP_NWOMANAGER_SETTINGS_DESC'						=> 'Here you can set the NWO Manager Settings',
	'ACP_NWOMANAGER_SETTINGS_NEWS'						=> 'NWO News And Announcements Settings',
	'ACP_NWOMANAGER_SETTINGS_NEWS_FORUMID'				=> 'Recent News Forum',
	'ACP_AFKMANAGER_SETTINGS_NEWS_MAX'					=> 'Recent News Maximum',
	'ACP_NWOMANAGER_SETTINGS_NEWS_FORUMID_EXPLAIN'		=> 'The forum that is selected to display recent news and announcements from.',
	'ACP_AFKMANAGER_SETTINGS_NEWS_MAX_EXPLAIN'			=> 'The maximum number of news and announcement forum posts to display on the front page.',
	'ACP_NWOMANAGER_SETTINGS_TOPICS'					=> 'NWO Recent Topics Settings',
	'ACP_NWOMANAGER_SETTINGS_TOPICS_FORUMID'			=> 'Recent Topics Forum',
	'ACP_AFKMANAGER_SETTINGS_TOPICS_MAX'				=> 'Recent Topics Maximum',
	'ACP_NWOMANAGER_SETTINGS_TOPICS_FORUMID_EXPLAIN'	=> 'The forum that is selected to display recent post topics from.',
	'ACP_AFKMANAGER_SETTINGS_TOPICS_MAX_EXPLAIN'		=> 'The maximum number of recent post topics to display on the front page.',
	'ACP_NWOMANAGER_SETTINGS_WELCOME'					=> 'NWO Welcome Box Settings',
	'ACP_NWOMANAGER_SETTINGS_WELCOME_BOX'				=> 'Welcome Box Banner',
	'ACP_NWOMANAGER_SETTINGS_WELCOME_BOX_EXPLAIN'		=> 'Contains a brief welcome message and/or information that is shown on the front page',
	'ACP_NWOMANAGER_SETTINGS_LOG'						=> 'NWO Log Settings',
	'ACP_AFKMANAGER_SETTINGS_LOG_ADMINS'				=> 'Admin List Groups',
	'ACP_AFKMANAGER_SETTINGS_LOG_ADMINS_EXPLAIN'		=> "List of groups id's seperated by commas that includes the admins for log entries.",
	'ACP_NWOMANAGER_SETTINGS_SAVED'						=> 'NWO settings successfully saved. <br /><br /> Click <a href="%s">here</a> to go back',


	// NWO Manager Menus: Menu Blocks List
	'ACP_NWOMANAGER_MENUBLOCK_TITLE'					=> 'Menu Block Title',
	'ACP_NWOMANAGER_MENUBLOCK_TEXT'						=> 'Menu Block Heading',
	'ACP_NWOMANAGER_MENUBLOCK_URL'						=> 'Menu Block URL',
	'ACP_NWOMANAGER_MENUBLOCK_CLASS'					=> 'CSS Style Class',
	'ACP_NWOMANAGER_MENUBLOCK_DIVID'					=> 'CSS Style Div Id',
	'ACP_NWOMANAGER_MENUBLOCK_POSITION'					=> 'Position',
	'ACP_NWOMANAGER_MENUBLOCK_TYPE'						=> 'Type',
	'ACP_NWOMANAGER_MENUBLOCK_MENUS'					=> 'Menus',
	'ACP_NWOMANAGER_MENUBLOCK_VISIBLE'					=> 'Visible',
	'ACP_NWOMANAGER_MENUBLOCKSES'						=> '</strong>) Menu Blocks.',
	'ACP_NWOMANAGER_NO_MENUBLOCKS'						=> 'No menu blocks are currently defined. Click <em>Add Menu Block</em> to create a new menu block entry.',
	'ACP_NWOMANAGER_ADD_MENUBLOCK'						=> 'Add Menu Block',
	'ACP_NWOMANAGER_MENUBLOCK_SAVED'					=> 'Menu block details successfully saved. <br /><br /> Click <a href="%s">here</a> to go back to the NWO Manager Menus.',
	'ACP_CONFIRM_MENUBLOCK_DELETE'						=> 'Are you sure you wish to delete this menu block? All menus contained in this menu block will also be deleted. <br /><br /><form method="post"><fieldset class="submit-buttons"><input class="button1" type="submit" name="confirmblock" value="Yes" />&nbsp;<input type="submit" class="button2" name="cancelblock" value="No" /></fieldset></form>',
	'ACP_NO_MENUBLOCK_ID'								=> 'No menu block has been specified for deletion.',
	'ACP_MENUBLOCK_DELETED'								=> 'Menu block deleted succesfully. <br /><br /> Click <a href="%s">here</a> to go back to the NWO Manager Menus.',

	// NWO Manager Menus: Menu Blocks->Menu Block Details
	'ACP_NWOMANAGER_MENUBLOCK_DESC'						=> 'Here you can edit the menu block details.',
	'ACP_NWOMANAGER_MENUBLOCK_TITLE_EXPLAIN'			=> 'A name or short description for the menu block. This is not visible on the front page.',
	'ACP_NWOMANAGER_MENUBLOCK_TEXT_EXPLAIN'				=> 'The menu text heading to appear over the menu block on the front page.',
	'ACP_NWOMANAGER_MENUBLOCK_URL_EXPLAIN'				=> '(Optional) The url that the menu block will go to once clicked.',
	'ACP_NWOMANAGER_MENUBLOCK_CLASS_EXPLAIN'			=> '(Optional) For css styling you can specify a class that the menu block has.',
	'ACP_NWOMANAGER_MENUBLOCK_DIVID_EXPLAIN'			=> '(Optional) The css styling you can specify a div id that the menu block has.',
	'ACP_NWOMANAGER_MENUBLOCK_POSITION_EXPLAIN'			=> 'The position of the menu in relation to the front page.',
	'ACP_NWOMANAGER_MENUBLOCK_TYPE_EXPLAIN'				=> 'The type of menu that will be displayed. Text or images.',
	'ACP_NWOMANAGER_MENUBLOCK_VISIBLE_EXPLAIN'			=> 'Set to Yes (Default) to show the menu block, otherwise set to no to not display.',

	// NWO Manager Menus: Menu Blocks->Menu Items List
	'ACP_NWOMANAGER_MENUITEM_TEXT'						=> 'Menu Item Title',
	'ACP_NWOMANAGER_MENUITEM_URL'						=> 'Menu Item URL',
	'ACP_NWOMANAGER_MENUITEM_IMAGE'						=> 'Menu Item Image',
	'ACP_NWOMANAGER_MENUITEM_VISIBLE'					=> 'Visible',
	'ACP_NWOMANAGER_MENUITEM_CATID'						=> 'Menu Block',
	'ACP_NWOMANAGER_MENUSES'							=> '</strong>) Menu Items.',
	'ACP_NWOMANAGER_NO_MENUITEMS'						=> 'No menu links are currently defined. Click <em>Add Menu Item</em> to create a new menu item entry.',
	'ACP_NWOMANAGER_ADD_MENUITEM'						=> 'Add Menu Item',
	'ACP_NWOMANAGER_MENUITEM_SAVED'						=> 'Menu item details successfully saved. <br /><br /> Click <a href="%s">here</a> to go back.',
	'ACP_CONFIRM_MENUITEM_DELETE'						=> 'Are you sure you wish to delete this menu item? <br /><br /><form method="post"><fieldset class="submit-buttons"><input class="button1" type="submit" name="confirmmenu" value="Yes" />&nbsp;<input type="submit" class="button2" name="cancelmenu" value="No" /></fieldset></form>',
	'ACP_NO_MENUITEM_ID'								=> 'No menu item has been specified for deletion.',
	'ACP_MENUITEM_DELETED'								=> 'Menu item deleted succesfully. <br /><br /> Click <a href="%s">here</a> to go back.',


	// NWO Manager Menus: Menu Blocks->Menu Items->Menu Item Details
	'ACP_NWOMANAGER_MENUITEM_DESC'						=> 'Here you can edit the menu item details.',
	'ACP_NWOMANAGER_MENUITEM_TEXT_EXPLAIN'				=> 'The menu item text label.',
	'ACP_NWOMANAGER_MENUITEM_URL_EXPLAIN'				=> 'The url (forum page, custom page or other location) that the menu item will go to once clicked.',
	'ACP_NWOMANAGER_MENUITEM_IMAGE_EXPLAIN'				=> 'The location of the menu item image. Shown on front page if parent is set to show images.',
	'ACP_NWOMANAGER_MENUITEM_VISIBLE_EXPLAIN'			=> 'Set to Yes (Default) to show the menu item, otherwise set to no to not display.',
	'ACP_NWOMANAGER_MENUITEM_CATID_EXPLAIN'				=> 'Parent menu block category that the menu item belongs to.',

	// NWO Manager Center Blocks: Center Blocks List
	'ACP_NWOMANAGER_CENTERBLOCK_TITLE'					=> 'Center Block Title',
	'ACP_NWOMANAGER_CENTERBLOCK_TEXT'					=> 'Center Block Text',
	'ACP_NWOMANAGER_CENTERBLOCK_VISIBLE'				=> 'Visible',
	'ACP_NWOMANAGER_CENTERBLOCKSES'						=> '</strong>) Center Blocks.',
	'ACP_NWOMANAGER_NO_CENTERBLOCKS'					=> 'No center blocks are currently defined. Click <em>Add Center Block</em> to create a new center block entry.',
	'ACP_NWOMANAGER_ADD_CENTERBLOCK'					=> 'Add Center Block',
	'ACP_NWOMANAGER_CENTERBLOCK_SAVED'					=> 'Center block details successfully saved. <br /><br /> Click <a href="%s">here</a> to go back to the NWO Manager Center Blocks.',
	'ACP_CONFIRM_CENTERBLOCK_DELETE'					=> 'Are you sure you wish to delete this center block?<br /><br /><form method="post"><fieldset class="submit-buttons"><input class="button1" type="submit" name="confirmcenterblock" value="Yes" />&nbsp;<input type="submit" class="button2" name="cancelcenterblock" value="No" /></fieldset></form>',
	'ACP_NO_CENTERBLOCK_ID'								=> 'No menu block has been specified for deletion.',
	'ACP_CENTERBLOCK_DELETED'							=> 'Center block deleted succesfully. <br /><br /> Click <a href="%s">here</a> to go back to the NWO Manager Center Blocks.',

	// NWO Manager Center Blocks: Center Blocks->Center Block Details
	'ACP_NWOMANAGER_CENTERBLOCK_DESC'					=> 'Here you can edit the center block details.',
	'ACP_NWOMANAGER_CENTERBLOCK_TITLE_EXPLAIN'			=> "Title for the center block's content",
	'ACP_NWOMANAGER_CENTERBLOCK_TEXT_EXPLAIN'			=> 'The text content of the center block',
	'ACP_NWOMANAGER_CENTERBLOCK_VISIBLE_EXPLAIN'		=> 'Set to Yes (Default) to show the center block, otherwise set to no to not display.',


	// NWO Manager Log List
	'ACP_NWOMANAGER_LOGFILTER'							=> 'Log Filter: ',
	'ACP_NWOMANAGER_FILTER'								=> 'Filter',
	'ACP_NWOMANAGER_RESETFILTER'						=> 'Reset',
	'ACP_NWOMANAGER_LOGENTRY_DATETIME'					=> 'Log Time',
	'ACP_NWOMANAGER_LOGENTRY_ADMIN'						=> 'Admin',
	'ACP_NWOMANAGER_LOGENTRY_SERVER'					=> 'Server',
	'ACP_NWOMANAGER_LOGENTRY_CATEGORY'					=> 'Category',
	'ACP_NWOMANAGER_LOGENTRY_CAT'						=> 'For',
	'ACP_NWOMANAGER_LOGENTRY_TYPE'						=> 'Type',
	'ACP_NWOMANAGER_LOGENTRY_SUMMARY'					=> 'Summary',
	'ACP_NWOMANAGER_LOGENTRY_NOTES'						=> 'Notes',
	'ACP_NWOMANAGER_LOGENTRIES'							=> '</strong>) Log Entries.',
	'ACP_NWOMANAGER_NO_LOGENTRIES'						=> 'No log entries found. Click <em>Add Log Entry</em> to create a new log entry.',
	'ACP_NWOMANAGER_ADD_LOGENTRY'						=> 'Add Log Entry',
	'ACP_NWOMANAGER_LOGENTRY_SAVED'						=> 'Log entry details successfully saved. <br /><br /> Click <a href="%s">here</a> to go back to the NWO Log List.',
	'ACP_CONFIRM_LOGENTRY_DELETE'						=> 'Are you sure you wish to delete this log entry? <br /><br /><form method="post"><fieldset class="submit-buttons"><input class="button1" type="submit" name="confirmlogentry" value="Yes" />&nbsp;<input type="submit" class="button2" name="cancellogentry" value="No" /></fieldset></form>',
	'ACP_NO_LOGENTRY_ID'								=> 'No log entry has been specified for deletion.',
	'ACP_LOGENTRY_DELETED'								=> 'Log entry deleted succesfully. <br /><br /> Click <a href="%s">here</a> to go back to the NWO Log List.',
	'ACP_LOGKEY'										=> '<em>Key: GHP=Game Host Provider, WHP=Web Host Provider, GSCP=Game Server Control Panel, WSCP=Web Server Control Panel, GS=Game Server, WS=Web Server, FRM=Forum, MA=Mani Admin, SM=SourceMod, MOD=Mod/Plugin/Script</em>',




	// NWO Manager Log List: Log Entry Subform
	'ACP_NWOMANAGER_LOGENTRY_DESC'						=> 'Here you can edit the log entry details.',
	'ACP_NWOMANAGER_LOGENTRY_DATETIME_EXPLAIN'			=> 'The date and time of the log entry.',
	'ACP_NWOMANAGER_LOGENTRY_ADMIN_EXPLAIN'				=> 'The administrator that created the log entry.',
	'ACP_NWOMANAGER_LOGENTRY_SERVER_EXPLAIN'			=> 'The server that the log entry applies to.',
	'ACP_NWOMANAGER_LOGENTRY_CATEGORY_EXPLAIN'			=> 'The log entry category.',
	'ACP_NWOMANAGER_LOGENTRY_TYPE_EXPLAIN'				=> 'The type of log entry.',
	'ACP_NWOMANAGER_LOGENTRY_SUMMARY_EXPLAIN'			=> 'A short summary of the log entry.',
	'ACP_NWOMANAGER_LOGENTRY_NOTES_EXPLAIN'				=> 'Detailed notes of the log entry.',


	// Misc
	'ACP_NWOMANAGER_THEREARE'							=> 'There Are (<strong>',


));

?>