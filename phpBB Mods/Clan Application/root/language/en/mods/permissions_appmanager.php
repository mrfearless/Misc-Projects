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
$lang['permission_cat']['appmanager']   = 'App Manager';

// Adding the permissions
$lang = array_merge($lang, array(

	// Admin perms
	'acl_a_app_view'      => array('lang' => 'Admin Can View The User Applications List', 'cat' => 'appmanager'),
	'acl_a_app_settings'  => array('lang' => 'Admin Can Modify Application Manager Settings', 'cat' => 'appmanager'),
	'acl_a_app_form' 	 => array('lang' => 'Admin Can Modify Application Manager Form', 'cat' => 'appmanager'),
	'acl_a_app_status' 	 => array('lang' => 'Admin Can Modify Application Manager Statuses', 'cat' => 'appmanager'),

));

?>