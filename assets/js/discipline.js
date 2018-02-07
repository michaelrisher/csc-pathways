/**
 * Created by michael on 11/9/2017.
 */
$( document ).ready( function(){
	function editModal( data, create ){
		var modal = createModal( {
			title: ( ( create ) ? ( 'Create Discipline' ) : ( 'Edit Discipline' ) ),
			shadowClose: true,
			shadowPrompt: "Are you sure you want to close? The discipline will not be saved",
			buttons: [
				{
					value: 'Save',
					name: 'save',
					focus: true,
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
		$( 'input[name=name]' ).focus();
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
					displayModal( modal, true );
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
					displayModal( modal, true );
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
							var str = '<li data-id="' + data.data.id + '">' + map['name'] + ' ' + map['description'];
							str += '<img class="delete" src="' + CORE_URL + 'assets/img/delete.png">';
							str += '<img class="edit" src="' + CORE_URL + 'assets/img/edit.svg">';
							str += '</li>';

							$( '.listing ul' ).append( str );
						} else {
							var str = map['name'] + ' ' + map['description'];
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

		setModalContent( modal, "<p style='color:red'>If this discipline is being used the references to this discipline will remain. This will cause major problems</p>" +
			"<p>Are you sure you want to delete. This can not be undone</p>" );
		displayModal( modal );
	} );

	/****************************Search Filter Button**********************************/
	$( '.classes .searchBar .search span' ).on( 'click', function(){
		var chevron = $( 'i', this );
		var filter = $( '.classes .searchBar .searchFilter' );
		if( $( chevron ).hasClass( 'down' ) ){
			//menu is not open
			//change icon
			$({deg: 0}).animate({deg: 180}, {
				step: function(now, fx){
					$( chevron).css({
						transform: "rotate(" + now + "deg)"
					});
				}
			});
			$( filter ).slideDown( 400, function(){
				$( chevron ).removeClass( 'down' );
				$( chevron ).addClass( 'up' );
			} );
		} else {
			//menu is open
			//change icon
			$({deg: 180}).animate({deg: 0}, {
				step: function(now, fx){
					$( chevron).css({
						transform: "rotate(" + now + "deg)"
					});
				}
			});
			$( filter ).slideUp( 400, function(){
				$( chevron ).removeClass( 'up' );
				$( chevron ).addClass( 'down' );
			} );
		}
	} );

	$( '.classes .search input.search' ).on( 'keyup', function(e) {
		clearTimeout( searchTypingTimer );
		if( e.keyCode != 13 ){
			searchTypingTimer = setTimeout( classSearch, 1000 );
		}
	} );

	$( '.classes .search input.search' ).on( 'search', function( e ){
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
				populateListing( data );
			}
		});
	}

	function populateListing( data ){
		if( data.success ){
			var value = data.data.q;
			var listing = $( '.listing ul');
			$( listing ).html('');
			var pagesDom = $( '.pages div' );
			var data = data.data;
			if( data.listing.length > 0 ) {
				for ( var i = 0; i < data.listing.length; i++ ) {
					var item = data.listing[i];
					var s = "<li data-id='" + item.id + "'>" + item.title;
					if ( item.delete )
						s += '<img class="delete tooltip" title="Delete class" src="' + CORE_URL + 'assets/img/delete.png">';
					if ( item.edit ) {
						s += '<img class="languageEdit tooltip" title="Edit in Different Language" src="' + CORE_URL + 'assets/img/region.png">';
						s += '<img class="edit tooltip" title="Edit class" src="' + CORE_URL + 'assets/img/edit.svg">';
					} else {
						s += '<img class="view tooltip" title="View class" src="' + CORE_URL + 'assets/img/view.png">';
					}
					s += "</li>";
					$( listing ).append( s );
				}

				$( pagesDom ).html( '' );
				var pages = Math.ceil( data.count / data.limit );
				var currentPage = data.currentPage;
				var amount = 3;
				var str = '';
				var link = 'editDisciplines';
				if ( currentPage > 1 ) {
					str += "<a href='" + CORE_URL + link + "/1'/>|&lt;</a>";
				}
				//left side of current math
				var left = 0;
				if ( currentPage <= amount ) {
					left = ( ( currentPage - amount ) + amount ) - 1;
				} else {
					left = amount;
				}
				var qsa = "?q=" + value;
				if( isset( data.sort ) ){
					qsa += '&sort=' + data.sort
				}
				if( isset( data.discs ) ){
					qsa += '&discs=' + data.discs;
				}
				if( isset( data.limit ) ){
					qsa += '&limit=' + data.limit;
				}
				for ( var i = left; i >= 1; i-- ) {
					str += "<a href='" + CORE_URL + link + '/' + ( currentPage - $i ) + qsa + "'>" + ( currentPage - $i ) + "</a>";
				}
				str += "<a href='" + CORE_URL + link + '/' + ( currentPage ) + qsa + "' class='current'>" + ( currentPage ) + "</a>";
				//right side of current math
				for ( var i = 1; i <= amount; i++ ) {
					if ( ( currentPage + i ) > pages ) {
						break;
					}
					str += "<a href='" + CORE_URL + link + '/' + ( currentPage + i ) + qsa + "'>" + ( currentPage + i ) + "</a>";
				}
				if ( currentPage < pages ) {
					str += "<a href='" + CORE_URL + link + "/" + pages + qsa + "'>&gt;|</a>";
				}
				$( pagesDom ).html( str );
			} else{
				$( listing ).append( '<li>There are no disciplines that match</li>' );
				$( pagesDom ).html('');
			}
		} else{
			$( '.listing ul' ).html( '<li>An error occurred searching for that. Please refresh the page and try again</li>' );
			$( '.pages div' ).html('');
		};
	}
	/****************************Search Filter Dropdown********************************/
	$( '.classes .searchFilter select' ).on( 'change', function(){
		var searchVal = $( '.classes input.search' ).val();
		var selects = $( '.classes .searchFilter select' );
		var filter = {};
		$( selects ).each( function( i, elem ){
			filter[$( elem ).attr( 'name' )] = $( elem ).val();
		} );
		console.log( $( this ).attr( 'name' ) );
		$.ajax({
			type: 'POST',
			url: CORE_URL + 'rest/classes/listing',
			dataType: 'json',
			data: {
				page : 1,
				search : searchVal,
				filter: filter
			},
			success : function( data ){
				populateListing( data );
			}
		});
	} );
} );