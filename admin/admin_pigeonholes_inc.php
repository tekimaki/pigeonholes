<?php
// $Header: /cvsroot/bitweaver/_bit_pigeonholes/admin/admin_pigeonholes_inc.php,v 1.15 2007/03/04 14:03:40 nickpalmer Exp $

$pigeonholeDisplaySettings = array(
	'pigeonholes_display_path' => array(
		'label' => 'Display Path',
		'note' => 'Display category paths above the page leading to the object.',
	),
	'pigeonholes_display_members' => array(
		'label' => 'Display Members',
		'note' => 'Show the other members of the same categories at the bottom of the page.',
	),
	'pigeonholes_display_description' => array(
		'label' => 'Display Description',
		'note' => 'When showing the category members, you can display the category description as well.',
	),
	'pigeonholes_display_subtree' => array(
		'label' => 'Display Subtree',
		'note' => 'When viewing the kategory list, you can display the subcategories as well.',
	),
	'pigeonholes_themes' => array(
		'label' => 'Theme selection',
		'note' => 'Allow the selection of a different theme to use for a category.',
	),
	'pigeonholes_permissions' => array(
		'label' => 'Permission gating',
		'note' => 'Limit category access to users with a given permission. Permission settings are inhertied by child categories.',
	),
	'pigeonholes_groups' => array(
		'label' => 'Group gating',
		'note' => 'Limit category access to specific groups. Group settings are inhertied by child categories.',
	),
	'reverse_assign_table' => array(
		'label' => 'Assign Categories in Rows',
		'note' => 'The assign categories page will have categories in rows instead of columns and content in columns instead of rows. This is better if you have lots of categories that do not fit on a single page easily.',
	),
);
$gBitSmarty->assign( 'pigeonholeDisplaySettings', $pigeonholeDisplaySettings );

$pigeonholeEditSettings = array(
	'pigeonholes_use_jstab' => array(
		'label' => 'Use separate Tab',
		'note' => 'When editing content use a separate tab to categorise.',
	),
);
$gBitSmarty->assign( 'pigeonholeEditSettings', $pigeonholeEditSettings );

$listStyles = array(
	'dynamic' => tra( 'Dynamic list' ),
	'table' => tra( 'Table based list' ),
);
$gBitSmarty->assign( 'listStyles', $listStyles );

$memberLimit = array(
	'0' => tra( 'None' ),
	'10' => 10,
	'20' => 20,
	'30' => 30,
	'50' => 50,
	'100' => 100,
	'9999' => tra( 'Unlimited' ),
);
$gBitSmarty->assign( 'memberLimit', $memberLimit );

if( !empty( $_REQUEST['pigeonhole_settings'] ) ) {
	$pigeonholeSettings = array_merge( $pigeonholeDisplaySettings, $pigeonholeEditSettings );
	foreach( array_keys( $pigeonholeSettings ) as $item ) {
		simple_set_toggle( $item, PIGEONHOLES_PKG_NAME );
	}

	simple_set_value( 'pigeonholes_limit_member_number', PIGEONHOLES_PKG_NAME );
	simple_set_value( 'pigeonholes_list_style', PIGEONHOLES_PKG_NAME );
	simple_set_value( 'pigeonholes_scrolling_list_number', PIGEONHOLES_PKG_NAME );
}
?>
