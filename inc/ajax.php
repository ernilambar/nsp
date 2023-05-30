<?php
/**
 * AJAX callbacks
 *
 * @package NSP
 */

/**
 * Ajax callback for setting thumbnail.
 *
 * @since 1.0.0
 */
function nsp_addons_image_ajax_callback_add() {
	$output = array();

	$pid = absint( $_POST['pid'] );
	$aid = absint( $_POST['aid'] );

	if ( $pid > 0 && $aid > 0 ) {
		$update = update_post_meta( $pid, '_thumbnail_id', $aid );

		if ( $update ) {
			$output['status']             = true;
			$output['pid']                = $pid;
			$output['aid']                = $aid;
			$output['thumbnail_url']      = get_the_post_thumbnail_url( $pid, 'thumbnail' );
			$output['thumbnail_full_url'] = get_the_post_thumbnail_url( $pid, 'large' );
		}
	}

	if ( ! empty( $output ) ) {
		wp_send_json_success( $output, 200 );
	} else {
		wp_send_json_error( $output, 404 );
	}
}

add_action( 'wp_ajax_nsp_image_add_featured', 'nsp_addons_image_ajax_callback_add' );
add_action( 'wp_ajax_nopriv_nsp_image_add_featured', 'nsp_addons_image_ajax_callback_add' );

/**
 * Ajax callback to update thumbnail.
 *
 * @since 1.0.0
 */
function nsp_addons_image_ajax_callback_update() {
	$output = array();

	$pid = absint( $_POST['pid'] );
	$aid = absint( $_POST['aid'] );

	if ( $pid > 0 && $aid > 0 ) {
		$update = update_post_meta( $pid, '_thumbnail_id', $aid );

		if ( $update ) {
			$output['status']             = true;
			$output['pid']                = $pid;
			$output['aid']                = $aid;
			$output['thumbnail_url']      = get_the_post_thumbnail_url( $pid, 'thumbnail' );
			$output['thumbnail_full_url'] = get_the_post_thumbnail_url( $pid, 'large' );
		}
	}

	if ( ! empty( $output ) ) {
		wp_send_json_success( $output, 200 );
	} else {
		wp_send_json_error( $output, 404 );
	}
}

add_action( 'wp_ajax_nsp_image_update_featured', 'nsp_addons_image_ajax_callback_update' );
add_action( 'wp_ajax_nopriv_nsp_image_update_featured', 'nsp_addons_image_ajax_callback_update' );

/**
 * Ajax callback for deleting thumbnail.
 *
 * @since 1.0.0
 */
function nsp_addons_image_ajax_callback_delete() {
	$output = array();

	$pid = absint( $_POST['pid'] );

	if ( $pid > 0 ) {
		$delete = delete_post_meta( $pid, '_thumbnail_id' );

		if ( $delete ) {
			$output['status'] = true;
			$output['pid']    = $pid;
		}
	}

	if ( ! empty( $output ) ) {
		wp_send_json_success( $output, 200 );
	} else {
		wp_send_json_error( $output, 404 );
	}
}

add_action( 'wp_ajax_nsp_image_delete_featured', 'nsp_addons_image_ajax_callback_delete' );
add_action( 'wp_ajax_nopriv_nsp_image_delete_featured', 'nsp_addons_image_ajax_callback_delete' );

/**
 * Ajax callback to update template.
 *
 * @since 1.0.0
 */
function nsp_addons_template_ajax_callback_update() {
	$output = array();

	$pid  = absint( $_POST['pid'] );
	$file = sanitize_text_field( $_POST['file'] );

	$update = update_post_meta( $pid, '_wp_page_template', $file );

	if ( $update ) {
		$output['status'] = true;
		$output['pid']    = $pid;
		$output['file']   = $file;
		$output['title']  = nsp_addons_get_template_title( $file );
	}

	if ( ! empty( $output ) ) {
		wp_send_json_success( $output, 200 );
	} else {
		wp_send_json_error( $output, 404 );
	}
}

add_action( 'wp_ajax_nsp_update_template_file', 'nsp_addons_template_ajax_callback_update' );
add_action( 'wp_ajax_nopriv_nsp_update_template_file', 'nsp_addons_template_ajax_callback_update' );
