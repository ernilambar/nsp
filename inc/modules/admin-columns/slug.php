<?php
/**
 * Admin column: Slug
 *
 * @package NSP
 */

add_action(
	'init',
	function() {
		$all_cpts = nsp_get_post_types();

		if ( empty( $all_cpts ) ) {
			return;
		}

		$post_types = array_keys( $all_cpts );

		$post_types = array_filter(
			$post_types,
			function( $item ) {
				if ( ! in_array( $item, array( 'attachment', 'e-landing-page', 'elementor_library', 'product' ), true ) ) {
					return $item;
				}
			}
		);

		if ( empty( $post_types ) ) {
			return;
		}

		if ( ! empty( $post_types ) ) {
			foreach ( $post_types as $p ) {
				add_filter(
					'manage_edit-' . $p . '_columns',
					function( $columns ) {
						$columns['nsp_slug'] = esc_html__( 'Slug', 'nsp' );

						return $columns;
					},
					10,
					1
				);

				add_action(
					'manage_' . $p . '_posts_custom_column',
					function( $column, $post_id ) {
						if ( 'nsp_slug' === $column ) {
							echo esc_html( get_post_field( 'post_name', $post_id ) );
						}
					},
					10,
					2
				);
			}
		}
	},
	99
);
