<?php
/**
 *
 * acp_afkmanager [English]
 *
 * @package Clan Application Manager
 * @version $Id: 0.1.0
 * @copyright (c) 2009 -[Nwo] fearless
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
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

// APP Manager

//$lang['permission_cat']['appmanager']   = 'App Manager';

$lang = array_merge($lang, array(
	'ACP_APPMANAGER'						=>	'App Manager',

	// App Manager User List
	'ACP_APPMANAGER_LIST'					=>	'App Manager User List',

/*	'ACP_AFKMANAGER_TITLE'					=>	'AFK Manager',
	'ACP_AFKMANAGER_DESC'					=>	'The Away From Keyboard (AFK) Manager shows a list of users that have toggled their AFK status to on.',
	'ACP_AFKMANAGER_THEREARE'				=>	'There Are (<strong>',
	'ACP_AFKMANAGER_USERS_AFKSTATUS'		=>	'</strong>) Users That Currently Have Their AFK Status Set To On',
*/


	// App Manager Settings
	'ACP_APPMANAGER_SETTINGS' 							=>	'App Manager Settings',
	'ACP_APPMANAGER_SETTINGS_DESC' 						=>	'Here you can set the Application Manager Settings',
	'ACP_APPMANAGER_SETTINGS_CLANNAME'					=>	'Application Clan Name',
	'ACP_APPMANAGER_SETTINGS_OPEN_ENABLE'				=>	'Applications Open?',
	'ACP_APPMANAGER_SETTINGS_QUESTIONS_ENABLE'			=>	'Application Questions?',
	'ACP_APPMANAGER_SETTINGS_CLANNAME_EXPLAIN'			=>	'Specify your community or clan name for use on the application form.',
	'ACP_APPMANAGER_SETTINGS_OPEN_ENABLE_EXPLAIN'		=>	'Toggles the application process being open or closed to new submissions.',
	'ACP_APPMANAGER_SETTINGS_QUESTIONS_ENABLE_EXPLAIN'	=>	'If on, will displays the questions page for applicants to answer, off will disable this feature.',
	'ACP_APPMANAGER_SETTINGS_AUTOPOST'					=>	'App Manager AutoPost Settings',
	'ACP_APPMANAGER_SETTINGS_POST_PRIVATE'				=>	'AutoPost Forum (Private)',
	'ACP_APPMANAGER_SETTINGS_POST_PUBLIC'				=>	'AutoPost Forum (Public)',
	'ACP_APPMANAGER_SETTINGS_POST_TOPICICON'			=>	'AutoPost Topic Icon',
	'ACP_APPMANAGER_SETTINGS_POST_PRIVATE_EXPLAIN'		=>	'This is the private forum that the application will be submitted to.',
	'ACP_APPMANAGER_SETTINGS_POST_PUBLIC_EXPLAIN'		=>	'This is the public forum that the application will be submitted to.',
	'ACP_APPMANAGER_SETTINGS_POST_TOPICICON_EXPLAIN'	=>	'This is the default Topic Icon to use with the Auto Post.',
	'ACP_APPMANAGER_SETTINGS_SAVED' 					=>	'Application settings successfully saved. <br /><br /> Click <a href="%s">here</a> to go back',


	// App Manager Form
	'ACP_APPMANAGER_FORM' 								=>	'App Manager Form',
	'ACP_APPMANAGER_FORM_DESC'							=>	'Here you can define the application forms sections. Each section has a title and text content.',
	'ACP_NO_SECTIONS'									=>	'No sections have been defined for the application form. Click <em>Add Section</em> to create a new section.',
	'ACP_CREATE_SECTION'								=>	'Add Section',
	'ACP_APPMANAGER_SECTION'							=>	'Section Details',
	'ACP_SECTION_ID'									=>	'ID',
	'ACP_SECTION_TITLE'									=>	'Section Title',
	'ACP_SECTION_TEXT'									=>	'Section Text',
	'ACP_SECTION_ORDER' 								=>	'Order',
	'ACP_SECTION_DISPLAY'								=>	'Display',


	// App Manager Form: Section Subform
	'ACP_APPMANAGER_SECTION_TITLE'						=>	'Section Title:',
	'ACP_APPMANAGER_SECTION_TITLE_EXPLAIN'				=>	'A title for the section.',
	'ACP_APPMANAGER_SECTION_TEXT'						=>	'Section Text:',
	'ACP_APPMANAGER_SECTION_TEXT_EXPLAIN'				=>	'This is the text body of the section. You can use the following variables in section text: {USERNAME}, {USER_IP}, {USER_REGDATE}, {USER_EMAIL}, {SITE_NAME}, {SITE_DESC} and {CLAN_NAME} <br />They will be automatically converted to the appropriate value when a user views the application form.',
	'ACP_APPMANAGER_SECTION_DISPLAY'					=>	'Section Display:',
	'ACP_APPMANAGER_SECTION_DISPLAY_EXPLAIN'			=>	'This enables you to show or hide sections. Sections hidden will not appear on the application form that the user views.',
	'ACP_APPMANAGER_FORM_NOTE'							=>	'You can use the following variables in section text: {USERNAME}, {USER_IP}, {USER_REGDATE}, {USER_EMAIL}, {SITE_NAME}, {SITE_DESC} and {CLAN_NAME} <br />They will be automatically converted to the appropriate value when a user views the application form.',
	'ACP_APPMANAGER_SECTION_SAVED'						=>	'Section details successfully saved. <br /><br /> Click <a href="%s">here</a> to go back to the App Manager Form.',
	'ACP_SECTION_DELETED'								=>	'Section deleted succesfully. <br /><br /> Click <a href="%s">here</a> to go back to the App Manager Form.',
	'ACP_NO_SECTION_ID'									=>	'No section has been specified for deletion.',
	'ACP_CONFIRM_SECTION_DELETE'						=>	'Are you sure you wish to delete this section? <br /><br /><form method="post"><fieldset class="submit-buttons"><input class="button1" type="submit" name="confirmsection" value="Yes" />&nbsp;<input type="submit" class="button2" name="cancelsection" value="No" /></fieldset></form>',


	// App Manager Statuses
	'ACP_APPMANAGER_STATUS'								=>	'App Manager Statuses',
	'ACP_APPMANAGER_STATUSES'							=>	'App Manager Statuses',
	'ACP_APPMANAGER_STATUSES_DESC'						=>	'Here you can define the application statuses and status descriptions.',
	'ACP_STATUS_TITLE'									=>	'Status Name',
	'ACP_STATUS_TEXT'									=>	'Status Description',
	'ACP_STATUS_REAPPLY'								=>	'Reapply?',
	'ACP_STATUS_PM'										=>	'PM?',
	'ACP_STATUS_PMMSG'									=>	'PM Message',
	'ACP_NO_STATUSES'									=>	'No Statuses have been defined. Click <em>Add Status</em> to create a new status.',
	'ACP_CREATE_STATUS'									=>	'Add Status',
	'ACP_APPMANAGER_STATUS_SAVED'						=>	'Status details successfully saved. <br /><br /> Click <a href="%s">here</a> to go back to the App Manager Statuses.',
	'ACP_STATUS_DELETED'								=>	'Status deleted succesfully. <br /><br /> Click <a href="%s">here</a> to go back to the App Manager Status.',
	'ACP_NO_STATUS_ID'									=>	'No status has been specified for deletion.',
	'ACP_CONFIRM_STATUS_DELETE'							=>	'Are you sure you wish to delete this status? <br /><br /><form method="post"><fieldset class="submit-buttons"><input class="button1" type="submit" name="confirmstatus" value="Yes" />&nbsp;<input type="submit" class="button2" name="cancelstatus" value="No" /></fieldset></form>',

	// App Manager Statuses: Status Subform
	'ACP_APPMANAGER_STATUS_TITLE'						=> 	'Status Title',
	'ACP_APPMANAGER_STATUS_TITLE_EXPLAIN'				=> 	'The title of the status.',
	'ACP_APPMANAGER_STATUS_VALUE'						=> 	'Status Value',
	'ACP_APPMANAGER_STATUS_VALUE_EXPLAIN'				=> 	'The value of the status. A negative value indicates unsuccessful, a positive value indicates successfull and 0 indicates pending or a neutral state.',
	'ACP_APPMANAGER_STATUS_TEXT'						=> 	'Status Text.',
	'ACP_APPMANAGER_STATUS_TEXT_EXPLAIN'				=> 	'The text description of the status.',
	'ACP_APPMANAGER_STATUS_COLOR'						=> 	'Status Colour',
	'ACP_APPMANAGER_STATUS_COLOR_EXPLAIN'				=> 	'The colour that the status title is shown in.',
	'ACP_APPMANAGER_STATUS_REAPPLY'						=> 	'Reapply?',
	'ACP_APPMANAGER_STATUS_REAPPLY_EXPLAIN'				=> 	'Indicates if a user that has had a previous application can reapply if they have this status.',
	'ACP_APPMANAGER_STATUS_PM'							=> 	'PM User On Status Change?',
	'ACP_APPMANAGER_STATUS_PM_EXPLAIN'					=> 	'Sends a private message to the applicant indicating their application status, when the status is changed to any status except pending (0)',
	'ACP_APPMANAGER_STATUS_PMMSG'						=> 	'PM Text',
	'ACP_APPMANAGER_STATUS_PMMSG_EXPLAIN'				=> 	'The content of the private message to send to the user.',

));

?>