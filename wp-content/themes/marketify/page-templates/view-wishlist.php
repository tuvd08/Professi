<?php
/**
 * Template Name: View wishlist
 *
 * @package Marketify
 */

get_header(); 
	
$pageid = basename(get_permalink());
$GLOBALS['view'] = $pageid;
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
<?php get_footer(); ?>
