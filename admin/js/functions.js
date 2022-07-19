jQuery(document).ready(function($) {
	$(document).on('click','.editor_icon a',function(e){
		var ele 		= $(this).attr('id');
		var textarea 	= document.getElementById("textArea");  
		var selection 	= (textarea.value).substring(textarea.selectionStart,textarea.selectionEnd);
		if(ele != '' && ele == 'bold'){
			$('#textArea').val((textarea.value).replace(selection, '*'+selection+'*'));
			// $('.newsletter_preview').html((textarea.value).replace(selection, '<b>'+selection+'</b>'));
		} else if(ele != '' && ele == 'italic') {
			$('#textArea').val((textarea.value).replace(selection, '_'+selection+'_'));
			// $('.newsletter_preview').html((textarea.value).replace(selection, '<i>'+selection+'</i>'));
		} else if(ele != '' && ele == 'strike') {
			$('#textArea').val((textarea.value).replace(selection, '~'+selection+'~'));
			// $('.newsletter_preview').html((textarea.value).replace(selection, '<s>'+selection+'</s>'));
		} else if(ele != '' && ele == 'monospace') {
			$('#textArea').val((textarea.value).replace(selection, '```'+selection+'```'));
			// $('.newsletter_preview').html((textarea.value).replace(selection, '<tt>'+selection+'</tt>'));
		}
	});
	// $(document).on('keypress blur','#textArea',function(){
	// 	$('.newsletter_preview').text($(this).val());
	// })

	$(document).on('click','.btn_msgsend',function(e){
		e.preventDefault();
		var _serialized = $('#textArea').serialize();
        var dataString  = _serialized+'&action=send_newsletters';
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
              
            }
        });
	});

	$(document).on('keypress blur','#txt_temp_title,#temp_hold_title,#temp_processing_title,#temp_pending_title,#temp_complete_title,temp_refund_title,#temp_faild_title',function(){
		var _this = $(this).val();
		_this=_this.split(' ').join('_')
		$(this).val(_this);
	})

	$(document).on('click','#btn_submit',function(e){
		window.onbeforeunload = null;
		e.preventDefault();
		var _serialized = $('#txt_temp_title, #txt_temp_head, #txt_temp_body ,#txt_temp_foot').serialize();
		var dataString  = _serialized+'&action=wwn_register_template';
		$('body').append('<div class="loading"></div>');
		$.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
            	$('.loading').remove();
            	if(response.type === 'success'){
            		$('.wwn_configuration_main').before("<div class='notification' style='background:green'>"+response.message+"</div>");
            		$('#txt_temp_title, #txt_temp_head, #txt_temp_body ,#txt_temp_foot').prop('disabled', true);
            		$('#mainform').submit();
            	} else {
            		$('.wwn_configuration_main').before("<div class='notification'>"+response.message+"</div>");
            		$('#txt_temp_title, #txt_temp_head, #txt_temp_body ,#txt_temp_foot').prop('disabled', false);
            	}
            	setTimeout(function(){ $('.notification').remove(); }, 2000);
            }
        });
	});

	$(document).on('click','#remove_template',function(e){
		window.onbeforeunload = null;
		e.preventDefault();
		var temp_name = $(this).data('name');
		var temp_key  = $(this).data('key');
		var dataString  = 'temp_name='+temp_name+'&key_name='+temp_key+'&action=wwn_delete_template';
		$('body').append('<div class="loading"></div>');
		$.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
            	$('.loading').remove();
            	if(response.type === 'success'){
            		$('.wwn_configuration_main').before("<div class='notification' style='background:green'>"+response.message+"</div>");
            		$('#txt_temp_title, #txt_temp_head, #txt_temp_body ,#txt_temp_foot').prop('disabled', true);
            		$('#mainform').submit();
            	} else {
            		$('.wwn_configuration_main').before("<div class='notification'>"+response.message+"</div>");
            		$('#txt_temp_title, #txt_temp_head, #txt_temp_body ,#txt_temp_foot').prop('disabled', false);
            	}
            	setTimeout(function(){ $('.notification').remove(); }, 2000);
            }
        });
	});

	$(document).on('click','#btn_save_settings',function(e){
		window.onbeforeunload = null;
		e.preventDefault();
		var _serialized = $('#wc_setting_api_token, #wc_setting_phone_number_id, #wc_setting_version ,#wc_setting_business_id').serialize();
		var dataString  = _serialized+'&action=wwn_configure_settings';
		$('body').append('<div class="loading"></div>');
		$.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
            	$('.loading').remove();
            	if(response.type === 'success'){
            		$('.wwn_configuration_main').before("<div class='notification' style='background:green'>"+response.message+"</div>");
            		$('#txt_temp_title, #txt_temp_head, #txt_temp_body ,#txt_temp_foot').prop('disabled', true);
            		$('#mainform').submit(); 
            	}
            	setTimeout(function(){ $('.notification').remove(); }, 2000);
            }
        });
	});

	$(document).on('click','.btn_save_temp',function(e){
		window.onbeforeunload = null;
		e.preventDefault();

		var temp_id     = $(this).attr('id');
		var temp_name   = $(this).parents('table').data('title');
		var get_values  = $(this).parents('table').find('input,textarea').serialize();
		var dataString  = get_values+'&action=wwn_register_status_templates&temp_name='+temp_name;
		$('body').append('<div class="loading"></div>');
		$.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
            	$('.loading').remove();
            	if(response.type === 'success'){
            		$('.wwn_configuration_main').before("<div class='notification' style='background:green'>"+response.message+"</div>");
            		$('#mainform').submit();
            	} else {
            		$('.wwn_configuration_main').before("<div class='notification'>"+response.message+"</div>");
            	}
            	setTimeout(function(){ $('.notification').remove(); }, 2000);
            }
        });
	});

	/*Media Upload*/
	// on upload button click

	$('body').on( 'click', '.upload_file', function(e){
		e.preventDefault();
		var button = $(this),
		custom_uploader = wp.media({
			title: 'Insert image',
			library : {
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false
		}).on('select', function() { // it also has "open" and "close" events
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			// console.log(attachment.url);
			var dataString  = 'action=wwn_upload_wp_media&attchment_url='+attachment.url+'&attachment_id='+attachment.id;

			// $('	.newsletter_preview').html('<a href="#" class="upload_file">'+
			// 	'<img class="prview_image" src="' + attachment.url + '"></a>'+
			// 	'<a href="#" class="misha-rmv">Remove image</a>').next().show().next().val(attachment.id);

			$.ajax({
	            type: 'POST',
	            url: ajaxurl,
	            data: dataString,
	            dataType: "json",
	            success: function (response) {
	            	console.log(response);
	            }
	        });
		}).open();
	
	});

	// on remove button click
	$('body').on('click', '.misha-rmv', function(e){
		e.preventDefault();
		var button = $(this);
		button.next().val(''); // emptying the hidden field
		button.hide().prev().remove();
	});
});