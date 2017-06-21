$( document ).ready( function(){
	CORE_URL = readCookie( 'url' );
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
		//var url = 'assets/inc/' + loc + '/' + goto + '.php'
		var url = loc + '/' + goto;
		$.ajax( {
			url: url,
			type: 'GET',
			success: function ( data ) {
				$( idLocation ).html( data );
				$( idLocation ).css( 'height', '' );
				$('html, body').animate({ //update scroll once ajax finishes
					scrollTop: $( idLocation ).offset().top
				}, 500);
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
					html += modalLi( 'text', 'transfer', 'Transfer', data.data.transfer ? data.data.transfer : '', "Enter the class transfer", false );
					html += modalLi( 'text', 'advisory', 'Advisory', data.data.advisory ? data.data.advisory : '', "Enter the class advisory", false, true );
					html += modalLi( 'text', 'prereq', 'Prerequisite', data.data.prereq ? data.data.prereq : '', "Enter the class prerequisite", false, true );
					html += modalLi( 'text', 'coreq', 'Corequisite', data.data.coreq ? data.data.coreq : '', "Enter the class corequisite", false, true );
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

	function saveClass( id ){
		//assume the only modal open is the one we are saving
		var modal = $('.modal' ).eq(0);
		var form = $( modal ).find( 'form' );
		var saveBtn = $( modal ).find( 'input[name="save"]' );
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

		if( !hasError && !window.processing ){
			//ajax save
			var successful = false;
			window.processing = true;
			saveBtn.addClass( 'processing' );
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
								map['title'] + '<img class="delete" src="assets/img/delete.png"><img class="edit" src="assets/img/edit.svg"></li>');
						}
						window.processing = false;
					} else{
						var modal = createModal( { title: "Error Saving Class", buttons : [ { value : 'Ok'} ] } );
						setModalContent( modal, data.data.error );
						displayModal( modal );
						successful = false;
						window.processing = false;
						saveBtn.removeClass( 'processing' );
					}
				}
			});
			return successful;
		} else{
			saveBtn.removeClass( 'processing');
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
				onclick : function(modalId){
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
		html += modalLi( 'text', 'transfer', 'Transfer', '', "Enter the class transfer", false );
		html += modalLi( 'text', 'advisory', 'Advisory', '', "Enter the class advisory", false, true );
		html += modalLi( 'text', 'prereq', 'Prerequisite', '', "Enter the class prerequisite", false, true );
		html += modalLi( 'text', 'coreq', 'Corequisite', '', "Enter the class corequisite", false, true );
		html += modalLi( 'textarea', 'description', 'Description', '', "Enter the class Description", false );
		html += "</ul></form>";
		setModalContent( modal, html );
		displayModal( modal );
		adjustTextarea( $( modal ).find( 'textarea' )[0] );
	} );

	$( document ).on( 'click', '.modal span a.addClass', function(){
		var input = $( this ).closest( 'li' ).find( 'input' );
		var prevModal = $(this ).closest('.modal');
		var id = $( prevModal ).attr( 'data-id' );
		$.ajax({
			type : 'POST',
			url : 'rest/classes/listing',
			dataType : 'json',
			//async : false,
			success : function( data ){
				if( data.success ){
					var modal = createModal({
						title: "Choose Class",
						buttons : [{
							value : 'Add',
							onclick : function( id ){
								var that = $( '.modal[data-id=' + id + ']');
								//$( that ).find( 'select'.val())
								$(input ).val( $(input ).val() + '~' + $( that ).find( 'select' ).val() + '~' );
								return true;
							}
						}, {
							value : 'Cancel',
							class : 'low'
						}]
					});
					var html = '<form><ul><li><label for="class">Classes</label>' +
						'<select name="class">';
					for( var  i = 0; i < data.data.length; i++ ){
						html += "<option value='" + data.data[i].id + "'>" + data.data[i].title + "</option>";
					}
					html += '</select><span>Pick a class to add</span></li>';
					setModalContent( modal, html );
					displayModal( modal );
				}
			}
		});
	});


	/******************************************************************************/
	/****************************certification edit********************************/
	/******************************************************************************/

	//init the html editors
	try{
		tinyMCE.init({
			mode : "textareas",
			branding : false,
			plugins: "table, lists, code, autoresize",
			autoresize_max_height: 600,
			toolbar: 'undo redo | bold italic underline subscript superscript | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify formatselect table ' +
			' bullist | addclass shortClass scheduleTable | code',
			menubar : false,
			setup: function (editor) {
				editor.addButton( 'addclass', {
					text: 'Add Class',
					icon: false,
					tooltip: 'Insert a link to a class',
					onclick: function () {
						var classText = '';
						if( getStorage( "invalidateCache" ) && getStorage( 'classes' ) ){
							var time = parseInt( getStorage( "invalidateCache" ) );
							var curr = + new Date(); //gives unix time
							if( curr >= time ){
								delete localStorage.classes;
							}
						}
						if( ( data = getStorageJSON( 'classes' ) ) != null ){
							loadClassModal( data );
						} else{
							requestClassListing( function( data ){
								if( data.success ){
									//store classes in cache
									setStorageJSON( 'classes', data.data );
									var t = + new Date(); //gives unix time
									setStorage( 'invalidateCache', ( t + ( 5 * 60 * 1000 ) ) );
									loadClassModal( data.data );
								}
							} );
						}
					}
				} );
				editor.addButton( 'scheduleTable', {
					text: 'Schedule Table',
					icon: false,
					tooltip: 'Create a table to plan classes',
					onclick: function () {
						var str = "<table>" +
						"<tr><td>Year</td><td>Summer</td><td>Fall</td><td>Winter</td><td>Spring</td></tr>" +
						"<tr><td>Year 1</td><td></td><td></td><td></td><td></td></tr>" +
						"</table>";
						editor.insertContent( str );
					}
				} );
				editor.addButton( 'shortClass', {
					text: '8 week class',
					tooltip: 'Add the 8 week class text',
					icon: false,
					onclick: function () {
						var str = "<table><tr><td colspan='2' style='text-align: center;'>8 Week Classes</td></tr>" +
							'<tr><td>class1</td><td style="text-align: right;">class2</td></table>';
						editor.insertContent( str );
					}
				} );


				//function for class listing request
				//onSuccess is the callback function to the success
				function requestClassListing( onSuccess ){
					$.ajax({
						type : 'POST',
						url : CORE_URL + '/rest/classes/listing',
						dataType : 'json',
						async : false,
						success : function( data ){
							onSuccess( data );
						},
						fail: function( data ){
							failedAjax();
						},
						error: function( data ){
							failedAjax();
						}
					});
				}

				//still in setup function for editor
				function loadClassModal( data, is8Week ){
					if( is8Week == undefined ) { is8Week = false;}
					//this either uses the cached copy of the classes or requests it from the network
					//will re-validate the cached copy every 5 minutes or after when you save the page
					var modal = createModal({
						title: "Choose Class",
						buttons : [{
							value : 'Add',
							onclick : function( id ){
								var that = $( '.modal[data-id=' + id + ']');
								var val = $( that ).find( 'select' ).val();
								var classCode = $( 'option[value=' + val + ']', that ).html().match( /[a-zA-Z]{3}\-\d{1,4}[a-zA-Z]*/ )[0];
								var float = $('input[name=float]:checked', that ).val();
								if( float ){
									editor.insertContent( '[class f="'+float.substr(0,1) + '" id="' + $( that ).find( 'select' ).val() + '" text="' + classCode + '" /]' );
								}else {
									editor.insertContent( '[class id="' + $( that ).find( 'select' ).val() + '" text="' + classCode + '" /]' );
								}
								return true;
							}
						}, {
							value : 'Cancel',
							class : 'low'
						}]
					});
					var html = '<form><ul><li><label for="class">Classes</label>' +
						'<select name="class">';
					for( var  i = 0; i < data.length; i++ ){
						html += "<option value='" + data[i].id + "'>" + data[i].title + "</option>";
					}
					html += '</select><span>Pick a class to add</span></li>';
					if( is8Week ) {
						html += '<li><label>8 Week Alignment</label>' +
							'<div><input type="radio" name="float" value="left"/>Left Side</div>' +
							'<div><input type="radio" name="float" value="right"/>Right Side</div>' +
							'<span>Only check if making an 8 week class</span></li>';
					}
					html += '</ul></form>';
					setModalContent( modal, html );
					displayModal( modal );
				}

				function failedAjax(){
					var modal = createModal({title: 'Failed to Load', buttons: [{value : 'Ok'}]});
					setModalContent( modal, "<p>Failed to load classes. Please try again</p><p>If the problem persists contact the administrator</p>" );
					displayModal( modal );
				}
			}
		});
	} catch( ignore ){}

	/******************************************************************************/
	/***************************certification delete*******************************/
	/******************************************************************************/
	$( document ).on( 'click', '#main .certs li img.delete', function(){
		var id = $( this ).closest( 'li' ).attr( 'data-id' );

		var modal = createModal({title: 'Are you sure',
			buttons : [ {
				value : "Delete",
				onclick : function(modalId){
					var successful = false;
					$.ajax({
						type : 'POST',
						url : 'rest/certs/delete/',
						data : {
							id : id
						},
						dataType : 'json',
						async : false,
						success : function( data ){
							if( data.success ){
								var modal = createModal( { title: "Certificate Deleted Successfully", buttons : [ { value : 'Ok'} ] } );
								setModalContent( modal, data.data.msg );
								displayModal( modal );
								successful = true;
								var that = $( '.certs .listing li[data-id=' + id + ']' );
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
	} );

	/******************************************************************************/
	/****************************certification save********************************/
	/******************************************************************************/
	$( '#main .admin .certs [type=submit]' ).on('click', function( e ){
		e.preventDefault(); //prevent the default submit action
		var form = $( this ).closest( 'form' );
		var data = $( form ).serializeArray();
		var map = {};
		jQuery.each( data, function ( i, field ) {
			map[field.name] = field.value;
		} ); //get form data for verify
		//get the html edit values
		map.description = tinymce.get( 'description' ).getContent();
		map.elo = tinymce.get( 'elo' ).getContent();
		map.schedule = tinymce.get( 'schedule' ).getContent();

		//remove errors
		$( 'li', form ).each(function( i, elem ){
			$(elem ).removeClass('error');
		} );
		var hasError = false;
		//scroll to error
		var scrollTo = null;
		//verify data
		if ( map['title'].length == 0 ) {
			scrollTo = $( form ).find( 'input[name=title]' ).closest( 'li' );
			scrollTo.addClass( 'error' );
			hasError = true;
		}
		if ( map['code'].length == 0 ) {
			scrollTo = $( form ).find( 'input[name=code]' ).closest( 'li' );
			scrollTo.addClass( 'error' );
			hasError = true;
		}
		if ( map['description'].length == 0 ) {
			scrollTo = $( form ).find( 'textarea[name=description]' ).closest( 'li' );
			scrollTo.addClass( 'error' );
			hasError = true;
		}
		if ( map['elo'].length == 0 ) {
			scrollTo = $( form ).find( 'textarea[name=elo]' ).closest( 'li' );
			scrollTo.addClass( 'error' );
			hasError = true;
		}
		if ( map['schedule'].length == 0 ) {
			scrollTo = $( form ).find( 'textarea[name=schedule]' ).closest( 'li' );
			scrollTo.addClass( 'error' );
			hasError = true;
		}

		if( !hasError ){
			//ajax login
			$.ajax({
				type : 'POST',
				url : CORE_URL + 'rest/' + $( form ).attr('action'),
				dataType : 'json',
				data : map,
				success : function( data ){
					console.log( data );
					if( data.success ){
						var modal = createModal( {
							title: 'Saved Successfully',
							buttons : [
								{ value : 'Finished', onclick : function(){ location.href = CORE_URL + 'editCerts'} },
								{ value:'Edit Again', class: 'low' }
							] } );
						setModalContent( modal, data.data.msg );
						displayModal( modal, true );
					} else {}
					//	var modal = createModal( { title: 'Log in failed', buttons : [{ value : 'Ok' }] } );
					//	setModalContent( modal, data.data.error );
					//	displayModal( modal, true )
					//}
				}
			})
		} else{
			//scroll to error
			$('html, body').animate({
				scrollTop: $( scrollTo ).offset().top
			}, 500);
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
			if( data[i].value == $(this).attr('value') ){
				//run the onclick
				var cleanExit = true;
				if( data[i].onclick ){
					cleanExit = data[i].onclick( id );
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


function modalLi( type, name, label, data, text ){
	return modalLi( type, name, label, data, text, false, false );
}

function modalLi( type, name, label, data, text, readOnly ){
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
function modalLi( type, name, label, data, text, readOnly, addClass ){
	var html = "<li>";
	html += "<label for='" + name + "'>" + label +"</label>";
	if ( type == 'textarea' ) {
		html += "<textarea onkeyup='adjustTextarea(this)' name='" + name + "' type='" + type + "' " + ( readOnly ? 'readonly' : '' ) + ">" + data + "</textarea>";
	} else {
		html += "<input name='" + name + "' type='" + type + "' value='" + data + "' " + ( readOnly ? 'readonly' : '' ) + "/>";
	}
	if( addClass ) {
		html += "<span>" + text + "<a class='addClass floatright'>+ Add Class</a></span>";
	} else {
		html += "<span>" + text + "</span>";
	}
	html += "</li>";

	return html;
}

function readCookie(name) {
	var cookiename = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++)
	{
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(cookiename) == 0) return decodeURIComponent( c.substring(cookiename.length,c.length) );
	}
	return null;
}

function setStorageJSON( key, data ){
	if (typeof(Storage) !== "undefined") {
		localStorage.setItem( key, JSON.stringify( data ) );
		return true;
	} else {
		return false;
	}
}

function getStorageJSON( key ){
	if (typeof(Storage) !== "undefined") {
		return JSON.parse( localStorage.getItem( key ) );
	} else {
		return false;
	}
}

function setStorage( key, data ){
	if (typeof(Storage) !== "undefined") {
		localStorage.setItem( key, ( data ) );
		return true;
	} else {
		return false;
	}
}

function getStorage( key ){
	if (typeof(Storage) !== "undefined") {
		return localStorage.getItem( key );
	} else {
		return false;
	}
}