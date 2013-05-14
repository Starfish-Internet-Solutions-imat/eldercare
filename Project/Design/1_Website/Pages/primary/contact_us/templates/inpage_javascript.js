$(document).ready(function(){
	//FORM VALIDATION
   
    $("#contact_form").submit(function(e) {
		e.preventDefault();
		
		if (validateForm()) {
			
			$.php('/ajax/contact/submitEmailAjax',$(this).serialize());

			php.error = function(xmlEr, typeEr, except) {}
			php.complete = function(XMLHttpRequest, textStatus) {
		
				$('#sent-confirmation').fadeIn();
				$('#sent-confirmation').delay(2000).fadeOut("slow");
				
				$('#wrapper').click(function() {
					$('#sent-confirmation').fadeOut("slow");
				});
				$('#contact_form input[type=text],#contact_form textarea').each(function(){
					$(this).val(' ');
				});
			}
		}
	});
    
    
    
	$('input[name=mobile]').keypress(function(){
		
		var telephone = $(this).val();
		
		if ((telephone.length == 3) || (telephone.length == 7))
		{
			telephone = telephone + '-';
			$(this).val(telephone);
			telephone = $(this).val();
		}
		
	});
    
    
});
function resetForm(id) {
	$('#'+id).each(function(){
		this.reset();
	});
}	
function validateForm() {
	
	return $("#contact_form").validate().form();
}

