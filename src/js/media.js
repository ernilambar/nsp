import $ from 'jquery';

let nspMediaFileFrame;

$( function() {
	// Remove Handling.
	jQuery( document ).on( 'click', 'a.btn-nsp-image-delete', function( event ) {
		event.preventDefault();

		const $this = $( this );

		const confirmation = confirm( 'Are you sure?' );
		if ( ! confirmation ) {
			return false;
		}

		$.ajax( {
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'nsp_image_delete_featured',
				pid: $this.data( 'pid' ),
			},

			complete( jqXHR ) {
				const response = JSON.parse( jqXHR.responseText );

				if ( true === response.success ) {
					$this.parent().parent().find( '.nsp-image-thumbnail' ).attr( 'src', NSP_ADMIN.thumbnail_default_url );
					$this.parent().parent().find( '.btn-nsp-image-preview' ).attr( 'href', '' );
					$this.parent().find( '.btn-nsp-image-add' ).removeClass( 'is-hidden' );
					$this.parent().find( '.btn-nsp-image-preview' ).addClass( 'is-hidden' );
					$this.parent().find( '.btn-nsp-image-update' ).addClass( 'is-hidden' );
					$this.parent().find( '.btn-nsp-image-delete' ).addClass( 'is-hidden' );
				}
			},
		} );
	} ); // End remove handling.

	// Add Handling.
	jQuery( document ).on( 'click', 'a.btn-nsp-image-add', function( event ) {
		event.preventDefault();

		const $this = $( this );

		// Create the media frame.
		nspMediaFileFrame = wp.media.frames.nspMediaFileFrame = wp.media( {
			title: 'Select Image',
			button: {
				text: 'Set As Featured',
			},
			library: {
				type: 'image',
			},
			multiple: false,
		} );

		// When an image is selected, run a callback.
		nspMediaFileFrame.on( 'select', function() {
			const attachment = nspMediaFileFrame.state().get( 'selection' ).first().toJSON();

			$.ajax( {
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'nsp_image_add_featured',
					pid: $this.data( 'pid' ),
					aid: attachment.id,
				},

				complete( jqXHR ) {
					const response = JSON.parse( jqXHR.responseText );

					if ( true === response.success ) {
						const data = response.data;

						$this.parent().parent().find( '.nsp-image-thumbnail' ).attr( 'src', data.thumbnail_url );
						$this.parent().parent().find( '.btn-nsp-image-preview' ).attr( 'href', data.thumbnail_full_url );
						$this.parent().find( '.btn-nsp-image-update' ).data( 'previous_attachment', data.aid );
						$this.parent().find( '.btn-nsp-image-add' ).addClass( 'is-hidden' );
						$this.parent().find( '.btn-nsp-image-preview' ).removeClass( 'is-hidden' );
						$this.parent().find( '.btn-nsp-image-update' ).removeClass( 'is-hidden' );
						$this.parent().find( '.btn-nsp-image-delete' ).removeClass( 'is-hidden' );
					}
				},
			} );
		} );

		// Finally, open the modal.
		nspMediaFileFrame.open();
	} ); // End add handling.

	// Update Handling.
	jQuery( document ).on( 'click', 'a.btn-nsp-image-update', function( event ) {
		event.preventDefault();

		const $this = $( this );

		// Create the media frame.
		nspMediaFileFrame = wp.media.frames.nspMediaFileFrame = wp.media( {
			title: 'Select Image',
			button: {
				text: 'Set As Featured',
			},
			library: {
				type: 'image',
			},
			multiple: false,
		} );

		nspMediaFileFrame.on( 'open', function() {
			const selection = nspMediaFileFrame.state().get( 'selection' );
			const selected = $this.data( 'previous_attachment' );
			if ( selected ) {
				selection.add( wp.media.attachment( selected ) );
			}
		} );

		// When an image is selected, run a callback.
		nspMediaFileFrame.on( 'select', function() {
			const attachment = nspMediaFileFrame.state().get( 'selection' ).first().toJSON();

			$.ajax( {
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'nsp_image_update_featured',
					pid: $this.data( 'pid' ),
					aid: attachment.id,
				},

				complete( jqXHR ) {
					const response = JSON.parse( jqXHR.responseText );

					if ( true === response.success ) {
						const data = response.data;

						$this.parent().parent().find( '.nsp-image-thumbnail' ).attr( 'src', data.thumbnail_url );
						$this.parent().parent().find( '.btn-nsp-image-preview' ).attr( 'href', data.thumbnail_full_url );
						$this.data( 'previous_attachment', data.aid );
					}
				},
			} );
		} );

		// Finally, open the modal.
		nspMediaFileFrame.open();
	} ); // End update handling.
} );
