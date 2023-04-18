<?php
/**
 * Helpers
 *
 * @package NSP
 */

/**
 * Return option items.
 *
 * @since 1.0.0
 *
 * @return array Option items.
 */
function nsp_get_options_items() {
	return array(
		'disable_admin_columns' => array(
			'id'    => 'disable_admin_columns',
			'label' => esc_html__( 'Disable Admin Columns', 'nsp' ),
			'file'  => 'admin-columns.php',
		),
		'disable_assets'        => array(
			'id'    => 'disable_assets',
			'label' => esc_html__( 'Disable Assets', 'nsp' ),
			'file'  => 'assets.php',
		),
		'disable_debug'         => array(
			'id'    => 'disable_debug',
			'label' => esc_html__( 'Disable Debug', 'nsp' ),
			'file'  => 'debug.php',
		),
		'disable_emoji'         => array(
			'id'    => 'disable_emoji',
			'label' => esc_html__( 'Disable Emoji', 'nsp' ),
			'file'  => 'emoji.php',
		),
		'disable_media_columns' => array(
			'id'    => 'disable_media_columns',
			'label' => esc_html__( 'Disable Media Columns', 'nsp' ),
			'file'  => 'media-columns.php',
		),
		'disable_quick_links'   => array(
			'id'    => 'disable_quick_links',
			'label' => esc_html__( 'Disable Quick Links', 'nsp' ),
			'file'  => 'quick-links.php',
		),
		'disable_woo_extras'    => array(
			'id'    => 'disable_woo_extras',
			'label' => esc_html__( 'Disable Woo Extras', 'nsp' ),
			'file'  => 'woo-extras.php',
		),
		'disable_templates'     => array(
			'id'    => 'disable_templates',
			'label' => esc_html__( 'Disable Templates', 'nsp' ),
			'file'  => 'templates.php',
		),
		'disable_debug_log'     => array(
			'id'    => 'disable_debug_log',
			'label' => esc_html__( 'Disable Debug Log', 'nsp' ),
			'file'  => 'debug-log.php',
		),
	);
}

/**
 * Return option.
 *
 * @since 1.0.0
 *
 * @param string $key Option key.
 * @return mixed Option value.
 */
function nsp_get_option( $key ) {
	$default_options = nsp_get_default_options();

	if ( empty( $key ) ) {
		return;
	}

	$current_options = (array) get_option( 'nsp_options' );
	$current_options = wp_parse_args( $current_options, $default_options );

	$value = null;

	if ( isset( $current_options[ $key ] ) ) {
		$value = $current_options[ $key ];
	}

	return $value;
}

/**
 * Return default options.
 *
 * @since 1.0.0
 *
 * @return array Default options.
 */
function nsp_get_default_options() {
	$def_options = array();

	$all_items = nsp_get_options_items();

	foreach ( $all_items as $item ) {
		$def_options[ $item['id'] ] = false;
	}

	return apply_filters( 'nsp_option_defaults', $def_options );
}

function nsp_debug_log_exists() {
	$debug_file = ABSPATH . 'wp-content/debug.log';

	return ( is_readable( $debug_file ) && file_exists( $debug_file ) ) ? true : false;
}
