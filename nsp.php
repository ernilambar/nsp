<?php
/**
 * Plugin Name: NSP
 * Plugin URI: https://github.com/ernilambar/nsp
 * Description: Dev helpers.
 * Version: 1.0.2
 * Author: Nilambar Sharma
 * Author URI: https://www.nilambar.net/
 * Text Domain: nsp
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package NSP
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NSP_VERSION', '1.0.2' );
define( 'NSP_SLUG', 'nsp' );
define( 'NSP_BASE_NAME', basename( __DIR__ ) );
define( 'NSP_BASE_FILEPATH', __FILE__ );
define( 'NSP_BASE_FILENAME', plugin_basename( __FILE__ ) );
define( 'NSP_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'NSP_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

// Include autoload.
if ( file_exists( NSP_DIR . '/vendor/autoload.php' ) ) {
	require_once NSP_DIR . '/vendor/autoload.php';
	require_once NSP_DIR . '/vendor/ernilambar/optioner/optioner.php';
	require_once NSP_DIR . '/vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';
}

// Init.
require_once NSP_DIR . '/inc/init.php';

// Updater.
$nsp_updater = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker( 'https://github.com/ernilambar/nsp', __FILE__, NSP_SLUG );
$nsp_updater->getVcsApi()->enableReleaseAssets();
