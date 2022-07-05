

jQuery(document).ready(function($) {
	$(document).on('change','#bold',function(e){
		e.preventDefault();
		var textarea = document.getElementById("textArea");  
		var selection = (textarea.value).substring(textarea.selectionStart,textarea.selectionEnd);
		
		if($(this).is(':checked')){
			$('#textArea').val('*'+selection+'*');
		} 
	})
});