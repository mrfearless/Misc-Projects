<?php
/**
 *
 * acp_afkmanager [English]
 *
 * @package Clan Application
 * @version $Id: 0.1.0
 * @copyright (c) 2009 -[Nwo] fearless
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

// Clan Application

$lang = array_merge($lang, array(
	'APPLICATION_SUBMITTED'					=>	'Your application has been successfully submitted. Please be patient while it is being reviewed.',
	//'APPLICATION_INTRODUCTION'			=>	'Application To NewWorldOrder: Overview',
	//'APPLICATION_QUESTIONS'				=>	'Application To NewWorldOrder: Questions',
	'APPLICATION_FOR'						=>	'Application For ',
	'APPLICATION_OVERVIEW'					=>	': Overview',
	'APPLICATION_QUESTIONS'					=>	': Questions',

	'APPLICATION_OPEN'						=>	'Application are currently: <strong>Open</strong>',
	'APPLICATION_CLOSED'					=>	'Application are currently: <strong>Closed</strong><br /><br />An announcement will be made on the forum if/when we decide to re-open them.',
	'APPLICATION_PENDING'					=>	'An application by you has already been submitted. Please be patient while it is being reviewed.',
	'APPLICATION_SUCCESSFUL'				=>	'Your application has been successful.',
	'APPLICATION_UNSUCCESSFUL'				=>	'Your application has been unsuccessful.',

	'APP_CONTINUE'							=>	'Continue',
	'APP_SUBMIT'							=>	'Submit Application',

	// Log
	'LOG_APP_SUBMIT'						=> '<strong>User has submitted an application</strong><br />» %s',

));

?>