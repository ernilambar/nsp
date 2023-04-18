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

