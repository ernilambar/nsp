<?php
/**
 * Hooks
 *
 * @package NSP
 */

/**
 * Customize plugin action links.
 *
 * @since 1.0.0
 */
add_filter(
	'plugin_action_links_' . NSP_BASE_FILENAME,
	function ( $actions ) {
		$url = add_query_arg(
			array(
				'page' => 'nsp-settings',
			),
			admin_url( 'options-general.php' )
		);

		$actions = array_merge(
			array(
				'settings' => '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'nsp' ) . '</a>',
			),
			$actions
		);

		return $actions;
	}
);
