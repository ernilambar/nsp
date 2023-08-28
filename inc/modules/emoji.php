<?php
/**
 * Disable emoji
 *
 * @package NSP_Addons
 */

/**
 * Disable the emoji's.
 *
 * @since 1.0.0
 */
add_action(
	'init',
	function () {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', 'nsp_disable_emoji_tinymce' );
		add_filter( 'wp_resource_hints', 'nsp_disable_emojis_remove_dns_prefetch', 10, 2 );
	}
);

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @since 1.0.0
 *
 * @param array $plugins Plugins.
 * @return array Difference betwen the two arrays.
 */
function nsp_disable_emoji_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}

	return array();
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @since 1.0.0
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array                 Difference betwen the two arrays.
 */
function nsp_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {

		// Strip out any URLs referencing the WordPress.org emoji location.
		$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
		foreach ( $urls as $key => $url ) {
			if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
				unset( $urls[ $key ] );
			}
		}
	}

	return $urls;
}
