/**
 * Opens the demo notice from any button link with the class
 * opens-demo-dialog. Without this script the links go to their own href.
 */
const dialog = document.getElementById( 'demo-dialog' );

if ( dialog ) {
	let opener = null;

	document
		.querySelectorAll( '.opens-demo-dialog a, a.opens-demo-dialog' )
		.forEach( ( trigger ) => {
			trigger.addEventListener( 'click', ( event ) => {
				event.preventDefault();
				opener = trigger;
				dialog.showModal();
			} );
		} );

	dialog.querySelectorAll( '[data-close-dialog]' ).forEach( ( button ) => {
		button.addEventListener( 'click', () => dialog.close() );
	} );

	// Clicks on the padding also target the dialog; only the backdrop closes it.
	dialog.addEventListener( 'click', ( event ) => {
		if ( event.target !== dialog ) {
			return;
		}
		const box = dialog.getBoundingClientRect();
		const inside =
			event.clientX >= box.left &&
			event.clientX <= box.right &&
			event.clientY >= box.top &&
			event.clientY <= box.bottom;
		if ( ! inside ) {
			dialog.close();
		}
	} );

	dialog.addEventListener( 'close', () => {
		opener?.focus();
		opener = null;
	} );
}
