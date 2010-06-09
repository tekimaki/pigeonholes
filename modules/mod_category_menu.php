<?php
/**
 * $Header$
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id$
 * @package categories
 * @subpackage modules
 */

global $gContent;
if( !empty( $gContent->mContentId ) ) {
	if( $gBitUser->hasPermission( 'p_pigeonholes_view' ) ) {
		$pigeonholes = new Pigeonholes();

		if( empty( $gStructure ) ) {
			$gStructure = new LibertyStructure();
		}
		if( $pigeons = $pigeonholes->getPigeonholesFromContentId( $gContent->mContentId ) ) {
			foreach( $pigeons as $pigeon ) {
				$modPigeonStructures[] = $gStructure->getSubTree( $pigeon['root_structure_id'], TRUE );
			}
			$gBitSmarty->assign( 'modPigeonStructures', !empty( $modPigeonStructures ) ? $modPigeonStructures : FALSE );
		}
	}
}
?>
