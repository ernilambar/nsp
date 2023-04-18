<?php
/**
 * Assets
 *
 * @package NSP
 */

/**
 * Modify source URL of assets.
 *
 * @since 1.0.0
 *
 * @param string $src The source URL of the enqueued style.
 * @return string Modified URL.
 */
function nsp_remove_query_string_from_static_files( $src ) {
	$debug_mode = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? true : false;

	if ( ! $debug_mode ) {
		return $src;
	}

	if ( strpos( $src, '?ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
		$src = add_query_arg( array( 'ver' => time() ), $src );
	}

	return $src;
}

add_filter( 'style_loader_src', 'nsp_remove_query_string_from_static_files' );
add_filter( 'script_loader_src', 'nsp_remove_query_string_from_static_files' );
