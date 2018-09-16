<?php
/**
 *
 * @package Clan Application
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
 * @package module_install
 */


/**
 * If you are adding a full file, you'll need to make your own info file too.
 * If you are adding mode, you'll need to edit the existing info file.
 * The info file should be placed in the includes/{MODULECLASS}/info folder, and have the same filename as the module file.
 * Example: includes/acp/info/acp_foobar.php.
 */

class acp_appmanager_info
{
	function module()
	{
		return array(
		'filename'   => 'acp_appmanager', // The module's filename
		'title'      => 'ACP_APPMANAGER', // The title (language string)
		'version'    => '0.1.0', // The module's version
		'modes'      => array( // This is where you add the mode(s)
		'appviewlist'	 => array('title' => 'ACP_APPMANAGER_LIST', 'auth' => 'acl_a_app_view', 'cat' => 'ACP_APPMANAGER'),
		'appsettings'	 => array('title' => 'ACP_APPMANAGER_SETTINGS', 'auth' => 'acl_a_app_settings', 'cat' => 'ACP_APPMANAGER'),
		'appform'		 => array('title' => 'ACP_APPMANAGER_FORM', 'auth' => 'acl_a_app_form', 'cat' => 'ACP_APPMANAGER'),
		'appstatus'		 => array('title' => 'ACP_APPMANAGER_STATUS', 'auth' => 'acl_a_app_status', 'cat' => 'ACP_APPMANAGER')
		),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}
?>