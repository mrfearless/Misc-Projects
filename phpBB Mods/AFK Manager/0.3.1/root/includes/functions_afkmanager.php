<?php
/**
*
* @package AFK Manager Functions
* @version $Id: 0.3.1
* @copyright (c) 2009 -[Nwo]- fearless
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}


/**********************************************************
 * Get list of users that auto afk days is equal to or 
 * greater than their last visit date
 * 
 * 
 **********************************************************/
function afkmanager_autoafk_users()
{
	global $config, $db;
	
	echo 'Checking afkmanager_autoafk_users';
	$afkautodaysdefault = $config['afkmanager_afk_autoafk_default'];
	if ($afkautodaysdefault == 0)
	{
		$sql = 'SELECT user_id, username, user_lastvisit, user_afkdate, user_afkstatus, user_afktopicid, user_afkreasoncat, user_afkreason, user_afkautodays
				FROM ' . USERS_TABLE . '
				WHERE user_afkstatus = 0 AND user_type != 2 AND user_afkautodays > 0 
				ORDER BY user_id ASC';
	}
	else 
	{
		$sql = 'SELECT user_id, username, user_lastvisit, user_afkdate, user_afkstatus, user_afktopicid, user_afkreasoncat, user_afkreason, user_afkautodays
				FROM ' . USERS_TABLE . '
				WHERE user_afkstatus = 0 AND user_type != 2 
				ORDER BY user_id ASC';		
	}
	$results_autoafkusers = $db->sql_query($sql);
	
	while ($row_autoafkusers = $db->sql_fetchrow($results_autoafkusers))
	{
		//$lastvisit = (int) (floor(( time() - $row_autoafkusers['user_lastvisit']) / 86400));
		//$afkdays = afkdays($row_autoafkusers['user_lastvisit']);
		$afkdays = round((time() - $row_autoafkusers['user_lastvisit'])/86400,0);
		// If admin has overriden users preference for AutoAFK days we use this instead, otherwise we use the users own preference.
		$afkautodays = ($afkautodaysdefault > 0) ? $afkautodaysdefault : $row_autoafkusers['user_afkautodays'];
		$afkuserid = $row_autoafkusers['user_id'];
		
		echo '<br />';
		echo $afkdays;
		echo ' | ';
		echo $afkautodays;

		if ($afkdays >= $afkautodays)
		{
			
			echo $row_autoafkusers['username'];
			
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
					user_afkpmmsg 		= "' . $afkpmmsg . '",
					user_afktopicid 	= "' . $afktopicid . '",
					user_afkreason		= "' . $afkreason . '",
					user_afkreasoncat 	= "' . $afkreasoncat . '"
					WHERE user_id 		= ' . $afkuserid;
			
			$results_afkuser = $db->sql_query($sql);
			//$row = $db->sql_fetchrow($results);
			$db->sql_freeresult($results_afkuser);			
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