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
});