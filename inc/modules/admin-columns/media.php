<?php
/**
 * Media
 *
 * @package NSP
 */

/**
 * Add custom media column title.
 *
 * @since 1.0.0
 *
 * @param array $posts_columns The initial array of column headings.
 * @return array The initial array of column headings.
 */
add_filter(
	'manage_media_columns',
	function( $posts_columns ) {
		$posts_columns = nsp_add_array_item_to_position( $posts_columns, array( 'nsp_id' => esc_html__( 'ID', 'nsp' ) ), 1 );

		$posts_columns['dimensions'] = esc_html__( 'Dimensions', 'nsp' );
		$posts_columns['filesize']   = esc_html__( 'File Size', 'nsp' );

		return $posts_columns;
	},
	11
);

/**
 * Add custom media column content.
 *
 * @since 1.0.0
 *
 * @param string $column_name The name of the column to display.
 * @param int    $post_id The ID of the post entry.
 */
add_action(
	'manage_media_custom_column',
	function( $column_name, $post_id ) {
		if ( 'nsp_id' === $column_name ) {
			echo absint( $post_id );
		}

		if ( 'dimensions' === $column_name ) {
			if ( wp_attachment_is_image( $post_id ) ) {
				list($url, $width, $height) = wp_get_attachment_image_src( $post_id, 'full' );
				printf( '%d &times; %d', (int) $width, (int) $height );
			}
		}

		if ( 'filesize' === $column_name ) {
			$attached_file = get_attached_file( $post_id );

			if ( file_exists( $attached_file ) ) {
				$filesize = filesize( $attached_file );
				echo esc_attr( size_format( $filesize, 2 ) );
			}
		}
	},
	11,
	2
);

/**
 * Render image sizes table.
 *
 * @since 1.0.0
 */
function nsp_render_media_image_sizes_field() {
	$details = nsp_get_image_sizes();
	if ( ! is_array( $details ) || 0 === count( $details ) ) {
		return;
	}

	echo '<table>';
	?>
		<tr>
			<th>Sn</th>
			<th>Image</th>
			<th>Dimension</th>
			<th>Crop</th>
		</tr>

		<?php $cnt = 1; ?>

		<?php foreach ( $details as $key => $item ) : ?>

			<tr>
				<td><?php echo absint( $cnt ); ?></td>
				<td><?php echo esc_html( $key ); ?></td>
				<td><?php echo sprintf( esc_html__( '%1$s X %2$s', 'nsp' ), absint( $item['width'] ), absint( $item['height'] ) ); ?></td>
				<td><?php echo ( $item['crop'] ) ? 'Y' : ''; ?></td>
			</tr>

			<?php ++$cnt; ?>

		<?php endforeach; ?>

	<?php
	echo '</table>';
}

/**
 * Add image size table in media settings page.
 *
 * @since 1.0.0
 */
add_action(
	'admin_init',
	function() {
		register_setting( 'media', 'nsp_media_table_field' );

		add_settings_section( 'nsp_media_section_image_sizes', esc_html__( 'Image Sizes', 'nsp' ), function(){}, 'media' );

		add_settings_field( 'nsp_media_table_field', esc_html__( 'Sizes', 'nsp' ), 'nsp_render_media_image_sizes_field', 'media', 'nsp_media_section_image_sizes' );
	}
);
