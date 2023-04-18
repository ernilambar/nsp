<?php
/**
 * Admin column: template
 *
 * @package NSP
 */

add_action(
	'init',
	function() {
		add_filter(
			'manage_edit-page_columns',
			function( $columns ) {
				$columns['nsp_template'] = esc_html__( 'Template' );
				return $columns;
			},
			10,
			1
		);

		add_action(
			'manage_page_posts_custom_column',
			function( $column, $post_id ) {
				if ( 'nsp_template' === $column ) {
					echo '<a href="#" class="js-btn-template-switcher" data-id="' . absint( $post_id ) . '"><span class="dashicons dashicons-update"></span></a>&nbsp;';

					$template_file  = get_page_template_slug( $post_id );
					$template_title = nsp_addons_get_template_title( $template_file );

					echo '<span class="template-file ' . ( empty( $template_title ) ? 'error' : '' ) . '">' . esc_attr( $template_file ) . '</span>';
					echo '&nbsp;<span class="template-title" style="font-weight:600; display:block;">' . esc_attr( $template_title ) . '</span>';
				}
			},
			10,
			2
		);

		add_filter(
			'manage_edit-page_sortable_columns',
			function( $cols ) {
				$cols['nsp_template'] = 'nsp_template';
				return $cols;
			}
		);
	},
	99
);

add_action(
	'admin_footer',
	function() {
		?>
		<div id="nsp-template-dialog" title="Select Template" style="display:none;">
			<?php $all_templates = wp_get_theme()->get_page_templates(); ?>

			<select name="nsp-select-template" id="nsp-select-template">
				<option value="">&mdash; Select &mdash;</option>

				<?php foreach ( $all_templates as $template_file => $template_title ) : ?>
					<option value="<?php echo esc_attr( $template_file ); ?>"><?php echo esc_html( $template_title ); ?></option>
				<?php endforeach; ?>

			</select><!-- #nsp-select-template -->
		</div><!-- #nsp-template-dialog -->
		<?php
	}
);
