<?php
/**
 * @package Clan Application
 * @version $Id: 0.1.0
 * @copyright (c) 2009 -[Nwo]- fearless
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('mods/application');
include($phpbb_root_path . 'includes/constants_appmanager.' . $phpEx);
include($phpbb_root_path . 'includes/message_parser.'.$phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

if ($user->data['user_id'] == ANONYMOUS)
{
    login_box('', $user->lang['LOGIN_APPLICATION_FORM']);
}


$action = request_var('action', '');

// Get some information for us to use later.
$user_id 		= $user->data['user_id'];
$username		= $user->data['username'];
$user_ip		= $user->data['user_ip'];
$user_regdate	= $user->format_date($user->data['user_regdate']);
$user_email		= $user->data['user_email'];
$user_posts		= $user->data['user_posts'];
$user_from		= $user->data['user_from'];
$user_birthday	= $user->data['user_birthday'];

switch ($action)
{

	// Application Questionnaire
	case 'questions':

		$submit = request_var('submit_application', false);

		// Output page
		$page_title = $user->lang['APPLICATION_FOR'] . (!empty($config['application_clanname']) ? $config['application_clanname'] : 'Clan') . $user->lang['APPLICATION_QUESTION'];
		page_header($page_title);

		$template->assign_vars(array(
			'PROCESS_APPQUESTIONS'	=> append_sid("{$phpbb_root_path}application.$phpEx?action=end"),
			'PAGE_TITLE'			=> $page_title,
			'USERNAME'				=> $username,
			));

		$template->set_filenames(array(
		    'body' => 'appquestions_body.html',
		));

		page_footer();
		break;
	/*	if ($submit)
		{
			// Check user hasnt submitted an application previously
			// If they have we need to get status of status of previous application: pending, approved, declined


			// Tell user about his submission
			$message = $user->lang['APPLICATION_SEND'];
			$message = $message . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');
			trigger_error($message);
		}*/


	// Application has been submitted
	case 'end':



		if ($config['application_questions'])
		{
			// Fetch vars for questions
		}

		// Get user information to submit to posts
		echo $user_id;
		echo $username;
		echo $user_regdate;
		echo $user_email;
		echo $user_posts;
		echo $user_from;
		echo $user_birthday;

		// Tell user about his submission
		$message = $user->lang['APPLICATION_SUBMITTED'];
		$message = $message . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');
		trigger_error($message);
		break;


	// Application Overview
	default:

		// First determine if applications are open
		$applications_open = $config['application_open'] ? true : false ;

		if ($applications_open)
		{
			// Check user hasnt made submission already, and let them know if they have.
			if (application_exists($user_id))
			{

				// Output page
				$page_title = $user->lang['APPLICATION_FOR'] . (!empty($config['application_clanname']) ? $config['application_clanname'] : 'Clan');
				page_header($page_title);

				$appstatus = application_status($user_id);
				echo $appstatus;

				if ($appstatus == 0) // 0 is pending/neutral state
				{
					echo 'Pending';
					// Tell user that applications are pending
					$message = $user->lang['APPLICATION_PENDING'];
					$message = $message . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');
					trigger_error($message);

				}
				elseif ($appstatus < 0) // -1 downwards is a rejection, the more negative the worse the reason
				{

					echo 'Unsuccessful';
					$appstatustitle = application_statustitle($appstatus);
					$appstatuscolor = application_statuscolor($appstatus);
					$appstatustext = application_statustext($appstatus);
					echo $appstatuscolor;
					// Tell user that applications are unsuccessful
					$message = $user->lang['APPLICATION_UNSUCCESSFUL'];
					$message = $message . '<br /><br /><strong>Status: </strong>' .  (!empty($appstatuscolor) ? '<span style="color:#' . $appstatuscolor . '">' : '') . $appstatustitle . (!empty($appstatuscolor) ? '</span>' : '');
					$message = $message . '<br /><br /><strong>Reason: </strong>' .  $appstatustext;
					$message = $message . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');
					trigger_error($message);
				}
				elseif ($appstatus > 0) // +1 upwards is a acceptance (of sorts), the more positive the better the acceptance level
				{
					echo 'Successful';
					// Tell user that applications are succesful
					$message = $user->lang['APPLICATION_SUCCESSFUL'];
					$message = $message . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');
					trigger_error($message);
				}
				break;
			}

			else // New application
			{

				// Get variables that are to be swapped out if specified in section text
				$app_vars = array(
					'{USERNAME}'		=> $username,
					'{USER_IP}'			=> $user_ip,
					'{USER_REGDATE}'	=> $user_regdate,
					'{USER_EMAIL}'		=> $user_email,
					'{SITE_NAME}'		=> $config['sitename'],
					'{SITE_DESC}'		=> $config['site_desc'],
					'{CLAN_NAME}'		=> !empty($config['application_clanname']) ? $config['application_clanname'] : 'Clan',
				);

				// Generate query for block assignment to template
				$sql = 'SELECT *
				        FROM phpbb_app_form
				        WHERE section_display=1
				        ORDER BY section_order ASC';
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$sectiontext = html_entity_decode($row['section_text']);
					$sectiontext = str_replace(array_keys($app_vars), array_values($app_vars), $sectiontext);
					$flags = (($config['allow_bbcode']) ? 1 : 0) + (($config['allow_smilies']) ? 2 : 0) + ((true) ? 4 : 0);
					$sectiontext = generate_text_for_display($sectiontext, $row['bbcode_uid'], $row['bbcode_bitfield'], $flags); //$row['section_text']

					$template->assign_block_vars('appform',array(
						'SECTION_TITLE'		=> $row['section_title'],
						//'SECTION_TEXT'		=> html_entity_decode($row['section_text']),
						'SECTION_TEXT'		=> $sectiontext,
					));
				}
				$db->sql_freeresult($result);


				// Output page
				$page_title = $user->lang['APPLICATION_FOR'] . (!empty($config['application_clanname']) ? $config['application_clanname'] : 'Clan') . $user->lang['APPLICATION_OVERVIEW'];
				page_header($page_title);

				$template->assign_vars(array(
						'PROCESS_APPINTRODUCTION'	=> $config['application_questions'] ? append_sid("{$phpbb_root_path}application.$phpEx?action=questions") : append_sid("{$phpbb_root_path}application.$phpEx?action=end"),
						'PAGE_TITLE'				=> $page_title,
						'S_QUESTIONS'				=> $config['application_questions'] ? true : false,
						'S_APPSOPEN'				=> $applications_open, // not used at the moment
					));

				$template->set_filenames(array(
				    'body' => 'appintroduction_body.html',
				));

				page_footer();
			}
		}

		else
		{
			// Output page
			$page_title = $user->lang['APPLICATION_FOR'] . (!empty($config['application_clanname']) ? $config['application_clanname'] : 'Clan');
			page_header($page_title);

			// Tell user that applications are closed
			$message = $user->lang['APPLICATION_CLOSED'];
			$message = $message . '<br /><br />' . sprintf($user->lang['RETURN_INDEX'], '<a href="' . append_sid("{$phpbb_root_path}index.$phpEx") . '">', '</a>');
			trigger_error($message);
			break;
		}
}

