<?php
/**
 * NSP_Templates_List
 *
 * @package NSP
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/screen.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

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
			'cb'          => esc_html__( 'Checkbox', 'nsp' ),
			'screenshot'  => esc_html__( 'Image', 'nsp' ),
			'name'        => esc_html__( 'Name', 'nsp' ),
			'description' => esc_html__( 'Description', 'nsp' ),
		);

		return $columns;
	}

	function column_cb( $item ) {
		return sprintf(
			'<label class="screen-reader-text" for="stylesheet_' . esc_attr( $item['ID'] ) . '">' . sprintf( __( 'Select %s', 'nsp' ), $item['name'] ) . '</label>'
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

		$image_url = nsp_get_placeholder_image_url();

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
		$output .= '<span>' . sprintf( esc_html__( 'Version %s', 'nsp' ), esc_html( $item['version'] ) ) . '</span>';

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
			$output .= ' | ' . sprintf( esc_html__( 'By %s', 'nsp' ), $author );
		}

		// View Details.
		$details = '';

		if ( 0 !== strlen( $item['theme_uri'] ) ) {
			$details = '<a href="' . esc_url( $item['theme_uri'] ) . '">' . esc_html__( 'View Details', 'nsp' ) . '</a>';
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
			$output[] = '<a href="' . esc_url( admin_url( '/site-editor.php' ) ) . '">' . esc_html__( 'Editor', 'nsp' ) . '</a>';
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
			$output[] = '<a href="' . esc_url( add_query_arg( array( 'return' => $current_url ), wp_customize_url() ) ) . '">' . esc_html__( 'Customize', 'nsp' ) . '</a>';
			$output[] = '<a href="' . esc_url( admin_url( '/widgets.php' ) ) . '">' . esc_html__( 'Widgets', 'nsp' ) . '</a>';
			$output[] = '<a href="' . esc_url( admin_url( '/nav-menus.php' ) ) . '">' . esc_html__( 'Menus', 'nsp' ) . '</a>';
			$output[] = '<a href="' . esc_url(
				add_query_arg(
					array(
						'return'             => $current_url,
						'autofocus[control]' => 'header_image',
					),
					wp_customize_url()
				)
			) . '">' . esc_html__( 'Header', 'nsp' ) . '</a>';
			$output[] = '<a href="' . esc_url(
				add_query_arg(
					array(
						'return'             => $current_url,
						'autofocus[control]' => 'background_image',
					),
					wp_customize_url()
				)
			) . '">' . esc_html__( 'Background', 'nsp' ) . '</a>';

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

			$actions['activate'] = '<a href="' . esc_url( add_query_arg( $params, $admin_page_url ) ) . '">' . esc_html__( 'Activate', 'nsp' ) . '</a>';

			// Add delete link.
			$params = array(
				'action'     => 'delete',
				'stylesheet' => $item['stylesheet'],
				'_wpnonce'   => wp_create_nonce( 'delete_theme' ),
			);

			$actions['delete'] = '<a href="' . esc_url( add_query_arg( $params, $admin_page_url ) ) . '">' . esc_html__( 'Delete', 'nsp' ) . '</a>';
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

		$views['all'] = '<a href="' . esc_url( admin_url( 'themes.php?page=templates' ) ) . '" class="current">' . sprintf( esc_html__( 'All %s', 'nsp' ), '<span class="count">(' . absint( $total_count ) . ')</span>' ) . '</a>';

		return $views;
	}

	public function get_bulk_actions() {
		$actions = array(
			'bulk-delete' => esc_html__( 'Delete', 'nsp' ),
		);

		return $actions;
	}
}
