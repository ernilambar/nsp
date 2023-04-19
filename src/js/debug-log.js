window.addEventListener( 'DOMContentLoaded', () => {
	const e = document.getElementById( 'log' );

	if ( e ) {
		let newContent = e.innerHTML.replace( /PHP Warning/g, '<span class="warning">$&</span>' );
		newContent = newContent.replace( /PHP Fatal error/g, '<span class="error">$&</span>' );
		newContent = newContent.replace( /Stack trace:/g, '<span class="trace">$&</span>' );
		newContent = newContent.replace( /\[(.*?)UTC]/g, '<span class="timestamp">$&</span>' );

		e.innerHTML = newContent;
	}
} );
