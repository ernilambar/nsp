<?php
/**
 * Plugin Name: NSP
 * Plugin URI: https://github.com/ernilambar/nsp
 * Description: Dev helpers.
 * Version: 1.0.0
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

define( 'NSP_VERSION', '1.0.0' );
define( 'NSP_BASE_NAME', basename( __DIR__ ) );
define( 'NSP_BASE_FILEPATH', __FILE__ );
define( 'NSP_BASE_FILENAME', plugin_basename( __FILE__ ) );
define( 'NSP_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'NSP_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
