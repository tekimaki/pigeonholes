<?php
/**
 * Provide a list of pigenoholes
 *
 * @package pigeonholes
 * @subpackage functions
 * @version $Header$
 *
 * Copyright ( c ) 2005 bitweaver.org
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 */

/**
 * Required Files
 */
$gContent = new Pigeonholes( ( !empty( $_REQUEST['structure_id'] ) ? $_REQUEST['structure_id'] : NULL ), ( !empty( $_REQUEST['content_id'] ) ? $_REQUEST['content_id'] : NULL ) );
$gContent->load( TRUE );
$gBitSmarty->assign_by_ref( 'gContent', $gContent );
?>
