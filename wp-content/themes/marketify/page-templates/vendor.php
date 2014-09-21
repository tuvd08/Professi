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

$avatar = get_avatar( $author, 150, apply_filters( 'marketify_default_avatar', null ) );
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
					<div class="teacher-container clearfix">
						<div class="teacher-info left">
							<div class="content-items clearfix">
								<div class="teacher-autors image-info left">
									<div class="content-grid-download">
										<div class="entry-image">
											<?php echo $avatar;?>
										</div>
									</div>
								</div>
								<div class="left fontsforweb_MyriadPro">
									<div class="teacher-name fontsforweb_fontid_9785">TheLocoTeacher</div>
									<div class="teacher-local gray-light">Athens, GA </div>
									<div class="teacher-ratings gray-light">Overall User Rating: <span>4.0 /4.0</span></div>
									<div class="teacher-store gray-light">Products in my store: <span>47</span></div>
									<div class="teacher-follow gray-light">
										<img width="14px" src="<?php echo content_url();?>/themes/marketify/images/star12x11.png"/>
										<strong>Follow me </strong><span>(675 Followers)</span></div>
								</div>
							</div>
							<div class="form-horizontal">
								<div class="control-group">
									<span class="control-label">GRADES </span>
									<span class="controls gray-light">PreK, Kindergarten, 1st, 2nd, 3rd, 4th, 5th</span>
								</div>
								<div class="control-group">
									<span class="control-label">SUBJECTS </span>
									<span class="controls gray-light">English Language Arts, Math, Science, For All Subject Areas</span>
								</div>
								<div class="control-quote">
									<span class="gray-light p-quote"><i class="quote start-qt"></i> Click here to visit my teaching blog {thelocoteacher}<i class="quote end-qt"></i></span>
								</div>
							</div>
							
						</div>
						<div class="teacher-dl content-items left">
							
<?php 
wp_reset_query(); 
$download_ = new WP_Query( array(
	'post_type'   => 'download',
	'post_status' => 'publish',
	'post_author' => $author,
	'posts_per_page' => 2
) );
$products = $download_->found_posts;

?>
					<?php while ( $download_->have_posts() ) : $download_->the_post(); ?>
							<div itemscope="" itemtype="http://schema.org/Product" class="edd_download content-grid-download"
									id="edd_download_<?php echo $post->ID; ?>" style="">
								<div class="edd_download_inner">
									<div class="entry-image">
										<?php if ( class_exists( 'MultiPostThumbnails' ) && MultiPostThumbnails::get_the_post_thumbnail( 'download', 'grid-image' ) ) : ?>
										<?php MultiPostThumbnails::the_post_thumbnail( 'download', 'grid-image', null, 'content-grid-download' ); ?>
										<?php elseif ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'content-grid-download' ); ?>
										<?php else : ?>
											<span class="image-placeholder"></span>
										<?php endif; ?>
									</div>
									<header class="entry-header">
										<div class="entry-title">
											<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
										</div>
									</header>
								</div>
							</div>
							<?php endwhile; ?>
<?php
	wp_reset_query(); 
?>
							
						</div>
						<div class="title-right left" style="padding-left: 50px;"><a href="#">See all my <?php echo $products;?> products <i class="glyphicon glyphicon-play"></i></a></div>
						<div class="title-right right" style="padding-right: 50px;"><a href="#">See all my <?php echo $products;?> products <i class="glyphicon glyphicon-play"></i></a></div>
					</div>
					
					
					
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
