<?php
/**
 * Templates
 *
 * @package NSP
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/screen.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

if ( ! function_exists( 'str_contains' ) ) {
	function str_contains( string $haystack, string $needle ) {
		return empty( $needle ) || strpos( $haystack, $needle ) !== false;
	}
}

/**
 * Register templates page.
 *
 * @since 1.0.0
 */
add_action(
	'admin_menu',
	function() {
		add_theme_page( 'Templates', 'Templates', 'manage_options', 'templates', 'nsp_addons_render_templates_page', 1 );
	}
);

function nsp_addons_render_templates_page() {
	$wp_list_table = new NSP_Templates_List();
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline">Themes</h1>
		<a href="<?php echo esc_url( admin_url( 'theme-install.php' ) ); ?>" class="hide-if-no-js page-title-action">Add New</a>

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
	'admin_enqueue_scripts',
	function( $hook ) {
		if ( 'appearance_page_templates' !== $hook ) {
			return;
		}

		add_thickbox();

		wp_enqueue_style( 'nsp-templates', NSP_URL . '/build/templates.css', array(), NSP_VERSION );
	}
);

class NSP_Templates_List extends WP_List_Table {
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'template',
				'plural'   => 'templates',
				'ajax'     => false,
			)
		);
	}

	function get_columns() {
		$columns = array(
			'cb'          => 'Checkbox',
			'screenshot'  => 'Image',
			'name'        => 'Name',
			'description' => 'Description',
		);

		return $columns;
	}

	function column_cb( $item ) {
		return sprintf(
			'<label class="screen-reader-text" for="stylesheet_' . $item['ID'] . '">' . sprintf( __( 'Select %s', 'nsp' ), $item['name'] ) . '</label>'
			. "<input type='checkbox' name='stylesheets[]' id='stylesheet_{$item['ID']}' value='{$item['ID']}' />"
		);
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'name':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );
		}
	}

	function column_description( $item ) {
		$output = '';

		$active_theme = get_option( 'stylesheet' );

		$output .= '<div class="template-description">';
		$output .= '<span>' . wp_trim_words( $item['description'], 30, '&hellip;' ) . '</span>';
		$output .= '</div><!-- .template-description -->';

		$output .= $this->get_template_meta( $item );

		if ( $item['stylesheet'] === $active_theme ) {
			$output .= $this->get_template_links( $item );
		}

		return $output;
	}

	function column_screenshot( $item ) {
		$link_open  = '';
		$link_close = '';

		$image_url = NSP_URL . '/src/img/no-image.png';

		if ( 0 !== strlen( $item['screenshot'] ) ) {
			$image_url = add_query_arg(
				array(
					'TB_iframe' => true,
					'width'     => 1200,
					'height'    => 900,
				),
				$item['screenshot']
			);

			$link_open  = '<a href="' . esc_url( $image_url ) . '" class="thickbox">';
			$link_close = '</a>';
		}

		echo '<figure>';
		echo $link_open;
		echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $item['name'] ) . '" /></a>';
		echo $link_close;
		echo '</figure>';
	}

	private function get_template_meta( $item ) {
		$output = '';

		$output .= '<div class="template-info">';

		// Version.
		$output .= '<span>' . 'Version ' . esc_html( $item['version'] ) . '</span>';

		// Author.
		$author = '';

		if ( 0 !== strlen( $item['author'] ) ) {
			if ( 0 !== strlen( $item['author_uri'] ) ) {
				$author = '<a href="' . esc_url( $item['author_uri'] ) . '">' . esc_html( $item['author'] ) . '</a>';
			} else {
				$author = esc_html( $item['author'] );
			}
		}

		if ( 0 !== strlen( $author ) ) {
			$output .= ' | By ' . $author;
		}

		// View Details.
		$details = '';

		if ( 0 !== strlen( $item['theme_uri'] ) ) {
			$details = '<a href="' . esc_url( $item['theme_uri'] ) . '">View details</a>';
		}

		if ( 0 !== strlen( $details ) ) {
			$output .= ' | ' . $details;
		}

		$output .= '</div><!-- .template-info -->';

		return $output;
	}

	private function get_template_links( $item ) {
		$output = '';

		$links = $this->get_template_link_items( $item );

		if ( count( $links ) > 0 ) {
			$output .= '<div class="template-links">';

			$output .= implode( ' | ', $links );

			$output .= '</div><!-- .template-links -->';
		}

		return $output;
	}

	private function get_template_link_items( $item ) {
		$output = array();

		if ( true === $item['block_theme'] ) {
			$output[] = '<a href="' . esc_url( admin_url( '/site-editor.php' ) ) . '">Editor</a>';
		} else {
			global $submenu;
			$appearance_items = $submenu['themes.php'];

			$all_files = wp_list_pluck( $appearance_items, 2 );

			$new_items = array_filter(
				$all_files,
				function( $item ) {
					return ! str_contains( $item, '.php' );
				}
			);

			$valid_items = array_filter(
				$new_items,
				function( $item ) {
					return ! in_array( $item, array( 'templates', 'custom-header', 'custom-background' ), true );
				}
			);

			$protocol = is_ssl() ? 'https://' : 'http://';

			$current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

			// Add URLs.
			$output[] = '<a href="' . esc_url( add_query_arg( array( 'return' => $current_url ), wp_customize_url() ) ) . '">Customize</a>';
			$output[] = '<a href="' . esc_url( admin_url( '/widgets.php' ) ) . '">Widgets</a>';
			$output[] = '<a href="' . esc_url( admin_url( '/nav-menus.php' ) ) . '">Menus</a>';
			$output[] = '<a href="' . esc_url(
				add_query_arg(
					array(
						'return'             => $current_url,
						'autofocus[control]' => 'header_image',
					),
					wp_customize_url()
				)
			) . '">Header</a>';
			$output[] = '<a href="' . esc_url(
				add_query_arg(
					array(
						'return'             => $current_url,
						'autofocus[control]' => 'background_image',
					),
					wp_customize_url()
				)
			) . '">Background</a>';

			if ( is_array( $valid_items ) && 0 !== count( $valid_items ) ) {
				foreach ( $valid_items as $slug ) {
					$new_menu_items = wp_list_filter( $appearance_items, array( '2' => $slug ) );

					if ( 0 !== count( $new_menu_items ) ) {
						$new_menu_item = reset( $new_menu_items );

						$output[] = '<a href="' . esc_url( add_query_arg( array( 'page' => $slug ), admin_url( '/themes.php' ) ) ) . '">' . esc_html( strip_tags( $new_menu_item[0] ) ) . '</a>';
					}
				}
			}
		}

		return $output;
	}

	protected function column_name( $item ) {
		$active_theme = get_option( 'stylesheet' );

		$admin_page_url = admin_url( 'themes.php?page=templates' );

		if ( $item['stylesheet'] === $active_theme ) {
			$row_value = '<strong>' . $item['name'] . '</strong>';
		} else {
			$row_value = '<span>' . $item['name'] . '</span>';
		}

		$actions = array();

		if ( $item['stylesheet'] !== $active_theme ) {
			// Add activate link.
			$params = array(
				'action'     => 'activate',
				'stylesheet' => $item['stylesheet'],
				'_wpnonce'   => wp_create_nonce( 'activate_theme' ),
			);

			$actions['activate'] = '<a href="' . esc_url( add_query_arg( $params, $admin_page_url ) ) . '">Activate</a>';

			// Add delete link.
			$params = array(
				'action'     => 'delete',
				'stylesheet' => $item['stylesheet'],
				'_wpnonce'   => wp_create_nonce( 'delete_theme' ),
			);

			$actions['delete'] = '<a href="' . esc_url( add_query_arg( $params, $admin_page_url ) ) . '">Delete</a>';
		}

		$output = $row_value;

		if ( 0 !== count( $actions ) ) {
			$output .= $this->row_actions( $actions, true );
		}

		return $output;
	}

	public function handle_table_actions() {
		$current_action = $this->current_action();
	}

	function prepare_items() {
		$data = array();

		$this->handle_table_actions();

		$all_themes = wp_get_themes();

		if ( ! empty( $all_themes ) ) {
			foreach ( $all_themes as $theme ) {
				$item = array();

				$item['name']        = $theme->get( 'Name' );
				$item['version']     = $theme->get( 'Version' );
				$item['theme_uri']   = $theme->get( 'ThemeURI' );
				$item['author']      = $theme->get( 'Author' );
				$item['author_uri']  = $theme->get( 'AuthorURI' );
				$item['description'] = $theme->get( 'Description' );
				$item['template']    = $theme->get( 'Template' );
				$item['status']      = $theme->get( 'Status' );
				$item['stylesheet']  = $theme->get_stylesheet();
				$item['screenshot']  = $theme->get_screenshot();
				$item['parent']      = $theme->parent();
				$item['ID']          = $item['stylesheet'];
				$item['block_theme'] = $theme->is_block_theme() ? true : false;

				$data[] = $item;
			}
		}

		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = $data;
	}

	public function get_views() {
		$views = array();

		$total_count = count( $this->items );

		$views['all'] = '<a href="' . esc_url( admin_url( 'themes.php?page=templates' ) ) . '" class="current">All <span class="count">(' . absint( $total_count ) . ')</span></a>';

		return $views;
	}

	public function get_bulk_actions() {
		$actions = array(
			'bulk-delete' => 'Delete',
		);

		return $actions;
	}
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
					NSPNotify::add( 'New theme activated.', 'success' );
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

					NSPNotify::add( 'Theme deleted successfully.', 'success' );
					delete_theme( sanitize_text_field( wp_unslash( $cur_stylesheet ) ) );
					wp_safe_redirect( admin_url( '/themes.php?page=templates' ) );
					exit;
				}
			}

			// Bulk delete.
			if ( ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'bulk-delete' ) || ( isset( $_REQUEST['action2'] ) && $_REQUEST['action2'] === 'bulk-delete' ) ) {
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

					NSPNotify::add( 'Themes deleted successfully.', 'success' );
					wp_safe_redirect( admin_url( '/themes.php?page=templates' ) );
					exit;
				}
			}
		}
	}
);
