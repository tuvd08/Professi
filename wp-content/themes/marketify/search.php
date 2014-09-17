<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Marketify
 */

$post_type = get_query_var( 'post_type' );
$GLOBALS['is_search'] = true;
if (in_array('download', $post_type))
	locate_template( array( 'search-download.php' ), true );
else
	locate_template( array( 'index.php' ), true );
