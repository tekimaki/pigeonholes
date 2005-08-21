<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/Attic/assign_non_members.php,v 1.1 2005/08/21 16:22:44 squareing Exp $
 *
 * Copyright ( c ) 2004 bitweaver.org
 * Copyright ( c ) 2003 tikwiki.org
 * Copyright ( c ) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: assign_non_members.php,v 1.1 2005/08/21 16:22:44 squareing Exp $
 * @package pigeonholes
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

$gBitSystem->verifyPackage( 'pigeonholes' );
$gBitSystem->verifyPermission( 'bit_p_insert_pigeonhole_member' );

include_once( PIGEONHOLES_PKG_PATH.'lookup_pigeonholes_inc.php' );

$feedback = '';
$gBitSmarty->assign_by_ref( 'feedback', $feedback );

$contentTypes = array( '' => tra( 'All Content' ) );
foreach( $gLibertySystem->mContentTypes as $cType ) {
	$contentTypes[$cType['content_type_guid']] = $cType['content_description'];
}
$gBitSmarty->assign( 'contentTypes', $contentTypes );
$gBitSmarty->assign( 'contentSelect', $contentSelect = !isset( $_REQUEST['content_type'] ) ? NULL : $_REQUEST['content_type'] );

$listHash = array(
	'find' => empty( $_REQUEST['find_objects'] ) ? NULL : $_REQUEST['find_objects'],
	'sort_mode' => empty( $_REQUEST['sort_mode'] ) ? NULL : $_REQUEST['sort_mode'],
	'max_rows' => ( !empty( $_REQUEST['max_rows'] ) && is_numeric( $_REQUEST['max_rows'] ) ) ? $_REQUEST['max_rows'] : 100,
);

$nonMembers = $gPigeonholes->getNonPigeonholeMembers( $listHash, $contentSelect, ( !empty( $_REQUEST['include'] ) && $_REQUEST['include'] == 'members' ) ? $_REQUEST['include'] : FALSE );

if( !empty( $_REQUEST['insert_content'] ) && isset( $_REQUEST['pigeonhole'] ) ) {
	// make an array that can be stored
	foreach( $nonMembers as $item ) {
		if( !empty( $_REQUEST['pigeonhole'][$item['content_id']] ) ) {
			foreach( $_REQUEST['pigeonhole'][$item['content_id']] as $parent_id ) {
				$memberHash[$parent_id][] = array(
					'parent_id' => $parent_id,
					'content_id' => $item['content_id'],
				);
			}
		}

		if( !empty( $_REQUEST['include'] ) && $_REQUEST['include'] == 'members' ) {
			if( !empty( $item['content_id'] ) && !$gPigeonholes->expungePigeonholeMember( NULL, $item['content_id'] ) ) {
				$feedback['error'] = 'The content could not be deleted before insertion.';
			}
		}
	}

	if( empty( $feedback['error'] ) ) {
		foreach( $memberHash as $memberStore ) {
			if( $gPigeonholes->insertPigeonholeMember( $memberStore ) ) {
				$feedback['success'] = 'The content was successfully inserted into the respective categories.';
			} else {
				$feedback['error'] = 'The content could not be inserted into the categories.';
			}
		}
	}

	// we need to reload the nonMembers, since settings have changed
	$nonMembers = $gPigeonholes->getNonPigeonholeMembers( $listHash, $contentSelect, ( !empty( $_REQUEST['include'] ) && $_REQUEST['include'] == 'members' ) ? $_REQUEST['include'] : FALSE );
}

$pigeonList = $gPigeonholes->getList( NULL, FALSE, TRUE );
$gBitSmarty->assign( 'pigeonList', $pigeonList['data'] );
$gBitSmarty->assign( 'nonMembers', $nonMembers );
$gBitSmarty->assign( 'contentCount', count( $nonMembers ) );

// Display the template
$gBitSystem->display( 'bitpackage:pigeonholes/assign_non_members.tpl', tra( 'Assign Content to Categories' ) );
?>
