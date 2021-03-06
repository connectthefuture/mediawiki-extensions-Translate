/*!
 * @author Santhosh Thottingal
 * @author Niklas Laxström
 * @license GPL-2.0+
 */

( function ( $, mw ) {
	'use strict';

	$( document ).ready( function () {
		$( '#wpUserLanguage' ).multiselectautocomplete( { inputbox: '#tpt-prioritylangs' } );

		$( '#mw-content-text' ).on( 'click', '.mw-translate-jspost', function ( e ) {
			var params,
				uri = new mw.Uri( e.target.href );

			params = uri.query;
			// Change to csrf when support for MW 1.26 is dropped
			params.token = mw.user.tokens.get( 'editToken' );
			$.post( uri.path, params ).done( function () {
				location.reload();
			} );

			e.preventDefault();
		} );
	} );
}( jQuery, mediaWiki ) );
