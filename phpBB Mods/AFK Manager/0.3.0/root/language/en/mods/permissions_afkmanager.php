<?php
/**
 * permissions_afkmanager [English]
 *
 * @package AFK Manager
 * @version $Id: 0.1.0
 * @copyright (c) 2009 -[Nwo]- fearless
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
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
$lang['permission_cat']['afkmanager']   = 'AFK Manager';

// Adding the permissions
$lang = array_merge($lang, array(
   // User perms
   'acl_u_afk_view'      => array('lang' => 'User Can View The AFK Manager Module', 'cat' => 'afkmanager'),
   
   // Admin perms
   'acl_a_afk_view'      => array('lang' => 'Admin Can View The AFK List', 'cat' => 'afkmanager'),
   'acl_a_afk_settings'  => array('lang' => 'Admin Can Modify AFK Manager Settings', 'cat' => 'afkmanager'),
   
));

?>