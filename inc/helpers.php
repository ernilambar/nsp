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
		'disable_scheme'        => array(
			'id'    => 'disable_scheme',
			'label' => esc_html__( 'Disable Admin Scheme', 'nsp' ),
			'file'  => 'scheme.php',
		),
	);
}

function nsp_get_admin_columns_options_details() {
	return array(
		'disable_column_id'       => array(
			'id'    => 'disable_column_id',
			'label' => esc_html__( 'Disable ID', 'nsp' ),
			'file'  => 'id.php',
		),
		'disable_column_template' => array(
			'id'    => 'disable_column_template',
			'label' => esc_html__( 'Disable Template', 'nsp' ),
			'file'  => 'template.php',
		),
		'disable_column_slug'     => array(
			'id'    => 'disable_column_slug',
			'label' => esc_html__( 'Disable Slug', 'nsp' ),
			'file'  => 'slug.php',
		),
		'disable_column_image'    => array(
			'id'    => 'disable_column_image',
			'label' => esc_html__( 'Disable Image', 'nsp' ),
			'file'  => 'image.php',
		),
		'disable_column_order'    => array(
			'id'    => 'disable_column_order',
			'label' => esc_html__( 'Disable Order', 'nsp' ),
			'file'  => 'order.php',
		),
		'disable_column_media'    => array(
			'id'    => 'disable_column_media',
			'label' => esc_html__( 'Disable Media Columns', 'nsp' ),
			'file'  => 'media.php',
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

	$all_items = nsp_get_admin_columns_options_details();

	foreach ( $all_items as $item ) {
		$def_options[ $item['id'] ] = false;
	}

	return apply_filters( 'nsp_option_defaults', $def_options );
}

/**
 * Check whether log file exists.
 *
 * @since 1.0.0
 *
 * @return bool True if exists; otherwise false.
 */
function nsp_debug_log_exists() {
	$debug_file = ABSPATH . 'wp-content/debug.log';

	return ( is_readable( $debug_file ) && file_exists( $debug_file ) ) ? true : false;
}

/**
 * Get post types.
 *
 * @since 1.0.0
 *
 * @return array Post types list.
 */
function nsp_get_post_types() {
	$list = array();

	$list = get_post_types(
		array(
			'public' => true,
		),
		'objects'
	);

	return $list;
}

/**
 * Return template title from file name.
 *
 * @since 1.0.0
 *
 * @param string $file Filename.
 * @return string Title.
 */
function nsp_addons_get_template_title( $file ) {
	$output = null;

	$all_templates = wp_get_theme()->get_page_templates();

	if ( isset( $all_templates[ $file ] ) ) {
		$output = $all_templates[ $file ];
	}

	return $output;
}

/**
 * Add given element in the array in the specified position.
 *
 * @since 1.0.0
 *
 * @param array $main_array Main array.
 * @param array $element Array element to be inserted.
 * @param int   $position Position.
 * @return array Updated array.
 */
function nsp_add_array_item_to_position( $main_array, $element = array(), $position = 0 ) {
	if ( empty( $element ) ) {
		return $main_array;
	}

	return array_merge( array_slice( $main_array, 0, $position ), $element, array_slice( $main_array, $position ) );
}

/**
 * Return placeholder image URL.
 *
 * @since 1.0.0
 *
 * @return string Full image URL.
 */
function nsp_get_placeholder_image_url() {
	return NSP_URL . '/build/img/no-image.png';
}

/**
 * Get information about available image sizes.
 *
 * @since 1.0.0
 *
 * @return array Image sizes details.
 */
function nsp_get_image_sizes( $size = '' ) {
	$wp_additional_image_sizes = wp_get_additional_image_sizes();

	$sizes = array();

	$get_intermediate_image_sizes = get_intermediate_image_sizes();

	foreach ( $get_intermediate_image_sizes as $_size ) {
		if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
			$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
			$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
			$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );
		} elseif ( isset( $wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $wp_additional_image_sizes[ $_size ]['width'],
				'height' => $wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $wp_additional_image_sizes[ $_size ]['crop'],
			);
		}
	}

	if ( $size ) {
		if ( isset( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		} else {
			return false;
		}
	}

	return $sizes;
}

if ( ! function_exists( 'str_contains' ) ) {
	function str_contains( string $haystack, string $needle ) {
		return empty( $needle ) || strpos( $haystack, $needle ) !== false;
	}
}
