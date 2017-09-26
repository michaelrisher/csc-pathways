/**
 * Created by michael on 9/26/2017.
 */


$( document ).ready( function(){

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
									setStorageJSON( 'classes', data.data.listing );
									var t = +new Date(); //gives unix time
									setStorage( 'invalidateCache', ( t + ( 5 * 60 * 1000 ) ) );
									loadClassModal( data.data.listing );
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
					$( 'select', modal ).select2();
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

	//language edit
	$( document ).on( 'click', '#main .certs li img.languageEdit', function () {
		//get the class info
		var id = $( this ).closest( 'li' ).attr( 'data-id' );
		$.ajax( {
			type: 'POST',
			url: CORE_URL + 'rest/language/listing',
			dataType: 'json',
			success: function ( data ) {
				if ( data.success ) {
					//alert( JSON.stringify( data ) );
					var modal = createModal( {
						title: 'Pick language to edit class in',
						buttons: [
							{
								value: 'Edit',
								name: 'edit',
								onclick : function( id ){
									var modal = $( '.modal[data-id=' + id + ']' ).eq( 0 );
									var lang = $( 'select', modal ).val();
									var certId = $( 'input[name="cert"]' ).val();
									location.href = CORE_URL + 'certs/edit/' + certId + '/' + lang;
									return true;
								}
							},
							{
								value: 'Cancel',
								class: 'low'
							}
						]
					} );
					var html = '<form><input type="hidden" name="cert" value="' + id + '" />';
					html += '<ul><li><label for="language">Languages</label><select name="language">';
					for ( var i = 0; i < data.data.length; i++ ) {
						html += "<option value='" + data.data[i].id + "'>" + data.data[i].code + ' - ' + data.data[i].fullName + "</option>";
					}
					html += '</select><span>Pick a class to add</span></li>';
					setModalContent( modal, html );
					displayModal( modal );
					//adjustTextarea( $( modal ).find( 'textarea' )[0] );
				} else {
					var modal = createModal( { title: 'Failed to load class', buttons: [{ value: 'Ok' }] } );
					setModalContent( modal, data.data.error );
					displayModal( modal, true )
				}
			}
		} );
	} );

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
						url: CORE_URL + 'rest/certs/delete/',
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
} );