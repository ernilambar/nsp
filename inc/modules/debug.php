<?php
/**
 * Debug functions
 *
 * @package NSP
 */

/**
 * Displays formatted output.
 *
 * @since 1.0.0
 *
 * @param mixed  $str What do you want to display.
 * @param string $title Title.
 * @param bool   $die Enable/disable die.
 * @param bool   $style Enable/disable styling.
 * @param bool   $html Encoded html content.
 * @param bool   $dump Enable/disable dump.
 */
function nspre( $str, $title = '', $die = false, $style = true, $html = false, $dump = false ) {
	$main_style = ( true === $style ) ? 'style="border:1px solid red; background-color:#eee;margin:3px;height:auto; margin-left:3%; margin-bottom:10px; overflow:hidden; width:94%;padding:5px; color:#000; text-align:left; white-space: pre-wrap; white-space: -moz-pre-wrap !important; word-wrap: break-word; white-space: -o-pre-wrap; clear: both; white-space: -pre-wrap;"' : '';

	$title_html = '';

	if ( $title ) {
		$title_style = ( true === $style ) ? 'style="border-bottom:1px solid red; color:#f00;font-weight:bold;padding:2px;margin:0px;text-align:left;margin-bottom:5px;"' : '';
		$title_html  = "<p {$title_style}>{$title}</p>";
	}

	if ( ! $html ) {
		if ( ! $dump ) {
			$content = print_r( $str, true );
		} else {
			ob_start();
			var_dump( $str );
			$content = ob_get_clean();
		}
	} elseif ( ! $dump ) {
		if ( ! is_array( $str ) ) {
			$content = print_r( htmlentities( $str ), true );
		} else {
			array_walk_recursive(
				$str,
				function ( &$value ) {
					$value = htmlentities( $value );
				}
			);
			$content = print_r( $str, true );
		}
	} else {
		ob_start();
		var_dump( htmlentities( $str ) );
		$content = ob_get_clean();
	}

	echo "<pre {$main_style}>{$title_html}{$content}</pre>";

	if ( $die ) {
		die;
	}
}

/**
 * Dump given content.
 *
 * @since 1.0.0
 *
 * @param mixed  $str What do you want to display.
 * @param string $title Title.
 * @param bool   $die Enable/disable die.
 * @param bool   $style Enable/disable styling.
 * @param bool   $html Encoded html content.
 */
function nsdump( $str, $title = '', $die = false, $style = true, $html = false ) {
	nspre( $str, $title, $die, $style, $html, $dump = true );
}

/**
 * Print last SQL query in WordPress.
 *
 * @since 1.0.0
 *
 * @param string $title Title.
 * @param bool   $die Enable/disable die.
 */
function nssql( $title = '', $die = false ) {
	nspre( $GLOBALS['wp_query']->request, $title, $die );
}

/**
 * Print SQL of custom query.
 *
 * @since 1.0.0
 *
 * @param WP_Query $q Query.
 */
function nsquery( $q ) {
	nspre( $q->request );
}

/**
 * Print and die.
 *
 * @since 1.0.0
 *
 * @param mixed  $str What do you want to display.
 * @param string $title Title.
 */
function nspd( $str, $title = '' ) {
	nspre( $str, $title, true );
}

/**
 * Dump and die.
 *
 * @since 1.0.0
 *
 * @param mixed  $str What do you want to display.
 * @param string $title Title.
 */
function nsdd( $str, $title = '' ) {
	nsdump( $str, $title, true );
}

/**
 * Clean print.
 *
 * @since 1.0.0
 *
 * @param mixed  $str What do you want to display.
 * @param string $title Title.
 */
function nspc( $str, $title = '' ) {
	nspre( $str, $title, $die = false, $style = false, $html = false, $dump = false );
}

/**
 * Clean dump.
 *
 * @since 1.0.0
 *
 * @param mixed  $str What do you want to display.
 * @param string $title Title.
 */
function nsdc( $str, $title = '' ) {
	nsdump( $str, $title, $die = false, $style = false, $html = false );
}

/**
 * HTML.
 *
 * @since 1.0.0
 *
 * @param mixed  $str What do you want to display.
 * @param string $title Title.
 * @param bool   $die Enable/disable die.
 * @param bool   $style Enable/disable styling.
 */
function nshtml( $str, $title = '', $die = false, $style = true ) {
	nspre( $str, $title, $die, $style, $html = true );
}

/**
 * Log message.
 *
 * @since 1.0.0
 *
 * @param mixed $message What do you want to log.
 */
function nslog( $message ) {
	if ( WP_DEBUG === true ) {
		if ( is_array( $message ) || is_object( $message ) ) {
			error_log( print_r( $message, true ) );
		} else {
			error_log( $message );
		}
	}
}

/**
 * Pre admin.
 *
 * @since 1.0.0
 *
 * @param mixed  $str What do you want to display.
 * @param string $title Title.
 */
function nspa( $str, $title = '' ) {
	new NSP_Message( $str, $title );
}

/**
 * Class.
 *
 * @since 1.0.0
 */
class NSP_Message {

	/**
	 * Message.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $message;

	/**
	 * Title.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $title;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Message.
	 * @param string $title Title.
	 */
	public function __construct( $message, $title = '' ) {
		$this->message = $message;
		$this->title   = $title;

		add_action( 'admin_notices', array( $this, 'render' ) );
	}

	/**
	 * Render.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		echo '<div class="notice">';
		nspre( $this->message, $this->title );
		echo '</div>';
	}
}

/**
 * Display debug info in footer.
 *
 * @since 1.0.0
 */
add_action(
	'wp_footer',
	function() {
		// Start HTML comment.
		echo '<!--' . "\n";

		do_action( 'nsp_debug_info_section' );

		// Close HTML comment.
		echo "\n" . '-->';
	},
	9999
);


/**
 * Display misc info in footer.
 *
 * @since 1.0.0
 */
add_action(
	'nsp_debug_info_section',
	function() {
		global $content_width;
		global $template;

		echo sprintf( 'Template: %s', $template ) . "\n";
		echo sprintf( 'Content Width: %s', $content_width ) . "\n";
		echo "\n";
	}
);

/**
 * Display templates info in footer.
 *
 * @since 1.0.0
 */
add_action(
	'nsp_debug_info_section',
	function() {
		$included_files = get_included_files();
		$stylesheet_dir = str_replace( '\\', '/', get_stylesheet_directory() );
		$template_dir   = str_replace( '\\', '/', get_template_directory() );

		foreach ( $included_files as $key => $path ) {
			$path = str_replace( '\\', '/', $path );

			if ( false === strpos( $path, $stylesheet_dir ) && false === strpos( $path, $template_dir ) ) {
				unset( $included_files[ $key ] );
			}
		}

		array_walk(
			$included_files,
			function( &$value, $key ) {
				$value = str_replace( ABSPATH . 'wp-content/', '', $value );
			}
		);

		echo "\n" . 'Included files:' . "\n";
		print_r( $included_files );
	},
	99
);
