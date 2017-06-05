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
						var modal = createModal( { title: 'Log in failed', buttons : [{ value : 'Ok' }] } );
						setModalContent( modal, data.data.error );
						displayModal( modal, true )
					}
				}
			})
		}
	} );

	//classes edit option
	$( document ).on( 'click', '#main .classes li img.edit', function(){
		//get the class info
		var id = $( this ).closest( 'li' ).attr( 'data-id' );
		$.ajax({
			type : 'POST',
			url : 'rest/classes/get/' + id,
			dataType : 'json',
			success : function( data ){
				if( data.success ){
					//alert( JSON.stringify( data ) );
					var modal = createModal({
						title : 'Edit Class',
						buttons: [
							{
								value: 'Save',
								name:'save',
								onclick: saveClass
							},
							{
								value: 'Cancel',
								class : 'low'
							}
						]
					});
					var html = "<form><ul>";
					//type, name, label, data, text
					html += modalLi( 'text', 'id', 'ID', data.data.id, "Enter the class ID", true );
					html += modalLi( 'text', 'title', 'Title', data.data.title, "Enter the class title", false );
					html += modalLi( 'number', 'units', 'Units', data.data.units, "Enter the class units", false );
					html += modalLi( 'text', 'advisory', 'Advisory', data.data.advisory ? data.data.advisory : '', "Enter the class advisory", false );
					html += modalLi( 'text', 'prereq', 'Prerequisite', data.data.prereq ? data.data.prereq : '', "Enter the class prerequisite", false );
					html += modalLi( 'text', 'transfer', 'Transfer', data.data.transfer ? data.data.transfer : '', "Enter the class transfer", false );
					html += modalLi( 'textarea', 'description', 'Description', data.data.description, "Enter the class Description", false );
					html += "</ul></form>";
					setModalContent( modal, html );
					displayModal( modal );
					adjustTextarea( $( modal ).find( 'textarea' )[0] );
				} else {
					var modal = createModal( { title: 'Failed to load class', buttons : [{ value : 'Ok' }] } );
					setModalContent( modal, data.data.error );
					displayModal( modal, true )
				}
			}
		});
	});

	function saveClass(){
		//assume the only modal open is the one we are saving
		var modal = $('.modal' ).eq(0);
		var form = $( modal ).find( 'form' );
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
		if ( map['title'].length == 0 ) {
			$( form ).find( 'input[name=title]' ).closest( 'li' ).addClass( 'error' );
			hasError = true;
		}
		if ( map['units'].length == 0 ) {
			$( form ).find( 'input[name=units]' ).closest( 'li' ).addClass( 'error' );
			hasError = true;
		}
		if ( map['description'].length == 0 ) {
			$( form ).find( 'textarea[name=description]' ).closest( 'li' ).addClass( 'error' );
			hasError = true;
		}

		if( !hasError ){
			//ajax save
			var successful = false;
			var url;
			if( map['create'] ){
				url = 'create';
			} else {
				url = 'save';
			}
			$.ajax({
				type : 'POST',
				url : 'rest/classes/' + url,
				dataType : 'json',
				data : map,
				async: false,
				success : function( data ){
					//alert( JSON.stringify( data ) );
					if( data.success ){
						var modal = createModal( { title: "Saved Class Successfully", buttons : [ { value : 'Ok'} ] } );
						setModalContent( modal, data.data.msg );
						displayModal( modal );
						successful = true;
						if( url == 'create' ){
							$( '.classes .listing ul' ).append('<li data-id="' + map['id'] + '">' +
								map['title'] + '<img class="delete" src="http://localhost/lab/assets/img/delete.png"><img class="edit" src="http://localhost/lab/assets/img/edit.svg"></li>');
						}
					} else{
						successful = false;
					}
				}
			});
			return successful;
		} else{
			return false;
		}
	}

	//classes edit option
	$( document ).on( 'click', '#main .classes li img.delete', function(){
		//get the class info
		var id = $( this ).closest( 'li' ).attr( 'data-id' );

		var modal = createModal({title: 'Are you sure',
			buttons : [ {
				value : "Delete",
				onclick : function(){
					var successful = false;
					$.ajax({
						type : 'POST',
						url : 'rest/classes/delete/',
						data : {
							id : id
						},
						dataType : 'json',
						async : false,
						success : function( data ){
							if( data.success ){
								var modal = createModal( { title: "Class Deleted Successfully", buttons : [ { value : 'Ok'} ] } );
								setModalContent( modal, data.data.msg );
								displayModal( modal );
								successful = true;
								var that = $( '.classes .listing li[data-id=' + id + ']' );
								that.slideUp( 400, function(){
									that.remove();
								});
							} else {
								successful = false;
							}
						}
					});
					return successful;
				}
			},{
				value : "Cancel",
				class : "low"
			} ] } );

		setModalContent( modal, "<p>Are you sure you want to delete. This can not be undone</p>");
		displayModal( modal );
	});

	//create class
	$('.classes input[name=createClass]' ).on( 'click', function(){
		var modal = createModal({
			title : 'Create Class',
			buttons: [
				{
					value: 'Save',
					name:'save',
					onclick: saveClass
				},
				{
					value: 'Cancel',
					class : 'low'
				}
			]
		});
		var html = "<form><ul>";
		//type, name, label, data, text
		html += "<input type='hidden' name='create' value='create' />";
		html += modalLi( 'text', 'id', 'ID', '', "Enter the class ID", false );
		html += modalLi( 'text', 'title', 'Title', '', "Enter the class title", false );
		html += modalLi( 'number', 'units', 'Units', 0, "Enter the class units", false );
		html += modalLi( 'text', 'advisory', 'Advisory', '', "Enter the class advisory", false );
		html += modalLi( 'text', 'prereq', 'Prerequisite', '', "Enter the class prerequisite", false );
		html += modalLi( 'text', 'transfer', 'Transfer', '', "Enter the class transfer", false );
		html += modalLi( 'textarea', 'description', 'Description', '', "Enter the class Description", false );
		html += "</ul></form>";
		setModalContent( modal, html );
		displayModal( modal );
		adjustTextarea( $( modal ).find( 'textarea' )[0] );
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
				var cleanExit = true;
				if( data[i].onclick ){
					cleanExit = data[i].onclick();
				}
				if ( cleanExit ) {
					closeModal( $(modal) );
				}
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

function adjustTextarea(h) {//
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
	var html = '<div data-id="'+ id + '" class="modal none">' +
		'<div class="modalWrapper">'
	if( options.title ){ //insert the header
		html += '<div class="modalHeader clearfix">'+
			'<span class="title">' + options.title + '</span>' +
			'<span class="close">&times;</span>'+
		'</div>';
	}
	//insert the content section
	html+='<div class="modalContent"></div>';

	if( options.footer || options.buttons ){ //insert the header
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
	window.modals.ids.push(id);
	return $('.modal[data-id=' + id +']');
}

function setModalContent( modal, html ){
	$( modal ).find( '.modalContent' ).html( html );
}

function displayModal( modal, shake ){
	$( modal ).fadeIn( 300 );
}

function closeModal( modal ){
	modal.find( '.modalWrapper' ).hide();
	var id = $( modal ).attr('data-id');
	$( modal ).fadeOut( 300, function(){
		$(modal).remove();
		delete window.modals.data[id];
		window.modals.ids.splice( window.modals.ids.indexOf( id ), 1 );
	});
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
function modalLi( type, name, label, data, text, readOnly ){
	var html = "<li>";
	html += "<label for='" + name + "'>" + label +"</label>";
	if ( type == 'textarea' ) {
		html += "<textarea onkeyup='adjustTextarea(this)' name='" + name + "' type='" + type + "' " + ( readOnly ? 'readonly' : '' ) + ">" + data + "</textarea>";
	} else {
		html += "<input name='" + name + "' type='" + type + "' value='" + data + "' " + ( readOnly ? 'readonly' : '' ) + "/>";
	}
	html += "<span>" + text + "</span>";
	html += "</li>";

	return html;
}