/******************************************
 * checks if an application exists
 * returns true if it does, false otherwise
 *****************************************/
function application_exists($userid)
{
	global $db;

	$sql = 'SELECT app_user_id
	        FROM phpbb_applications
	        WHERE app_user_id=' . $userid;
	$result = $db->sql_query($sql);
	if($row = $db->sql_fetchrow($result))
	{
		$db->sql_freeresult($result);
		return true;
	}
	else
	{
		return false;
	}
}


/******************************************
 * checks the status of an application
 * returns -1 if no application exists
 * any other value indicates an app exists
 * return value is the status type
 *****************************************/
function application_status($userid)
{
	global $db;

	$sql = 'SELECT app_status
	        FROM phpbb_applications
	        WHERE app_user_id=' . $userid;
	$result = $db->sql_query($sql);
	if($row = $db->sql_fetchrow($result))
	{
		$appstatus = $row['app_status'];
		$db->sql_freeresult($result);
		return $appstatus;
	}
}


/******************************************
 * Returns the status title of an
 * application status from the status table
 *****************************************/
function application_statustitle($statusvalue)
{
	global $db;

	$sql = 'SELECT status_title
	        FROM phpbb_app_status
	        WHERE status_value=' . $statusvalue;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	$statustitle = $row['status_title'];
	return $statustitle;
}


/******************************************
 * Returns the status text of an
 * application status from the status table
 *****************************************/
function application_statustext($statusvalue)
{
	global $db;

	$sql = 'SELECT status_text, bbcode_uid, bbcode_bitfield
	        FROM phpbb_app_status
	        WHERE status_value=' . $statusvalue;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	$flags = (($config['allow_bbcode']) ? 1 : 0) + (($config['allow_smilies']) ? 2 : 0) + ((true) ? 4 : 0);
	$statustext = generate_text_for_display($row['status_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $flags);
	return $statustext;
}

/******************************************
 * Returns the status color of an
 * application status from the status table
 *****************************************/
function application_statuscolor($statusvalue)
{
	global $db;

	$sql = 'SELECT status_color
	        FROM phpbb_app_status
	        WHERE status_value=' . $statusvalue;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	$statuscolor = $row['status_color'];
	return $statuscolor;
}



?>