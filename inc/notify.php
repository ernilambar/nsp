<?php
/**
 * NSPNotify
 *
 * @package NSP
 */

/**
 * NSPNotify class.
 *
 * @since 1.0.0
 */
class NSPNotify {
	const NOTICES_KEY = 'nsp_notices';

	/**
	 * Init.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_action( 'admin_notices', array( __CLASS__, 'display_notices' ) );
	}

	/**
	 * Show notices.
	 *
	 * @since 1.0.0
	 */
	public static function display_notices() {
		$notices = self::get_notices();
		if ( empty( $notices ) ) {
			return;
		}

		foreach ( $notices as $type => $messages ) {
			foreach ( $messages as $message ) {
				printf( '<div class="notice notice-%1$s is-dismissible"><p>%2$s</p></div>', $type, $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		// Remove notices.
		self::update_notices( array() );
	}

	/**
	 * Show notices.
	 *
	 * @since 1.0.0
	 *
	 * @return array|void Stored notices.
	 */
	private static function get_notices() {
		$notices = get_option( self::NOTICES_KEY, array() );

		return $notices;
	}

	/**
	 * Saves notices.
	 *
	 * @since 1.0.0
	 *
	 * @param array $notices Notices.
	 */
	private static function update_notices( array $notices ) {
		update_option( self::NOTICES_KEY, $notices );
	}

	/**
	 * Add notice item.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Message.
	 * @param string $type Message type.
	 */
	private static function add_notice( $message, $type = 'success' ) {
		$notices = self::get_notices();

		$notices[ $type ][] = $message;

		self::update_notices( $notices );
	}

	/**
	 * Add notice.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Message.
	 * @param string $type Message type.
	 */
	public static function add( $message, $type = 'success' ) {
		self::add_notice( $message, $type );
	}
}
