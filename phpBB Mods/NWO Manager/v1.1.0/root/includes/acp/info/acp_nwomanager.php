<?php
/**
 *
 * @package NWO Manager
 * @version $Id: 1.0.1
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

class acp_nwomanager_info
{
	function module()
	{
		return array(
		'filename'   		=> 'acp_nwomanager', 	// The module's filename
		'title'      		=> 'ACP_NWOMANAGER', 	// The title (language string)
		'version'    		=> '1.0.0', 			// The module's version
		'modes'     		=> array( 				// This is where you add the mode(s)
		'nwoserverlist'	 	=> array('title' => 'ACP_NWOMANAGER_SERVERLIST', 'auth' => 'acl_a_nwo_servers', 'cat' => 'ACP_NWOMANAGER'),
		'nwosettings'		=> array('title' => 'ACP_NWOMANAGER_SETTINGS', 'auth' => 'acl_a_nwo_settings', 'cat' => 'ACP_NWOMANAGER'),
		'nwomenulinks'	 	=> array('title' => 'ACP_NWOMANAGER_MENULINKS', 'auth' => 'acl_a_nwo_menus', 'cat' => 'ACP_NWOMANAGER'),
		'nwocenterblocks'	=> array('title' => 'ACP_NWOMANAGER_CENTERBLOCKS', 'auth' => 'acl_a_nwo_centers', 'cat' => 'ACP_NWOMANAGER'),
		'nwologlist'		=> array('title' => 'ACP_NWOMANAGER_LOGLIST', 'auth' => 'acl_a_nwo_logs', 'cat' => 'ACP_NWOMANAGER'),
		'nwoservermoddb'	=> array('title' => 'ACP_NWOMANAGER_SERVERMODDB', 'auth' => 'acl_a_nwo_servermoddb', 'cat' => 'ACP_NWOMANAGER'),
		'nwobannerimages'	=> array('title' => 'ACP_NWOMANAGER_BANNERIMAGES', 'auth' => 'acl_a_nwo_bannerimages', 'cat' => 'ACP_NWOMANAGER'),
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