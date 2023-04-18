<?php
/**
 * Debug log
 *
 * @package NSP
 */

/**
 * Register debug log page.
 *
 * @since 1.0.0
 */
function nsp_addons_register_debug_log_page() {
	add_management_page( 'Debug Log', 'Debug Log', 'manage_options', 'nsp-debug-log', 'nsp_addons_render_debug_log_page' );
}

add_action( 'admin_menu', 'nsp_addons_register_debug_log_page' );

/**
 * Render debug log page.
 *
 * @since 1.0.0
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
