<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

get_header(); 

global $wp_query;
?>

	<div class="container">
		<div class="home-container clearfix">
			<div class="left-container sidebar left">
				<?php dynamic_sidebar( 'sidebar-download-single' ); ?>
			</div>
			<div id="content" class="right-container site-content row left">
				<div class="download-product-review-details content-items clearfix">
					<section id="primary" class="content-area col-md-<?php echo is_active_sidebar( 'sidebar-download' ) ? '9' : '12'; ?> col-sm-7 col-xs-12">
						<main id="main" class="site-main" role="main">
		
						<div class="the-title-home"><?php marketify_downloads_section_title(); ?></div>
		
						<?php if ( have_posts() ) : ?>
		
							<div class="download-grid-wrapper columns-<?php echo marketify_theme_mod( 'product-display', 'product-display-columns' ); ?> row clearfix" data-columns>
								<?php while ( have_posts() ) : the_post(); ?>
									<?php get_template_part( 'content-grid', 'download' ); ?>
								<?php endwhile; ?>
							</div>
		
							<?php marketify_content_nav( 'nav-below' ); ?>
		
						<?php else : ?>
		
							<?php get_template_part( 'no-results', 'download' ); ?>
		
						<?php endif; ?>
		
						</main><!-- #main -->
					</section><!-- #primary -->
				<?php get_sidebar( 'archive-download' ); ?>
				</div>
			</div><!-- #content -->

		</div><!--home-container-->
	</div>
<?php get_footer(); ?>
