<?php
/**
 * Templates
 *
 * @package NSP
 */

/**
 * Register templates page.
 *
 * @since 1.0.0
 */
add_action(
	'admin_menu',
	function() {
		add_theme_page( esc_html__( 'Templates', 'nsp' ), esc_html__( 'Templates', 'nsp' ), 'manage_options', 'templates', 'nsp_addons_render_templates_page', 1 );
	}
);

/**
 * Render templates admin page.
 *
 * @since 1.0.0
 */
function nsp_addons_render_templates_page() {
	$wp_list_table = new NSP_Templates_List();
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Themes', 'nsp' ); ?></h1>
		<a href="<?php echo esc_url( admin_url( 'theme-install.php' ) ); ?>" class="hide-if-no-js page-title-action"><?php esc_html_e( 'Add New', 'nsp' ); ?></a>

		<hr class="wp-header-end">

		<form method="post" class="nsp-templates-form">
			<?php
			$wp_list_table->prepare_items();
			$wp_list_table->views();
			$wp_list_table->display();
			?>
		</form><!-- .nsp-templates-form -->

	</div><!-- .wrap -->
	<?php
}

add_action(
	'init',
	function() {
		if ( isset( $_GET['page'] ) && 'templates' === $_GET['page'] ) {
			$cur_action     = ( isset( $_GET['action'] ) && 0 !== strlen( $_GET['action'] ) ) ? sanitize_text_field( $_GET['action'] ) : '';
			$cur_stylesheet = ( isset( $_GET['stylesheet'] ) && 0 !== strlen( $_GET['stylesheet'] ) ) ? sanitize_text_field( $_GET['stylesheet'] ) : '';

			// Activate theme.
			if ( 'activate' === $cur_action && 0 !== strlen( $cur_stylesheet ) ) {
				$nonce = wp_unslash( $_REQUEST['_wpnonce'] );

				if ( wp_verify_nonce( $nonce, 'activate_theme' ) ) {
					NSP_Notify::add( 'New theme activated.', 'success' );
					switch_theme( sanitize_text_field( wp_unslash( $cur_stylesheet ) ) );
					wp_safe_redirect( admin_url( '/themes.php?page=templates' ) );
					exit;
				}
			}

			// Delete theme.
			if ( 'delete' === $cur_action && 0 !== strlen( $cur_stylesheet ) ) {
				$nonce = wp_unslash( $_REQUEST['_wpnonce'] );

				if ( wp_verify_nonce( $nonce, 'delete_theme' ) ) {
					if ( ! function_exists( 'delete_theme' ) ) {
						require_once ABSPATH . 'wp-admin/includes/file.php';
						require_once ABSPATH . 'wp-admin/includes/theme.php';
					}

					NSP_Notify::add( 'Theme deleted successfully.', 'success' );
					delete_theme( sanitize_text_field( wp_unslash( $cur_stylesheet ) ) );
					wp_safe_redirect( admin_url( '/themes.php?page=templates' ) );
					exit;
				}
			}

			// Bulk delete.
			if ( ( isset( $_REQUEST['action'] ) && 'bulk-delete' === $_REQUEST['action'] ) || ( isset( $_REQUEST['action2'] ) && 'bulk-delete' === $_REQUEST['action2'] ) ) {
				$nonce = wp_unslash( $_REQUEST['_wpnonce'] );

				if ( wp_verify_nonce( $nonce, 'bulk-templates' ) ) {
					$stylesheets = ( isset( $_REQUEST['stylesheets'] ) ) ? $_REQUEST['stylesheets'] : array();

					if ( ! function_exists( 'delete_theme' ) ) {
						require_once ABSPATH . 'wp-admin/includes/file.php';
						require_once ABSPATH . 'wp-admin/includes/theme.php';
					}

					if ( 0 !== count( $stylesheets ) ) {
						foreach ( $stylesheets as $t ) {
							delete_theme( $t );
						}
					}

					NSP_Notify::add( 'Themes deleted successfully.', 'success' );
					wp_safe_redirect( admin_url( '/themes.php?page=templates' ) );
					exit;
				}
			}
		}
	}
);
