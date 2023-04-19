<?php
/**
 * Admin columns
 *
 * @package NSP
 */

// Load columns.
require_once NSP_DIR . '/inc/modules/admin-columns/media.php';
require_once NSP_DIR . '/inc/modules/admin-columns/id.php';
require_once NSP_DIR . '/inc/modules/admin-columns/template.php';
require_once NSP_DIR . '/inc/modules/admin-columns/image.php';
require_once NSP_DIR . '/inc/modules/admin-columns/order.php';

add_filter(
	'pre_get_posts',
	function( $query ) {
		if ( ! is_admin() ) {
			return;
		}

		$orderby = $query->get( 'orderby' );

		if ( 'nsp_image' === $orderby ) {
			$query->set( 'meta_key', '_thumbnail_id' );
			$query->set( 'orderby', 'meta_value' );
		} elseif ( 'nsp_template' === $orderby ) {
			$query->set( 'meta_key', '_wp_page_template' );
			$query->set( 'orderby', 'meta_value' );
		} elseif ( 'nsp_menu_order' === $orderby ) {
			$query->set( 'orderby', 'menu_order' );
		}
	}
);
