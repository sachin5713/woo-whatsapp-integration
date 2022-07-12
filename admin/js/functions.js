jQuery(document).ready(function($) {
	$(document).on('click','.editor_icon a',function(e){
		var ele 		= $(this).attr('id');
		var textarea 	= document.getElementById("textArea");  
		var selection 	= (textarea.value).substring(textarea.selectionStart,textarea.selectionEnd);
		if(ele != '' && ele == 'bold'){
			$('#textArea').val((textarea.value).replace(selection, '*'+selection+'*'));
			$('.newsletter_preview').html((textarea.value).replace(selection, '<b>'+selection+'</b>'));
		} else if(ele != '' && ele == 'italic') {
			$('#textArea').val((textarea.value).replace(selection, '_'+selection+'_'));
			$('.newsletter_preview').html((textarea.value).replace(selection, '<i>'+selection+'</i>'));
		} else if(ele != '' && ele == 'strike') {
			$('#textArea').val((textarea.value).replace(selection, '~'+selection+'~'));
			$('.newsletter_preview').html((textarea.value).replace(selection, '<s>'+selection+'</s>'));
		} else if(ele != '' && ele == 'monospace') {
			$('#textArea').val((textarea.value).replace(selection, '```'+selection+'```'));
			$('.newsletter_preview').html((textarea.value).replace(selection, '<tt>'+selection+'</tt>'));
		}
	});
	$(document).on('keypress blur','#textArea',function(){
		$('.newsletter_preview').text($(this).val());
	})

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

	$(document).on('keypress blur','#txt_temp_title',function(){
		var _this = $(this).val();
		_this=_this.split(' ').join('_')
		$("#txt_temp_title").val(_this);
	})

	$(document).on('click','#btn_submit',function(e){
		e.preventDefault();
		var _serialized = $('#txt_temp_title, #txt_temp_head, #txt_temp_body ,#txt_temp_foot').serialize();
		var dataString  = _serialized+'&action=wwn_register_template';
		$(this).append('<img class="loader" src="'+ajax_obj.gif_url+'" width="25" height="25" />');
		$.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
            	$('.loader').remove();
            	if(response.type === 'success'){
            		$('.wwn_message_wrapper').before("<div class='notification' style='background:green'>"+response.message+"</div>");
            		$('#txt_temp_title, #txt_temp_head, #txt_temp_body ,#txt_temp_foot').prop('disabled', true);
            	} else {
            		$('.wwn_message_wrapper').before("<div class='notification'>"+response.message+"</div>");
            		$('#txt_temp_title, #txt_temp_head, #txt_temp_body ,#txt_temp_foot').prop('disabled', false);
            	}
            	setTimeout(function(){ $('.notification').remove(); window.location.href=window.location.href; }, 2000);
            }
        });
	});

	$(document).on('click','#remove_template',function(e){
		e.preventDefault();
		var temp_name = $(this).data('name');
		var dataString  = 'temp_name='+temp_name+'&action=wwn_delete_template';
		$.ajax({
            type: 'POST',
            url: ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
            	$('.loader').remove();
            	if(response.type === 'success'){
            		$('.wwn_message_wrapper').before("<div class='notification' style='background:green'>"+response.message+"</div>");
            		$('#txt_temp_title, #txt_temp_head, #txt_temp_body ,#txt_temp_foot').prop('disabled', true);
            	} else {
            		$('.wwn_message_wrapper').before("<div class='notification'>"+response.message+"</div>");
            		$('#txt_temp_title, #txt_temp_head, #txt_temp_body ,#txt_temp_foot').prop('disabled', false);
            	}
            	setTimeout(function(){ $('.notification').remove(); window.location.href=window.location.href; }, 2000);
            }
        });
	});
});