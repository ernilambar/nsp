<?php
/**
 * Init
 *
 * @package NSP
 */

// Load classes.
require_once NSP_DIR . '/inc/classes/notify.php';
NSP_Notify::init();
require_once NSP_DIR . '/inc/classes/file.php';
require_once NSP_DIR . '/inc/classes/assets.php';
require_once NSP_DIR . '/inc/classes/woo.php';
require_once NSP_DIR . '/inc/classes/scheme.php';
require_once NSP_DIR . '/inc/classes/templates-list.php';

// Load files.
require_once NSP_DIR . '/inc/helpers.php';
require_once NSP_DIR . '/inc/options.php';
require_once NSP_DIR . '/inc/hooks.php';
require_once NSP_DIR . '/inc/ajax.php';

$all_items = nsp_get_options_items();

foreach ( $all_items as $item ) {
	$disable_status = nsp_get_option( $item['id'] );

	if ( true !== $disable_status ) {
		$module_file = NSP_DIR . '/inc/modules/' . $item['file'];

		if ( file_exists( $module_file ) ) {
			require_once $module_file;
		}
	}
}

add_action(
	'admin_enqueue_scripts',
	function() {
		add_thickbox();

		wp_enqueue_media();

		$script_asset_path = get_template_directory() . '/build/admin.asset.php';
		$script_asset      = file_exists( $script_asset_path ) ? require $script_asset_path : array(
			'dependencies' => array(),
			'version'      => filemtime( __FILE__ ),
		);

		$script_asset['dependencies'][] = 'jquery';
		$script_asset['dependencies'][] = 'jquery-ui-dialog';

		wp_enqueue_style( 'nsp-jquery-ui', NSP_URL . '/third-party/jquery-ui/jquery-ui.css', array(), '1.8.1' );

		wp_enqueue_style( 'nsp-admin', NSP_URL . '/build/admin.css', array(), $script_asset['version'] );
		wp_enqueue_script( 'nsp-admin', NSP_URL . '/build/admin.js', $script_asset['dependencies'], $script_asset['version'], true );

		$localized_data = array(
			'thumbnail_default_url' => nsp_get_placeholder_image_url(),
		);

		wp_localize_script( 'nsp-admin', 'NSP_ADMIN', $localized_data );
	},
	99
);
