/**
 * Created by michael on 11/14/2017.
 */
$( document ).ready( function(){
	/************************ Upload Button ************************/
	$( 'button.upload' ).on( 'click', function(){
		var topDiv = $( this ).closest( 'div' );
		var name = $( '[type=text]', topDiv ).attr( 'name' );
		var accept = $(this ).attr( 'accept' )
		$( topDiv ).append( '<input ' +
			'name="' + name + 'Upload"' +
			' type="file" ' +
			( ( accept ) ? ( ' accept="' + accept + '"' ) : ( '' ) ) +
		'/>');
		$( '[type=file]', topDiv ).click();
		$( '[type=file]', topDiv ).on( 'change', function(){
			fileChangeAction( $( '[type=file]', topDiv ) );
		} );
	} );

	/************************ Upload Action ************************/
	function fileChangeAction( input ){
		var topDiv = $( input ).closest( 'div' );
		var fileLoc = input.val();
		fileLoc = fileLoc.replace( 'fakepath', '...');
		$( '[type=text]', topDiv ).val( fileLoc );
	}

	/************************ Save ************************/
	var SUBMITED = false;
	$( '#main .admin .pages [type=submit]' ).on( 'click', function ( e ) {
		if( SUBMITED ){
			SUBMITED = false;
			return;
		}
		e.preventDefault(); //prevent the default submit action
		var form = $( this ).closest( 'form' );
		var data = $( form ).serializeArray();
		var map = {};
		jQuery.each( data, function ( i, field ) {
			map[field.name] = field.value;
		} ); //get form data for verify
		//get the html edit values

		//remove errors
		$( 'li', form ).each( function ( i, elem ) {
			$( elem ).removeClass( 'error' );
		} );
		var hasError = false;
		//scroll to error
		var scrollTo = null;
		//verify data
		if ( map['name'].length == 0 ) {
			scrollTo = $( form ).find( 'input[name=title]' ).closest( 'li' );
			scrollTo.addClass( 'error' );
			hasError = true;
		}
		if ( map['path'].length == 0 ) {
			scrollTo = $( form ).find( 'input[name=code]' ).closest( 'li' );
			scrollTo.addClass( 'error' );
			hasError = true;
		}

		if ( !hasError ) {
			//cant ajax uploads
			$( form ).submit();
			//$.ajax( {
			//	type: 'POST',
			//	url: CORE_URL + 'rest/' + $( form ).attr( 'action' ),
			//	dataType: 'json',
			//	data: map,
			//	success: function ( data ) {
			//		console.log( data );
			//		if ( data.success ) {
			//			var modal = createModal( {
			//				title: 'Saved Successfully',
			//				buttons: [
			//					{
			//						value: 'Finished',
			//						focus : true,
			//						onclick: function () {
			//							location.href = CORE_URL + 'editPages'
			//						}
			//					},
			//					{ value: 'Edit Again', class: 'low' }
			//				]
			//			} );
			//			setModalContent( modal, data.data.msg );
			//			displayModal( modal );
			//		} else {
			//			var modal = createModal( { title: 'Error', buttons : [{ value : 'Ok', focus : true }] } );
			//			setModalContent( modal, data.data.error );
			//			displayModal( modal )
			//		}
			//	}
			//} )
		} else {
			//scroll to error
			$( 'html, body' ).animate( {
				scrollTop: $( scrollTo ).offset().top
			}, 500 );
		}
	} );
} );

