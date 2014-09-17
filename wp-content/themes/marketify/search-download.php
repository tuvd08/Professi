<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

$Cats= (isset($GLOBALS['cat_search'])) ? $GLOBALS['cat_search'] : '';

$cat_ = get_query_var('category_name');

// $cat = get_category_by_slug( $slug );
//$cat_p = get_term( $cat -> category_parent, 'category' );
//
get_header(); ?>

	<div class="container clear">
		<div class="home-container clearfix">
			<div class="left-container sidebar left">
				<aside id="selected-categories" class="widget download-single-widget widget_edd_categories_tags_widget">
					<h1 class="download-single-widget-title"></h1>
					<ul class="edd-taxonomy-widget">
						<li class="cat-item cat-item-15">
							<a style="width: 226px;">YOU SELECTED</a>
							<ul class="children">
								<li class="cat-item cat-item-20">
										<a>1 – 2</a>
										<?php echo $cat_; ?>
								</li>
								<li class="cat-item cat-item-21">
										<a>3 – 5</a>
								</li>
							</ul>
						</li>
					</ul>
				</aside>
				
				<?php dynamic_sidebar( 'sidebar-download-single' ); ?>
				<?php
					
				?>
			</div>

			<div id="content" class="right-container site-content row left">
				<div class="download-product-review-details content-items clearfix">

					<section id="primary" class="content-area col-md-<?php echo is_active_sidebar( 'sidebar-download' ) ? '9' : '12'; ?> col-sm-7 col-xs-12">
						<main id="main" class="site-main" role="main">

						<div class="the-title-home"><?php marketify_downloads_section_title();?></div>
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
		</div>
	</div>
<script type="text/javascript">
	window.searchResult = true;
	window.lastSearchCats = '<?php echo $Cats; ?>';
</script>
<?php get_footer(); ?>
