<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/Attic/servicefunctions_inc.php,v 1.4 2005/10/18 11:31:07 squareing Exp $
 *
 * Copyright ( c ) 2004 bitweaver.org
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * @package pigeonholes
 * @subpackage functions
 */

/**
 * Pigeonhole display service
 */
function display_pigeonholes( &$pObject ) {
	global $gBitSmarty, $gBitUser, $gPreviewStyle;
	require_once( PIGEONHOLES_PKG_PATH.'Pigeonholes.php' );
	$pigeonholes = new Pigeonholes( NULL, NULL, FALSE );

	$settings = $pigeonholes->getPigeonholeSettings( NULL, $pObject->mContentId );
	if( !empty( $settings['style'] ) ) {
		$gPreviewStyle = $settings["style"];
	}

	if( $gBitUser->hasPermission( 'bit_p_view_pigeonholes' ) ) {
		if( $pigeons = $pigeonholes->getPigeonholesFromContentId( $pObject->mContentId ) ) {
			foreach( $pigeons as $pigeon ) {
				$pigeonholes->mContentId = $pigeon['content_id'];
				$pigeonholes->load( TRUE );
				$pigeonData[] = $pigeonholes->mInfo;
			}
			$gBitSmarty->assign( 'pigeonData', !empty( $pigeonData ) ? $pigeonData : FALSE );
		}
	}
}

/**
 * Pigeonhole edit template service
 */
function pigeonholes_input_content( $pObject=NULL ) {
	global $gBitSmarty, $gBitUser;
	$pigeonPathList = array();

	if( $gBitUser->hasPermission( 'bit_p_insert_pigeonhole_member' ) ) {
		require_once( PIGEONHOLES_PKG_PATH.'Pigeonholes.php' );
		$pigeonholes = new Pigeonholes( NULL, NULL, FALSE );

		// get pigeonholes path list
		if( $pigeonPathList = $pigeonholes->getPigeonholesPathList( !empty( $pObject->mContentId ) ? $pObject->mContentId : NULL ) ) {
			$gBitSmarty->assign( 'pigeonPathList', $pigeonPathList );
		}
	}
}

/**
 * Pigeonhole edit template service
 */
function pigeonholes_expunge_member( $pObject=NULL ) {
	require_once( PIGEONHOLES_PKG_PATH.'Pigeonholes.php' );
	$pigeonholes = new Pigeonholes( NULL, NULL, FALSE );
	$pigeonholes->expungePigeonholeMember( NULL, $pObject->mContentId );
}

/**
 * Pigeonhole preview service
 * when we hit preview, we make the selections persistent
 */
function pigeonholes_preview_content() {
	global $gBitSmarty, $gBitUser;
	$pigeonPathList = array();

	if( $gBitUser->hasPermission( 'bit_p_insert_pigeonhole_member' ) ) {
		require_once( PIGEONHOLES_PKG_PATH.'Pigeonholes.php' );
		$pigeonholes = new Pigeonholes( NULL, NULL, FALSE );

		// get pigeonholes path list
		if( $pigeonPathList = $pigeonholes->getPigeonholesPathList() ) {
			foreach( $pigeonPathList as $key => $path ) {
				if( !empty( $_REQUEST['pigeonholes']['pigeonhole'] ) && in_array( $key, $_REQUEST['pigeonholes']['pigeonhole'] ) ) {
					$pigeonPathList[$key][0]['selected'] = TRUE;
				} else {
					$pigeonPathList[$key][0]['selected'] = FALSE;
				}
			}
			$gBitSmarty->assign( 'pigeonPathList', $pigeonPathList );
		}
	}
}

/**
 * Pigeonhole store service
 * store the content in any pigeonhole it wants
 */
function pigeonholes_store_member( $pObject, $pParamHash ) {
	global $gBitSmarty, $gBitUser;
	if( $gBitUser->hasPermission( 'bit_p_insert_pigeonhole_member' ) ) {
		require_once( PIGEONHOLES_PKG_PATH.'Pigeonholes.php' );

		if( !empty( $pParamHash['content_id'] ) ) {
			if( is_object( $pObject ) && empty( $pParamHash['content_id'] ) ) {
				$pParamHash['content_id'] = $pObject->mContentId;
			}

			$pigeonholes = new Pigeonholes( NULL, NULL, FALSE );
			$pigeonPathList = $pigeonholes->getPigeonholesPathList( $pParamHash['content_id'] );

			// here we need to work out if we need to save at all
			// get all originally selected items
			$selectedItem = array();
			if( !empty( $pigeonPathList ) ) {
				foreach( $pigeonPathList as $path ) {
					if( !empty( $path[0]['selected'] ) ) {
						$pathItem = array_pop( $path );
						$selectedItem[] = $pathItem['content_id'];
					}
				}
			}

			// quick and dirty check to start off with
			if( empty( $_REQUEST['pigeonholes'] ) || count( $_REQUEST['pigeonholes']['pigeonhole'] ) != count( $selectedItem ) ) {
				$modified = TRUE;
			} else {
				// more thorough check
				foreach( $selectedItem as $item ) {
					if( !in_array( $item, $_REQUEST['pigeonholes']['pigeonhole'] ) ) {
						$modified = TRUE;
					}
				}
			}

			if( !empty( $modified ) ) {
				// first remove all entries with this content_id
				if( $pigeonholes->expungePigeonholeMember( NULL, $pParamHash['content_id'] ) && !empty( $_REQUEST['pigeonholes'] ) ) {
					// insert the content into the desired pigeonholes
					foreach( $_REQUEST['pigeonholes']['pigeonhole'] as $p_id ) {
						$memberHash[] = array(
							'parent_id' => $p_id,
							'content_id' => $pParamHash['content_id']
						);
					}

					if( !$pigeonholes->insertPigeonholeMember( $memberHash ) ) {
						$gBitSmarty->assign( 'msg', tra( "There was a problem inserting the content into the pigeonholes." ) );
						$gBitSystem->display( 'error.tpl' );
						die;
					}
				}
			}
		}
	}
}
?>
