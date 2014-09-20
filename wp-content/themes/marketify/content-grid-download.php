<?php
/**
 * @package Marketify
 */
 
$clazz = 'content-grid-download';
if(isset($GLOBALS['view']) && $GLOBALS['view'] === 'viewWhishlist' ) {
		$clazz = 'view-whishlist clearfix';
} 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $clazz ); ?>>
	<?php edd_get_template_part( 'shortcode', 'content-image' ); ?>

	<?php edd_get_template_part( 'shortcode', 'content-title' ); ?>
</article><!-- #post-## -->
