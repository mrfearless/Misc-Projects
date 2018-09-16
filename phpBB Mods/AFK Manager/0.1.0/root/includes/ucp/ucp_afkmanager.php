<?php
/**
 * 
 * @package AFK Manager
 * @version $Id: 0.1.0
 * @copyright (c) 2009 -[Nwo]- fearless
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License 
 */

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
 * @package UCP
 */

$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './' . $config['script_path'];
$phpEx = substr(strrchr(__FILE__, '.'), 1);
//include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
//$auth->acl($user->data);

/**
 * {CLASSNAME}: Same as filename, without extension. Example: acp_foobar.
 * $u_action: Path to current module, including session id. This property is set automatically. Example: ./index.php?i=foobar&sid={SESSION_ID}&mode=bar
 * $tpl_path: Template file's filename, without extension. Should be set in the module. Example: acp_foobar
 * $page_title: Page title, should be set in the module.
 * $module_path: Path to module files. Set automatically. Example: ./includes/acp
 */
	
class ucp_afkmanager
{
	var $u_action;
	var $tpl_path;
	var $page_title;

	function main($id, $mode)
	{
		global $config, $db, $user, $template;

		$user->add_lang('mods/info_ucp_afkmanager');
		$submit = (!empty($_POST['ucp-afkmanager-status'])) ? true : false;
		$forumid = $config['afkmanager_afk_posting_forumid'];
		
		if ($submit) 
		{
			$afkstatus = request_var('AFKSTATUS', '0');
			$afkdate = date('Y-m-d H:i:s');
			//$afkpmmsg = request_var('AFKPMMSG', 'My status is marked as Away From Keyboard (AFK) at the moment. Any PMs sent to me in the meantime wont be read until my return.');
			$afkpmmsg = utf8_normalize_nfc(request_var('AFKPMMSG', 'My status is marked as Away From Keyboard (AFK) at the moment. Any PMs sent to me in the meantime wont be read until my return.', true));
			$afkreason = request_var('afkreasonselect', '0');
			
			$sql = 'UPDATE ' . USERS_TABLE . '
					SET 
					user_afkstatus 	= "' . $afkstatus . '",
					user_afkdate 	= "' . $afkdate . '",
					user_afkpmmsg 	= "' . $afkpmmsg . '",
					user_afkreason 	= "' . $afkreason . '"
					WHERE user_id='. $user->data['user_id'];			
			
			$results = $db->sql_query($sql);
			$row = $db->sql_fetchrow($results);
			$db->sql_freeresult($results);

			// If afkstatus is 1, and auto post is enabled, we will show the afker their post link as well. If they are setting it back to 0, we dont show it.
/*			if ($afkstatus == 0)
			{
				$template->assign_vars(array(
					'S_AUTO_POST'	=> false,
				));	
			}
			else 
			{
				$template->assign_vars(array(
					'S_AUTO_POST'	=> $config['afkmanager_afk_posting_enable'] ? true : false,
				));		
			}*/
			
			$template->assign_vars(array(
				'S_SAVE_DONE'	=> true,
			));

			// Log entry to tell admin that user has changed AFK status
			add_log('admin', 'LOG_USER_AFK_STATUS', $afkstatus ? 'AFK' : 'Not AFK', $user->data['username']);

			$afkmanager_afk_posting_enable = $config['afkmanager_afk_posting_enable'];
			if ($afkmanager_afk_posting_enable == 1) // Has admin enabled for auto posting? if so we post msg
			{

				// Check to see if user has made a post already as we want to reply to that. If they havent then we dont submit a reply msg. Otherwise we will.
				$sql = "SELECT user_afktopicid
					FROM ". USERS_TABLE ."
					WHERE user_id=". $user->data['user_id'];
				$results = $db->sql_query($sql);
				$row = $db->sql_fetchrow($results);
				$db->sql_freeresult($results);		
				$topicid = $row['user_afktopicid'];				
				
				// If afkstatus is 1, and auto post is enabled, and topic id is 0 we will show the afker their post link as well. If they are setting afkstatus back to 0, we dont show it.
				if ($afkstatus == 1)
				{
					if ($topicid == 0) // Check to prevent users repeatedly hitting yes and submitting in UCP, post msg if no topic id 
					{
						$template->assign_vars(array(
							'S_AUTO_POST'	=> $config['afkmanager_afk_posting_enable'] ? true : false,
						));		
						postafkmsg('post', 0, $afkreason, $afkpmmsg);
					}
					else 
					{
						$template->assign_vars(array(
							'S_AUTO_POST'	=> false,
						));		
					}
				}
				else 
				{
					$template->assign_vars(array(
						'S_AUTO_POST'	=> false,
					));	
					
					if ($topicid != 0) // Check to prevent users repeatedly hitting no and submitting in UCP. post reply only if a topic id exists
					{
						postafkmsg('reply', $topicid, 0, '');
					}
				}	
			}
		}
		
		$sql = "SELECT user_afkstatus, user_afkdate, user_afkpmmsg, user_afktopicid, user_afkreason
			FROM ". USERS_TABLE ."
			WHERE user_id=". $user->data['user_id'];
		$results = $db->sql_query($sql);
		$row = $db->sql_fetchrow($results);
		$db->sql_freeresult($results);

		$afkreasonsarray = array(
			'0'		=>	' ', 
			'1'		=>	'Holiday', 
			'2'		=>	'School/College/University', 
			'3'		=>	'Illness',
			'4'		=>	'Family Matter',
			'5'		=>	'Real Life Issues', 
			'6'		=>	'Work/Training', 
			'7'		=>	'Party/Celebration',
			'7'		=>	'Night Out/Social Event',
			'8'		=>	'Marriage/Engagement',
			'9'		=>	'Internet/Computer Problem',
			'10'	=>	'Inactive',			
		);			
		
		// If afkstatus is 1 then we will set and show the reason, otherwise if it is 0, then we set afkreason to 0 (reset it for next time)
		$afkreasons = afkreasons_select($afkreasonsarray, ($row['user_afkstatus'] ? $row['user_afkreason'] : 0));
		$template->assign_vars(array(
			//'S_AFKAUTOPOSTING'		=> ($afkmanager_afk_posting_enable == 0) ? true : false,
			'AFKREASONS'	=> $afkreasons,
		));		
		
		$template->assign_vars(array(
			'AFKSTATUS_PROCESSOR'	=> $this->u_action,
			'AFKSTATUS'				=> $row['user_afkstatus'],
			'AFKDATE'				=> $row['user_afkdate'],
			'AFKPMMSG'				=> (!empty($row['user_afkpmmsg']) ? $row['user_afkpmmsg'] : 'My status is marked as Away From Keyboard (AFK) at the moment. Any PMs sent to me in the meantime wont be read until my return.'),
			//'AFKPMMSG'				=> $row['user_afkpmmsg']
			'S_AFKSTATUS'			=> ($row['user_afkstatus'] == 1) ? true : false,
			'AFKTOPICID'			=> $row['user_afktopicid'],
			'AFKER_TOPIC_LINK'		=> append_sid('./viewtopic.php?f=' . $forumid . '&t=' . $row['user_afktopicid'], false, false, false),
			
		));
		
		$this->tpl_name = 'ucp_afkmanager';
		$this->page_title = 'UCP_AFKMANAGER';

	}
}

