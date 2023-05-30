<?php
/**
 * NSP_Scheme
 *
 * @package NSP
 */

/**
 * NSP_Scheme class.
 *
 * @since 1.0.0
 */
class NSP_Scheme {

	public function __construct() {
		add_filter( 'admin_init', array( $this, 'update_color_scheme' ) );
	}

	/**
	 * Update admin color scheme.
	 *
	 * @since 1.0.0
	 */
	public function update_color_scheme( $src ) {
		if ( current_user_can( 'manage_options' ) ) {
			$user_id = get_current_user_id();

			$current_color = get_user_meta( $user_id, 'admin_color', true );

			if ( 'fresh' !== $current_color ) {
				update_user_meta( $user_id, 'admin_color', 'fresh' );
			}
		}
	}
}
