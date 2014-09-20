<?php
/**
 *
 */

global $post;
$viewWhishlist = false;
if(isset($GLOBALS['view']) && $GLOBALS['view'] === 'viewWhishlist' ) {
		$viewWhishlist = true;
} 
?>

<header class="entry-header<?php if($viewWhishlist === true){ echo ' viewWhishlist';} ?>">
	<h1 class="entry-title<?php if($viewWhishlist === true){ echo ' fontsforweb_fontid_9785';} ?>"><?php if($viewWhishlist === true){ echo '<span class="bookName">BOOK NAME</span> - ';} ?><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

	<?php if ( marketify_theme_mod( 'product-display', 'product-display-excerpt' ) ) : ?>

		<div class="entry-excerpt"><?php echo esc_attr( wp_trim_words( $post->post_content, 10 ) ); ?></div>

	<?php endif; ?>

	<div class="entry-meta">
		<?php do_action( 'marketify_download_entry_meta_before_' . get_post_format() ); ?>

		<?php if ( marketify_is_multi_vendor() ) : ?>
			<?php
				printf(
					__( '<span class="byline"> by <span class="user">%1$s</span></span>', 'marketify' ),
					sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s %4$s</a></span>',
						//marketify_edd_fes_author_url( get_the_author_meta( 'ID' ) ),
						str_replace( 'vendor', 'fes-vendor', marketify_edd_fes_author_url( get_the_author_meta( 'ID' ) ) ) ,
						esc_attr( sprintf( __( 'View all %s by %s', 'marketify' ), edd_get_label_plural(), get_the_author() ) ),
						esc_html( get_the_author_meta( 'display_name' ) ),
						get_avatar( get_the_author_meta( 'ID' ), 50, apply_filters( 'marketify_default_avatar', null ) )
					)
				);
			?>
		<?php endif;?>
		<?php if($viewWhishlist === true) {
						$excerpt = $post->post_excerpt;
						if(!$excerpt || strlen($excerpt) == 0) {
							$excerpt = $post->post_content;
						}
		?>
						<div class="entry-excerpt fontsforweb_fontid_9785"><?php echo esc_attr( wp_trim_words( $excerpt, 43, '...' ) ); ?></div>
		<?php } ?>
			
		<?php do_action( 'marketify_download_entry_meta_after_' . get_post_format() ); ?>
	</div>
</header><!-- .entry-header -->
<?php
//.edd-wl-open-modal
/*
<a href="#" class="edd-wl-button  edd-wl-action edd-wl-open-modal glyph-left no-text  edd-has-js" 
* data-action="edd_wl_open_modal" data-download-id="37" data-variable-price="no" data-price-mode="single">


*/
$list_id = (isset($GLOBALS['list_id']) ? $GLOBALS['list_id'] : "");
$inWL = ($list_id && strlen($list_id) > 0);
$key = (isset($GLOBALS['key']) ? $GLOBALS['key'] : "");
$rating = edd_reviews()->average_rating( false );
$full = intval($rating);
$ratingCount = edd_reviews()->count_reviews();

if($viewWhishlist === true) : ?>
<footer class="whishlist-footer left fontsforweb_fontid_9785">
	<div class="price"><?php echo edd_cart_item_price( $post->ID, $post->options );?></div>
	<?php if($inWL === true) {?>
		<div class="type">Digital Download</div>
		<div class="edit-licence">1 Licence | <a href="#">Edit</a></div>
		<a class="order" href="#">S U B M I T&nbsp;&nbsp;O R D E R</a>
		<a class="edd-remove-from-wish-list remove edd-has-js" href="#"
		data-action="edd-remove-from-wish-list"
		data-download-id="<?php echo $post->ID; ?>"
		data-list-id="<?php echo $list_id; ?>"
		data-cart-item="<?php echo $key; ?>"
		><i class="icon"></i>Remove</a>
	<?php } else {?>
		<div class="ratings"><?php echo $ratingCount; ?> ratings</div>
		<div class="star-ratings">
			<?php $j = 0; for($i = 0; $i < $full; ++ $i)  {?>
			<i class="star star-full"></i>
			<?php $j = $j + 1; }
				if($rating > $full) {
					echo '<i class="star star-half"></i>';
					$j = $j + 1;
				}
				for($i = $j; $i < 5; ++ $i)  {
			?>
				<i class="star star-no"></i>
			<?php } ?>
			<span><?php 
			if(strlen($rating) === 1) {
				$rating = $rating.'.0';
			}
			echo $rating; 
			?></span>
		</div>
		<div class="type">Digital Download</div>
		<div class="file-type"><span>PDF</span>(24,47 Mb)</div>
		<div class="add-wish-list"><a class="edd-add-to-cart-from-wish-list edd-wl-open-modal edd-has-js" href="#"
			data-action="edd_wl_open_modal"
			data-download-id="<?php echo $post->ID; ?>"
			data-variable-price="no"
			data-price-mode="single"
			><i class="add-wl"></i>W I S H&nbsp;&nbsp;L I S T</a></div>
	<?php } ?>
	
</footer>
<?php endif;?>
