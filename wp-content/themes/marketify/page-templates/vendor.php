<?php
/**
 * Template Name: Vendor
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

$author = get_query_var( 'vendor' );
$author = get_user_by( 'slug', $author );

if ( ! $author ) {
	$author = get_current_user_id();
}

get_header(); ?>


	<div class="container vendor clearfix">
	<?php while ( have_posts() ) : the_post(); ?>
		<div class="home-container clearfix">
			<div class="left-container sidebar left">
				<?php dynamic_sidebar( 'sidebar-download-single' ); ?>
			</div>

			<div id="content" class="right-container site-content row left">
				<div class="title-top-container header clearfix">
					<div class="title-top page-title fontsforweb_fontid_9785 left">O U R&nbsp; T E A C H E R&nbsp;  A U T O R S</div>
					<div class="title-right right"><a href="#">See all <i class="glyphicon glyphicon-play"></i></a></div>
				</div>
				<div class="download-product-review-details content-items clearfix">

					<section id="primary" class="content-area col-md-12 col-sm-7 col-xs-12">
						<main id="main" class="site-main" role="main">

							<div class="the-title-home">OUR TEACHER AUTORS</div>
							<div class="teacher-autors clearfix">
								<?php echo do_shortcode( sprintf( '[downloads number="%s"]', get_option( 'posts_per_page' ) ) ); ?>
							</div>

						</main><!-- #main -->
					</section><!-- #primary -->

				</div>
				<div class="title-top-container header clearfix">
					<div class="title-top page-title fontsforweb_fontid_9785">F E A T U R E D&nbsp;  T E A C H E R&nbsp;  A U T H O R</div>
				</div>
				<div class="teacher-author">
					<hr/>
					
					<hr/>
				</div>
				<div class="title-top-container header clearfix">
					<div class="title-top page-title fontsforweb_fontid_9785 left">C R E A T E D&nbsp;  B Y&nbsp;  T E A C H E R S</div>
					<div class="title-right right"><a href="#">See all <i class="glyphicon glyphicon-play"></i></a></div>
				</div>
				<div class="download-product-review-details content-items clearfix">

					<section id="primary" class="content-area col-md-12 col-sm-7 col-xs-12">
						<main id="main" class="site-main" role="main">

							<div class="the-title-home">FEATURED LESSONS</div>
							<div class="clearfix">
								<?php echo do_shortcode( sprintf( '[downloads number="%s"]', get_option( 'posts_per_page' ) ) ); ?>
							</div>

						</main><!-- #main -->
					</section><!-- #primary -->

				</div>
				
				
				
				
				
			</div><!-- #content -->
		</div>
	<?php endwhile; ?>
	</div>
<?php get_footer(); ?>
