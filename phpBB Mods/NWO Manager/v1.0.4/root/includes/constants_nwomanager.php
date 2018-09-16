<?php
/**
 *
 * @package NWO Manager
 * @version $Id: 1.0.0
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

define('NWOSERVERS_TABLE', $table_prefix . 'nwo_servers');
define('NWOMENUBLOCKS_TABLE', $table_prefix . 'nwo_menu_blocks');
define('NWOMENUITEMS_TABLE', $table_prefix . 'nwo_menu_items');
define('NWOCENTERBLOCKS_TABLE', $table_prefix . 'nwo_center_blocks');
define('NWOLOGS_TABLE', $table_prefix . 'nwo_log');
define('NWOSERVERMODSDB_TABLE', $table_prefix . 'nwo_servers_moddb');
define('NWOSERVERMODS_TABLE', $table_prefix . 'nwo_server_mods');

?>