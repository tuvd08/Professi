<?php
/**
 *
 */

global $post;

/*
$view_page = edd_get_option( 'edd_wl_page_view' );
$edit_page = edd_get_option( 'edd_wl_page_edit' );
if ( is_page( $view_page ) ) {
	$list_id = get_query_var( 'view' );
} elseif ( is_page( $edit_page ) ) {
	$list_id = get_query_var( 'edit' );
}
* 
*/
$viewWhishlist = false;
if(isset($GLOBALS['view']) && $GLOBALS['view'] === 'viewWhishlist' ) {
		$viewWhishlist = true;
} 
if($viewWhishlist === false) {
?>
<div class="entry-image">
	<div class="overlay">
		<?php do_action( 'marketify_download_content_image_overlay_before' ); ?>

		<div class="actions">
			<?php marketify_purchase_link( get_the_ID() ); ?>
			<a href="<?php the_permalink(); ?>" rel="bookmark" class="button"><?php _e( 'Details', 'marketify' ); ?></a>

			<strong class="item-price"><span><?php printf( __( 'Item Price: %s', 'marketify' ), edd_price( get_the_ID(), false ) ); ?></span></strong>

			<?php do_action( 'marketify_download_content_image_overlay_after' ); ?>
		</div>
	</div>

	<?php if ( class_exists( 'MultiPostThumbnails' ) && MultiPostThumbnails::get_the_post_thumbnail( 'download', 'grid-image' ) ) : ?>
		<?php MultiPostThumbnails::the_post_thumbnail( 'download', 'grid-image', null, 'content-grid-download' ); ?>
	<?php elseif ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail( 'content-grid-download' ); ?>
	<?php else : ?>
		<span class="image-placeholder"></span>
	<?php endif; ?>
</div>

<?php locate_template( array( 'modal-download-purchase.php' ), true, false ); ?>


<?php } else { ?>
<div class="entry-image">
	<?php if ( class_exists( 'MultiPostThumbnails' ) && MultiPostThumbnails::get_the_post_thumbnail( 'download', 'grid-image' ) ) : ?>
	<?php MultiPostThumbnails::the_post_thumbnail( 'download', 'grid-image', null, 'content-grid-download' ); ?>
	<?php elseif ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail( 'content-grid-download' ); ?>
	<?php else : ?>
		<span class="image-placeholder"></span>
	<?php endif; ?>
</div>		
<?php }?>
