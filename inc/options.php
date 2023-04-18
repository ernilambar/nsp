<?php
/**
 * Options
 *
 * @package NSP
 */

use Nilambar\Optioner\Optioner;

/**
 * Register plugin options.
 *
 * @since 1.0.0
 */
add_action(
	'optioner_admin_init',
	function() {
		$obj = new Optioner();

		$obj->set_page(
			array(
				'page_title'    => esc_html__( 'NSP Settings', 'nsp' ),
				/* translators: %s: version. */
				'page_subtitle' => sprintf( esc_html__( 'Version: %s', 'nsp' ), NSP_VERSION ),
				'menu_title'    => esc_html__( 'NSP Settings', 'nsp' ),
				'capability'    => 'manage_options',
				'menu_slug'     => 'nsp-settings',
				'option_slug'   => 'nsp_options',
			)
		);

		$obj->set_quick_links(
			array(
				array(
					'text' => 'Plugin Page',
					'url'  => 'https://github.com/ernilambar/nsp',
					'type' => 'primary',
				),
				array(
					'text' => 'Get Support',
					'url'  => 'https://github.com/ernilambar/nsp/issues',
					'type' => 'secondary',
				),
			)
		);

		// Tab: nsp_settings_tab.
		$obj->add_tab(
			array(
				'id'    => 'nsp_settings_tab',
				'title' => esc_html__( 'Settings', 'nsp' ),
			)
		);

		$all_items = nsp_get_options_items();

		foreach ( $all_items as $item ) {
			$obj->add_field(
				'nsp_settings_tab',
				array(
					'id'    => $item['id'],
					'type'  => 'toggle',
					'title' => $item['label'],
				)
			);
		}

		// Sidebar.
		$obj->set_sidebar(
			array(
				'render_callback' => function ( $obj ) {
					$obj->render_sidebar_box(
						array(
							'title'   => 'Help &amp; Support',
							'icon'    => 'dashicons-editor-help',
							'content' => '<h4>Questions, bugs or great ideas?</h4><p><a href="https://github.com/ernilambar/nsp/issues" target="_blank">Visit our plugin support page</a></p>',
						),
						$obj
					);
				},
			)
		);

		// Run now.
		$obj->run();
	}
);
