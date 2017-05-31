$( document ).ready( function(){
	$( 'html' ).on( 'click', '.fakeLink', function( e ){
		e.preventDefault();
		var loc = $( this ).attr( 'data-to' );//location we are going to
		var idLocation = '#' + loc; //the class to look up
		var goto = $( this ).attr( 'data-code'); //the code for the filename eg CE803

		if ( loc == 'cert' && !$('#class' ).hasClass('none') ) {
			$( "#class" ).toggleClass( 'none' ) ;
		}

		if( $(idLocation ).hasClass( 'none' ) ){
			$(idLocation ).toggleClass( 'none' );
		}
		var height = parseInt( $( idLocation ).css('height'), 10);
		$( idLocation ).html( "<div class='aligncenter'><img src='assets/img/ajax-loader.gif' /><p>Loading</p></div>" );
		var newHeight = parseInt( $( idLocation ).css('height'), 10);
		if( newHeight < height ){
			$( idLocation ).css( 'height', height +'px' );
		}
		$('html, body').animate({
			scrollTop: $( idLocation ).offset().top
		}, 500);

		//var url = loc == 'cert' ? ( 'assets/inc/' + loc + '/' + goto + '.php' ): ( loc + '/' + goto );
		var url = 'assets/inc/' + loc + '/' + goto + '.php'
		$.ajax( {
			url: url,
			type: 'GET',
			success: function ( data ) {
				$( idLocation ).html( data );
				$( idLocation ).css( 'height', '' );
			},
			fail: function( data ){
				failedAjax( idLocation );
			},
			error: function( data ){
				failedAjax( idLocation );
			}
		} );

		function failedAjax( idLocation ){
			$( idLocation ).html( "<div class='aligncenter'><p>Failed to load page.<br>Try reloading the page.</p></div>")
		}
	});

	$( 'html' ).on( 'click', 'img.back', function( e ){ //back button
		var loc = $( this ).attr( 'data-to' );//location we are going to
		if( loc == 'top'){
			loc = "wrapper";
		}
		var idLocation ='#' + loc;

		$('html, body').animate({
			scrollTop: $( idLocation ).offset().top - 50
		}, 500);
	});
});