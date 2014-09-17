<?php
/**
 * The template for displaying search forms in Marketify
 *
 * @package Marketify
 */
?>

<form role="search" method="get" id="quick-search-form" name="quick-search-form" class="search-form-active" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<button type="submit" class="search-submit hide"><i class="icon-search"></i></button>
	<label>
		<span class="screen-reader-text"><?php _ex( 'Search for:', 'label', 'marketify' ); ?></span>
		<input type="search" id="input-search-field" class="form-control icon-search search-field-input" placeholder="<?php echo esc_attr__( 'Type and hit enter', 'marketify' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php echo esc_attr__( 'Search for:', 'marketify' ); ?>">
	</label>
	<input type="hidden" name="post_type" value="download" />
	<input type="hidden" name="absc_search_cat" id="absc_search_cat" value="" />
</form>
