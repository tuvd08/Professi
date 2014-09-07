<?php
/**
 * Template Name: Homepage
 *
 * @package Marketify
 */

$pageid = basename(get_permalink());
$isHome = (strcmp($pageid, "") == 0 || strcmp($pageid, "Professi") == 0);
$GLOBALS['is_home'] = $isHome;
get_header(); ?>

	<div class="container clear">
		<div class="home-container clearfix">
			<div class="left-container sidebar left">
				<?php dynamic_sidebar( 'sidebar-download-single' ); ?>
			</div>

			<div id="content" class="right-container site-content row left">
				<?php if($isHome == true) {?>
					<div class="download-product-review-details">
						<div class="home-review-content">
							<?php if ( is_active_sidebar( 'preview-1' ) ) : ?>
								<?php dynamic_sidebar( 'preview-1' ); ?>
							<?php endif; ?>
						</div>
					</div>
				<?php }?>
				<div class="download-product-review-details content-items">
				<?php if ( ! is_paged() && ! get_query_var( 'orderby' ) && ! is_page_template( 'page-templates/popular.php' ) ) : ?>
					<?php get_template_part( 'content-grid-download', 'popular' ); ?>
				<?php endif; ?>

					<section id="primary" class="content-area col-md-<?php echo is_active_sidebar( 'sidebar-download' ) ? '9' : '12'; ?> col-sm-7 col-xs-12">
						<main id="main" class="site-main" role="main">

						<div class="the-title-home"><?php marketify_downloads_section_title();?></div>

						<?php echo do_shortcode( sprintf( '[downloads number="%s"]', get_option( 'posts_per_page' ) ) ); ?>

						</main><!-- #main -->
					</section><!-- #primary -->
					<?php get_sidebar( 'archive-download' ); ?>
				</div>
			</div><!-- #content -->
		</div>
	</div>
<?php get_footer(); ?>
