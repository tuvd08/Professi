<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class FES_Frontend_Form_Post extends FES_Render_Form {
	private static $_instance;
	
	function __construct() {
		add_shortcode( 'fes-form', array(
			 $this,
			'add_post_shortcode' 
		) );
		// ajax requests
		add_action( 'wp_ajax_fes_submit_post', array(
			 $this,
			'submit_post' 
		) );
		add_action( 'wp_ajax_nopriv_fes_submit_post', array(
			 $this,
			'submit_post' 
		) );
		add_filter( 'comments_open', array(
			$this,
			'force_comments_close_on_upload',
		), 10, 2 );

	}
	
	public static function init() {
		if ( !self::$_instance ) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	public function add_post_shortcode( $post_id = NULL ) {
		ob_start();
		$this->render_form( EDD_FES()->fes_options->get_option( 'fes-submission-form' ), $post_id );
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	public function submit_post() {
		require_once EDD_PLUGIN_DIR . 'includes/admin/upload-functions.php';
		if ( function_exists( 'edd_set_upload_dir' ) ) {
			add_filter( 'upload_dir', 'edd_set_upload_dir' );
		}
		$form_id       = isset( $_POST[ 'form_id' ] ) ? intval( $_POST[ 'form_id' ] ) : 0;
		if ($form_id != EDD_FES()->fes_options->get_option( 'fes-submission-form' ) ){
			return;
		}
		check_ajax_referer( 'fes-form_add' );
		@header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
		$form_vars     = $this->get_input_fields( $form_id );
		$form_settings = get_post_meta( $form_id, 'fes-form_settings', true );
		list( $post_vars, $taxonomy_vars, $meta_vars ) = $form_vars;
		// don't check captcha on post edit
		
		
		if ( !isset( $_POST[ 'post_id' ] ) ) {
			// check recaptcha
			if ( $this->search( $post_vars, 'input_type', 'recaptcha' ) ) {
				$this->validate_re_captcha();
			}
		}
		$error = apply_filters( 'fes_add_post_validate', '' );
		if ( !empty( $error ) ) {
			$this->send_error( $error );
		}
		
		
		if ( isset( $_POST[ 'post_id' ] ) ) {
			$post_id = $_POST[ 'post_id' ];
		}
		$post_author = get_current_user_id();
		$pending     = EDD_FES()->fes_options->get_option( 'edd_fes_auto_approve_submissions' );
		$state       = 'pending';
		if ( $pending == 1 ) {
			$pending = false;
			$status  = 'publish';
		} else {
			$pending = true;
			$status  = 'pending';
		}
		$postarr = array(
			'post_type' => 'download',
			'post_status' => $status,
			'post_author' => $post_author,
			'post_title' => isset( $_POST[ 'post_title' ] ) ? trim( $_POST[ 'post_title' ] ) : '',
			'post_content' => isset( $_POST[ 'post_content' ] ) ? trim( $_POST[ 'post_content' ] ) : '',
			'post_excerpt' => isset( $_POST[ 'post_excerpt' ] ) ? trim( $_POST[ 'post_excerpt' ] ) : '' 
		);
		if ( isset( $_POST[ 'category' ] ) ) {
			$category                   = $_POST[ 'category' ];
			$postarr[ 'post_category' ] = is_array( $category ) ? $category : array(
				 $category 
			);
		}
		if ( isset( $_POST[ 'tags' ] ) ) {
			$postarr[ 'tags_input' ] = explode( ',', $_POST[ 'tags' ] );
		}
		$postarr = apply_filters( 'fes_add_post_args', $postarr, $form_id, $form_settings, $form_vars );
		if (!isset($post_id)){
			$post_id = wp_insert_post( $postarr );
		}
		else{
			$postarr['ID'] = $post_id;
			wp_update_post( $postarr );
		}
		if ( $post_id ) {
			self::update_post_meta( $meta_vars, $post_id );
			// set the post form_id for later usage
			update_post_meta( $post_id, self::$config_id, $form_id );
			// find our if any images in post content and associate them
			if ( !empty( $postarr[ 'post_content' ] ) ) {
				$dom = new DOMDocument();
				$dom->loadHTML( $postarr[ 'post_content' ] );
				$images = $dom->getElementsByTagName( 'img' );
				if ( $images->length ) {
					foreach ( $images as $img ) {
						$url           = $img->getAttribute( 'src' );
						$url           = str_replace( array(
							 '"',
							"'",
							"\\" 
						), '', $url );
						$attachment_id = fes_get_attachment_id_from_url( $url );
						if ( $attachment_id ) {
							fes_associate_attachment( $attachment_id, $post_id );
						}
					}
				}
			}
			foreach ( $taxonomy_vars as $taxonomy ) {
				if ( isset( $_POST[ $taxonomy[ 'name' ] ] ) ) {
					if ( is_object_in_taxonomy( 'download', $taxonomy[ 'name' ] ) ) {
						$tax = $_POST[ $taxonomy[ 'name' ] ];
						// if it's not an array, make it one
						if ( !is_array( $tax ) ) {
							$tax = array(
								 $tax 
							);
						}
						if ( $taxonomy[ 'type' ] == 'text' ) {
							$hierarchical = array_map( 'trim', array_map( 'strip_tags', explode( ',', $_POST[ $taxonomy[ 'name' ] ] ) ) );
							wp_set_object_terms( $post_id, $hierarchical, $taxonomy[ 'name' ] );
						} else {
							if ( is_taxonomy_hierarchical( $taxonomy[ 'name' ] ) ) {
								wp_set_post_terms( $post_id, $_POST[ $taxonomy[ 'name' ] ], $taxonomy[ 'name' ] );
							} else {
								if ( $tax ) {
									$non_hierarchical = array();
									foreach ( $tax as $value ) {
										$term = get_term_by( 'id', $value, $taxonomy[ 'name' ] );
										if ( $term && !is_wp_error( $term ) ) {
											$non_hierarchical[] = $term->name;
										}
									}
									wp_set_post_terms( $post_id, $non_hierarchical, $taxonomy[ 'name' ] );
								}
							} // hierarchical
						} // is text
					} // is object tax
				} // isset tax
			}
			$options   = isset( $_POST[ 'option' ] ) ? $_POST[ 'option' ] : '';
			$files     = isset( $_POST[ 'files' ] ) ? $_POST[ 'files' ] : '';
			$prices    = array();
			$edd_files = array();
			if ( isset( $options ) && $options != '' ) {
				foreach ( $options as $key => $option ) {
					$prices[] = array(
						'name' => sanitize_text_field( $option[ 'description' ] ),
						'amount' => $option[ 'price' ] 
					);
				}
				if ( !empty( $files ) ) {
					foreach ( $files as $key => $url ) {
						$edd_files[ $key ] = array(
							 'name' => basename( $url ),
							'file' => $url,
							'condition' => $key 
						);
					}
				}
			}

			if ( count( $prices ) === 1 ) {
				update_post_meta( $post_id, '_variable_pricing', 0 );
				update_post_meta( $post_id, 'edd_price', $prices[ 0 ][ 'amount' ] );
				update_post_meta( $post_id, 'edd_variable_prices', $prices ); // Save variable prices anyway so that price options are saved
			} else {
				update_post_meta( $post_id, '_variable_pricing', 1 );
				update_post_meta( $post_id, 'edd_variable_prices', $prices );
			}
			if ( !empty( $files ) ) {
				update_post_meta( $post_id, 'edd_download_files', $edd_files );
			}
			if ( EDD_FES()->vendors->is_commissions_active() ) {
				$commission = array(
					 'amount' => eddc_get_recipient_rate( 0, $post_author ),
					'user_id' => $post_author,
					'type' => 'percentage' 
				);
				update_post_meta( $post_id, '_edd_commission_settings', $commission );
				update_post_meta( $post_id, '_edd_commisions_enabled', '1' );
			}
			$email_post = get_post( $post_id );
			EDD_FES()->emails->new_edd_fes_submission_admin( $email_post );
			EDD_FES()->emails->new_edd_fes_submission_user( $email_post );
			// send the response (these are options in 2.1, so let's set this array up for that)
			if ( function_exists( 'edd_set_upload_dir' ) ) {
				remove_filter( 'upload_dir', 'edd_set_upload_dir' );
			}

			do_action( 'edd_fes_submit_success', $post_id );

			$response = array(
				'success' => true,
				'redirect_to' => get_permalink( EDD_FES()->fes_options->get_option( 'vendor-dashboard-page' ) ),
				'message' => __( 'Success!', 'edd_fes' ),
				'is_post' => true 
			);
			$response = apply_filters( 'fes_add_post_redirect', $response, $post_id, $form_id, $form_settings );
			echo json_encode( $response );
			exit;
		}
		else {
			// send the response (these are options in 2.1, so let's set this array up for that)
			if ( function_exists( 'edd_set_upload_dir' ) ) {
				remove_filter( 'upload_dir', 'edd_set_upload_dir' );
			}
			$this->send_error( __( 'Something went wrong. Error 1049.', 'edd_fes' ) );	
		}
	}
	
	public static function update_post_meta( $meta_vars, $post_id ) {
		// prepare the meta vars
		list( $meta_key_value, $multi_repeated, $files ) = self::prepare_meta_fields( $meta_vars );
		// set featured image if there's any
		if ( isset( $_POST[ 'fes_files' ][ 'featured_image' ] ) ) {
			//$attachment_id = $_POST[ 'fes_files' ][ 'featured_image' ];
			foreach( $_POST[ 'fes_files' ][ 'featured_image' ] as $attachment_id ) {
				fes_associate_attachment( $attachment_id, $post_id );
				set_post_thumbnail( $post_id, $attachment_id );
			}
		}
		// save all custom fields
		foreach ( $meta_key_value as $meta_key => $meta_value ) {
			update_post_meta( $post_id, $meta_key, $meta_value );
		}
		// save any multicolumn repeatable fields
		foreach ( $multi_repeated as $repeat_key => $repeat_value ) {
			// first, delete any previous repeatable fields
			delete_post_meta( $post_id, $repeat_key );
			// now add them
			foreach ( $repeat_value as $repeat_field ) {
				update_post_meta( $post_id, $repeat_key, $repeat_field );
			}
		}
		// save any files attached
		foreach ( $files as $file_input ) {
			// delete any previous value
			$ids = array();
			delete_post_meta( $post_id, $file_input[ 'name' ] );
			foreach ( $file_input[ 'value' ] as $attachment_id ) {
				fes_associate_attachment( $attachment_id, $post_id );
				$ids[] = $attachment_id;
			}
			update_post_meta( $post_id, $file_input[ 'name' ], $ids );
		}
	}

	public function force_comments_close_on_upload( $open, $post_id ) {
		global $post, $is_fes_upload;

		// Forces comments to be closed on the upload form, related to #146 and #127

		if ( $is_fes_upload )
			$open = false;

		return $open;
	}
}
new FES_Frontend_Form_Post;