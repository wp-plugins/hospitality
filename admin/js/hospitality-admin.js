jQuery(document).ready( function($) {


	$('.gst-sortable').sortable();
	$('.gst-sortable').disableSelection();


	/* This function makes an ajax call to retrieve js configs for post edit input fields and then sets them */
	setPostEditOptions();


	$('.gst_date_input').datepicker({ dateFormat: 'M dd' });

	$('.gst_pricing_input').on('change', function() {
		$( this).attr("value", $(this).val() );
	});

	$( '#meta_pricing_model_list' ).on( "sortupdate", function( event, ui ) {
		reInitPriceModelList( event, $(this) );
	});



	$('.gst-sort-edit-delete').click( function( e ) {
		$( this ).parent().parent().remove();
	});

	$('#meta_pricing_model_list .gst-sort-edit-delete').click( function( e) {
		$(this).parent().parent().remove();
		reInitPriceModelList( e, $(this) );
	});


	$('#post').submit( function() {

	});



	var meta_image_frame;

	$('.gst-sort-edit-image-upload').click(function(e){
		handleImageUpload( e, $(this) );
	});

	$('.gst-sort-edit-image-add').click( function( e ) {


		var cloneHTML = atob( $(this).siblings('.gst_clone_template').val() );
		$( this ).parent().parent().children().filter('.gst-sortable').append( cloneHTML );

		// connect event hanlders to new list item.
		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('.gst-sort-edit-delete').click( function() {
			$( this ).parent().parent().remove();
		});

		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('.gst-sort-edit-image-upload').click( function() {
			handleImageUpload( e, $(this) );
		});

		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('.gst_clear_input_target').val('');
		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('.gst-sort-edit-image-upload').trigger('click');

	});


	$('.gst-sort-edit-add').click( function() {


		var cloneHTML = atob( $(this).siblings('.gst_clone_template').val() );
		$( this ).parent().parent().children().filter('.gst-sortable').append( cloneHTML );

		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('input').attr('value','');

		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('.gst-sort-edit-delete').click( function() {
			$( this ).parent().parent().remove();
		});
	});


	$('.gst-sort-edit-pricing-add').click( function( e ) {

		var cloneHTML = atob( $(this).siblings('.gst_clone_template').val() );
		$( this ).parent().parent().children().filter('.gst-sortable').append( cloneHTML );
		$( this ).parent().parent().children().filter('.gst-sortable').children().last().find('input').attr('value','');
		reInitPriceModelList( e, $(this));

	});



	$(".option-tree-setting-body").append('<a class="ot_setting_body_done">Done</a>');
	
	getAmenityList( $("#meta_room_amenity_select").val() );
	getPricingModel( $("#meta_room_pricing_select").val() );
	
	$('#meta_room_amenity_select').change( function() {	
			getAmenityList( this.value );		
	});
	
	$('#meta_room_pricing_select').change( function() {	
		getPricingModel( this.value );	
	});
	
	$(".ot_setting_body_done").click( function() {
		$('.option-tree-setting-body[style="display: block;"]').css('display','none');
	});
	
	$('.option-tree-list-item-add').click( function() {
		// console.log('got it');
		setTimeout( function() {
			 $('.option-tree-setting-body[style="display: block;"]').append('<a class="ot_setting_body_done">Done</a>');
			 $('.option-tree-setting-body[style="display: block;"] .ot_setting_body_done').click( function() {
					$('.option-tree-setting-body[style="display: block;"]').css('display','none');
				});
		}, 500);
	});

	function setPostEditOptions() {

		$.ajax({
			url:  hsp_admin_objectl10n.wpsiteinfo.site_url + '/wp-admin/admin-ajax.php',
			data:{
				'action':'hospitality_ajax',
				'fn':'get_post_edit_options'
			},
			dataType: 'JSON',
			success:function(data){
				if ( data.errorData != null && data.errorData == 'true' ) {
					reportError( data );
				}

				// Set options here.
				var postEditOptions = data;
				$('.gst_countable').simplyCountable( {
					counter:            '#gst_counter',
					countType:          'characters',
					maxCount:           postEditOptions.room_excerpt_max_char_count,
					strictMax:          false,
					overClass:			'gst-excerpt-over',
					countDirection:     'down'
				});

			},
			error: function(errorThrown){
				console.log(errorThrown);
			}

		});


	}

	/** 
	 * Get amenity list functions. 
	 */
	function getAmenityList( postID ) {
		
	
		if ( postID != null && postID != "" ) {
			$.ajax({
				url:  hsp_admin_objectl10n.wpsiteinfo.site_url + '/wp-admin/admin-ajax.php',
				data:{
					'action':'hospitality_ajax',
					'fn':'get_amenity_set_list',
					'postID' : postID
				},
				dataType: 'JSON',
				success:function(data){
					if ( data.errorData != null && data.errorData == 'true' ) {
						reportError( data );
					}
					displayAmenityList( data );
			    },
				error: function(errorThrown){
					console.log(errorThrown);
			    }
			
			});
		}
		else {
			displayAmenityList("");
		}
	}
	
	function displayAmenityList( amenitySetList ) {
	
		var targetElem = ".adm_amenity_set_list";
		var containerElem = "#amenity_list_panel";
		
		$( targetElem ).empty();
		
		if ( amenitySetList == "") {
			$( targetElem ).append('<p>' + hsp_admin_objectl10n.note_no_amnenity_set_selected + '</p>');
			return ;
		}
		
		$( targetElem ).append('<div id="' +  containerElem +  '">');	
		$( targetElem ).append('<ul>');
		for ( i in amenitySetList ) {
			$( targetElem ).append( '<li>' + amenitySetList[i] + '</li>');
		}
		$( targetElem ).append('</ul>');
		$( targetElem ).append('</div>');
		
	}
	
	/**
	 * Get pricing model functions. 
	 */
	
	function getPricingModel( postID ) {
			
		if ( postID != null && postID != "" ) {
			$.ajax({
				url:  hsp_admin_objectl10n.wpsiteinfo.site_url + '/wp-admin/admin-ajax.php',
				data:{
					'action':'hospitality_ajax',
					'fn':'get_pricing_model',
					'postID' : postID
				},
				dataType: 'JSON',
				success:function(data){
					if ( data.errorData != null && data.errorData == 'true' ) {
						reportError( data );
					}
					displayPricingModel( data );
			    },
				error: function(errorThrown){
					alert( hsp_admin_objectl10n.get_pricing_model_error + errorThrown.responseText.substring(0,500) );
					console.log(errorThrown);
			    }
			
			});
		}
		else {
			displayPricingModel("");
		}
	}

	function displayPricingModel( pricingModel ) {
		var targetElem = ".adm_pricing_model";
		var containerElem = "pricing_model_panel";
		
		
		$( targetElem ).empty();
		
		if ( pricingModel == "") {
			$( targetElem ).append('<p>' + hsp_admin_objectl10n.note_no_pricing_model_selected + '</p>');
			return ;
		}
		
		
		var htmlSrc = '<div id="' +  containerElem +  '">' ;
		htmlSrc += '<ul id="pricing_model">';
		for ( i in pricingModel ) {
			htmlSrc += '<li>' + pricingModel[i].title + '</li><p>';
			if ( pricingModel[i].dateRange1 != "") 
				 htmlSrc += pricingModel[i].dateRange1 ;
			if ( pricingModel[i].dateRange2 != "") 
				htmlSrc +=  ', ' + pricingModel[i].dateRange2  ;
			if ( pricingModel[i].dateRange3 != "") 
				htmlSrc += ', ' + pricingModel[i].dateRange3  ;
			htmlSrc += '<span class="hsp_room_price">' + pricingModel[i].price + '</span></p>';	
			
		}
		htmlSrc += '</ul>';
		htmlSrc += '</div>';
		
		$( targetElem ).append( htmlSrc );
		
		
		
	}



	function handleImageUpload( e, elem ) {

		e.preventDefault();

		// testFunction( e, $(this) )

		//$( this).siblings('input[type="text"]').addClass('gst-set-media-target');

		// tag fields that will be updated by the media metabox.
		elem.siblings('.gst_image_url_target').addClass('gst-set-media-target');
		elem.siblings('a').children('img').addClass('gst-set-media-thumbnail-target');


		// Sets up the media library frame
		meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
			// title: meta_image.title,
			// button: { text:  meta_image.button },
			library: { type: 'image' }
		});

		// Runs when an image is selected.
		meta_image_frame.on('select', function(){


			// Grabs the attachment selection and creates a JSON representation of the model.
			var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

			// Sends the attachment URL to our custom image input field.
			$('.gst-set-media-target').val(media_attachment.url);
			$('.gst-set-media-target').removeClass('gst-set-media-target');
			$('.gst-set-media-thumbnail-target').attr( "src", media_attachment.url );
			$('.gst-set-media-thumbnail-target').removeClass('gst-set-media-thumbnail-target');


		});

		// Opens the media library frame.
		// wp.media.editor.open();
		meta_image_frame.open();
	}


	function reInitPriceModelList ( e, elem ) {

		$('#meta_pricing_model_list').children().each( function( idx, elem ) {
			var liHTML = $( elem ).html();
			var replaceStr = 'meta_pricing_model_list[' + idx + ']' ;
			var modliHTML = liHTML.replace(/meta_pricing_model_list\[[0-9]*\]/g, replaceStr );
			modliHTML = '<li class="ui-state-default">' +  modliHTML + '</li>';
			$( this).replaceWith( modliHTML);
		});

		$('.gst_date_input').removeClass('hasDatepicker');
		$('.gst_date_input').attr('id', '');
		$('.gst_date_input').datepicker({ dateFormat: 'M dd' });

		$('.gst_pricing_input').on('change', function() {
			$(this).attr("value", $(this).val() );
		});

		$('#meta_pricing_model_list .gst-sort-edit-delete').click( function() {
			$(this).parent().parent().remove();
			reInitPriceModelList( e, $(this) );
		});

	}

	function testFunction( e, elem ) {
		alert('test function');
	}

	function reportError ( error ) {
		console.log ( error );
	}
	
});
