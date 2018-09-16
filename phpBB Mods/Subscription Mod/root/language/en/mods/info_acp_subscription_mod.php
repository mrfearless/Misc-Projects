<?php
/***************************************************************************
*
* @package Medals Mod for phpBB3
* @version $Id: medals.php,v 0.7.0 2008/01/14 Gremlinn$
* @copyright (c) 2008 Nathan DuPra (mods@dupra.net)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
***************************************************************************/

/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'ACP_SUBSCRIPTION_INDEX'			=> 'Subscription ACP',
	'ACP_SUBSCRIPTION_INDEX_EXPLAIN'	=> 'Subscription Index Explain',
	'ACP_SUBSCRIPTION_TITLE'			=> 'Subscription Management',
	'ACP_SUBSCRIPTION_SETTINGS'			=> 'Configuration',

	'SUBSCRIPTION_MOD_INSTALLED'		=> 'Subscription MOD version %s installed',
	'SUBSCRIPTION_MOD_UPDATED'			=> 'Subscription MOD updated to version %s',
	'SUBSCRIPTION_MOD_MANUAL'			=> 'You have an older version of Subscription MOD installed.<br />You will need to uninstall that version first<br />Be sure to make backups first.',

	'acl_a_manage_subscription'			=>  array('lang' => 'Can use the subscription management module', 'cat' => 'misc'),

// Subscription Management
	'ACP_SUBSCRIPTION_MGT_TITLE'		=> 'Subscription Management',
	'ACP_SUBSCRIPTION_MGT_DESC'			=> 'Here you can view, create, modify, and delete subscriptions',

	'ACP_SUBSCRIPTION'					=> 'Subscriptions',
	//'ACP_MEDALS_DESC'					=> 'Here you can view, create, modify, and delete medals for this category.',
	//'ACP_MULT_TO_USER'				=> 'Number of Awards per user',
	'ACP_USER_SUBSCRIPTION'				=> 'User Subscription',
	'ACP_SUBSCRIPTION_LEGEND'			=> 'Subscription',
	'ACP_SUBSCRIPTION_TITLE_EDIT'		=> 'Edit Subscription',
	'ACP_SUBSCRIPTION_TEXT_EDIT'		=> 'Modify an existing Subscription',
	'ACP_SUBSCRIPTION_TITLE_ADD'		=> 'Create Subscription',
	'ACP_SUBSCRIPTION_TEXT_ADD'			=> 'Create a new Subscription from scratch',
	'ACP_SUBSCRIPTION_DELETE_GOOD'		=> 'The Subscription was removed successfully.<br /><br /> Click <a href="%s">here</a> to return the previous page',
	'ACP_SUBSCRIPTION_EDIT_GOOD'		=> 'The Subscription was updated successfully.<br /><br /> Click <a href="%s">here</a> to return the previous page',
	'ACP_SUBSCRIPTION_ADD_GOOD'			=> 'The Subscription was added successfully.<br /><br /> Click <a href="%s">here</a> to return the previous page',
	'ACP_SUBSCRIPTION_ADD_FAIL'			=> 'No user name was listed for addition.<br /><br /> Click <a href="%s">here</a> to return the previous page',
	'ACP_CONFIRM_MSG_1'					=> 'Are you sure you wish to delete this Subscription? <br /><br /><form method="post"><fieldset class="submit-buttons"><input class="button1" type="submit" name="confirm" value="Yes" />&nbsp;<input type="submit" class="button2" name="cancelsubscription" value="No" /></fieldset></form>',

	'ACP_SUBSCRIPTION_ACTIVATE' 		=> 'Subscription MOD Activated',
	'ACP_NO_SUBCRIPTION_USERID'			=> 'The username you specified does not exist.',
	'ACP_SUBSCRIPTION_CYCLES_REQUIRED'	=> 'You must define subscription cycles before you can add a new subscription account.<br /><br /> Click <a href="%s">here</a> to return the previous page',
	
	
