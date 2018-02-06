var searchTypingTimer = -1;

$( document ).ready( function () {
	//auto load selects
	$( 'select[autoload]' ).each( function( i, elem ){
		try{
			var options = {};
			if( $( elem ).is( '[nosearch]' ) ){
				options.minimumResultsForSearch = -1;
			}

			$( elem ).select2( options );
		}catch ( e ){}
	} );
	$( 'html' ).on( 'click', '.fakeLink', function ( e ) {
		e.preventDefault();
		var parentDiv = $( this ).closest( '.datablock' );
		console.log( parentDiv );
		var loc = $( this ).attr( 'data-to' );//location we are going to
		var idLocation = '#' + loc; //the class to look up
		var goto = $( this ).attr( 'data-code' ); //the code for the filename eg CE803

		//hide class when switching certs
		if ( loc == 'cert' && !$( '#class' ).hasClass( 'none' ) ) {
			$( "#class" ).toggleClass( 'none' );
			var qsa = queryString( 'class', null );
			updateUrl( stripQueryString() + queryStringToUrl( qsa ) );
		}

		//hide classgroup when switching certs
		if ( loc == 'cert' && !$( '#classGroup' ).hasClass( 'none' ) ) {
			$( "#classGroup" ).toggleClass( 'none' );
			var qsa = queryString( 'classGroup', null );
			updateUrl( stripQueryString() + queryStringToUrl( qsa ) );
		}

		//hide classgroup if clicked a class from cert block
		if( loc == 'class' && parentDiv.attr( 'id' ) == 'cert' ){
			$( "#classGroup" ).addClass( 'none' );
			var qsa = queryString( 'classGroup', null );
			updateUrl( stripQueryString() + queryStringToUrl( qsa ) );
		}

		//hide class if classGroup is clicked
		if( loc == 'classGroup' ){
			$( "#class" ).addClass( 'none' );
			var qsa = queryString( 'class', null );
			updateUrl( stripQueryString() + queryStringToUrl( qsa ) );
		}

		if ( $( idLocation ).hasClass( 'none' ) ) {
			$( idLocation ).toggleClass( 'none' );
		}
		var height = parseInt( $( idLocation ).css( 'height' ), 10 );
		$( idLocation ).html( "<div class='aligncenter'><img src='assets/img/ajax-loader.gif' /><p>Loading</p></div>" );
		var newHeight = parseInt( $( idLocation ).css( 'height' ), 10 );
		if ( newHeight < height ) {
			$( idLocation ).css( 'height', height + 'px' );
		}
		$( 'html, body' ).animate( {
			scrollTop: $( idLocation ).offset().top
		}, 500 );

		//var url = loc == 'cert' ? ( 'assets/inc/' + loc + '/' + goto + '.php' ): ( loc + '/' + goto );
		//var url = 'assets/inc/' + loc + '/' + goto + '.php'
		var url = loc + '/' + goto;
		$.ajax( {
			url: url,
			type: 'GET',
			success: function ( data ) {
				$( idLocation ).html( data );
				$( idLocation ).css( 'height', '' );
				var qsa = queryString( loc, goto );
				updateUrl( stripQueryString() + queryStringToUrl( qsa ) );
				$( 'html, body' ).animate( { //update scroll once ajax finishes
					scrollTop: $( idLocation ).offset().top
				}, 500, function(){
					//check if class was clicked and update the up link to go to the next element
					//could be cert or classGroup
					if( loc == 'class' ){
						if( $( '#classGroup' ).hasClass( 'none' ) ){
							$( '#class .back' ).attr( 'alt', 'To Certificate Info');
							$( '#class .back' ).attr( 'title', 'To Certificate Info');
							$( '#class .back' ).attr( 'data-to', 'cert');
						} else {
							$( '#class .back' ).attr( 'alt', 'To Class Group Info');
							$( '#class .back' ).attr( 'title', 'To Class Group Info');
							$( '#class .back' ).attr( 'data-to', 'classGroup');
						}
					}
				} );
			},
			fail: function ( data ) {
				failedAjax( idLocation );
			},
			error: function ( data ) {
				failedAjax( idLocation );
			}
		} );

		function failedAjax( idLocation ) {
			$( idLocation ).html( "<div class='aligncenter'><p>Failed to load page.<br>Try reloading the page.</p></div>" )
		}
	} );

	$( 'html' ).on( 'click', 'img.back', function ( e ) { //back button
		var loc = $( this ).attr( 'data-to' );//location we are going to
		if ( loc == 'top' ) {
			loc = "wrapper";
		}
		var idLocation = '#' + loc;

		$( 'html, body' ).animate( {
			scrollTop: $( idLocation ).offset().top - 50
		}, 500 );
	} );

	$( 'html' ).on( 'click', 'img.link', function ( e ) { //link button
		var parent = $( this ).closest( '.datablock' );
		if( $( parent ).attr( 'id' ) == 'cert' ) {
			var qsa = getQueryString();
			var qsaE = { cert : qsa.cert };
			var url = stripQueryString() + queryStringToUrl( qsaE );
		} else {
			var url = location.href;
		}
		var modal = createModal( { title : "Copy Link", buttons : [ { value : 'Ok' } ] } );
		setModalContent( modal, '<p>The link has been copied</p><textarea>' + url + '</textarea>' );
		displayModal( modal );
		$( 'textarea', modal ).select();

		try {
			var successful = document.execCommand('copy');
		} catch (err) { }
	} );

	/******************************************************************************/
	/********************************login*****************************************/
	/******************************************************************************/

	$( '#main .login [type=submit]' ).on( 'click', function ( e ) {
		e.preventDefault(); //prevent the default submit action
		$( ':focus' ).blur();
		var form = $( this ).closest( 'form' );
		var data = $( form ).serializeArray();
		var map = {};
		jQuery.each( data, function ( i, field ) {
			map[field.name] = field.value;
		} ); //get form data for verify
		//remove errors
		$( 'li', form ).each( function ( i, elem ) {
			$( elem ).removeClass( 'error' );
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

		if ( !hasError ) {
			//ajax login
			$.ajax( {
				type: 'POST',
				url: CORE_URL + 'rest/' + $( form ).attr( 'action' ),
				dataType: 'json',
				data: {
					user: map['user'],
					password: sha512( map['password'] )
				},
				success: function ( data ) {
					if ( data.success ) {
						location.href = data.data.redirect;
					} else {
						var modal = createModal( { title: 'Log in failed', buttons: [{ value: 'Ok', focus : true }] } );
						setModalContent( modal, data.data.error );
						displayModal( modal, true )
					}
				}
			} )
		}
	} );

	/******************************************************************************/
	/******************************Password Reset**********************************/
	/******************************************************************************/
	$( '.resetPassword input[type=submit]' ).on( 'click', function ( e ) {
		e.preventDefault();
		$( ':focus' ).blur();
		var form = $( this ).closest( 'form' );
		var data = $( form ).serializeArray();
		var map = {};
		jQuery.each( data, function ( i, field ) {
			map[field.name] = field.value;
		} ); //get form data for verify
		//remove errors
		$( 'li', form ).each( function ( i, elem ) {
			$( elem ).removeClass( 'error' );
		} );
		var hasError = false;

		//verify data
		if ( map['user'].length == 0 ) {
			$( form ).find( 'input[name=user]' ).closest( 'li' ).addClass( 'error' );
			hasError = true;
		}

		if ( map['stage'] == 1 ) {
			var modal = createModal( { title: 'Error', buttons: [{ value: 'Ok', focus: true }] } );
			setModalContent( modal, '' );
			if ( map['password'].length == 0 ) {
				$( form ).find( 'input[name=password]' ).closest( 'li' ).addClass( 'error' );
				hasError = true;
			}

			if ( map['password2'].length == 0 ) {
				$( form ).find( 'input[name=password2]' ).closest( 'li' ).addClass( 'error' );
				hasError = true;
			}

			if ( map['password'] != map['password2'] ) {
				$( form ).find( 'input[name=password]' ).closest( 'li' ).addClass( 'error' );
				$( form ).find( 'input[name=password2]' ).closest( 'li' ).addClass( 'error' );

				appendModalContent( modal, "<p>You passwords do not match</p>" );
				hasError = true;
			}

			//DONE make sure this doesn't alternate because of the /[a-z]/g flag
			if ( !regex['password'].test( $( form ).find( 'input[name=password]' ).val() ) ) {
				$( form ).find( 'input[name=password]' ).closest( 'li' ).addClass( 'error' );
				appendModalContent( modal, "Your passwords must have at least:" +
					"<ol style='padding-left:40px;'>" +
					"<li>Ten characters or longer</li>" +
					"<li>One lowercase letter</li>" +
					"<li>One uppercase letter</li>" +
					"<li>One digit</li>" +
					"<li>One symbol</li></ol>" );
				hasError = true;
			}
		}

		if ( hasError ){
			displayModal( modal, 0 );
		}

		if ( !hasError ) {
			//ajax login
			if ( map['stage'] == 0 ) {
				$.ajax( {
					type: 'POST',
					url: CORE_URL + 'rest/' + $( form ).attr( 'action' ),
					dataType: 'json',
					data: {
						token: map['token'],
						user: map['user']
					},
					success: function ( data ) {
						if ( data.success ) {
							var modal = createModal( { title: 'Reset Your Password', buttons: [{ value: 'Ok', focus : true }] } );
							setModalContent( modal, data.data.msg );
							displayModal( modal, 0 );
							$( 'input[name=stage]', form ).val( 1 );
							$( 'input[name=user]', form ).attr( 'readonly' );
							$( 'input[name=user]', form ).closest( 'li' ).slideUp();
							$( 'input[name=password]', form ).closest( 'li' ).removeClass( 'noneImportant' );
							$( 'input[name=password2]', form ).closest( 'li' ).removeClass( 'noneImportant' );
							$( 'input[type=submit]', form ).val( 'Reset Password' );
							$( form ).attr( 'action', 'users/setPassword' );
						} else {
							var modal = createModal( { title: 'Log in failed', buttons: [{ value: 'Ok', focus : true }] } );
							setModalContent( modal, data.data.error );
							displayModal( modal, true )
						}
					}
				} );
			} else if ( map['stage'] == 1 ) {
				closeModal( modal );
				$.ajax( {
					type: 'POST',
					url: CORE_URL + 'rest/' + $( form ).attr( 'action' ),
					dataType: 'json',
					data: {
						token: map['token'],
						user: map['user'],
						password: sha512( map['password'] )
					},
					success: function ( data ) {
						if ( data.success ) {
							var modal = createModal( {
								title: 'Success', buttons: [
									{
										value: 'Ok',
										focus : true,
										onclick: function () {
											location.href = CORE_URL + data.data.redirect;
										}
									}]
							} );
							setModalContent( modal, data.data.msg );
							displayModal( modal, true );
						} else {
							var modal = createModal( { title: 'Log in failed', buttons: [{ value: 'Ok', focus : true }] } );
							setModalContent( modal, data.data.error );
							displayModal( modal, true )
						}
					}
				} );
			}
		}
	} );

	/******************************************************************************/
	/******************************Language Picker*********************************/
	/******************************************************************************/
	$( '.langList li' ).on( 'click', function ( e ) {
		var lang = $( this ).attr( 'data-value' );
		if( lang == 'en' ){
			var qsa = queryString( 'lang', null );
		} else {
			var qsa = queryString( 'lang', lang );
		}
		setCookie( 'lang', lang );
		location.href = stripQueryString() + queryStringToUrl( qsa );
	} );

	/******************************************************************************/
	/*******************************ToolTip Events*********************************/
	/******************************************************************************/
	//tooltip from http://www.alessioatzeni.com/blog/simple-tooltip-with-jquery-only-text/
	$( document ).on( 'mouseover', '.tooltip', function(e){
		// Hover over code
		var title = $(this).attr('title');
		$(this).data('tipText', title).removeAttr('title');
		$('<p class="tip"></p>')
			.text(title)
			.appendTo('body')
			.fadeIn('slow');
	});

	$( document ).on( 'mouseout', '.tooltip', function(e){
		// Hover out code
		$(this).attr('title', $(this).data('tipText'));
		$('.tip').remove();
	});

	$( document ).on( 'mousemove', '.tooltip', function(e){
		var mousex = e.pageX + 20; //Get X coordinates
		var mousey = e.pageY + 10; //Get Y coordinates
		$('.tip') .css({ top: mousey, left: mousex })
	});

	/******************************************************************************/
	/******************************Notice Dismiss**********************************/
	/******************************************************************************/
	$( document ).on( 'click', '.noticeDismiss', function(){
		$( this ).closest( '#warningWrapper' ).slideUp();
		setCookie( "noticeDismissed", "1", 1 );
	} );

	/******************************************************************************/
	/*******************************Modal Events***********************************/
	/******************************************************************************/
	//$(document).on(event, selector, handler).
	//bind to future event
	//default close x option
	$( document ).on( 'click', '.modal .close', function () {
		var modal = $( this ).closest( '.modal' );
		var id = $( modal ).attr( 'data-id' );
		closeModal( modal );
	} );

	//modal button events
	$( document ).on( 'click', '.modal input[type=button]', function () {
		var modal = $( this ).closest( '.modal' );
		var id = $( modal ).attr( 'data-id' );
		//get data for modal
		var data = window.modals.data[id].buttons;
		for ( i = 0; i < data.length; i++ ) {
			if ( data[i].value == $( this ).attr( 'value' ) ) {
				//run the onclick
				var cleanExit = true;
				if ( data[i].onclick ) {
					cleanExit = data[i].onclick( id );
				}
				if ( cleanExit ) {
					closeModal( $( modal ) );
				}
			}
		}
	} );

	//modal esc key event
	$( document ).on( 'keyup', function ( e ) {
		if ( window.modals.displaying ) {
			if ( e.keyCode == 27 ) {
				closeModal( $( '.modal[data-id=' + window.modals.displaying + ']' ) );
			}
		}
	} );

	//modal reposition after resize
	$( window ).on( 'resize', function(){
		$( '.modal' ).each( function( idx, elem ){
			$( elem ).css( 'left', ( $( 'body' ).width() / 2 ) - ( $( elem ).width() / 2 ) + 'px' );
		} );
	} );

	//modal shadow click
	$( document ).on( 'click', '.modalShadow', function(){
		//check the data on it to make sure there is no override for this option
		var id = $( this ).attr( 'data-id' );
		var modal = window.modals.data[id];
		if ( modal.shadowClose ) {
			if( isset( modal.shadowPrompt ) ){
				confirmModal( modal.shadowPrompt, function(){ closeModalId( id ); return true }, undefined );
			} else {
				closeModalId( id );
			}
		}
	} );

	/******************************************************************************/
	/******************************modal tab switcher******************************/
	/******************************************************************************/
	$( document ).on( 'click', '.tabWrapper .tab', function(){
		if( $( this ).hasClass( 'active' ) ) return;
		var tab = $( this ).attr( 'data-tab' );
		var active = $( this ).siblings( '.active' );
		var activeTab = $( active ).attr( 'data-tab' );

		//change active tab to that you clicked
		$( active ).removeClass( 'active' );
		$( this ).addClass( 'active' );

		//switch the content tab
		$( '.tabContent[data-tab="' + tab + '"]' ).toggleClass( 'none' );
		$( '.tabContent[data-tab="' + activeTab + '"]' ).toggleClass( 'none' );
	} );
} );

window.modals = { ids: [], data: {}, displaying: false };
/**
 * options structure
 * {
 *  title: "string", //the header for the title
 *  footer: true, //has a footer [optional]
 *  shadowClose: true, //clicking the shadow closes the modal [optional]
 *  shadowPrompt: "", //prompt for the user to close [optional]
 *  buttons: [ //buttons for footer
 *  	0 : {
 *  		value : "string", //value of the button
 *			name : "string", //name of the input [optional]
 *  		onclick : function, //click event to run [optional]
 *  		class : "string" //class of the button for style or whatever [optional]
 *  		focus : true //focus on this button [optional]
 *  	}
 *  ]
 * @param options
 */
function createModal( options ) {
	var id = 0;
	if ( window.modals.ids.length == 0 ) {
		id = 1;
	} else {
		id = window.modals.ids[window.modals.ids.length - 1] + 1;
	}

	if ( !isset( options['shadowClose'] ) ) {
		options['shadowClose'] = true;
	}

	if ( !isset( options['focus'] ) ) {
		options['focus'] = false;
	}
	var zIndex = ' style="z-index: ' + ( 5 + ( id - 1 ) ) + '" ';
	var html = '<div data-id="' + id + '" class="modal none"' + zIndex + '>' +
		'<div class="modalWrapper">';
	if ( options.title ) { //insert the header
		html += '<div class="modalHeader clearfix">' +
			'<span class="title">' + options.title + '</span>' +
			'<span class="close">&times;</span>' +
			'</div>';
	}
	//insert the content section
	html += '<div class="modalContent"></div>';

	if ( options.footer || options.buttons ) { //insert the header
		html += '<div class="modalFooter">';
		if ( options.buttons ) {
			for ( i = 0; i < options.buttons.length; i++ ) {
				var x = options.buttons[i];
				x.class = x.class ? x.class : '';
				x.name = x.name ? x.name : '';
				html += "<input type='button' value='" + x.value + "' class='" + x.class + "' name='" + x.name + "' " + ( ( x.focus ) ? 'autofocus />' : '/>' );
			}
		}
		html += '</div>';
	}
	$( 'body' ).append( '<div class="modalShadow none" data-id="' + id + '"' + zIndex + '>&nbsp;</div>' ).append( html );
	window.modals.data[id] = options
	window.modals.ids.push( id );
	return $( '.modal[data-id=' + id + ']' );
}

function setModalContent( modal, html ) {
	$( modal ).find( '.modalContent' ).html( html );
}

function appendModalContent( modal, html ){
	$( modal ).find( '.modalContent' ).append( html );
}

function appendModalContent( modal, html ){
	$( modal ).find( '.modalContent' ).html( $( modal ).find( '.modalContent' ).html() + html );
}

function displayModal( modal, focusIndex ) {
	//center the modal on the screen
	var id = $( modal ).attr( 'data-id' );
	$( modal ).css( 'left', ( $( 'body' ).width() / 2 ) - ( $( modal ).width() / 2 ) + 'px' );
	$( modal ).css( 'top', ( $(document ).scrollTop() ) + 'px' );
	$( '.modalShadow[data-id=' + id + ']' ).fadeIn( 300 );
	$( modal ).fadeIn( 300 );
	//find focus if any exist
	var data = window.modals.data[id];
	for ( var i = 0; i < data.buttons.length; i++ ) {
		if( data.buttons[i].focus  ){
			$( '.modal[data-id=' + id + '] input[name="' + data.buttons[i].name + '"]' ).focus();
			$( '.modal[data-id=' + id + '] input[value="' + data.buttons[i].value + '"]' ).focus();
		}
	}
	//$('html, body').animate({
	//	scrollTop: $( modal ).offset().top
	//}, 200);
	window.modals.displaying = $( modal ).attr( 'data-id' );
}

function closeModalId( id ){
	var modal = $( '.modal[data-id=' + id + ']');
	delete window.modals.data[id];
	window.modals.ids.splice( window.modals.ids.indexOf( id ), 1 );
	if( $( '.modal:not([data-id=' + id + '])' ).last().length ){
		window.modals.displaying = $( '.modal' ).last().attr( 'data-id' );
	} else {
		window.modals.displaying = false;
	}
	$( modal ).fadeOut( 300, function () {
		$( modal ).remove();
	} );
	$( '.modalShadow[data-id=' + id + ']' ).fadeOut( 300, function(){
		$(this ).remove();
	});
}

function closeModal( modal ) {
	//modal.find( '.modalWrapper' ).hide();
	var id = $( modal ).attr( 'data-id' );
	delete window.modals.data[id];
	window.modals.ids.splice( window.modals.ids.indexOf( id ), 1 );
	if( $( '.modal:not([data-id=' + id + '])' ).last().length ){
		window.modals.displaying = $( '.modal' ).last().attr( 'data-id' );
	} else {
		window.modals.displaying = false;
	}
	$( modal ).fadeOut( 300, function () {
		$( modal ).remove();
	} );
	$( '.modalShadow[data-id=' + id + ']' ).fadeOut( 300, function(){
		$(this ).remove();
	});
}

function confirmModal( question, yesAction, noAction ){
	var modal = createModal( {
		title : "Confirm",
		buttons: [
			{
				value: "Yes",
				onclick: yesAction
			},
			{
				value: "Cancel",
				onclick: noAction,
				class: 'low'
			}
		]
	} );
	setModalContent( modal, question );
	displayModal( modal );
	$( modal ).effect( 'shake' );
}

function modalLi( type, name, label, data, text ) {
	return modalLi( type, name, label, data, text, false, false );
}

function modalLi( type, name, label, data, text, readOnly ) {
	return modalLi( type, name, label, data, text, readOnly, false );
}

/**
 * create an li for a form in the modal
 * @param type input type
 * @param name input name
 * @param label label text
 * @param data input value
 * @param text span text
 * @param readOnly set if readonly
 * @returns {string}
 */
function modalLi( type, name, label, data, text, readOnly, addClass, tooltip ) {
	var html = "<li>";
	html += "<label for='" + name + "'>" + label + "</label>";
	if ( type == 'textarea' ) {
		html += "<textarea onkeyup='adjustTextarea(this)' name='" + name + "' type='" + type + "' " + ( readOnly ? 'readonly' : '' ) + ( tooltip ? ( 'class="tooltip" title="' + tooltip +'"' ):'' ) + ">" + data + "</textarea>";
	} else if ( type == "checkbox" ) {
		html += "<input name='" + name + "' type='" + type + "' value='1'" + ( data == 1 ? 'checked' : '' ) + " " + ( readOnly ? 'readonly' : '' ) + ( tooltip ? ( 'class="tooltip" title="' + tooltip +'"' ):'' ) + "/>" + text;
	} else {
		html += "<input name='" + name + "' type='" + type + "' value='" + data + "' " + ( readOnly ? 'readonly' : '' ) + ( tooltip ? ( 'class="tooltip" title="' + tooltip +'"' ):'' ) +  "/>";
	}
	if ( addClass ) {
		html += "<span>" + text + "<a class='addClass floatright'>+ Add Class</a></span>";
	} else {
		html += "<span>" + text + "</span>";
	}
	html += "</li>";

	return html;
}

function startTimer( element, fn ) {
	var time = parseInt( $( element ).attr( 'data-time' ) );
	var interval = setInterval( function () {
		$( element ).text( parseInt( $( element ).text() ) - 1 );
	}, 1000 );
	setTimeout( function () {
		clearInterval( interval );
		fn();
	}, ( time * 1000 ) + 10 );
}

function adjustTextarea( h ) {//
	h.style.height = "20px";
	h.style.height = (h.scrollHeight) + "px";
}


/**
 * read a cookie from storage
 * @param name
 * @returns {*}
 */
function readCookie( name ) {
	var cookiename = name + "=";
	var ca = document.cookie.split( ';' );
	for ( var i = 0; i < ca.length; i++ ) {
		var c = ca[i];
		while ( c.charAt( 0 ) == ' ' ) c = c.substring( 1, c.length );
		if ( c.indexOf( cookiename ) == 0 ) return decodeURIComponent( c.substring( cookiename.length, c.length ) );
	}
	return null;
}

/**
 * put a cookie into storage
 * @param name
 * @param data
 * @param expires
 */
function setCookie( name, data, expires ){
	if( expires ){
		var date = new Date();
		date.setTime( date.getTime()+( expires * 24 * 60 * 60 * 1000) );
		expires = date.toUTCString();
		document.cookie = name + "=" + data + "; expires=" + expires + "; path=/";
	} else{
		document.cookie = name + "=" + data + "; path=/";
	}
}

/**
 * insert whole objects into localStorage JSON.stringifies for you
 * @param key
 * @param data
 * @returns {boolean}
 */
function setStorageJSON( key, data ) {
	if ( typeof(Storage) !== "undefined" ) {
		localStorage.setItem( key, JSON.stringify( data ) );
		return true;
	} else {
		return false;
	}
}

/**
 * Retrieve an object from localStroage JSON.parses for you
 * @param key
 * @returns {boolean}
 */
function getStorageJSON( key ) {
	if ( typeof(Storage) !== "undefined" ) {
		return JSON.parse( localStorage.getItem( key ) );
	} else {
		return false;
	}
}

/**
 * insert a string into localStorage
 * @param key
 * @param data
 * @returns {boolean}
 */
function setStorage( key, data ) {
	if ( typeof(Storage) !== "undefined" ) {
		localStorage.setItem( key, ( data ) );
		return true;
	} else {
		return false;
	}
}

/**
 * retrieve a string from localStorage
 * @param key
 * @returns {boolean}
 */
function getStorage( key ) {
	if ( typeof(Storage) !== "undefined" ) {
		return localStorage.getItem( key );
	} else {
		return false;
	}
}

/**
 * change url without refreshing page
 */
function updateUrl(url) {
	history.pushState(null, null, url);
}

function stripQueryString(){
	var url = location.href;
	if( url.indexOf( '?' ) > 0 ){
		url = url.substr( 0, url.indexOf( '?' ) );
	} else{
		url = url.substr( 0, url.indexOf( '&' ) );
	}
	return url;
}
function queryStringToUrl( obj ){
	var s = '';
	for( x in obj ){
		//this works in javascript
		//s += `${x}=${obj[x]}`;
		s += '&' + x + '=' + obj[x];
	}
	if( s.length != 0 ) {
		s = '?' + s.substr( 1 );
	}
	return s;
}

function queryString( key, value ){
	var obj = getQueryString();
	if( value == null && obj[key] ){
		delete obj[key];
	} else if( value != null ){
		obj[key] = value;
	}
	return obj;
}

function getQueryString(){
	var url = location.href;
	var vars;
	var obj = {};
	if( url.indexOf( '?' ) == -1 && url.indexOf( '&' ) == -1){
		return {};
	}
	if( url.indexOf( '?' ) > 0 ){
		vars = url.substr( url.indexOf( '?' ) + 1 );
	} else{
		vars = url.substr( url.indexOf( '&' ) + 1 );
	}
	var arr = vars.split( '&' );
	for( var i = 0; i < arr.length; i++ ){
		var s = arr[i];
		s = s.split( '=' );
		obj[s[0]] = s[1];
	}
	return obj;
}

function isset( value ){
	if( typeof value !== 'undefined' ){
		return true;
	}
	return false;
}

var regex = {};
regex['password'] = /.*(?=.{10,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[&"'(\-_)=~#{[|`\\^@\]}^$*иг╡%,;:!?./з+]).*/g;
regex['strongPassword'] = /^(?!.*(.)\1{1})(?=(.*[\d]){2,})(?=(.*[a-z]){2,})(?=(.*[A-Z]){2,})(?=(.*[@#$%!]){2,})(?:[\da-zA-Z@#$%!]){15,100}$/;
regex['email'] = /[a-z\d]+([\.\_]?[a-z\d]+)+@[a-z\d]+(\.[a-z]+)+/;
regex['classTitle'] = /^[A-Za-z]{3}-.+? - .+$/;

var CORE_URL = readCookie( 'url' );