function postafkmsg($mode, $topicid, $afkreason, $afkmsg)
{
	global $config, $db, $user, $auth;

	$afkreasonsarray = array(
		'0'		=>	' ', 
		'1'		=>	'Holiday', 
		'2'		=>	'School/College/University', 
		'3'		=>	'Illness',
		'4'		=>	'Family Matter',
		'5'		=>	'Real Life Issues', 
		'6'		=>	'Work/Training', 
		'7'		=>	'Party/Celebration',
		'7'		=>	'Night Out/Social Event',
		'8'		=>	'Marriage/Engagement',
		'9'		=>	'Internet/Computer Problem',
		'10'	=>	'Inactive',	
	);			
	
	$forumid = $config['afkmanager_afk_posting_forumid'];
	if ($forumid == 0 ) // If no forum id is specified, we cant go ahead and post
	{
		echo 'Returning';
		return;
	}
	
	$auth = new auth();
	
	// back up $user and $auth and then overwrite them with new data
	$backup = array(
   		'user'   => $user,
   		'auth'   => $auth,
	);
	
	$user_id = $user->data['user_id'];
	
	// overwrite the users userdata and re-auth (get the new users permissions).
	$sql = "SELECT *
   		FROM " . USERS_TABLE . "
    	WHERE user_id=". $user_id;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$user->data = array_merge($user->data, $row);
	$auth->acl($user->data);
	
	
	// Start setting some post variables
	$user->ip = '0.0.0.0';
	
	if ($mode == 'post')
	{	
		$my_subject = 'My AFK Status: ';
		$my_subject .= ($afkreason > 0) ? $afkreasonsarray[$afkreason] : 'AFK';
	}
	else 
	{
/*		if ($topicid ==0) // If its a reply and topicid is 0 then user is just submitting akf no in UCP so ignore.
		{
			return;
		}		
		else
		{*/
			if ($config['afkmanager_afk_posting_reply'] ==0) // Has admin enabled for auto reply? if so we post msg reply, otherwise we change it to a (new msg)
			{
				//$mode = 'post';
				$topicid = 0;
			}
		//}	
		$my_subject = "My AFK Status: I'm Back";
		$afkmsg = "Just a quick note to let everyone know I've returned.";
	}
	
	// Prepare post info
	$subject = utf8_normalize_nfc($my_subject, true);
	$message = utf8_normalize_nfc($afkmsg, true);
	
	// variables to hold the parameters for submit_post
	$poll = $uid = $bitfield = $options = '';

	generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
	generate_text_for_storage($message, $uid, $bitfield, $options, true, true, true);
	
	//$username 
	$topic_type = POST_NORMAL ;
	$update_message = true ;
	
	$data = array( 
	    // General Posting Settings
	    'forum_id'			=> $forumid, // The forum ID in which the post will be placed. (int)
	    'topic_id'			=> $topicid, // Post a new topic or in an existing one? Set to 0 to create a new one, if not, specify your topic ID here instead.
	    'icon_id'           => false,    // The Icon ID in which the post will be displayed with on the viewforum, set to false for icon_id. (int)

	    // Defining Post Options
	    'enable_bbcode'   	=> true,     // Enable BBcode in this post. (bool)
	    'enable_smilies'   	=> true,     // Enabe smilies in this post. (bool)
	    'enable_urls'       => true,     // Enable self-parsing URL links in this post. (bool)
	    'enable_sig'       	=> true,     // Enable the signature of the poster to be displayed in the post. (bool)

	    // Message Body
	    'message'           => $message, // Your text you wish to have submitted. It should pass through generate_text_for_storage() before this. (string)
	    'message_md5'    	=> md5($message),// The md5 hash of your message

	    // Values from generate_text_for_storage()
	    'bbcode_bitfield'   => $bitfield,// Value created from the generate_text_for_storage() function.
	    'bbcode_uid'        => $uid,     // Value created from the generate_text_for_storage() function.

	    // Other Options
	    'post_edit_locked'  => 0,        // Disallow post editing? 1 = Yes, 0 = No
	    'topic_title'       => $subject, // Subject/Title of the topic. It should pass through generate_text_for_storage() before this. (string)
	    //'topic_approved'	=> true,

	    // Email Notification Settings
	    'notify_set'        => false,    // (bool)
	    'notify'            => false,    // (bool)
	    'post_time'         => 0,        // Set a specific time, use 0 to let submit_post() take care of getting the proper time (int)
	    'forum_name'        => '',       // For identifying the name of the forum in a notification email. (string)
 
	    // Indexing
	    'enable_indexing'   => true,     // Allow indexing the post? (bool)
	);
	
	if ($mode == 'post') // we only need to get topic id and store it if user making new post and not a reply to one made already
	{
		// Post the new topic post msg
		submit_post($mode, $subject, '', $topic_type, $poll, $data);
		
		// Get topic id and store it in table for later replying to it
		$topicid = $data['topic_id'];
	}
	else 
	{
		if ($config['afkmanager_afk_posting_reply'] ==0)
		{
			// Post the reply as a new topic msg
			submit_post('post', $subject, '', $topic_type, $poll, $data);
		}	
		else	
		{
			// Post the reply msg to the existing topic
			submit_post($mode, $subject, '', $topic_type, $poll, $data);
		}
		// we set topicid back to 0, just in case someone re-submits no as their status in ucp. 
		$topicid = 0;
	}
	
	$sql = 'UPDATE ' . USERS_TABLE . '
			SET 
			user_afktopicid 	= "' . $topicid . '"
			WHERE user_id='. $user_id;			
				
	$results = $db->sql_query($sql);
	$row = $db->sql_fetchrow($results);
	$db->sql_freeresult($results);	
	
	// Restore auth
	extract($backup);
}

// create select options listbox from array, and select the selected option
function afkreasons_select($optionsarray, $selected = "")
{
	$returnval = '';
	foreach ($optionsarray as $field=>$value) 
	{
		if ($field == $selected)
  		{
	 		$returnval .= '<option selected value="' . $field . '">' . $value . '</option>\n';
		}
	    else
	    {
			$returnval .= '<option value="' . $field . '">' . $value . '</option>\n';
		}
	}
	return $returnval;
}

?>