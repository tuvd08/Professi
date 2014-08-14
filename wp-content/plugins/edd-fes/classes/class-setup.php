<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class FES_Setup {
	public function __construct() {
		add_action( 'admin_init', array(
			 $this,
			'is_wp_and_edd_activated' 
		), 1 );
		add_action( 'init', array(
			 $this,
			'register_post_type' 
		) );
		add_action( 'plugins_loaded', array(
			 $this,
			'load_textdomain' 
		) );
		add_action( 'switch_theme', 'flush_rewrite_rules', 15 );
		add_action( 'wp_enqueue_scripts', array(
			 $this,
			'enqueue_scripts' 
		) );
		add_action( 'wp_enqueue_scripts', array(
			 $this,
			'enqueue_styles' 
		) );
		add_action( 'admin_enqueue_scripts', array(
			 $this,
			'admin_enqueue_scripts' 
		) );
		add_action( 'admin_enqueue_scripts', array(
			 $this,
			'admin_enqueue_styles' 
		) );
		add_action( 'wp_head', array(
			 $this,
			'fes_version' 
		) );
		add_filter( 'media_upload_tabs', array(
			 $this,
			'remove_media_library_tab' 
		) );
		add_action( 'wp_footer', array(
			 $this,
			'edd_lockup_uploaded' 
		) );
		add_post_type_support( 'download', 'author' );
		add_post_type_support( 'download', 'comments' );

		add_filter('parse_query', array( $this, 'restrict_media' ) );

		$this->add_new_roles();
	}
	
	public function is_wp_and_edd_activated() {
		global $wp_version;
		if ( version_compare( $wp_version, '3.8', '< ' ) ) {
			if ( is_plugin_active( EDD_FES()->basename ) ) {
				deactivate_plugins( EDD_FES()->basename );
				unset( $_GET[ 'activate' ] );
				add_action( 'admin_notices', array(
					 $this,
					'wp_notice' 
				) );
			}
		} else if ( !class_exists( 'Easy_Digital_Downloads' ) || ( version_compare( EDD_VERSION, '1.9' ) < 0 ) ) {
			if ( is_plugin_active( EDD_FES()->basename ) ) {
				deactivate_plugins( EDD_FES()->basename );
				unset( $_GET[ 'activate' ] );
				add_action( 'admin_notices', array(
					 $this,
					'edd_notice' 
				) );
			}
		}
	}
	
	public function edd_notice() {
?>
	<div class="updated">
		<p><?php
		printf( __( '<strong>Notice:</strong> Easy Digital Downloads Frontend Submissions requires Easy Digital Downloads 1.9 or higher in order to function properly.', 'edd_fes' ) );
?>
		</p>
	</div>
	<?php
	}
	public function wp_notice() {
?>
	<div class="updated">
		<p><?php
		printf( __( '<strong>Notice:</strong> Easy Digital Downloads Frontend Submissions requires WordPress 3.8 or higher in order to function properly.', 'edd_fes' ) );
?>
		</p>
	</div>
	<?php
	}
	
	public function load_textdomain() {
		load_plugin_textdomain( 'edd_fes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	public function enqueue_scripts() {
		if ( is_admin() ) {
			return;
		}
		global $post;
		if ( is_page( EDD_FES()->fes_options->get_option( 'vendor-dashboard-page' ) ) ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'underscore' );
			wp_enqueue_style( 'fes-css', fes_plugin_url . 'assets/css/frontend.css' );
			wp_enqueue_script( 'fes-form', fes_plugin_url . 'assets/js/frontend-form.js', array(
				 'jquery' 
			) );
			wp_enqueue_script( 'comment-reply' ); 
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'suggest' );
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_script( 'plupload-handlers' );
			wp_enqueue_script( 'zxcvbn', includes_url( '/js/zxcvbn.min.js' ) );
			wp_enqueue_script( 'jquery-ui-timepicker', fes_plugin_url . 'assets/js/jquery-ui-timepicker-addon.js', array(
				 'jquery-ui-datepicker' 
			) );
			wp_enqueue_script( 'fes-upload', fes_plugin_url . 'assets/js/upload.js', array(
				 'jquery',
				'plupload-handlers' 
			) );
			wp_localize_script( 'fes-form', 'fes_frontend', array(
				 'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'error_message' => __( 'Please fix the errors to proceed', 'edd_fes' ),
				'nonce' => wp_create_nonce( 'fes_nonce' ) 
			) );
			wp_localize_script( 'fes-upload', 'fes_frontend_upload', array(
				 'confirmMsg' => __( 'Are you sure?', 'edd_fes' ),
				'nonce' => wp_create_nonce( 'fes_nonce' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'plupload' => array(
					 'url' => admin_url( 'admin-ajax.php' ) . '?nonce=' . wp_create_nonce( 'fes_featured_img' ),
					'flash_swf_url' => includes_url( 'js/plupload/plupload.flash.swf' ),
					'filters' => array(
						 array(
							 'title' => __( 'Allowed Files' ),
							'extensions' => '*' 
						) 
					),
					'multipart' => true,
					'urlstream_upload' => true 
				) 
			) );
		}
	}
	
	public function admin_enqueue_scripts() {
		if ( !is_admin() ) {
			return;
		}
		global $pagenow, $post;
		$current_screen = get_current_screen();
		if ( $current_screen->post_type === 'fes-forms' || $current_screen->post_type === 'download' || $pagenow == 'profile.php' ) {
			wp_register_script( 'jquery-tiptip', fes_plugin_url . 'assets/js/jquery-tiptip/jquery.tipTip.min.js', array(
				 'jquery' 
			), '2.0', true );
			wp_enqueue_script( 'edd-fes-admin-js', fes_plugin_url . 'assets/js/admin.js', array(
				 'jquery',
				'jquery-tiptip' 
			), '2.0', true );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'jquery-smallipop', fes_plugin_url . 'assets/js/jquery.smallipop-0.4.0.min.js', array(
				 'jquery' 
			) );
			wp_enqueue_script( 'fes-formbuilder', fes_plugin_url . 'assets/js/formbuilder.js', array(
				 'jquery',
				'jquery-ui-sortable' 
			) );
			wp_enqueue_script( 'fes-upload', fes_plugin_url . 'assets/js/upload.js', array(
				 'jquery',
				'plupload-handlers' 
			) );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'suggest' );
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_localize_script( 'fes-form', 'fes_frontend', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'error_message' => __( 'Please fix the errors to proceed', 'edd_fes' ),
				'avatar_failure_message' => __( 'There was a problem deleting your avatar, please try again', 'edd_fes' ),
				'nonce' => wp_create_nonce( 'fes_nonce' ) 
			) );
			wp_localize_script( 'fes-upload', 'fes_frontend_upload', array(
				 'confirmMsg' => __( 'Are you sure?', 'edd_fes' ),
				'nonce' => wp_create_nonce( 'fes_nonce' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'plupload' => array(
					 'url' => admin_url( 'admin-ajax.php' ) . '?nonce=' . wp_create_nonce( 'fes_featured_img' ),
					'flash_swf_url' => includes_url( 'js/plupload/plupload.flash.swf' ),
					'filters' => array(
						 array(
							 'title' => __( 'Allowed Files' ),
							'extensions' => '*' 
						) 
					),
					'multipart' => true,
					'urlstream_upload' => true 
				) 
			) );
		}
	}
	
	public function admin_enqueue_styles() {
		if ( !is_admin() ) {
			return;
		}
		$current_screen = get_current_screen();
		if ( $current_screen->post_type === 'fes-forms' || $current_screen->post_type === 'download' ) {
			wp_enqueue_style( 'edd-fes-admin-css', fes_plugin_url . 'assets/css/admin.css' );
			wp_enqueue_style( 'jquery-smallipop', fes_plugin_url . 'assets/css/jquery.smallipop.css' );
			if ( $current_screen->post_type === 'fes-forms' ){
				wp_enqueue_style( 'fes-formbuilder', fes_plugin_url . 'assets/css/formbuilder.css' );
			}
			wp_enqueue_style( 'jquery-ui-core', fes_plugin_url . 'assets/css/jquery-ui-1.9.1.custom.css' );
		}
	}
	
	public function enqueue_styles() {
		if ( is_admin() ||  EDD_FES()->fes_options->get_option( 'edd_fes_use_css' ) === 0 || EDD_FES()->fes_options->get_option( 'edd_fes_use_css' ) === false) {
			return;
		}
		global $post;
		if ( is_page( EDD_FES()->fes_options->get_option( 'vendor-dashboard-page' ) ) && EDD_FES()->fes_options->get_option( 'edd_fes_use_css' ) ) {
			wp_enqueue_style( 'fes-css', fes_plugin_url . 'assets/css/frontend.css' );
			wp_enqueue_style( 'jquery-ui', fes_plugin_url . 'assets/css/jquery-ui-1.9.1.custom.css' );
		}
	}
	
	public function fes_version() {
		// Newline on both sides to avoid being in a blob
		echo '<meta name="generator" content="EDD FES v' . fes_plugin_version . '" />' . "\n";
	}
	
	public function edd_lockup_uploaded() {
		if ( is_admin() ) {
			return;
		}
?>
  <script type="text/javascript">
    jQuery(document).on("DOMNodeInserted", function(){
        // Lock uploads to "Uploaded to this post"
        jQuery('select.attachment-filters [value="uploaded"]').attr( 'selected', true ).parent().trigger('change');
    });
	<?php
		if ( EDD_FES()->vendors->is_s3_active() ) {
?>
	// handlediv rem
	jQuery(document).on("DOMNodeInserted", function(){
		//jQuery('.media-frame-menu').remove();
    });
	jQuery(document).on("DOMNodeInserted", function(){
		jQuery('#media-upload-header').remove();
		});
	<?php
		}
?>
</script>
	<?php
	}
	
	// removes URL tab in image upload for post
	public function remove_media_library_tab( $tabs ) {
		if ( is_admin() ) {
			return $tabs;
		}
		if ( EDD_FES()->vendors->is_s3_active() && EDD_FES()->vendors->is_vendor( get_current_user_id() ) && !current_user_can( 'fes_is_admin' ) ) {
			//home
			unset( $tabs[ 'type' ] );
			unset( $tabs[ 'type_url' ] );
			unset( $tabs[ 'gallery' ] );
			// s3 library
			unset( $tabs[ 's3_library' ] );
			return $tabs;
		} else if ( !current_user_can( 'fes_is_admin' ) ) {
			unset( $tabs[ 'library' ] );
			unset( $tabs[ 'gallery' ] );
			unset( $tabs[ 'type' ] );
			unset( $tabs[ 'type_url' ] );
			return $tabs;
		} else {
			return $tabs;
		}
	}

	// Prevents vendors from seeing media files that aren't theirs
	public function restrict_media( $wp_query ) {
		if ( is_admin() ) {
			if ( ! current_user_can( 'fes_is_admin' ) && $wp_query->get( 'post_type' ) == 'attachment' ) {
				$wp_query->set( 'author', get_current_user_id() );
			}
		}
	}
	
	public function register_post_type() {
		$capability = 'manage_options';
		register_post_type( 'fes-forms', array(
			'label' => __( 'EDD FES Forms', 'edd_fes' ),
			'public' => false,
			'rewrites' => false,
			'capability_type' => 'post',
			'capabilities' => array(
				 'publish_posts' => 'cap_that_doesnt_exist',
				'edit_posts' => $capability,
				'edit_others_posts' => $capability,
				'delete_posts' => 'cap_that_doesnt_exist',
				'delete_others_posts' => 'cap_that_doesnt_exist',
				'read_private_posts' => 'cap_that_doesnt_exist',
				'edit_post' => $capability,
				'delete_post' => 'cap_that_doesnt_exist',
				'read_post' => $capability,
				'create_posts' => 'cap_that_doesnt_exist' 
			),
			'hierarchical' => false,
			'query_var' => false,
			'supports' => array(
				 'title' 
			),
			'labels' => array(
				 'name' => __( 'EDD FES Forms', 'edd_fes' ),
				'singular_name' => __( 'FES Form', 'edd_fes' ),
				'menu_name' => __( 'FES Forms', 'edd_fes' ),
				'add_new' => __( 'Add FES Form', 'edd_fes' ),
				'add_new_item' => __( 'Add New Form', 'edd_fes' ),
				'edit' => __( 'Edit', 'edd_fes' ),
				'edit_item' => __( 'Edit', 'edd_fes' ),
				'new_item' => __( 'New FES Form', 'edd_fes' ),
				'view' => __( 'View FES Form', 'edd_fes' ),
				'view_item' => __( 'View FES Form', 'edd_fes' ),
				'search_items' => __( 'Search FES Forms', 'edd_fes' ),
				'not_found' => __( 'No FES Forms Found', 'edd_fes' ),
				'not_found_in_trash' => __( 'No FES Forms Found in Trash', 'edd_fes' ),
				'parent' => __( 'Parent FES Form', 'edd_fes' ) 
			) 
		) );
		register_post_type( 'fes-applications', array(
			'label' => __( 'EDD FES Applications', 'edd_fes' ),
			'public' => false,
			'rewrites' => false,
			'capability_type' => 'post',
			'capabilities' => array(
				 'publish_posts' => 'cap_that_doesnt_exist',
				'edit_posts' => $capability,
				'edit_others_posts' => $capability,
				'delete_posts' => 'cap_that_doesnt_exist',
				'delete_others_posts' => 'cap_that_doesnt_exist',
				'read_private_posts' => 'cap_that_doesnt_exist',
				'edit_post' => $capability,
				'delete_post' => 'cap_that_doesnt_exist',
				'read_post' => $capability,
				'create_posts' => 'cap_that_doesnt_exist' 
			),
			'hierarchical' => false,
			'query_var' => false,
			'supports' => array(
				 'title' 
			),
			'labels' => array(
				 'name' => __( 'EDD FES Applications', 'edd_fes' ),
				'singular_name' => __( 'FES Application', 'edd_fes' ),
				'menu_name' => __( 'FES Applications', 'edd_fes' ),
				'add_new' => __( 'Add FES Application', 'edd_fes' ),
				'add_new_item' => __( 'Add New Form', 'edd_fes' ),
				'edit' => __( 'Edit', 'edd_fes' ),
				'edit_item' => __( 'Edit', 'edd_fes' ),
				'new_item' => __( 'New FES Application', 'edd_fes' ),
				'view' => __( 'View FES Application', 'edd_fes' ),
				'view_item' => __( 'View FES Application', 'edd_fes' ),
				'search_items' => __( 'Search FES Applications', 'edd_fes' ),
				'not_found' => __( 'No FES Applications Found', 'edd_fes' ),
				'not_found_in_trash' => __( 'No FES Applications Found in Trash', 'edd_fes' ),
				'parent' => __( 'Parent FES Application', 'edd_fes' ) 
			) 
		) );
	}
	
	private function add_new_roles() {
		global $wp_roles;
		remove_role( 'pending_vendor' );
		add_role( 'pending_vendor', __( 'Pending Vendor', 'edd_fes' ), array(
			 'read' => true,
			'edit_posts' => false,
			'delete_posts' => false 
		) );
		remove_role( 'frontend_vendor' );
		add_role( 'frontend_vendor', 'Frontend Vendor', array(
			 'read' => true,
			'edit_posts' => true,
			'upload_files' => true,
			'delete_posts' => false,
			'manage_categories' => false
		) );
		if ( class_exists( 'WP_Roles' ) && !isset( $wp_roles ) )
			$wp_roles = new WP_Roles();
		if ( is_object( $wp_roles ) ) {
			$capabilities     = array();
			$capability_types = array(
				 'product' 
			);
			foreach ( $capability_types as $capability_type ) {
				$capabilities[ $capability_type ] = array(
					// Post type
					 "edit_{$capability_type}",
					"read_{$capability_type}",
					"delete_{$capability_type}",
					"edit_{$capability_type}s",
					"read_private_{$capability_type}s",
					"edit_private_{$capability_type}s",
					// Terms
					"manage_{$capability_type}_terms",
					"edit_{$capability_type}_terms",
					"assign_{$capability_type}_terms" 
				);
			}
			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->add_cap( 'frontend_vendor', $cap );
				}
			}
			$wp_roles->add_cap( 'frontend_vendor', 'edit_product' );
			$wp_roles->add_cap( 'frontend_vendor', 'edit_products' );
			$wp_roles->add_cap( 'frontend_vendor', 'upload_files' );
			$wp_roles->add_cap( 'frontend_vendor', 'assign_product_terms' );
			$wp_roles->add_cap( 'frontend_vendor', 'delete_product' );
			$wp_roles->add_cap( 'frontend_vendor', 'delete_products' );
			$wp_roles->add_cap( 'administrator', 'fes_is_admin' );
			$wp_roles->add_cap( 'editor', 'fes_is_admin' );
			$wp_roles->add_cap( 'shop_vendor', 'fes_is_vendor' );
			$wp_roles->add_cap( 'frontend_vendor', 'fes_is_vendor' );
		}
	}
}
