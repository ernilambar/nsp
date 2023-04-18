<?php
/**
 * Debug log
 *
 * @package NSP
 */

class NSP_File {

	/**
	 * Use to store fOpen connection
	 *
	 * @type bool
	 * @access private
	 */
	private $handle;

	/**
	 * To store the file URL/location
	 *
	 * @type string
	 * @access private
	 */
	private $file;

	/**
	 * Used to initialize the file
	 *
	 * @access public
	 * @param string $file_url
	 * File location/url
	 * @example 'dir/mytext.txt'
	 * @return bool
	 */
	public function load( $file_url ) {
		$this->file = $file_url;
		if ( $this->handle = fopen( $file_url, 'c+' ) ) {
			return $this;
		}
	}

	/**
	 * Used to write inside the file,
	 * If file doesn't exists it will create it
	 *
	 * @access public
	 * @param string $text
	 * The text which we have to write
	 * @example 'My text here in the file.';
	 * @return bool
	 */
	public function write( $text ) {
		if ( fwrite( $this->handle, $text ) ) {
			fclose( $this->handle );
			return true;
		} else {
			fclose( $this->handle );
			return false;
		}
	}

	/**
	 * To read the contents of the file.
	 *
	 * @access public
	 * @param bool $nl2br
	 * By default set to false, if set to true will return
	 * the contents of the file by preserving the data.
	 * @example (true)
	 * @return string|bool
	 */
	public function read( $nl2br = false ) {
		$file_size = filesize( $this->file );

		if ( 0 === $file_size ) {
			return false;
		}

		$read = fread( $this->handle, $file_size );

		if ( $read ) {
			if ( $nl2br == true ) {
				fclose( $this->handle );
				return nl2br( $read );
			}

			fclose( $this->handle );
			return $read;
		} else {
			fclose( $this->handle );
			return false;
		}
	}

	/**
	 * Use to delete the file.
	 *
	 * @access public
	 * @return bool
	 */
	public function delete() {
		fclose( $this->handle );

		if ( file_exists( $this->file ) ) {
			if ( unlink( $this->file ) ) {
				return true;
			} else {
				return false;
			}
		}
	}
}

/**
 * Register debug log page.
 *
 * @since 1.0.3
 */
function nsp_addons_register_debug_log_page() {
	add_management_page( 'Debug Log', 'Debug Log', 'manage_options', 'nsp-debug-log', 'nsp_addons_render_debug_log_page' );
}

add_action( 'admin_menu', 'nsp_addons_register_debug_log_page' );

/**
 * Render debug log page.
 *
 * @since 1.0.3
 */
function nsp_addons_render_debug_log_page() {
	?>
	<div class="wrap">
		<style>
			.warning {
				color: #ffb900;
			}
			.error {
				color: #dc3232;
			}
			.trace {
				display: block;
				margin-top: 10px;
				margin-bottom: -15px;
			}
			.timestamp {
				color: #533ebb;
				display: block;
				margin-top: 15px;
				font-weight: 600;
			}
		</style>
		<h2>Debug Log</h2>
		<?php if ( nsp_debug_log_exists() ) : ?>
			<p>
				<a href="<?php echo esc_url( home_url( '/?debug_log' ) ); ?>" target="_blank">View Log</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo esc_url( admin_url() . '?nsp-delete-log=1&redir=' . admin_url( basename( $_SERVER['REQUEST_URI'] ) ) ); ?>">Delete Log</a>
			</p>
		<?php endif; ?>

		<?php
		$debug_file = ABSPATH . 'wp-content/debug.log';

		if ( is_readable( $debug_file ) ) {
			$file = new NSP_File();

			$read = $file->load( $debug_file )->read( true );

			if ( $read ) {
				echo '<div id="log">';
				echo $read;
				echo '</div>';
			} else {
				echo 'Log file is empty.';
			}
		}
		?>
		<script>
			window.addEventListener('DOMContentLoaded', (event) => {
				var e = document.getElementById('log');
				var newContent = e.innerHTML.replace(/PHP Warning/g, '<span class="warning">$&</span>');
				newContent = newContent.replace(/PHP Fatal error/g, '<span class="error">$&</span>');
				newContent = newContent.replace(/Stack trace:/g, '<span class="trace">$&</span>');
				newContent = newContent.replace(/\[(.*?)UTC]/g, '<span class="timestamp">$&</span>');
				e.innerHTML = newContent;
			});
		</script>

	</div><!-- .wrap -->
	<?php
}

add_action(
	'admin_init',
	function() {
		if ( isset( $_REQUEST['nsp-delete-log'] ) && 1 === absint( $_REQUEST['nsp-delete-log'] ) ) {
			$debug_file = ABSPATH . 'wp-content/debug.log';

			$file = new NSP_File();

			$file->load( $debug_file )->delete();

			wp_safe_redirect( $_REQUEST['redir'] );
			exit;
		}
	}
);

add_action(
	'init',
	function() {
		if ( isset( $_REQUEST['debug_log'] ) ) {
			if ( nsp_debug_log_exists() ) {
				?>
				<style>
					* {
						margin: 0;
						padding: 0;
						box-sizing: border-box;
					}

					body {
						background: #f1f1f1;
						color: #444;
						font-family: Consolas, Monaco, monospace;
						font-size: 13px;
						margin: 10px;
					}

					.warning {
						color: #ffb900;
					}
					.error {
						color: #dc3232;
					}
					.trace {
						display: block;
						margin-top: 10px;
						margin-bottom: -15px;
					}
					.timestamp {
						color: #533ebb;
						display: block;
						margin-top: 15px;
						font-weight: 600;
					}

				</style>
				<script>
					window.addEventListener('DOMContentLoaded', (event) => {
						var e = document.getElementById('log');
						var newContent = e.innerHTML.replace(/PHP Warning/g, '<span class="warning">$&</span>');
						newContent = newContent.replace(/PHP Fatal error/g, '<span class="error">$&</span>');
						newContent = newContent.replace(/Stack trace:/g, '<span class="trace">$&</span>');
						newContent = newContent.replace(/\[(.*?)UTC]/g, '<span class="timestamp">$&</span>');
						e.innerHTML = newContent;
					});
				</script>
				<?php
				$debug_file = ABSPATH . 'wp-content/debug.log';

				$file = new NSP_File();

				$read = $file->load( $debug_file )->read( true );

				if ( $read ) {
					echo '<div id="log">';
					echo $read;
					echo '</div>';
				} else {
					echo 'Log file is empty.';
				}
			}

			exit;
		}
	}
);
