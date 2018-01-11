/**
 * Created by michael on 9/26/2017.
 */

$( document ).ready( function(){
	//class edit
	$( document ).on( 'click', '#main .classes li img.edit', function () {
		//get the class info
		var id = $( this ).closest( 'li' ).attr( 'data-id' );
		$.ajax( {
			type: 'POST',
			url: CORE_URL + 'rest/classes/edit/' + id,
			success: function ( data ) {
				if ( data ) {
					createClassModal( data, false );
				} else {
					var modal = createModal( { title: 'Failed to load user', buttons: [{ value: 'Ok', focus : true }] } );
					setModalContent( modal, "An error occurred." );
					displayModal( modal, true )
				}
			}
		} );
	} );

	function createClassModal( data, create ){
		var modal = createModal( {
			title: ( ( create ) ? ( 'Create Class' ) : ( 'Edit Class' ) ),
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
		setModalContent( modal, data );
		displayModal( modal );
		adjustTextarea( $( modal ).find( 'textarea' )[0] );
		$( 'select', modal ).select2();
	}

	//language edit
	$( document ).on( 'click', '#main .classes li img.languageEdit', function () {
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
								onclick : getClassAjax
							},
							{
								value: 'Cancel',
								class: 'low'
							}
						]
					} );
					var html = '<form><input type="hidden" name="class" value="' + id + '" />';
					html += '<ul><li><label for="language">Languages</label><select name="language">';
					for ( var i = 0; i < data.data.length; i++ ) {
						html += "<option value='" + data.data[i].id + "'>" + data.data[i].code + ' - ' + data.data[i].fullName + "</option>";
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
				url: CORE_URL + 'rest/classes/edit/' + classId,
				data : {
					language : lang
				},
				success: function ( data ) {
					createClassModal( data );
				}
			});
			return true;
		}
	} );

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
		var scrollTo = null;
		if( typeof map['discipline'] == 'undefined' ){
			scrollTo = $( form ).find( 'select[name=discipline]' ).closest( 'li' );
			scrollTo.addClass( 'error' );
			hasError = true;
		}
		if ( map['title'].length == 0 ) {
			scrollTo = $( form ).find( 'input[name=title]' ).closest( 'li' );
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
		if( !regex['classTitle'].test( map['title'] ) ){ //if doesnt match the
			scrollTo = $( form ).find( 'input[name=title]' ).closest( 'li' );
			scrollTo.addClass( 'error' );
			hasError = true;
			var modalNew = createModal({ title: "Error", buttons : [{value: "Ok"}] });
			setModalContent( modalNew, "<p>Title must match this pattern</p><p>EXA-1 - Example title for class example-1</p>");
			displayModal( modalNew );
		}

		if ( !hasError && !window.processing ) {
			//ajax save
			var successful = false;
			window.processing = true;
			saveBtn.addClass( 'processing' );
			var url = 'save/' + map.id;
			$.ajax( {
				type: 'POST',
				url: CORE_URL + 'rest/classes/' + url,
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
						if ( map['create'] ) {
							var str = '<li data-id="' + map['id'] + '">' + map['title'];
							if ( data.data.deletable ) str += '<img class="delete" src="' + CORE_URL + 'assets/img/delete.png">';
							if ( data.data.editable ) str += '<img class="languageEdit tooltip" src="' + CORE_URL + 'assets/img/region.png" title="Edit in Different Language"><img class="edit" src="' + CORE_URL + 'assets/img/edit.svg">';
							str += '</li>';

							$( '.classes .listing ul' ).append( str );
						} else {
							var str = map['title'];
							if ( data.data.deletable ) str += '<img class="delete" src="' + CORE_URL + 'assets/img/delete.png">';
							if ( data.data.editable ) str += '<img class="languageEdit tooltip" src="' + CORE_URL + 'assets/img/region.png" title="Edit in Different Language"><img class="edit" src="' + CORE_URL + 'assets/img/edit.svg">';
							$( 'li[data-id=' + map['id'] + ']' ).html( str );
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
			//scroll to error
			$( 'div.modalContent' ).animate( {
				scrollTop: $( scrollTo ).offset().top - $('div.modalContent' ).offset().top
			}, 500 );
			saveBtn.removeClass( 'processing' );
			return false;
		}
	}

	//classes delete option
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
						url: CORE_URL + 'rest/classes/delete/',
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

	//create class
	$( '.classes input[name=createClass]' ).on( 'click', function () {
		$.ajax( {
			type: 'POST',
			url: CORE_URL + 'rest/classes/edit',
			success: function ( data ) {
				if ( data ) {
					createClassModal( data, true );
				} else {
					var modal = createModal( { title: 'Error', buttons: [{ value: 'Ok', focus : true }] } );
					setModalContent( modal, "Failed to contact database. Please try again." );
					displayModal( modal, true )
				}
			}
		} );
	} );

	//add class
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
			createClassListModal( data );
		} else {
			$.ajax( {
				type: 'POST',
				url: CORE_URL + 'rest/classes/listing',
				data : { all : true },
				dataType: 'json',
				//async : false,
				success: function ( data ) {
					if ( data.success ) {
						setStorageJSON( 'classes', data.data.listing );
						var t = +new Date(); //gives unix time
						setStorage( 'invalidateCache', ( t + ( 5 * 60 * 1000 ) ) );
						createClassListModal( data.data.listing );
					}
				}
			} );
		}

		function createClassListModal( data ){
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
			$( 'select', modal ).select2();
		}
	} );

	$( '.classes input.search' ).on( 'keyup', function(e) {
		clearTimeout( searchTypingTimer );
		if( e.keyCode != 13 ){
			searchTypingTimer = setTimeout( classSearch, 1000 );
		}
	} );

	$( '.classes input.search' ).on( 'search', function( e ){
		clearTimeout( searchTypingTimer );
		classSearch();
	} );

	function classSearch(){
		var value = $( '.classes input.search' ).val();
		$.ajax({
			type: 'POST',
			url: CORE_URL + 'rest/classes/listing',
			dataType: 'json',
			data: {
				page : 1,
				search : value
			},
			success : function( data ){
				if( data.success ){
					var listing = $( '.listing ul');
					$( listing ).html('');
					var data = data.data;
					for( var i = 0; i < data.listing.length; i++ ){
						var item = data.listing[i];
						var s = "<li data-id='" + item.id + "'>" + item.title;
						if( item.delete )
							s += '<img class="delete tooltip" title="Delete class" src="' + CORE_URL + 'assets/img/delete.png">';
						if( item.edit ) {
							s += '<img class="languageEdit tooltip" title="Edit in Different Language" src="' + CORE_URL + 'assets/img/region.png">';
							s += '<img class="edit tooltip" title="Edit class" src="' + CORE_URL + 'assets/img/edit.svg">';
						}
						s += "</li>";
						$( listing ).append( s );
					}

					var pagesDom = $( '.pages div' );
					$( pagesDom ).html('');
					var pages = Math.ceil( data.count / data.limit );
					var currentPage = data.currentPage;
					var amount = 3;
					var str = '';
					if(  currentPage > 1 ){
						str += "<a href='" + CORE_URL + "editClass/1'/>|&lt;</a>";
					}
					//left side of current math
					var left = 0;
					if( currentPage <= amount ){
						left = ( ( currentPage - amount ) + amount ) - 1;
					} else{
						left = amount;
					}
					for( var i = left; i >= 1; i-- ){
						str += "<a href='" + CORE_URL + 'editClass/' + ( currentPage - $i ) + "?q=" + value + "'>" + ( currentPage - $i ) + "</a>";
					}
					str += "<a href='" + CORE_URL + 'editClass/' + ( currentPage ) + "?q=" + value + "' class='current'>"  + ( currentPage ) + "</a>";
					//right side of current math
					for( var i = 1; i <= amount; i++ ){
						if( ( currentPage + i ) > pages ){ break; }
						str += "<a href='" + CORE_URL + 'editClass/' + ( currentPage + i ) + "?q=" + value + "'>"  + ( currentPage + i ) + "</a>";
					}
					if(  currentPage < pages ){
						str += "<a href='" + CORE_URL + "editClass/" + pages + "?q=" + value + "'>&gt;|</a>";
					}
					$( pagesDom ).html( str );
				} else{
					//TODO catch a search failure
				};
			}
		});
	}
} );
