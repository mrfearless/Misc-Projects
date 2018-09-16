<?php
/**
*
* @package acp
* @version $Id: medal_system_mod_version.php 01 2008-03-12 17:35:00Z Gremlinn $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package medal_system_mod
*/
class subscription_system_mod_version
{
	function version()
	{
		global $config;
		return array(
			'author'	=> 'fearless',
			'title'		=> 'Subscription System Mod for phpbb3',
			'tag'		=> 'mod_version_check',
			'version'	=> $config['subscription_mod_version'],
			'file'		=> array('www.newworldorder.org.uk/download/', 'download', 'subscription.xml'),
		);
	}
}

?>