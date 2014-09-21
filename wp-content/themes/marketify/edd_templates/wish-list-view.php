<?php
/**
 * Wish List template
 *
 *
 * @since 1.0
*/

$list_id = get_query_var( 'view' );
// gets the list
$downloads = edd_wl_get_wish_list( $list_id );

if ( ! is_array( $downloads ) ) {
	return;
}
$GLOBALS['list_id'] = $list_id;
$keys = array();
foreach ( $downloads as $key => $item ) {
	$keys[] = $key;
}
$i = 0;
$viewWhishlist = false;
if(isset($GLOBALS['view']) && $GLOBALS['view'] === 'viewWhishlist' ) {
		$viewWhishlist = true;
}

$downloads = wp_list_pluck( $downloads, 'id' );

$downloads = new WP_Query( array(
	'post_type'   => 'download',
	'post_status' => 'publish',
	'post__in'    => $downloads,
	'posts_per_page' => 10
) );
// get list post object
$list = get_post( $list_id );
// title
remove_filter( 'the_title', 'edd_wl_the_title', 10, 2 );
//status
$privacy = get_post_status( $list_id );
//page-title fontsforweb_fontid_9785
?>
<?php if ( $downloads->have_posts() ) : ?>

<?php
if($viewWhishlist === false) {
		echo '<p class="title">'. $list->post_content . '</p>';
		/**
		 * All all items in list to cart
		*/
		echo '<p>' . edd_wl_add_all_to_cart_link( array( 'list_id' => $list_id ) ) . '</p>';
	?>


	<div class="row">
		<?php while ( $downloads->have_posts() ) : $downloads->the_post(); ?>
			<div class="col-lg-4 col-md-6 col-sm-12">
				<?php get_template_part( 'content-grid', 'download' ); ?>
			</div>
		<?php endwhile; ?>
	</div>

	<?php
} else {
?>
	<div class="header">
		<div class="page-title fontsforweb_fontid_9785"><?php echo $list->post_content; ?></div>
	</div>
	<div class="view-whishlist" >
		<hr/>
			<div class="view-icon-list fontsforweb_fontid_9785"><span>VIEW: </span><a href="#" class="view box"></a><a class="view list" href="#"></a></div>
		<hr/>
	</div>
	<br/>
	<div class="dlcontainer">
		<?php while ( $downloads->have_posts() ) : $downloads->the_post(); ?>
			<?php $GLOBALS['key'] = $keys[$i]; $i = $i + 1; ?>
			<div class="">
				<?php get_template_part( 'content-grid', 'download' ); ?>
			</div>
		<?php endwhile; ?>
	</div>
	<div class="view-whishlist">
		<hr/><br/>
		<?php 
			$current_page = max( 1, get_query_var('paged') );
			$max_current = ($current_page - 1) * 10  + $downloads->post_count;
			$max_pages = $downloads->max_num_pages;
		 ?>
		<div class="clearfix">
			<div class="left">
				
				<span>Showing </span><span><?php echo ($downloads->current_post + 2); ?>-<?php echo $max_current; ?> of <?php echo $downloads->found_posts; ?></span>
				<?php
				if($current_page > 1) {
					echo "<span>&nbsp;Back</span>";	
				}
				if($current_page < $max_pages) {
					echo "<span>&nbsp;Next</span>";	
				}
				?>
			</div>
			<div class="right"><?php echo $current_page; ?></div>
		</div>
		</div><br/>
	</div>
<?php	
}
	/**
	 * Sharing - only shown for public lists
	*/
	if ( 'private' !== get_post_status( $list_id ) ) : ?>
		<div class="edd-wl-sharing">
			<h3>
				<?php _e( 'Share', 'edd-wish-lists' ); ?>
			</h3>
			<p>
				<?php
				/**
				 * Shortlink to share
				 */
				echo wp_get_shortlink( $list_id );
				?>
			</p>
			<p>
				<?php
				/**
				 * Share via email
				 */
				echo edd_wl_share_via_email_link();
				?>
			</p>
			<?php
				/**
				 * Social sharing services
				 */
				echo edd_wl_sharing_services();
			?>
		</div>
	<?php endif; ?>

<?php endif; ?>

<?php
/**
 * Edit list
*/
if ( edd_wl_is_users_list( $list_id ) ) : ?>

	<p><a href="<?php echo edd_wl_get_wish_list_edit_uri( $list_id ); ?>"><?php printf( __( 'Edit %s', 'edd-wish-lists' ), edd_wl_get_label_singular( true ) ); ?></a></p>

<?php endif; wp_reset_query(); ?>
