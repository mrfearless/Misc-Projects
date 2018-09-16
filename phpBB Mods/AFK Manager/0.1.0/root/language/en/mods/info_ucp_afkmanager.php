<?php
/**
 *
 * ucp_afkmanager [English]
 *
 * @package AFK Manager
 * @version $Id: 0.1.0
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
	'UCP_AFKMANAGER'			=>	'AFK Manager',
	'UCP_AFKMANAGER_EXPLAIN'	=>	'This is the Away From Keyboard (AFK) control panel, where you can change your AFK status and other settings. Submit your changes by clicking <em>Change AFK Status</em>.',
	'UCP_AFKMANAGER_CHANGE'		=>	'Change AFK Status',
	'UCP_AFKMANAGER_CHANGED'	=>	'AFK Status Changed Successfully.',
	'UCP_AFKMANAGER_QUESTION'	=>	'<strong>Away From Keyboard?</strong>',
	'UCP_AFKMANAGER_REASON'		=>	'<strong>AFK Reason:</strong>',
	'UCP_AFKMANAGER_PMMSG_EXPLAIN'	=>	'<strong>Auto Post Message: </strong>(If this feature has been enabled by your board administrator)',
	'UCP_AFKMANAGER_POSTING_ENABLED' => 'Auto Posting is <strong>Enabled</strong> on this forum',
	'UCP_AFKMANAGER_POSTING_DISABLED' => 'Auto Posting has been <strong>Disabled</strong> on this forum',
	
	// User perms
	'acl_u_afk_view'      		=> array('lang' => 'User Can View The AFK Manager Module', 'cat' => 'afkmanager'),
  	
));



?>