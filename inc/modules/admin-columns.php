<?php
/**
 * Admin columns
 *
 * @package NSP
 */

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
