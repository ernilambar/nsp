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
add_action(
	'admin_menu',
	function() {
		add_management_page( esc_html__( 'Debug Log', 'nsp' ), esc_html__( 'Debug Log', 'nsp' ), 'manage_options', 'nsp-debug-log', 'nsp_addons_render_debug_log_page' );
	}
);

/**
 * Render debug log page.
 *
 * @since 1.0.0
 */
function nsp_addons_render_debug_log_page() {
	?>
	<div class="wrap wrap-debug-log">

		<h2><?php esc_html_e( 'Debug Log', 'nsp' ); ?></h2>

		<?php if ( nsp_debug_log_exists() ) : ?>
			<ul class="debug-log-links">
				<li><a href="<?php echo esc_url( home_url( '/?log' ) ); ?>" target="_blank">View Log</a></li>
				<li><a href="<?php echo esc_url( admin_url() . '?nsp-delete-log=1&redir=' . admin_url( basename( $_SERVER['REQUEST_URI'] ) ) ); ?>">Delete Log</a></li>
			</ul>
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
	</div><!-- .wrap -->
	<?php
}

add_action(
	'admin_init',
	function() {
		if ( isset( $_REQUEST['nsp-delete-log'] ) && 1 === absint( $_REQUEST['nsp-delete-log'] ) ) {
			$debug_file = ABSPATH . 'wp-content/debug.log';

			if ( file_exists( $debug_file ) ) {
				$file = new NSP_File();

				$file->load( $debug_file )->delete();
			}

			wp_safe_redirect( $_REQUEST['redir'] );
			exit;
		}
	}
);

add_action(
	'init',
	function() {
		if ( isset( $_REQUEST['log'] ) ) {
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
