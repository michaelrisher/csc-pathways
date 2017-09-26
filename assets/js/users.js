/**
 * Created by michael on 9/26/2017.
 */

$( document ).ready( function(){
	/******************************************************************************/
	/*******************************User Edit/Create*******************************/
	/******************************************************************************/
	$( document ).on( 'click', '#main .users li img.edit', function () {
		//get the class info
		var id = $( this ).closest( 'li' ).attr( 'data-id' );
		$.ajax( {
			type: 'POST',
			url: CORE_URL + 'rest/users/get/' + id,
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
		var html = "<div class='tabWrapper users'>"+
			"<div class='tab active' data-tab='edit' >Edit</div>"+
			"<div class='tab' data-tab='roles' >Roles</div>"+
			"<div class='tab' data-tab='dept' >Discipline</div>"+
			"</div>" +
			"<form><ul>";
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
				url: CORE_URL + 'rest/users/save',
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