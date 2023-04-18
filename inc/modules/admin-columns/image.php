<?php
/**
 * Admin column: image
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
				if ( ! post_type_supports( $p, 'thumbnail' ) ) {
					continue;
				}

				add_filter(
					'manage_edit-' . $p . '_columns',
					function( $columns ) {
						$columns['nsp_image'] = 'Image';
						return $columns;
					},
					10,
					1
				);

				add_action(
					'manage_' . $p . '_posts_custom_column',
					function( $column, $post_id ) {
						if ( 'nsp_image' === $column ) {
							$post_thumbnail_id = get_post_thumbnail_id( $post_id );
							$thumbnail_url     = NSP_URL . '/src/img/no-image.png';

							$thumbnail_full_url = '';

							if ( $post_thumbnail_id > 0 ) {
								$thumbnail_full_url = get_the_post_thumbnail_url( $post_id, 'large' );
								$thumbnail_url      = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
							}

							echo '<div class="nsp-image-wrap">';
							echo '<div class="nsp-image-content">';
							echo "<img class='nsp-image-thumbnail' src='" . esc_url( $thumbnail_url ) . "' />";
							echo '</div><!-- .nsp-image-content -->';

							if ( current_user_can( 'upload_files' ) ) {
								echo '<div class="nsp-image-buttons">';

								$image_status = $thumbnail_full_url ? true : false;

								echo '<a href="' . esc_url( $thumbnail_full_url ) . '" class="thickbox btn-nsp-image-preview ' . ( $image_status ? '' : 'is-hidden' ) . '"><span class="dashicons dashicons-visibility"></span></a>';

								echo '<a href="#" class="btn-nsp-image-add ' . ( ! $image_status ? '' : 'is-hidden' ) . '" data-pid="' . absint( $post_id ) . '"><span class="dashicons dashicons-plus-alt"></span></a>';
								echo '<a href="#" class="btn-nsp-image-update ' . ( $image_status ? '' : 'is-hidden' ) . '" data-pid="' . absint( $post_id ) . '" data-previous_attachment="' . absint( $post_thumbnail_id ) . '"><span class="dashicons dashicons-update"></span></a>';
								echo '<a href="#" class="btn-nsp-image-delete ' . ( $image_status ? '' : 'is-hidden' ) . '" data-pid="' . absint( $post_id ) . '"><span class="dashicons dashicons-trash"></span></a>';

								echo '</div>';
							}

							echo '</div><!-- .nsp-image-wrap -->';
						}
					},
					10,
					2
				);

				add_filter(
					'manage_edit-' . $p . '_sortable_columns',
					function( $cols ) {
						$cols['nsp_image'] = 'nsp_image';
						return $cols;
					}
				);
			}
		}
	},
	99
);
