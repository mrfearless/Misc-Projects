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
if (!defined('IN_PHPBB'))
{
   exit;
}

if (empty($lang) || !is_array($lang))
{
   $lang = array();
}


// Adding new category
$lang['permission_cat']['nwomanager']   = 'NWO Manager';

// Adding the permissions
$lang = array_merge($lang, array(

   // Admin perms
   'acl_a_nwo_servers'     	=> array('lang' => 'Admin Can View/Modify NWO Manager Servers', 'cat' => 'nwomanager'),
   'acl_a_nwo_settings'  	=> array('lang' => 'Admin Can View/Modify NWO Manager Settings', 'cat' => 'nwomanager'),
   'acl_a_nwo_menus'  		=> array('lang' => 'Admin Can View/Modify NWO Manager Menus', 'cat' => 'nwomanager'),
   'acl_a_nwo_centers'  	=> array('lang' => 'Admin Can View/Modify NWO Manager Center Blocks', 'cat' => 'nwomanager'),
   'acl_a_nwo_logs'  		=> array('lang' => 'Admin Can View/Modify NWO Manager Logs', 'cat' => 'nwomanager'),
   'acl_a_nwo_servermoddb'  => array('lang' => 'Admin Can View/Modify NWO Manager Server Mods', 'cat' => 'nwomanager'),
   'acl_a_nwo_bannerimages' => array('lang' => 'Admin Can View/Modify NWO Banner Images', 'cat' => 'nwomanager'),
   'acl_a_nwo_donations'  	=> array('lang' => 'Admin Can View/Modify NWO Donations', 'cat' => 'nwomanager'),

));

?>