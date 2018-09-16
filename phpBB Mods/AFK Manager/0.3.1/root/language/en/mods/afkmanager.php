<?php
/** 
*
* acp_afkmanager [English]
*
* @package AFK Manager
* @version $Id: afkmanager.php 0.1.0 
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

// Bot settings
$lang = array_merge($lang, array(
	'AFKMANAGER'					=> 'AFK Manager',
	
	// Installation file stuff, not needed anymore after installation is complete
	'AFKMANAGER_PERM_CREATED'		=> 	'AFK Manager Permissions created.',
	'AFKMANAGER_FIELDS_ADDED'		=> 	'AFK Manager Added Fields To User Table.',
	'AFKMANAGER_FIELDS_EXIST'		=> 	'AFK Manager Fields Already Exist In User Table.',
	'AFKMANAGER_MODULE_ADDED'		=> 	'AFK Manager Module has been added.',
	'AFKMANAGER_INSTALL_COMPLETE'	=> 	'<strong>AFK Manager installation complete. Please delete this folder (/install)!!</strong>',
	'AFKMANAGER_INSTALL_RETURN'		=> 	'<br /><br /><br />Click %shere%s to return to the board index.',
	'AFKMANAGER_INSTALLED'			=>	'<strong>AFK Manager Mod Installed</strong>',

	'AFKMANAGER_PREV_MODS_SAVE'		=>	'AFK Manager already in database stored   <br />',
	'AFKMANAGER_PREV_TABLE_DELETE'	=>	'AFK Manager table deleted   <br />',
	'AFKMANAGER_PREV_TABLE_POP'		=>	'AFK Manager created <br/>',
	'AFKMANAGER_MODULE_READDED'		=> 	'AFK Manager Module has been re-added.',

	'AFKMANAGER_CONFIG_DELETE'		=> 	'AFK Manager config fields deleted   <br />',
	'AFKMANAGER_MODULE_DELETED'		=> 	'AFK Manager Module has been deleted   <br />',
	'AFKMANAGER_DELETE_COMPLETE'	=> 	'<strong>AFK Manager deletion complete. Please delete this folder (/install)!!	</strong>',
	'AFKMANAGER_BACKUP_WARN'		=> 	'Make sure you have backed up your database before proceeding!!!',
	'AFKMANAGER_INSTALL_DESC'		=> 	'This installation file will create the Database table/fields and add the appropriate module. <br />To proceed please click on the appropriate action below:',
	'AFKMANAGER_UPGRADE_DESC'		=> 	'This installation file will upgrade/delete the Database table/fields and add/remove the appropriate module. <br />To proceed please click on the appropriate action below:',
	
	'AFKMANAGER_NEW_INSTALL'		=> 	'New Installation',
	'AFKMANAGER_UPGRADE'			=> 	'Upgrade to %s',
	'AFKMANAGER_UNINSTALL'			=> 	'Uninstall',
																	
	'AFKMANAGER_DESCRIPTION' 		=>	'Adds a simple AFK Manager for keeping track of the users that are marked as AFK in the UCP module that is part of this package.
Permissions for viewing the UCP module and the ACP module can be set. Founders will be able to see the ACP panel anyway.',
	

));

?>