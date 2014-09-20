<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Marketify
 */
?>
	<footer id="colophon" class="site-footer <?php echo marketify_theme_mod( 'footer', 'footer-style' ); ?>" role="contentinfo">
		<div class="container footer">
			<ul class="wishlist fontsforweb_fontid_9785 clearfix">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Terms of Service</a></li>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Privacy Policy</a></li>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Copyright Policy</a></li>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Copyright FAQ</a></li>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">About Us</a></li>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Contact Us</a></li>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">FAQs & HELP</a></li>
			</ul>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->
<?php wp_footer(); ?>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/base.js"></script>
</body>
</html>
