<?php
/**
 * Template Name: View wishlist
 *
 * @package Marketify
 */

get_header(); 
	
$pageid = basename(get_permalink());
$GLOBALS['view'] = $pageid;
if($pageid === 'view') {
	$GLOBALS['view'] = 'viewWhishlist';
}
//
?>

<div class="container">
	<div class="wishlist">
		<div id="content" class="site-content row content-items">

			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">

				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="entry-content">
								<?php the_content(); ?>
							</div><!-- .entry-content -->
						</article><!-- #post-## -->

					<?php endwhile; ?>

					<?php marketify_content_nav( 'nav-below' ); ?>

				<?php else : ?>

					<?php get_template_part( 'no-results', 'index' ); ?>

				<?php endif; ?>

				</main><!-- #main -->
			</div><!-- #primary -->

		</div>
	</div>
</div>
<script type='text/javascript'>
	var edd_wl_scripts = {
		"wish_list_page":"<?php echo edd_wl_get_wish_list_uri();?>",
		"wish_list_add":"<?php echo edd_wl_get_wish_list_create_uri();?>",
		"ajax_nonce":"<?php echo wp_create_nonce( 'edd_wl_ajax_nonce' );?>"
	};
</script>
<?php get_footer(); ?>
