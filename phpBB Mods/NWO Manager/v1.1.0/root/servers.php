<?php
/**
 * @package NWO Servers
 * @version $Id: 1.0.0
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
$user->setup();
include($phpbb_root_path . 'includes/constants_nwomanager.' . $phpEx);
//include($phpbb_root_path . 'includes/functions_nwomanager.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

$hlstatsx = false; // set to true to display  hlstatsx iframes if server has hlstatsx
nwo_serverlist('css');
nwo_serverlist('tf2');
nwo_serverlist('l4d');

// Output page
$page_title = 'NWO Servers';
page_header($page_title, false);

$template->set_filenames(array(
	'body' => 'nwoservers_body.html',
));

page_footer();



/**********************************************************
 * Populates the template variables for the servers block
 * Each template block is named after the passed param
 * of <servertype>_servers
 * for example:
 *
 * nwo_serverlist('css') will create a template block
 * named css_servers
 *
 **********************************************************/
function nwo_serverlist($servertype = '')
{
	global $config, $db, $template;

	$servertypearray = array(
		'0'		=>	'CSS',
		'1'		=>	'TF2',
		'2'		=>	'L4D',
		'3'		=>	'L4D2',
		'4'		=>	'COD4',
		'5'		=>	'COD5',
		'6'		=>	'INS',
		'7'		=>	'HL2',
		'8'		=>	'Mod',
		'9'		=>	'Other',
	);

	$reverseservertypearray = array(
		'CSS'		=>	'0',
		'TF2'		=>	'1',
		'L4D'		=>	'2',
		'L4D2'		=>	'3',
		'COD4'		=>	'4',
		'COD5'		=>	'5',
		'INS'		=>	'6',
		'HL2'		=>	'7',
		'Mod'		=>	'8',
		'Other'		=>	'9',
	);

	$adminmodarray = array(
		'0' 	=>	'Mani Admin',
		'1'		=>	'SourceMod Admin',
		'2'		=>	'eXtensible Admin',
		'3'		=>	'RCON Only',
	);

	// Get all servers that are visible
	$sqlservers = 'SELECT *
			FROM ' . NWOSERVERS_TABLE . '
			WHERE server_visible=1 ' .
			(!empty($servertype) ? 'AND server_type=' . $reverseservertypearray[strtoupper($servertype)] : '') .'
			ORDER BY server_order';

	$resultservers = $db->sql_query($sqlservers);
	while ($rowservers = $db->sql_fetchrow($resultservers))
	{

		// Assign to block_var array server details
    	$template->assign_block_vars( !empty($servertype) ? strtolower($servertype) : 'all' . '_servers', array(
        	'SERVER_ID'				=> $rowservers['server_id'],
    		'SERVER_URL'			=> 'steam://connect/' . $rowservers['server_ip'],
    		'SERVER_NAME'			=> !empty($rowservers['server_name']) ? $rowservers['server_name'] : 'Server #' . $rowservers['server_id'],
        	'SERVER_DESCRIPTION'	=> trim($rowservers['server_description']),
        	'SERVER_MAP'			=> $rowservers['server_map'],
        	'SERVER_IP'				=> $rowservers['server_ip'],
			'SERVER_TYPE'			=> $servertypearray[$rowservers['server_type']],
			'SERVER_ADMINMOD'		=> $adminmodarray[$rowservers['server_adminmod']],
        	'S_SERVER_PRIVATE'		=> $rowservers['server_private'] ? true : false,
        	'S_SERVER_SHOW_HLSTATSX'=> $hlstatsx ? true : false,
        	'S_SERVER_HLSTATSX'		=> $rowservers['server_hlstatsx'] ? true : false,
        	'S_SERVER_SOURCEBANS'	=> $rowservers['server_sourcebans'] ? true : false,
        	'S_SERVER_STEAMBANS'	=> $rowservers['server_steambans'] ? true : false,
    	));
	}
	$db->sql_freeresult($resultservers);
}

?>



