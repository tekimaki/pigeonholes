<?php
/**
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.6 $
 * @package  Pigeonholes
 * @subpackage functions
 */
global $gBitSystem, $gBitUser;
$gBitSystem->registerPackage( 'pigeonholes', dirname( __FILE__).'/', TRUE, LIBERTY_SERVICE_CATEGORIZATION );

define( 'PIGEONHOLES_CONTENT_TYPE_GUID', 'pigeonholes' );

if( $gBitSystem->isPackageActive( 'pigeonholes' ) ) {
	$gLibertySystem->registerService( LIBERTY_SERVICE_CATEGORIZATION, PIGEONHOLES_PKG_NAME, array(
		'content_display_function' => 'display_pigeonholes',
		'content_preview_function' => 'pigeonholes_preview_content',
		'content_edit_function' => 'pigeonholes_input_content',
		'content_store_function' => 'pigeonholes_store_member',
		'content_expunge_function' => 'pigeonholes_expunge_member',
		'content_edit_tab_tpl' => 'bitpackage:pigeonholes/pigeonholes_input_inc.tpl',
		'content_view_tpl' => 'bitpackage:pigeonholes/display_members.tpl',
		'content_nav_tpl' => 'bitpackage:pigeonholes/display_paths.tpl',
	) );

	// include service functions
	require_once( PIGEONHOLES_PKG_PATH.'servicefunctions_inc.php' );

	if( $gBitUser->hasPermission( 'bit_p_view_pigeonholes' ) ) {
		$gBitSystem->registerAppMenu( 'pigeonholes', 'Categories', PIGEONHOLES_PKG_URL.'index.php', 'bitpackage:pigeonholes/menu_pigeonholes.tpl', 'Pigeonholes' );
	}
}
?>
