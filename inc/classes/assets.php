<?php
/**
 * NSP_Assets
 *
 * @package NSP
 */

class NSP_Assets {

	public function __construct() {
		add_filter( 'style_loader_src', array( $this, 'update_query_string' ) );
		add_filter( 'script_loader_src', array( $this, 'update_query_string' ) );
	}

	/**
	 * Modify source URL of assets.
	 *
	 * @since 1.0.0
	 *
	 * @param string $src The source URL of the enqueued style.
	 * @return string Modified URL.
	 */
	public function update_query_string( $src ) {
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
}