// Subscription Detail: Edit/New	
	'ACP_SUBSCRIPTION_NAME_TITLE'		=> 'Account Name',
	'ACP_SUBSCRIPTION_OVERDUE_TITLE'	=> 'Overdue',
	'ACP_SUBSCRIPTION_OVERDUE_EXPLAIN'	=> 'Is the account overdue their payment?',
	'ACP_SUBSCRIPTION_REGDATE_TITLE'	=> 'Registration Date',
	'ACP_SUBSCRIPTION_REGDATE_EXPLAIN'	=> 'Date that the subscription account was created',
	'ACP_SUBSCRIPTION_INITIALPAY_TITLE' => 'Initial Payment',
	'ACP_SUBSCRIPTION_INITIALPAY_EXPLAIN' => 'The initial payment on opening the subscription account',
	'ACP_SUBSCRIPTION_CYCLE_TITLE' 		=> 'Payment Cycle',
	'ACP_SUBSCRIPTION_CYCLE_EXPLAIN'	=> 'The recurring cycle of payments',
	'ACP_SUBSCRIPTION_AMOUNT_TITLE'		=> 'Payment Amount',
	'ACP_SUBSCRIPTION_AMOUNT_EXPLAIN'	=> 'The payment amount per cycle',
	'ACP_SUBSCRIPTION_NEXTDATE_TITLE'	=> 'Payment Next Due',
	'ACP_SUBSCRIPTION_NEXTDATE_EXPLAIN'	=> 'The next payment date',
	'ACP_SUBSCRIPTION_STATUS_TITLE'		=> 'Status',
	'ACP_SUBSCRIPTION_STATUS_EXPLAIN'	=> 'The status of the subscription account',
	
	
	//'ACP_NAME_DESC'					=> 'Medal Description',
	//'ACP_IMAGE_TITLE'					=> 'Medal Image',
	//'ACP_IMAGE_EXPLAIN'				=> 'The gif image for the medal inside the images/medals/ directory',
	//'ACP_DEVICE_TITLE'				=> 'Device Image',
	//'ACP_DEVICE_EXPLAIN'				=> 'The base name of the gif image inside the images/medals/devices directory, to be applied to dynamically create medals.<br /> Ex. device-2.gif = device',
	//'ACP_PARENT_TITLE'				=> 'Medal Category',
	//'ACP_PARENT_EXPLAIN'				=> 'The category that the medal is to be put in',
	//'ACP_DYNAMIC_TITLE'				=> 'Dynamic Medal Image',
	//'ACP_DYNAMIC_EXPLAIN'				=> 'Dynamically create the image for multiple awardings.',
	//'ACP_NOMINATED_TITLE'				=> 'Medal Nominations',
	//'ACP_NOMINATED_EXPLAIN'			=> 'Can users nominate other users for this medal?',
	'ACP_CREATE_SUBSCRIPTION'			=> 'Create Subscription',


