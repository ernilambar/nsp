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
