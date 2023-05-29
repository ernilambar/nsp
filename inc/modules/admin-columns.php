<?php
/**
 * Admin columns
 *
 * @package NSP
 */

// Load columns.

$slugs = array(
	'media',
	'id',
	'template',
	'slug',
	'image',
	'order',
);

foreach ( $slugs as $slug ) {
	$disable_status = nsp_get_option( 'disable_column_' . $slug );

	if ( true !== $disable_status ) {
		require_once NSP_DIR . '/inc/modules/admin-columns/' . $slug . '.php';
	}
}

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
