<?php
/**
 *
 */

global $post;
$viewWhishlist = false;
if(isset($GLOBALS['view']) && $GLOBALS['view'] === 'view' ) {
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
						$excerpt = $post->post_content;
						if(!$excerpt || strlen($excerpt) == 0) {
							$excerpt = $post->post_content;
						}
		?>
						<div class="entry-excerpt fontsforweb_fontid_9785"><?php echo esc_attr( wp_trim_words( $excerpt, 43, '...' ) ); ?></div>
		<?php } ?>
			
		<?php do_action( 'marketify_download_entry_meta_after_' . get_post_format() ); ?>
	</div>
</header><!-- .entry-header -->
<?php if($viewWhishlist === true) : ?>
<footer class="whishlist-footer left fontsforweb_fontid_9785">
	<div class="price"><?php echo edd_cart_item_price( $post->ID, $post->options );?></div>
	<div class="type">Digital Download</div>
	<div class="edit-licence">1 Licence | <a href="#">Edit</a></div>
	<a class="order" href="#">S U B M I T  O R D E R</a>
	<a class="remove" href="#"><i class="icon"></i>Remove</a>
</footer>
<?php endif;?>
