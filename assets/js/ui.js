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

	$( '#main .login [type=submit]' ).on('click', function( e ){
		e.preventDefault(); //prevent the default submit action
		var form = $( this ).closest( 'form' );
		var data = $( form ).serializeArray();
		var map = {};
		jQuery.each( data, function ( i, field ) {
			map[field.name] = field.value;
		} ); //get form data for verify
		//remove errors
		$( 'li', form ).each(function( i, elem ){
			$(elem ).removeClass('error');
		} );
		var hasError = false;
		//verify data
		if ( map['user'].length == 0 ) {
			$( form ).find( 'input[name=user]' ).closest( 'li' ).addClass( 'error' );
			hasError = true;
		}
		if ( map['password'].length == 0 ) {
			$( form ).find( 'input[name=password]' ).closest( 'li' ).addClass( 'error' );
			hasError = true;
		}

		if( !hasError ){
			//ajax login
			$.ajax({
				type : 'POST',
				url : 'rest/' + $( form ).attr('action'),
				dataType : 'json',
				data : {
					user : map['user'],
					password : sha512( map['password'] )
				},
				success : function( data ){
					if( data.success ){
						location.href = data.data.redirect;
					} else {
						alert( JSON.stringify( data ) );
					}
				}

			})
		}
	} );



	//modal events
	//$(document).on(event, selector, handler).
	//bind to future event
	//default close x option
	$( document ).on( 'click', '.modal .close', function(){
		var modal = $(this).closest( '.modal' );
		var id = $( modal ).attr( 'data-id' ) ;
		closeModal( modal );
	} );
	
	//modal button events
	$( document ).on( 'click', '.modal input[type=button]', function(){
		var modal = $(this).closest( '.modal' );
		var id = $( modal ).attr( 'data-id' ) ;
		//get data for modal
		var data = window.modals.data[id].buttons;
		for( i = 0; i < data.length; i++ ){
			if( data[i].name == $(this).attr('name') ){
				//run the onclick
				data[i].onclick();
				closeModal( $(modal) );
			}
		}
	} );
});

var regex = {};
regex['password'] = /^((?=.*\d)(?=.*[A-Z])(?=.*\W).[\S]{7,50})$/g;
regex['email'] = /[a-z\d]+([\.\_]?[a-z\d]+)+@[a-z\d]+(\.[a-z]+)+/g;
function startTimer( element, fn ){
	var time = parseInt( $( element ).attr( 'data-time' ) );
	var interval = setInterval( function(){
		$( element ).text( parseInt( $(element ).text() ) - 1 );
	}, 1000 );
	setTimeout( function(){
		clearInterval( interval );
		fn();
	}, ( time * 1000 ) + 10 );
}

function adjustTextarea(h) {
	h.style.height = "20px";
	h.style.height = (h.scrollHeight)+"px";
}

window.modals = { ids : [], data : {} };
/**
 * options structure
 * {
 *  title: "string", //the header for the title
 *  footer: true, //has a footer
 *  buttons: [ //buttons for footer
 *  	0 : {
 *  		value : "string", //value of the button
 *			name : "string", //name of the input
 *  		onclick : function, //click event to run
 *  		class : "string" //class of the button for style or whatever
 *  	}
 *  ]
 * @param options
 */
function createModal( options ){
	var id = 0;
	if( window.modals.ids.length == 0 ){
		id = 1;
	}else {
		id = window.modals.ids[window.modals.ids.length - 1] + 1;
	}
	var html = '<div data-id="'+ id + '" class="modal">' +
		'<div class="modalWrapper">'
	if( options.title ){ //insert the header
		html += '<div class="modalHeader clearfix">'+
			'<span class="title">' + options.title + '</span>' +
			'<span class="close">&times;</span>'+
		'</div>';
	}
	//insert the content section
	html+='<div class="modalContent">'+
		'<p>Some text in the Modal..</p>'+
	'</div>';

	if( options.footer ){ //insert the header
		html += '<div class="modalFooter">';
		if( options.buttons ){
			for( i = 0; i < options.buttons.length; i++ ){
				var x = options.buttons[i];
				x.class = x.class ? x.class : '';
				x.name = x.name ? x.name : '';
				html += "<input type='button' value='" + x.value + "' class='" + x.class + "' name='" + x.name + "'/>";
			}
		}
		html+= '</div>';
	}
	$('body' ).append( html );
	window.modals.data[id] = options
	return $('.modal[data-id=' + id +']');
}

function closeModal( modal ){
	modal.find( '.modalWrapper' ).hide();
	$( modal ).fadeOut( 300, function(){
		$(modal).remove();
	});
}