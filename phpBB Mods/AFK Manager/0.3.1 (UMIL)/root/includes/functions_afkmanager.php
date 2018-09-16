<?php
/**
 *
 * @package AFK Manager Functions
 * @version $Id: 0.3.1
 * @copyright (c) 2009 -[Nwo]- fearless
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
	exit;
}


/**********************************************************
 * Get list of users that days AFK is equal to or
 * greater than their last visit date and set them to
 * AutoAFK status if AutoAFK setting is enabled in ACP.
 * This function will check to see if each user that
 * potentially could be marked as AFK, has the permissions
 * to use the UCP AFK Manager module. That way we limit
 * the scope to just those users.
 * Admin can override users own preference for AutoAFK days
 * in the ACP, so we check for this as well.
 **********************************************************/
function afkmanager_autoafk_users()
{
	global $config, $db;

	$afkautodaysdefault = $config['afkmanager_afk_autoafk_default'];
	if ($afkautodaysdefault == 0)
	{
		$sql = 'SELECT user_id, username, user_permissions, user_type, user_lastvisit, user_afkdate, user_afkstatus, user_afktopicid, user_afkreasoncat, user_afkreason, user_afkautodays
				FROM ' . USERS_TABLE . '
				WHERE user_afkstatus = 0 AND user_type != 2 AND user_afkautodays > 0
				ORDER BY user_id ASC';
	}
	else
	{
		$sql = 'SELECT user_id, username, user_permissions, user_type, user_lastvisit, user_afkdate, user_afkstatus, user_afktopicid, user_afkreasoncat, user_afkreason, user_afkautodays
				FROM ' . USERS_TABLE . '
				WHERE user_afkstatus = 0 AND user_type != 2
				ORDER BY user_id ASC';
	}
	$results_autoafkusers = $db->sql_query($sql);

	while ($row_autoafkusers = $db->sql_fetchrow($results_autoafkusers))
	{

		// Check to see if user has permissions to use the UCP, that way we limit the scope just to those that have set a AutoAFK value, or if AutoAFK
		// setting is overridden by admin in ACP, to just users with the required permission and not just everyone as per the sql statement.
		$afkauth = new auth();
		$afkuserid = $row_autoafkusers['user_id'];
		$afkusertype = $row_autoafkusers['user_type'];
		$afkuserpermissions = $row_autoafkusers['user_permissions'];
		$afkuserdata = array(
			'user_id'			=> $afkuserid,
			'user_permissions'	=> $afkuserpermissions,
			'user_type'			=> $afkusertype,
		);
		$afkauth->acl($afkuserdata);
		$afkaclresult = $afkauth->acl_get('u_afk_view');
		//echo '<br />Checking user permssion: ' . $afkuserid . ' = ' . $afkaclresult;
		if ($afkaclresult)
		{

			$afkdays = round((time() - $row_autoafkusers['user_lastvisit'])/86400,0);
			// If admin has overriden users preference for AutoAFK days we use this instead, otherwise we use the users own preference.
			$afkautodays = ($afkautodaysdefault > 0) ? $afkautodaysdefault : $row_autoafkusers['user_afkautodays'];


			//echo ' AFK for ' . $afkdays . 'days. AutoAFK Setting is for ' . $afkautodays . ' days since last visit';
			if ($afkdays >= $afkautodays)
			{
				// Log entry to tell admin that user has been moved to AutoAFK status
				add_log('admin', 'LOG_USER_AUTOAFK', $row_autoafkusers['username']);
				//echo ' -' . $row_autoafkusers['username'] . ' will be marked AutoAFK';

				$afkstatus = 1;
				$afkreason = 'Auto AFK: User defined after '.$afkautodays .' days inactive' ;
				$afkreasoncat = 99;
				$afktopicid = 0;
				$afkpmmsg = '';
				$afkdate = date('Y-m-d H:i:s');

				// Set user to auto afk status and change afkstatus, afkreason, afreasoncat, afkdate to reflect this
				$sql = 'UPDATE ' . USERS_TABLE . '
						SET
						user_afkstatus 		= "' . $afkstatus . '",
						user_afkdate 		= "' . $afkdate . '",
						user_afktopicid 	= "' . $afktopicid . '",
						user_afkreason		= "' . $afkreason . '",
						user_afkreasoncat 	= "' . $afkreasoncat . '"
						WHERE user_id 		= ' . $afkuserid;
						//user_afkpmmsg 		= "' . $afkpmmsg . '",

				$results_afkuser = $db->sql_query($sql);
				//$row = $db->sql_fetchrow($results);
				$db->sql_freeresult($results_afkuser);
			}

		}
	}
	$db->sql_freeresult($results_autoafkusers);
}



/**********************************************************
 * Calculate amount of days user is afk
 * @param $d: date to check against todays date
 * Returns: amount of days afk
 **********************************************************/
function afkdays($d)
{
	$ts = time() - $d;
	$val = round($ts/86400,0);
	return $val;
}

?>