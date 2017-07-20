$( document ).ready( function () {
	CORE_URL = readCookie( 'url' );
	$( 'html' ).on( 'click', '.fakeLink', function ( e ) {
		e.preventDefault();
		var loc = $( this ).attr( 'data-to' );//location we are going to
		var idLocation = '#' + loc; //the class to look up
		var goto = $( this ).attr( 'data-code' ); //the code for the filename eg CE803

		if ( loc == 'cert' && !$( '#class' ).hasClass( 'none' ) ) {
			$( "#class" ).toggleClass( 'none' );
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
				}, 500 );
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
				url: 'rest/' + $( form ).attr( 'action' ),
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
	/*****************************Class functions**********************************/
	/******************************************************************************/
	//class edit
	$( document ).on( 'click', '#main .classes li img.edit', function () {
		//get the class info
		var id = $( this ).closest( 'li' ).attr( 'data-id' );
		$.ajax( {
			type: 'POST',
			url: 'rest/classes/get/' + id,
			dataType: 'json',
			success: function ( data ) {
				editClassModal( data );
			}
		} );
	} );

	//language edit
	$( document ).on( 'click', '#main .classes li img.languageEdit', function () {
		//get the class info
		var id = $( this ).closest( 'li' ).attr( 'data-id' );
		$.ajax( {
			type: 'POST',
			url: 'rest/language/listing',
			dataType: 'json',
			success: function ( data ) {
				if ( data.success ) {
					//alert( JSON.stringify( data ) );
					var modal = createModal( {
						title: 'Edit Class',
						buttons: [
							{
								value: 'Edit',
								name: 'edit',
								onclick : getClassAjax
							},
							{
								value: 'Cancel',
								class: 'low'
							}
						]
					} );
					var html = '<form><input type="hidden" name="class" value="' + id + '" />';
					html += '<ul><li><label for="class">Languages</label><select name="language">';
					for ( var i = 0; i < data.data.length; i++ ) {
						html += "<option value='" + data.data[i].id + "'>" + data.data[i].fullName + "</option>";
					}
					html += '</select><span>Pick a class to add</span></li>';
					setModalContent( modal, html );
					displayModal( modal );
					//adjustTextarea( $( modal ).find( 'textarea' )[0] );
				} else {
					var modal = createModal( { title: 'Failed to load class', buttons: [{ value: 'Ok', focus : true }] } );
					setModalContent( modal, data.data.error );
					displayModal( modal, true )
				}
			}
		} );

		function getClassAjax( id ){
			var modal = $( '.modal[data-id=' + id + ']' ).eq( 0 );
			var lang = $( 'select', modal ).val();
			var classId = $( 'input[name="class"]' ).val();
			$.ajax({
				type: 'POST',
				url: 'rest/classes/get/' + classId,
				data : {
					language : lang
				},
				dataType: 'json',
				success: function ( data ) {
					editClassModal( data );
				}
			});
			return true;
		}
	} );

	function editClassModal( data ){
		if ( data.success ) {
			//alert( JSON.stringify( data ) );
			var modal = createModal( {
				title: 'Edit Class',
				buttons: [
					{
						value: 'Save',
						name: 'save',
						onclick: saveClass
					},
					{
						value: 'Cancel',
						class: 'low'
					}
				]
			} );
			var html = "<p>* fields are required</p>";
			html += "<form><ul>";
			//type, name, label, data, text
			html += "<input type='hidden' name='language' value='" + data.data.language + "'/>";
			html += modalLi( 'text', 'id', 'ID*', data.data.id, "Enter the class ID", true );
			html += modalLi( 'text', 'title', 'Title*', data.data.title, "Enter the class title", false, false, 'Class title should look like: CIS-1 - Title' );
			html += modalLi( 'number', 'units', 'Units*', data.data.units, "Enter the class units", false );
			html += modalLi( 'text', 'transfer', 'Transfer', data.data.transfer ? data.data.transfer : '', "Enter the class transfer", false );
			html += modalLi( 'text', 'advisory', 'Advisory', data.data.advisory ? data.data.advisory : '', "Enter the class advisory", false, true );
			html += modalLi( 'text', 'prereq', 'Prerequisite', data.data.prereq ? data.data.prereq : '', "Enter the class prerequisite", false, true );
			html += modalLi( 'text', 'coreq', 'Corequisite', data.data.coreq ? data.data.coreq : '', "Enter the class corequisite", false, true );
			html += modalLi( 'textarea', 'description', 'Description*', data.data.description, "Enter the class Description", false );
			html += "</ul></form>";
			setModalContent( modal, html );
			displayModal( modal );
			adjustTextarea( $( modal ).find( 'textarea' )[0] );
		} else {
			var modal = createModal( { title: 'Failed to load class', buttons: [{ value: 'Ok' }] } );
			setModalContent( modal, data.data.error );
			displayModal( modal, true )
		}
	}

	function saveClass( id ) {
		//assume the only modal open is the one we are saving
		var modal = $( '.modal' ).eq( 0 );
		var form = $( modal ).find( 'form' );
		var saveBtn = $( modal ).find( 'input[name="save"]' );
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
		if ( map['id'].length == 0 ) {
			$( form ).find( 'input[name=id]' ).closest( 'li' ).addClass( 'error' );
			hasError = true;
		}
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
		if( !regex['classTitle'].test( map['title'] ) ){ //if doesnt match the
			$( form ).find( 'input[name=title]' ).closest( 'li' ).addClass( 'error' );
			hasError = true;
			var modal = createModal({ title: "Error", buttons : [{value: "Ok"}] });
			setModalContent( modal, "<p>Title must match this pattern</p><p>EXA-1 - Example title for class example-1</p>");
			displayModal( modal );
		}

		if ( !hasError && !window.processing ) {
			//ajax save
			var successful = false;
			window.processing = true;
			saveBtn.addClass( 'processing' );
			var url;
			if ( map['create'] ) {
				url = 'create';
			} else {
				url = 'save';
			}
			$.ajax( {
				type: 'POST',
				url: 'rest/classes/' + url,
				dataType: 'json',
				data: map,
				async: false,
				success: function ( data ) {
					//alert( JSON.stringify( data ) );
					if ( data.success ) {
						var modal = createModal( { title: "Saved Class Successfully", buttons: [{ value: 'Ok', focus : true }] } );
						setModalContent( modal, data.data.msg );
						displayModal( modal );
						successful = true;
						if ( url == 'create' ) {
							$( '.classes .listing ul' ).append( '<li data-id="' + map['id'] + '">' +
								map['title'] + '<img class="delete" src="assets/img/delete.png"><img class="edit" src="assets/img/edit.svg"></li>' );
						}
						//remove the class cache
						if ( getStorage( 'invalidateCache' ) ) {
							setStorage( 'invalidateCache', +new Date() );
						}
						window.processing = false;
					} else {
						var modal = createModal( { title: "Error Saving Class", buttons: [{ value: 'Ok', focus : true }] } );
						setModalContent( modal, data.data.error );
						displayModal( modal );
						successful = false;
						window.processing = false;
						saveBtn.removeClass( 'processing' );
					}
				}
			} );
			return successful;
		} else {
			saveBtn.removeClass( 'processing' );
			return false;
		}
	}

	//classes edit option
	$( document ).on( 'click', '#main .classes li img.delete', function () {
		//get the class info
		var id = $( this ).closest( 'li' ).attr( 'data-id' );

		var modal = createModal( {
			title: 'Are you sure',
			buttons: [{
				value: "Delete",
				onclick: function ( modalId ) {
					var successful = false;
					$.ajax( {
						type: 'POST',
						url: 'rest/classes/delete/',
						data: {
							id: id
						},
						dataType: 'json',
						async: false,
						success: function ( data ) {
							if ( data.success ) {
								var modal = createModal( {
									title: "Class Deleted Successfully",
									buttons: [{ value: 'Ok' }]
								} );
								setModalContent( modal, data.data.msg );
								displayModal( modal );
								successful = true;
								var that = $( '.classes .listing li[data-id=' + id + ']' );
								that.slideUp( 400, function () {
									that.remove();
								} );
							} else {
								successful = false;
							}
						}
					} );
					return successful;
				}
			}, {
				value: "Cancel",
				class: "low"
			}]
		} );

		setModalContent( modal, "<p>Are you sure you want to delete. This can not be undone</p>" );
		displayModal( modal );
	} );

	//create class
	$( '.classes input[name=createClass]' ).on( 'click', function () {
		var modal = createModal( {
			title: 'Create Class',
			buttons: [
				{
					value: 'Save',
					name: 'save',
					onclick: saveClass
				},
				{
					value: 'Cancel',
					class: 'low'
				}
			]
		} );
		var html = "<p>* fields are required</p>";
		html+="<form><ul>";
		//type, name, label, data, text
		html += "<input type='hidden' name='create' value='create' />";
		html += modalLi( 'text', 'id', 'ID*', '', "Enter the class ID", false, false, 'An sample id for CIS-1A would be c1a' );
		html += modalLi( 'text', 'title', 'Title*', '', "Enter the class title", false, false, 'Class title should look like: CIS-1 - Title' );
		html += modalLi( 'number', 'units', 'Units*', 0, "Enter the class units", false );
		html += modalLi( 'text', 'transfer', 'Transfer', '', "Enter the class transfer", false );
		html += modalLi( 'text', 'advisory', 'Advisory', '', "Enter the class advisory", false, true );
		html += modalLi( 'text', 'prereq', 'Prerequisite', '', "Enter the class prerequisite", false, true );
		html += modalLi( 'text', 'coreq', 'Corequisite', '', "Enter the class corequisite", false, true );
		html += modalLi( 'textarea', 'description', 'Description*', '', "Enter the class Description", false );
		html += "</ul></form>";
		setModalContent( modal, html );
		displayModal( modal );
		adjustTextarea( $( modal ).find( 'textarea' )[0] );
	} );

	$( document ).on( 'click', '.modal span a.addClass', function () {
		var input = $( this ).closest( 'li' ).find( 'input' );
		var prevModal = $( this ).closest( '.modal' );
		var id = $( prevModal ).attr( 'data-id' );
		if ( getStorage( "invalidateCache" ) && getStorage( 'classes' ) ) {
			var time = parseInt( getStorage( "invalidateCache" ) );
			var curr = +new Date(); //gives unix time
			if ( curr >= time ) {
				delete localStorage.classes;
			}
		}
		if ( ( data = getStorageJSON( 'classes' ) ) != null ) {
			createClassModal( data );
		} else {
			$.ajax( {
				type: 'POST',
				url: 'rest/classes/listing',
				dataType: 'json',
				//async : false,
				success: function ( data ) {
					if ( data.success ) {
						setStorageJSON( 'classes', data.data );
						var t = +new Date(); //gives unix time
						setStorage( 'invalidateCache', ( t + ( 5 * 60 * 1000 ) ) );
						createClassModal( data.data );
					}
				}
			} );
		}

		function createClassModal( data ){
			var modal = createModal( {
				title: "Choose Class",
				buttons: [{
					value: 'Add',
					focus : true,
					onclick: function ( id ) {
						var that = $( '.modal[data-id=' + id + ']' );
						var val = $( that ).find( 'select').val();
						//$( input ).val( $( input ).val() + '~' + $( that ).find( 'select' ).val() + '~' );
						var classCode = $( 'option[value=' + val + ']', that ).html().match( /[a-zA-Z]{3}\-\d{1,4}[a-zA-Z]*/ )[0];
						$( input ).val( $( input ).val() + '[class id="' + $( that ).find( 'select' ).val() + '" text="' + classCode + '" /]' );
						return true;
					}
				}, {
					value: 'Cancel',
					class: 'low'
				}]
			} );
			var html = '<form><ul><li><label for="class">Classes</label>' +
				'<select name="class">';
			for ( var i = 0; i < data.length; i++ ) {
				html += "<option value='" + data[i].id + "'>" + data[i].title + "</option>";
			}
			html += '</select><span>Pick a class to add</span></li>';
			setModalContent( modal, html );
			displayModal( modal );
		}
	} );


	/******************************************************************************/
	/****************************certification edit********************************/
	/******************************************************************************/

	//init the html editors
	try {
		tinyMCE.init( {
			mode: "textareas",
			branding: false,
			plugins: "table, lists, code, autoresize, textcolor, colorpicker",
			autoresize_max_height: 600,
			toolbar: 'undo redo | bold italic underline subscript superscript | fontselect fontsizeselect forecolor backcolor ' +
			'alignleft aligncenter alignright alignjustify | formatselect table ' +
			' bullist | addclass shortClass scheduleTable | code',
			menubar: false,
			setup: function ( editor ) {
				editor.addButton( 'addclass', {
					text: 'Add Class',
					icon: false,
					tooltip: 'Insert a link to a class',
					onclick: function () {
						var classText = '';
						if ( getStorage( "invalidateCache" ) && getStorage( 'classes' ) ) {
							var time = parseInt( getStorage( "invalidateCache" ) );
							var curr = +new Date(); //gives unix time
							if ( curr >= time ) {
								delete localStorage.classes;
							}
						}
						if ( ( data = getStorageJSON( 'classes' ) ) != null ) {
							loadClassModal( data );
						} else {
							requestClassListing( function ( data ) {
								if ( data.success ) {
									//store classes in cache
									setStorageJSON( 'classes', data.data );
									var t = +new Date(); //gives unix time
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
					text: '8 Week Class',
					tooltip: 'Add an 8 week class table',
					icon: false,
					onclick: function () {
						var str = "<table><tr><td colspan='2' style='text-align: center;'>8 Week Classes</td></tr>" +
							'<tr><td>class1</td><td style="text-align: right;">class2</td></table>';
						editor.insertContent( str );
					}
				} );


				//function for class listing request
				//onSuccess is the callback function to the success
				function requestClassListing( onSuccess ) {
					$.ajax( {
						type: 'POST',
						url: CORE_URL + '/rest/classes/listing',
						dataType: 'json',
						async: false,
						success: function ( data ) {
							onSuccess( data );
						},
						fail: function ( data ) {
							failedAjax();
						},
						error: function ( data ) {
							failedAjax();
						}
					} );
				}

				//still in setup function for editor
				function loadClassModal( data ) {
					//this either uses the cached copy of the classes or requests it from the network
					//will re-validate the cached copy every 5 minutes or after when you save the page
					var modal = createModal( {
						title: "Choose Class",
						buttons: [{
							value: 'Add',
							onclick: function ( id ) {
								var that = $( '.modal[data-id=' + id + ']' );
								var val = $( that ).find( 'select' ).val();
								var classCode = $( 'option[value=' + val + ']', that ).html().match( /[a-zA-Z]{3}\-\d{1,4}[a-zA-Z]*/ )[0];
								var float = $( 'input[name=float]:checked', that ).val();
								editor.insertContent( '[class id="' + $( that ).find( 'select' ).val() + '" text="' + classCode + '" /]' );
								return true;
							}
						}, {
							value: 'Cancel',
							class: 'low'
						}]
					} );
					var html = '<form><ul><li><label for="class">Classes</label>' +
						'<select name="class">';
					for ( var i = 0; i < data.length; i++ ) {
						html += "<option value='" + data[i].id + "'>" + data[i].title + "</option>";
					}
					html += '</select><span>Pick a class to add</span></li>';
					html += '</ul></form>';
					setModalContent( modal, html );
					displayModal( modal );
				}

				function failedAjax() {
					var modal = createModal( { title: 'Failed to Load', buttons: [{ value: 'Ok' }] } );
					setModalContent( modal, "<p>Failed to load classes. Please try again</p><p>If the problem persists contact the administrator</p>" );
					displayModal( modal );
				}
			}
		} );
	} catch ( ignore ) {
	}

	/******************************************************************************/
	/***************************certification delete*******************************/
	/******************************************************************************/
	$( document ).on( 'click', '#main .certs li img.delete', function () {
		var id = $( this ).closest( 'li' ).attr( 'data-id' );

		var modal = createModal( {
			title: 'Are you sure',
			buttons: [{
				value: "Delete",
				onclick: function ( modalId ) {
					var successful = false;
					$.ajax( {
						type: 'POST',
						url: 'rest/certs/delete/',
						data: {
							id: id
						},
						dataType: 'json',
						async: false,
						success: function ( data ) {
							if ( data.success ) {
								var modal = createModal( {
									title: "Certificate Deleted Successfully",
									buttons: [{ value: 'Ok' }]
								} );
								setModalContent( modal, data.data.msg );
								displayModal( modal );
								successful = true;
								var that = $( '.certs .listing li[data-id=' + id + ']' );
								that.slideUp( 400, function () {
									that.remove();
								} );
							} else {
								successful = false;
							}
						}
					} );
					return successful;
				}
			}, {
				value: "Cancel",
				class: "low"
			}]
		} );

		setModalContent( modal, "<p>Are you sure you want to delete. This can not be undone</p>" );
		displayModal( modal );
	} );

	/******************************************************************************/
	/****************************certification save********************************/
	/******************************************************************************/
	$( '#main .admin .certs [type=submit]' ).on( 'click', function ( e ) {
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
		$( 'li', form ).each( function ( i, elem ) {
			$( elem ).removeClass( 'error' );
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
		if ( map['units'].length == 0 ) {
			scrollTo = $( form ).find( 'input[name=units]' ).closest( 'li' );
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

		if ( !hasError ) {
			//ajax login
			$.ajax( {
				type: 'POST',
				url: CORE_URL + 'rest/' + $( form ).attr( 'action' ),
				dataType: 'json',
				data: map,
				success: function ( data ) {
					console.log( data );
					if ( data.success ) {
						var modal = createModal( {
							title: 'Saved Successfully',
							buttons: [
								{
									value: 'Finished',
									focus : true,
									onclick: function () {
									location.href = CORE_URL + 'editCerts'
								}
								},
								{ value: 'Edit Again', class: 'low' }
							]
						} );
						setModalContent( modal, data.data.msg );
						displayModal( modal );
					} else {
						var modal = createModal( { title: 'Error', buttons : [{ value : 'Ok', focus : true }] } );
						setModalContent( modal, data.data.error );
						displayModal( modal )
					}
				}
			} )
		} else {
			//scroll to error
			$( 'html, body' ).animate( {
				scrollTop: $( scrollTo ).offset().top
			}, 500 );
		}
	} );


	/******************************************************************************/
	/*******************************User Edit/Create*******************************/
	/******************************************************************************/
	$( document ).on( 'click', '#main .users li img.edit', function () {
		//get the class info
		var id = $( this ).closest( 'li' ).attr( 'data-id' );
		$.ajax( {
			type: 'POST',
			url: 'rest/users/get/' + id,
			dataType: 'json',
			success: function ( data ) {
				if ( data.success ) {
					createUserModal( data, false );
				} else {
					var modal = createModal( { title: 'Failed to load class', buttons: [{ value: 'Ok', focus : true }] } );
					setModalContent( modal, data.data.error );
					displayModal( modal, true )
				}
			}
		} );
	} );

	$( '.users input[name=createUser]' ).on( 'click', function () {
		var data = {};
		data.data = { id: -1, username: '', isAdmin: 0, active: 0 };
		createUserModal( data, true );
	} );

	function createUserModal( data, create ) {
		var modalData = {
			title: 'Edit User',
			buttons: [
				{
					value: 'Save',
					name: 'save',
					onclick: saveUser
				},
				{
					value: 'Reset Password',
					onclick: resetUser,
					class: 'low'
				},
				{
					value: 'Cancel',
					class: 'low'
				}
			]
		};
		if ( create ) {
			modalData.buttons.splice( 1, 1 );
			modalData.title = "Create User";
		}
		var modal = createModal( modalData );
		var html = "<form><ul>";
		//type, name, label, data, text
		html += "<input type='hidden' name='id' value='" + ( data.data.id ? data.data.id : -1 ) + "'/>";
		if ( create ) {
			html += "<input type='hidden' name='create' value='create'/>";
		}
		html += modalLi( 'text', 'username', 'User name', data.data.username, "Enter the username", false );
		html += modalLi( 'checkbox', 'isAdmin', 'Admin', data.data.isAdmin, "Check if the user should be able to edit users", false );
		html += modalLi( 'checkbox', 'active', 'Active User', data.data.active, "Check if the user should be allowed to login", false );
		html += "</ul></form>";
		setModalContent( modal, html );
		displayModal( modal );
	}

	function saveUser( id ) {
		var modal = $( '.modal[data-id=' + id + ']' );
		var form = $( modal ).find( 'form' );
		var saveBtn = $( modal ).find( 'input[name="save"]' );
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
		if ( map['username'].length == 0 ) {
			$( form ).find( 'input[name=username]' ).closest( 'li' ).addClass( 'error' );
			hasError = true;
		}
		if ( !hasError && !window.processing ) {
			//ajax save
			var successful = false;
			window.processing = true;
			saveBtn.addClass( 'processing' );
			$.ajax( {
				type: 'POST',
				url: 'rest/users/save',
				dataType: 'json',
				data: {
					id: map.id,
					create: (map.create) ? map.create : 0,
					username: map.username,
					isAdmin: ( map.isAdmin ) ? map.isAdmin : 0,
					active: ( map.active ) ? map.active : 0
				},
				async: false,
				success: function ( data ) {
					//alert( JSON.stringify( data ) );
					if ( data.success ) {
						var modal = createModal( { title: "Saved User Successfully", buttons: [{ value: 'Ok', focus : true }] } );
						setModalContent( modal, data.data.msg );
						displayModal( modal );
						successful = true;
						if ( map.create ) {
							$( '.users .listing ul' ).append( '<li data-id="' + data.data['id'] + '">' +
								map['username'] + '<img class="delete" src="assets/img/delete.png"><img class="edit" src="assets/img/edit.svg"></li>' );
						}
						window.processing = false;
					} else {
						var modal = createModal( { title: "Error Saving User", buttons: [{ value: 'Ok', focus : true }] } );
						setModalContent( modal, data.data.error );
						displayModal( modal );
						successful = false;
						window.processing = false;
						saveBtn.removeClass( 'processing' );
					}
				}
			} );
			return successful;
		} else {
			saveBtn.removeClass( 'processing' );
			return false;
		}
	}

	function resetUser( id ) {
		var modal = $( '.modal[data-id=' + id + ']' );
		var userId = modal.find( 'input[name=id]' ).val();
		$.ajax( {
			type: 'POST',
			url: 'rest/users/createResetPassword/' + userId,
			dataType: 'json',
			async: false,
			success: function ( data ) {
				//alert( JSON.stringify( data ) );
				if ( data.success ) {
					var modal = createModal( { title: "Reset Password", buttons: [{ value: 'Ok' }] } );
					setModalContent( modal, data.data.msg );
					displayModal( modal );
				} else {
					var modal = createModal( { title: "Error Saving User", buttons: [{ value: 'Ok' }] } );
					setModalContent( modal, data.data.error );
					displayModal( modal );
				}
			}
		} );
	}


	/******************************************************************************/
	/********************************User Delete***********************************/
	/******************************************************************************/
	$( document ).on( 'click', '#main .users li img.delete', function () {
		var id = $( this ).closest( 'li' ).attr( 'data-id' );
		var modal = createModal( {
			title: 'Are you sure',
			buttons: [{
				value: "Delete",
				onclick: function ( modalId ) {
					var successful = false;
					$.ajax( {
						type: 'POST',
						url: 'rest/users/delete/',
						data: {
							id: id
						},
						dataType: 'json',
						async: false,
						success: function ( data ) {
							if ( data.success ) {
								var modal = createModal( {
									title: "Users Deleted Successfully",
									buttons: [{ value: 'Ok' }]
								} );
								setModalContent( modal, data.data.msg );
								displayModal( modal );
								successful = true;
								var that = $( '.users .listing li[data-id=' + id + ']' );
								that.slideUp( 400, function () {
									that.remove();
								} );
							} else {
								successful = false;
							}
						}
					} );
					return successful;
				}
			}, {
				value: "Cancel",
				class: "low"
			}]
		} );

		setModalContent( modal, "<p>Are you sure you want to delete. This can not be undone</p>" );
		displayModal( modal );
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
			var modal = createModal( { title: 'Error', buttons: [{ value: 'Ok' }] } );
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

			//todo make sure this doesn't alternate because of the /[a-z]/g flag
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
} );


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

window.modals = { ids: [], data: {}, displaying: false };
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
 *  		focus : true //focus on this button
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
	var html = '<div data-id="' + id + '" class="modal none">' +
		'<div class="modalWrapper">'
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
	$( 'body' ).append( html );
	window.modals.data[id] = options
	window.modals.ids.push( id );
	return $( '.modal[data-id=' + id + ']' );
}

function setModalContent( modal, html ) {
	$( modal ).find( '.modalContent' ).html( html );
}

function appendModalContent( modal, html ){
	$( modal ).find( '.modalContent' ).html( $( modal ).find( '.modalContent' ).html() + html );
}

function displayModal( modal, focusIndex ) {
	$( modal ).fadeIn( 300 );
	window.modals.displaying = $( modal ).attr( 'data-id' );
}

function closeModal( modal ) {
	modal.find( '.modalWrapper' ).hide();
	var id = $( modal ).attr( 'data-id' );
	$( modal ).fadeOut( 300, function () {
		$( modal ).remove();
		delete window.modals.data[id];
		window.modals.ids.splice( window.modals.ids.indexOf( id ), 1 );
		if ( $( '.modal' ).last().length ) {
			window.modals.displaying = $( '.modal' ).last().attr( 'data-id' );
		} else {
			window.modals.displaying = false;
		}

	} );
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
 * put a cokkie into storage
 * @param name
 * @param data
 * @param expires
 */
function setCookie( name, data, expires ){
	if( expires ){
		var date = new Date();
		date.setTime(date.getTime()+( expires * 24 * 60 * 60 * 1000));
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

var regex = {};
regex['password'] = /.*(?=.{10,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[&"'(\-_)=~#{[|`\\^@\]}^$*иг╡%,;:!?./з+]).*/g;
regex['strongPassword'] = /^(?!.*(.)\1{1})(?=(.*[\d]){2,})(?=(.*[a-z]){2,})(?=(.*[A-Z]){2,})(?=(.*[@#$%!]){2,})(?:[\da-zA-Z@#$%!]){15,100}$/;
regex['email'] = /[a-z\d]+([\.\_]?[a-z\d]+)+@[a-z\d]+(\.[a-z]+)+/;
regex['classTitle'] = /^[A-Za-z]{3}-.+? - .+$/;