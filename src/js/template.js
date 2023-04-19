import $ from 'jquery';

$( function() {
	const $nspDialog = $( '#nsp-template-dialog' );

	const nspHandleTemplateUpdate = ( $opener, newFile ) => {
		$.ajax( {
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'nsp_update_template_file',
				file: newFile,
				pid: $opener.data( 'id' ),
			},
			complete( jqXHR ) {
				const response = JSON.parse( jqXHR.responseText );

				if ( true === response.success ) {
					const data = response.data;
					$opener.parent().find( '.template-file' ).html( data.file );
					$opener.parent().find( '.template-title' ).html( data.title );

					if ( '' === data.title ) {
						$opener.parent().find( '.template-file' ).addClass( 'error' );
					} else {
						$opener.parent().find( '.template-file' ).removeClass( 'error' );
					}
				}
			},
		} );
	};

	$( '.js-btn-template-switcher' ).on( 'click', function( e ) {
		e.preventDefault();

		const $opener = $( this );

		$nspDialog.data( 'opener', $opener ).dialog( {
			modal: true,
			draggable: false,
			resizable: false,
			closeOnEscape: true,
			minHeight: 200,
			open() {
				const currentTemplate = $opener.parent().find( '.template-file' ).text();

				if ( currentTemplate ) {
					$( this ).find( 'select' ).children( `option[value="${ currentTemplate }"]` ).prop( 'selected', true );
				} else {
					$( this ).find( 'select' ).children( 'option' ).prop( 'selected', false );
				}
			},
			buttons: [
				{
					text: 'Update',
					click() {
						const selectedFile = $( '#nsp-template-dialog' ).find( 'option:selected' ).val();
						nspHandleTemplateUpdate( $opener, selectedFile );
						$( this ).dialog( 'close' );
					},
				},
			],
		} );
	} );
} );
