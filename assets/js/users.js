/**
 * Created by michael on 9/26/2017.
 */

$( document ).ready( function(){
	/******************************************************************************/
	/*******************************User Edit/Create*******************************/
	/******************************************************************************/
	$( document ).on( 'click', '#main .users li img.view', function () {
		//get the class info
		var id = $( this ).closest( 'li' ).attr( 'data-id' );
		$.ajax( {
			type: 'POST',
			url: CORE_URL + 'rest/users/edit/' + id,
			success: function ( data ) {
				if ( data ) {
					createUserModal( data, false, true );
				} else {
					var modal = createModal( { title: 'Failed to load user', buttons: [{ value: 'Ok', focus : true }] } );
					setModalContent( modal, "An error occurred." );
					displayModal( modal, true )
				}
			}
		} );
	} );

	$( document ).on( 'click', '#main .users li img.edit', function () {
		//get the class info
		var id = $( this ).closest( 'li' ).attr( 'data-id' );
		$.ajax( {
			type: 'POST',
			url: CORE_URL + 'rest/users/edit/' + id,
			success: function ( data ) {
				if ( data ) {
					createUserModal( data, false );
				} else {
					var modal = createModal( { title: 'Failed to load user', buttons: [{ value: 'Ok', focus : true }] } );
					setModalContent( modal, "An error occurred." );
					displayModal( modal, true )
				}
			}
		} );
	} );

	$( '.users input[name=createUser]' ).on( 'click', function () {
		$.ajax( {
			type: 'POST',
			url: CORE_URL + 'rest/users/edit/-1',
			success: function ( data ) {
				if ( data ) {
					createUserModal( data, true );
				} else {
					var modal = createModal( { title: 'Failed to load user', buttons: [{ value: 'Ok', focus : true }] } );
					setModalContent( modal, "An error occurred." );
					displayModal( modal, true )
				}
			}
		} );
	} );

	function createUserModal( data, create, readonly ) {
		readonly = ( typeof readonly == 'undefined' ? false : true );
		var modalData = {
			title: 'Edit User',
			shadowClose: true,
			shadowPrompt: "Are you sure you want to close? The user will not be saved",
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
		if ( readonly ) {
			modalData.title = "View User";
			modalData.buttons = [
				{
					value: 'Close'
				}
			]
		}
		var modal = createModal( modalData );
		setModalContent( modal, data );
		displayModal( modal );
	}

	/******************************************************************************/
	/**********************************User Save***********************************/
	/******************************************************************************/
	function saveUser( id ) {
		var modal = $( '.modal[data-id=' + id + ']' );
		var form = $( modal ).find( 'form' );
		var saveBtn = $( modal ).find( 'input[name="save"]' );
		var data = [];
		form.each( function( i, elem ){
			var a = $( elem ).serializeArray();
			data = data.concat( a );
		} );
		//var data = $( form ).serializeArray();
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
			map.create = ( ( map.create ) ? ( map.create ) : ( 0 ) );
			map.isAdmin = ( ( map.isAdmin ) ? ( map.isAdmin ) : ( 0 ) );
			map.active = ( ( map.active ) ? ( map.active ) : ( 0 ) );
			$.ajax( {
				type: 'POST',
				url: CORE_URL + 'rest/users/save',
				dataType: 'json',
				data: map,
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
			window.processing = false;
			saveBtn.removeClass( 'processing' );
			return false;
		}
	}

	/******************************************************************************/
	/*********************************Reset User**********************************/
	/******************************************************************************/
	function resetUser( id ) {
		var modal = $( '.modal[data-id=' + id + ']' );
		var userId = modal.find( 'input[name=id]' ).val();
		$.ajax( {
			type: 'POST',
			url: CORE_URL + 'rest/users/createResetPassword/' + userId,
			dataType: 'json',
			async: false,
			success: function ( data ) {
				//alert( JSON.stringify( data ) );
				if ( data.success ) {
					var modal = createModal( { title: "Reset Password", buttons: [{ value: 'Ok' }] } );
					setModalContent( modal, data.data.msg );
					displayModal( modal );
				} else {
					var modal = createModal( { title: "Error Resetting User", buttons: [{ value: 'Ok' }] } );
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
						url: CORE_URL + 'rest/users/delete/',
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
								var modal = createModal( {
									title: "An Error Occurred",
									buttons: [{ value: 'Ok' }]
								} );
								setModalContent( modal, data.data.error );
								displayModal( modal );
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
	/*******************************Add user role**********************************/
	/******************************************************************************/
	$( document ).on( 'change', '.modal #roleTable input[type=checkbox]', function() {
		var that = $(this);
		var module = $( this ).attr( 'data' ).split( '-' )[0];
		var field = $( this ).attr( 'data' ).split( '-' )[1];
		var action = field.substr( 1 );
		var fields = $( 'input[type=checkbox][data*="' + module + '"][name*="' + action + '"]' );
		var otherPair;
		//find the other button either in global or discipline
		fields.each( function ( i, elem ) {
			if( $( that ).val() != $( elem ).val() ){
				otherPair = elem;
			}
		} );
		if( isset( otherPair ) ) {
			if ( $( otherPair ).is( ':checked' ) ) {
				//uncheck it
				$( otherPair ).prop( 'checked', false );
			}
		} else{
			//it could be the dataManage
			if( $( that ).attr( 'data' ) == 'all-dataManage' ) {
				if( $( that ).is( ':checked' ) ){
					//uncheck all to start fresh
					$( '.modal .userRoles input[type=checkbox]:not([data=all-dataManage])' ).prop( 'checked', false );
					//check all the global boxes
					$( 'input[data*="class-g"]').prop( 'checked', true );
					$( 'input[data*="cert-g"]').prop( 'checked', true );
					$( 'input[data*="user-g"]').prop( 'checked', true );
				}
			}
		}
	});

	/******************************************************************************/
	/*******************************Add user discipline**********************************/
	/******************************************************************************/
	$( document ).on( 'click', '.modal .userDept .add input[type=button]', function(){
		$.ajax( {
			type: 'POST',
			url: CORE_URL + 'rest/users/modalAddDiscipline',
			success: function ( data ) {
				//alert( JSON.stringify( data ) );
				if ( data ) {
					var modal = createModal( {
						title: "Add Discipline",
						buttons: [{
							value: 'Add',
							onclick : function( id ){
								//get value
								var value = $( '.modal[data-id=' + id + '] select' ).val();
								//get ids already displayed in the listing
								var idsSet = JSON.parse( $( '.userDept input[name=disciplines]' ).val() );
								//check id isnt already there
								for( var i = 0; i < idsSet.length; i++ ){
									if( idsSet[i] == value ){
										var modal = createModal( { title: "Error", buttons: [{ value: 'Ok', focus:true }] } );
										setModalContent( modal, "You cannot add the same discipline twice." );
										displayModal( modal );
										return false;
									}
								}
								idsSet.push( value );
								var html = "<li data-id='" + value + "'>";
								html += $( '.modal[data-id=' + id + '] select option:selected' ).text();
								html += '<img class="delete tooltip" src="' + CORE_URL + 'assets/img/delete.png" title="Delete Discipline">'
								html += '</li>';
								//add to listing
								$( '.userDept .listing' ).append( html );
								//add to input
								$( '.userDept input[name=disciplines]' ).val( JSON.stringify( idsSet ) );
								return true;
							}
						},{
							value: 'Cancel',
							class: 'low'
						}]
					} );
					setModalContent( modal, data );
					displayModal( modal );
					$( 'select', modal ).select2();
				} else {
					var modal = createModal( { title: "Error Saving User", buttons: [{ value: 'Ok' }] } );
					setModalContent( modal, data.data.error );
					displayModal( modal );
				}
			}
		} );
	} );


	/******************************************************************************/
	/******************************delete user discipline**************************/
	/******************************************************************************/
	$( document ).on( 'click', '.modal .userDept img.delete', function(){
		var li = $( this ).closest( 'li' );
		var id = $( li ).attr( 'data-id' );
		//get ids already displayed in the listing
		var idsSet = JSON.parse( $( '.userDept input[name=disciplines]' ).val() );

		var index = idsSet.indexOf( id );
		idsSet.splice( index, 1 );
		$( '.userDept input[name=disciplines]' ).val( JSON.stringify( idsSet ) );

		$( li ).slideUp( 400, function(){
			$( li ).remove();
		}  );

	} );
} );