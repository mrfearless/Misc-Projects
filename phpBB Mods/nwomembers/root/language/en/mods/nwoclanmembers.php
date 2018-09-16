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

$lang = array_merge($lang, array(
	'NWOCLANMEMBERS'			=>	'New World Order Clan Members List',
	'USERNAME'					=>	'Admin Name',
	'STEAMID'					=>	'Steam ID',
	'FRIENDID'					=>	'Steam Profile',
	'VACSTATUS'					=>	'VAC Status',
	'ADDTOFRIENDS'				=>	'Add To Friends',
	'NO_DIRECTORS'				=>	'No NWO Directors Listed',
	'NO_COUNCIL'				=>	'No NWO Council Listed',
	'NO_MEMBERS'				=>	'No NWO Members Listed',
	'NWODIRECTORS'				=>	'NWO Directors',
	'NWOCOUNCIL'				=>	'NWO Council',
	'NWOMEMBERS'				=>	'NWO Members',
	'NWOTRIAL'					=>	'NWO Trial Members',
	'ADD_STEAM_FRIEND'			=>	'Add User to Steam Friends List',
));

?>