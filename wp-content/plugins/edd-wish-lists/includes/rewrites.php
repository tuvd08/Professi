<?php
/**
 * Rewrites
 *
 * @since 1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * When the EDD settings -> extensions tab has been saved, we create/reflush the rewrite rules
 * @todo flush rewrite rules when the page select menu options have been changed/updated.
 *
 * @since 1.0
*/
function edd_wl_plugin_settings_save() {
    global $pagenow, $typenow;

    // check that the extensions tab has been updated
    if ( 
        ( 'download' == $typenow && 'edit.php' == $pagenow ) 
        && ( isset( $_GET['page'] ) && $_GET['page'] == 'edd-settings' ) 
        && ( isset( $_GET['tab'] ) && $_GET['tab'] == 'extensions' ) 
        && ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ) 
    ) {
        edd_wl_rewrite_rules();
    }

}
add_action( 'admin_init', 'edd_wl_plugin_settings_save' );

/**
 * Run edd_wl_rewrite_rules() function when permalinks have been updated
 * This is so view/edit pages can safely be shown
 * 
 * @return void
 * @since  1.0
 * @uses   edd_wl_rewrite_rules()
 */
function edd_wl_save_permalinks() {
    global $pagenow;

    if ( $pagenow == 'options-permalink.php' && ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) ) {
        edd_wl_rewrite_rules();
    }
}
add_action( 'admin_init', 'edd_wl_save_permalinks' );

/**
 * Rewrite rules
 * @since  1.0
 */
function edd_wl_rewrite_rules() {
    $wish_list_view_page_id = edd_get_option( 'edd_wl_page_view', null );
    $wish_list_edit_page_id = edd_get_option( 'edd_wl_page_edit', null );
    
    $view_slug = edd_wl_get_page_slug( 'view' ) ? edd_wl_get_page_slug( 'view' ) : '';
    $edit_slug = edd_wl_get_page_slug( 'edit' ) ? edd_wl_get_page_slug( 'edit' ) : '';
    
    add_rewrite_rule(
        '.*' . $view_slug . '/([0-9]+)?$',
        'index.php?page_id=' . $wish_list_view_page_id . '&view=$matches[1]',
        'top'
    );

    add_rewrite_rule(
        '.*' . $edit_slug . '/([0-9]+)?$',
        'index.php?page_id=' . $wish_list_edit_page_id . '&edit=$matches[1]',
        'top'
    );

    // flush the rewrite rules
    flush_rewrite_rules();
}

/**
 * Filter calls to get_post_permalink()
 * 
 * This affects the CPT slug shown in the admin and prevents the wishlist on the front end from redirecting to the actual name. 
 * Uses ID as permalink
 *
 * @since 1.0
*/
function edd_wl_post_type_link( $post_link, $post, $leavename, $sample ) {
    if ( $post->post_type == 'edd_wish_list' ) {
        return edd_wl_get_wish_list_view_uri( $post->ID );
    }

    return $post_link;
}
add_filter( 'post_type_link', 'edd_wl_post_type_link', 10, 4 );

/**
 * Rewrite tags
 * Adds our 'view' and 'edit' to query vars as seen in the add_rewrite_rule's above
 *
 * @since 1.0
*/
function edd_wl_add_rewrite_tag() {
	add_rewrite_tag( '%view%', '([^/]+)');
    add_rewrite_tag( '%edit%', '([^/]+)');
}
add_action( 'init', 'edd_wl_add_rewrite_tag' );