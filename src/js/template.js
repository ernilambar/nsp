import $ from 'jquery';
import ldCover from '../../node_modules/ldCover/index.js';

$( function() {
	const $nspDialog = $( '#nsp-template-dialog' );

	const ldcv = new ldCover( { root: '#nsp-template-dialog' } );

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

		ldcv.on( 'toggle.on', function() {
			const currentTemplate = $opener.parent().find( '.template-file' ).text();

			if ( currentTemplate ) {
				$nspDialog.find( 'select' ).children( `option[value="${ currentTemplate }"]` ).prop( 'selected', true );
			} else {
				$nspDialog.find( 'select' ).children( 'option' ).prop( 'selected', false );
			}
		} );

		ldcv.toggle();

		ldcv.get().then( function( response ) {
			if ( '1' === response ) {
				const selectedFile = $nspDialog.find( 'option:selected' ).val();
				nspHandleTemplateUpdate( $opener, selectedFile );
			}
		} );
	} );
} );
