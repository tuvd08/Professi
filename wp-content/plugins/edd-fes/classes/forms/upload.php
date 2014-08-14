<?php

/**
 * Attachment Uploader class
 *
 * @since 1.0
 * @package fes
 */
class FES_Upload {

    function __construct() {

        add_action( 'wp_ajax_fes_file_upload', array($this, 'upload_file') );
        add_action( 'wp_ajax_nopriv_fes_file_upload', array($this, 'upload_file') );

        add_action( 'wp_ajax_fes_file_del', array($this, 'delete_file') );
        add_action( 'wp_ajax_nopriv_fes_file_del', array($this, 'delete_file') );

        add_action( 'wp_ajax_fes_insert_image', array( $this, 'insert_image' ) );
        add_action( 'wp_ajax_nopriv_fes_insert_image', array( $this, 'insert_image' ) );
    }

    function upload_file( $image_only = false ) {
        $upload = array(
            'name' => $_FILES['fes_file']['name'],
            'type' => $_FILES['fes_file']['type'],
            'tmp_name' => $_FILES['fes_file']['tmp_name'],
            'error' => $_FILES['fes_file']['error'],
            'size' => $_FILES['fes_file']['size']
        );

        header('Content-Type: text/html; charset=' . get_option('blog_charset'));

        $attach = $this->handle_upload( $upload );

        if ( $attach['success'] ) {

            $response = array( 'success' => true );

            if ($image_only) {
				// To be an option in 2.1 but we need to test this first before ill pro this
                $image_size = 'thumbnail';
                $image_type = 'slink';

                if ( $image_type == 'link' ) {
                    $response['html'] = wp_get_attachment_link( $attach['attach_id'], $image_size );
                } else {
                    $response['html'] = wp_get_attachment_image( $attach['attach_id'], $image_size );
                }

            } else {
                $response['html'] = $this->attach_html( $attach['attach_id'] );
            }

            echo $response['html'];
        } else {
            echo 'error';
        }
        exit;
    }

    /**
     * Generic function to upload a file
     *
     * @param string $field_name file input field name
     * @return bool|int attachment id on success, bool false instead
     */
    function handle_upload( $upload_data ) {

        $uploaded_file = wp_handle_upload( $upload_data, array('test_form' => false) );

        // If the wp_handle_upload call returned a local path for the image
        if ( isset( $uploaded_file['file'] ) ) {
            $file_loc = $uploaded_file['file'];
            $file_name = basename( $upload_data['name'] );
            $file_type = wp_check_filetype( $file_name );

            $attachment = array(
                'post_mime_type' => $file_type['type'],
                'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $file_name ) ),
                'post_content' => '',
                'post_status' => 'inherit'
            );

            $attach_id = wp_insert_attachment( $attachment, $file_loc );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $file_loc );
            wp_update_attachment_metadata( $attach_id, $attach_data );

            return array('success' => true, 'attach_id' => $attach_id);
        }

        return array('success' => false, 'error' => $uploaded_file['error']);
    }

    public static function attach_html( $attach_id, $type = NULL ) {
        if ( !$type ) {
            $type = isset( $_GET['type'] ) ? $_GET['type'] : 'image';
        }

        $attachment = get_post( $attach_id );

        if (!$attachment) {
            return;
        }

        if (wp_attachment_is_image( $attach_id)) {
            $image = wp_get_attachment_image_src( $attach_id, 'thumbnail' );
            $image = $image[0];
        } else {
            $image = wp_mime_type_icon( $attach_id );
        }

        $html = '<li class="image-wrap thumbnail" style="width: 150px">';
        $html .= sprintf( '<div class="attachment-name"><img src="%s" alt="%s" /></div>', $image, esc_attr( $attachment->post_title ) );
        $html .= sprintf( '<div class="caption"><a href="#" class="btn btn-danger btn-small attachment-delete" data-attach_id="%d">%s</a></div>', $attach_id, __( 'Delete', 'edd_fes' ) );
        $html .= sprintf( '<input type="hidden" name="fes_files[%s][%d]" value="%d">', $type, $attach_id, $attach_id );
        $html .= '</li>';

        return $html;
    }

    function delete_file() {
        check_ajax_referer( 'fes_nonce', 'nonce' );

        $attach_id = isset( $_POST['attach_id'] ) ? intval( $_POST['attach_id'] ) : 0;
        $attachment = get_post( $attach_id );

        //post author or editor role
        if ( current_user_can( 'delete_private_pages' ) || get_current_user_id() == $attachment->post_author ) {
            wp_delete_attachment( $attach_id, true );
            echo 'success';
        }

        exit;
    }

    function associate_file( $attach_id, $post_id ) {
        wp_update_post( array(
            'ID' => $attach_id,
            'post_parent' => $post_id
        ) );
    }

    function insert_image() {
        $this->upload_file( true );
    }

}