// Subscription View	
	'ACP_SUBSCRIPTION_ACCOUNT'			=> 'Account',
	'ACP_SUBSCRIPTION_REGDATE'			=> 'Reg Date',
	'ACP_SUBSCRIPTION_INITIALPAY'		=> 'Initial Pay',
	'ACP_SUBSCRIPTION_CYCLE'			=> 'Cycle',
	'ACP_SUBSCRIPTION_AMOUNT'			=> 'Amount',
	'ACP_SUBSCRIPTION_NEXTDATE'			=> 'Next Due',
	'ACP_SUBSCRIPTION_OVERDUE'			=> 'Overdue',
	'ACP_SUBSCRIPTION_STATUS'			=> 'Status',
	

	
	//'ACP_MEDAL_TITLE_CAT'				=> 'Edit Category',
	//'ACP_MEDAL_TEXT_CAT'				=> 'Modify an existing category',
	//'ACP_MEDAL_LEGEND_CAT'				=> 'Category',
	//'ACP_NAME_TITLE_CAT'				=> 'Category Name',
	//'ACP_CREATE_CAT'					=> 'Create Category',
	//'ACP_CAT_ADD_FAIL'					=> 'No category name was listed for addition.<br /><br /> Click <a href="%s">here</a> to return the categories list page',
	//'ACP_CAT_ADD_GOOD'					=> 'The category was added successfully.<br /><br /> Click <a href="%s">here</a> to return the categories list page',
	//'ACP_CAT_EDIT_GOOD'					=> 'The category was edited successfully.<br /><br /> Click <a href="%s">here</a> to return the categories list page',
	//'ACP_CAT_DELETE_CONFIRM'			=> 'Which category would you like to move all this category\'s medals to upon deletion? <br /><form method="post"><fieldset class="submit-buttons"><select name="newcat">%s</select><br /><br /><input class="button1" type="submit" name="moveall" value="Move All Medals" />&nbsp;<input class="button2" type="submit" name="deleteall" value="Delete All Medals" />&nbsp;<input type="submit" class="button2" name="cancelcat" value="Cancel Deletion" /></fieldset></form>',
	//'ACP_CAT_DELETE_CONFIRM_ELSE'		=> 'There are no other categories to move these medals to.<br />Are you sure you wish to remove this category and all of its medals?<br /><form method="post"><fieldset class="submit-buttons"><br /><input class="button2" type="submit" name="deleteall" value="Yes" />&nbsp;<input type="submit" class="button2" name="cancelcat" value="No" /></fieldset></form>',
	//'ACP_CAT_DELETE_GOOD'				=> 'This category, all of its contents, and all of its contents that were awarded were deleted successfully<br /><br /> Click <a href="%s">here</a> to return the categories list page',
	//'ACP_CAT_DELETE_MOVE_GOOD'			=> 'All medals from "%1$s" have been moved to "%2$s" and the category has been deleted successfully.<br /><br /> Click <a href="%3$s">here</a> to return the categories list page',
	'ACP_NO_SUBSCRIPTION'					=> 'No Subscriptions',
	'ACP_NO_SUBSCRIPTION_ID'				=> 'No Subscription ID found',

// Subscription Configuration
	'ACP_SUBSCRIPTION_CONFIG_TITLE'		=> 'Subscription Configuration',
	'ACP_SUBSCRIPTION_CONFIG_DESC'		=> 'Here you can set options for theSubscription Mod',
	'ACP_SUBSCRIPTION_CONF_SETTINGS'	=> 'Subscription Configuration Settings',
	'ACP_SUBSCRIPTION_CONF_SAVED'		=> 'Subscription configuration saved<br /><br /> Click <a href="%s">here</a> to go theSubscription\'s ACP Configuration',


// Subscription Cycles
	'ACP_SUBSCRIPTION_CYCLES_INDEX'		=> 'Subscription Cycles',
	'ACP_SUBSCRIPTION_CYCLES_TITLE'		=> 'Subscription Cycles',
	'acl_a_manage_subscription_cycles'	=>  array('lang' => 'Can use the subscription cycles management module', 'cat' => 'misc'),
	'SUBSCRIPTION_CYCLES_MOD_INSTALLED'	=> 'Subscription Cycles Management Module version %s installed',
	'SUBSCRIPTION_CYCLES_MOD_UPDATED'	=> 'Subscription Cycles Management Module updated to version %s',
	'SUBSCRIPTION_CYCLES_MOD_MANUAL'	=> 'You have an older version of Subscription Cycles Management Module installed.<br />You will need to uninstall that version first<br />Be sure to make backups first.',
	'ACP_NO_SUBSCRIPTION_CYCLE_ID'		=> 'No subscription cycle ID found',
	'ACP_SUBSCRIPTION_CYCLE_DELETE_GOOD'	=> 'The Subscription Cycle was removed successfully.<br /><br /> Click <a href="%s">here</a> to return to the previous page',
	'ACP_SUBSCRIPTION_CYCLE_EDIT_GOOD'	=> 'The Subscription Cycle was updated successfully.<br /><br /> Click <a href="%s">here</a> to return to the previous page',


));

?>