<?php
define('IN_PHPBB', true);
$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

$user->session_begin();
$auth->acl($user->data);
$user->setup();

include($phpbb_root_path . 'includes/acp/auth.' . $phpEx);
$auth_admin = new auth_admin();

$auth_admin->acl_add_option(array(
    'local'     => array(),
    'global'    => array('u_afk_view', 'a_afk_view')
));


$sql_ary[] = "ALTER TABLE {$table_prefix}users ADD user_afkstatus TINYINT(1) NOT NULL default '0';";
$sql_ary[] = "ALTER TABLE {$table_prefix}users ADD user_afkdate DATE;";
$sql_ary[] = "ALTER TABLE {$table_prefix}users ADD user_afkpmmsg TEXT;";

trigger_error('AFK Manager Installed. Please delete or rename install.php', E_USER_WARNING );

?>