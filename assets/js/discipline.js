/**
 * Created by michael on 11/9/2017.
 */
$( document ).ready( function(){
	function editModal( data, create ){
		var modal = createModal( {
			title: ( ( create ) ? ( 'Create Discipline' ) : ( 'Edit Discipline' ) ),
			buttons: [
				{
					value: 'Save',
					name: 'save',
					onclick: save
				},
				{
					value: 'Cancel',
					class: 'low'
				}
			]
		} );
		setModalContent( modal, data );
		displayModal( modal );
	}

	/************************ Edit ************************/
	$( document ).on( 'click', '#main .listing li img.edit', function () {
		//get the class info
		var id = $( this ).closest( 'li' ).attr( 'data-id' );
		$.ajax( {
			type: 'POST',
			url: CORE_URL + 'rest/discipline/edit/' + id,
			success: function ( data ) {
				if ( data ) {
					editModal( data, false );
				} else {
					var modal = createModal( { title: 'Failed to load discipline', buttons: [{ value: 'Ok', focus : true }] } );
					setModalContent( modal, "An error occurred." );
					displayModal( modal, true )
				}
			}
		} );
	} );

	/************************ Create ************************/
	$( 'input[name=createDiscipline]' ).on( 'click', function () {
		$.ajax( {
			type: 'POST',
			url: CORE_URL + 'rest/discipline/edit',
			success: function ( data ) {
				if ( data ) {
					editModal( data, true );
				} else {
					var modal = createModal( { title: 'Error', buttons: [{ value: 'Ok', focus : true }] } );
					setModalContent( modal, "Failed to contact database. Please try again." );
					displayModal( modal, true )
				}
			}
		} );
	} );

	/************************ save ************************/
	function save( modalId ){
		var modal = $( '.modal[data-id='+ modalId + ']' ).eq( 0 );
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
		if ( map['name'].length == 0 ) {
			$( form ).find( 'input[name=name]' ).closest( 'li' ).addClass( 'error' );
			hasError = true;
		}
		if ( map['description'].length == 0 ) {
			$( form ).find( 'textarea[name=description]' ).closest( 'li' ).addClass( 'error' );
			hasError = true;
		}

		if ( !hasError && !window.processing ) {
			//ajax save
			var successful = false;
			window.processing = true;
			saveBtn.addClass( 'processing' );
			var url = 'rest/discipline/save/' + map.id;
			$.ajax( {
				type: 'POST',
				url: CORE_URL + url,
				dataType: 'json',
				data: map,
				async: false,
				success: function ( data ) {
					if ( data.success ) {
						var modal = createModal( { title: "Discipline Successfully", buttons: [{ value: 'Ok', focus : true }] } );
						setModalContent( modal, data.data.msg );
						displayModal( modal );
						successful = true;
						if ( map['create'] ) {
							var str = '<li data-id="' + data.data.id + '">' + map['description'];
							str += '<img class="delete" src="' + CORE_URL + 'assets/img/delete.png">';
							str += '<img class="edit" src="' + CORE_URL + 'assets/img/edit.svg">';
							str += '</li>';

							$( '.listing ul' ).append( str );
						} else {
							var str = map['description'];
							str += '<img class="delete" src="' + CORE_URL + 'assets/img/delete.png">';
							str += '<img class="edit" src="' + CORE_URL + 'assets/img/edit.svg">';
							$( 'li[data-id=' + map['id'] + ']' ).html( str );
						}
						window.processing = false;
					} else {
						var modal = createModal( { title: "Error Saving Discipline", buttons: [{ value: 'Ok', focus : true }] } );
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

	/************************ Delete ************************/
	$( document ).on( 'click', '#main .listing li img.delete', function () {
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
						url: CORE_URL + 'rest/discipline/delete/' + id,
						dataType: 'json',
						async: false,
						success: function ( data ) {
							if ( data.success ) {
								var modal = createModal( {
									title: "Discipline Deleted Successfully",
									buttons: [{ value: 'Ok' }]
								} );
								setModalContent( modal, data.data.msg );
								displayModal( modal );
								successful = true;
								var that = $( '.listing li[data-id=' + id + ']' );
								that.slideUp( 400, function () {
									that.remove();
								} );
							} else {
								var modal = createModal( {
									title: "Error",
									buttons: [{ value: 'Ok' }]
								} );
								setModalContent( modal, data.data.error );
								displayModal( modal );
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

} );