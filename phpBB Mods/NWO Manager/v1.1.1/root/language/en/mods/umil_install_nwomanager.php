<?php
/**
*
*
* @package NWO Manager UMIL Install Language File
* @version $Id: 1.0.0
* @copyright (c) 2008 -[Nwo]- fearless
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

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

$lang = array_merge($lang, array(
	'NWOMANAGER'					=>	'NWO Manager',
	'ACP_NWOMANAGER'				=>	'NWO Manager',
	'ACP_NWOMANAGER_SERVERLIST'		=>	'NWO Manager Server List',
	'ACP_NWOMANAGER_SETTINGS' 		=>	'NWO Manager Settings',
	'ACP_NWOMANAGER_MENULINKS'		=>	'NWO Manager Menus',
	'ACP_NWOMANAGER_LOGLIST'		=>	'NWO Manager Log',
	'ACP_NWOMANAGER_CENTERBLOCKS'	=>	'NWO Manager Center Blocks',
	'ACP_NWOMANAGER_SERVERMODDB'	=>	'NWO Manager Server Mods',
	'ACP_NWOMANAGER_SERVERMODS'		=>	'NWO Manager Server Mods',
	'ACP_NWOMANAGER_BANNERIMAGES'	=>	'NWO Banner Images',
	'ACP_NWOMANAGER_DONATIONS'		=>	'NWO Donations',

	// Umil
	'INSTALL_NWOMANAGER'			=>	'Install NWO Manager',
	'INSTALL_NWOMANAGER_CONFIRM'	=>	'Do you wish to install NWO Manager?',
	'UPDATE_NWOMANAGER'				=>	'Update NWO Manager',
	'UPDATE_NWOMANAGER_CONFIRM'		=>	'Do you wish to update NWO Manager?',
	'UNINSTALL_NWOMANAGER'			=>	'Uninstall NWO Manager',
	'UNINSTALL_NWOMANAGER_CONFIRM'	=>	'Do you wish to uninstall NWO Manager?',

));

?>