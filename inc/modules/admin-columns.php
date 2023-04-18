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

add_action(
	'admin_enqueue_scripts',
	function() {
		wp_enqueue_media();

		$script_asset_path = get_template_directory() . '/build/columns.asset.php';
		$script_asset      = file_exists( $script_asset_path ) ? require $script_asset_path : array(
			'dependencies' => array(),
			'version'      => filemtime( __FILE__ ),
		);

		$script_asset['dependencies'][] = 'jquery';
		$script_asset['dependencies'][] = 'jquery-ui-dialog';

		wp_enqueue_style( 'nsp-jquery-ui', NSP_URL . '/third-party/jquery-ui/jquery-ui.css', array(), '1.8.1' );

		wp_enqueue_style( 'nsp-columns', NSP_URL . '/build/columns.css', array(), $script_asset['version'] );
		wp_enqueue_script( 'nsp-columns', NSP_URL . '/build/columns.js', $script_asset['dependencies'], $script_asset['version'], true );

		wp_localize_script(
			'nsp-columns',
			'NSP_COLUMNS',
			array(
				'thumbnail_default_url' => NSP_URL . '/src/img/no-image.png',
			)
		);
	},
	99
);

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
