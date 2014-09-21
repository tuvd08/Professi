<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <main id="main">
 *
 * @package Marketify
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	
	<link rel="stylesheet" id="bootstrap-css" href="<?php echo get_template_directory_uri(); ?>/bootstrap/css/bootstrap.min.css" type="text/css" media="all">
  <link rel="stylesheet" id="bootstrap-theme-css" href="<?php echo get_template_directory_uri(); ?>/bootstrap/css/bootstrap-theme.min.css" type="text/css" media="all">
  <link rel="stylesheet" id="fonts-css" href="<?php echo get_template_directory_uri(); ?>/fonts/font.css" type="text/css" media="all">
  
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header" role="banner">
		<div class="container">
			<div class="top-bar clearfix">
				<div class="right-top-bar right">
					<ul class="none list-top clearfix">
						<li class="left"><i class="uiIcon16x16 uiIconTop man_top"></i><a class="actionIcon" href="<?php echo esc_url( home_url( '/' ) ); ?>wp-admin/profile.php">My Account</a></li>
						<li class="left"><i class="uiIcon16x16 uiIconTop heart_top"></i><a class="actionIcon" href="<?php echo esc_url( home_url( '/' ) ); ?>wish-lists/">Wishlist</a></li>
						<li class="left"><i class="uiIcon16x16 uiIconTop arrow_top"></i><a class="actionIcon" href="<?php echo esc_url( home_url( '/' ) ); ?>checkout/">Checkout</a></li>
					</ul>
				</div>
			</div>


			<table class="site-branding" style="margin:0px">
				<tr>
					<td class="left-bn" style="width:10%"></td>
					<td class="log_header" style="width:425px;min-width:350px;max-width:430px;">
					<?php $header_image = get_header_image(); ?>
					<?php if ( ! empty( $header_image ) ) : ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="custom-header"><img src="<?php echo esc_url( $header_image ); ?>" alt=""></a>
					<?php endif; ?>
						<div class="site-description-header"><?php bloginfo( 'description' ); ?></div>
					</td>
					<td class="search-forms" style="width:450px;min-width:350px;max-width:450px;">
					<?php locate_template( array( 'searchform-header.php' ), true ); ?>
					</td>
					<td class="buy-info" style="width:230px;min-width:230px; max-width:250px;">
						<i class="buy-icon"></i>
						<?php $cart_items = edd_get_cart_contents(); $total = ($cart_items && is_array($cart_items)) ? count($cart_items) : 0; ?>
						<span><?php echo $total; ?> item(s) - <?php edd_cart_total(); ?></span>
					</td>
					<td class="left-info"></td>
				</tr>
			</table>
			<h1 class="site-title" style="display:none"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'main-menu clearfix') ); ?>
			</nav>

		</div>
	</header><!-- #masthead -->

	
