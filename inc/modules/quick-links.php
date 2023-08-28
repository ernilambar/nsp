<?php
/**
 * Quick links
 *
 * @package NSP
 */

/**
 * Add menu items in admin bar.
 *
 * @since 1.0.0
 *
 * @param WP_Admin_Bar $admin_bar WP_Admin_Bar object.
 */
add_action(
	'admin_bar_menu',
	function ( $admin_bar ) {
		// View Site link.
		if ( is_admin() ) {
			$admin_bar->add_menu(
				array(
					'id'    => 'nsp-view-site',
					'title' => esc_html__( 'View Site', 'nsp' ),
					'href'  => home_url( '/' ),
					'meta'  => array( 'target' => '_blank' ),
				)
			);

			$current_theme = wp_get_theme();

			if ( $current_theme->is_block_theme() ) {
				$admin_bar->add_menu(
					array(
						'id'    => 'nsp-site-editor',
						'title' => esc_html__( 'Site Editor', 'nsp' ),
						'href'  => admin_url( '/site-editor.php' ),
					)
				);
			} else {
				$admin_bar->add_menu(
					array(
						'id'    => 'nsp-view-customize',
						'title' => esc_html__( 'Customize', 'nsp' ),
						'href'  => wp_customize_url(),
					)
				);
			}
		}

		// Quick front page edit link.
		if ( current_user_can( 'manage_options' ) ) {
			$page_on_front = get_option( 'page_on_front' );
			if ( absint( $page_on_front ) > 0 ) {
				$page_menu_item = array(
					'id'    => 'nsp-homepage',
					'title' => esc_html__( 'Front Page', 'nsp' ),
					'href'  => '#',
				);

				$page_menu_item['href'] = get_edit_post_link( absint( $page_on_front ) );

				$admin_bar->add_menu( $page_menu_item );
			}
		}

		if ( is_admin() && current_user_can( 'manage_options' ) ) {
			// Theme switcher.
			$current_theme = wp_get_theme();
			$all_themes    = wp_get_themes();

			$admin_bar->add_menu(
				array(
					'id'    => 'nsp-theme',
					'title' => $current_theme->get( 'Name' ),
					'href'  => admin_url( 'themes.php?theme=' . get_stylesheet() ),
				)
			);

			$redirect_to = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

			if ( ! empty( $all_themes ) ) {
				foreach ( $all_themes as $key => $theme ) {

					// Add menu items.
					$admin_bar->add_menu(
						array(
							'id'     => 'nsp-theme-' . $key,
							'parent' => 'nsp-theme',
							'title'  => $theme->get( 'Name' ),
							'href'   => add_query_arg(
								array(
									'nsp-switch' => $key,
									'nsp-go'     => esc_url( $redirect_to ),
								),
								admin_url()
							),
						)
					);
				} // End foreach.
			} // End if.
		}
	},
	110
);

/**
 * Switch theme.
 *
 * @since 1.0.0
 */
add_action(
	'init',
	function () {
		if ( isset( $_GET['nsp-switch'] ) && ! empty( $_GET['nsp-switch'] ) ) {
			$theme = wp_get_theme( $_GET['nsp-switch'] );

			$go_url = admin_url( 'themes.php?activated=true' );

			if ( isset( $_REQUEST['nsp-go'] ) && ! empty( $_REQUEST['nsp-go'] ) ) {
				$go_url = $_REQUEST['nsp-go'];
			}

			if ( false === $theme->errors() ) {
				switch_theme( $theme->get_stylesheet() );
				wp_safe_redirect( $go_url );
				exit;
			}
		}
	}
);
