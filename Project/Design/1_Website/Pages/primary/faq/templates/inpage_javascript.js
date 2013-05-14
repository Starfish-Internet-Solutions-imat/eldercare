$(document).ready(function(){

	$('.ans').show();
	
	Cufon.replace('#faq_container label', { fontFamily: 'Gotham Rounded Bold' });
	Cufon.replace('#faq_container li strong', { fontFamily: 'Gotham Rounded Bold' });
	Cufon.replace('#faq_container h3', { fontFamily: 'Helvetica Neue LT Std' });
	
	$('#faq_container li > label').live('click',function(){
		$(this).parent().children('div').slideDown();
		$(this).parent().addClass('activated');
		$(this).parent().children('strong').show();
		$(this).parent().children('label').hide();
	});
	
	$('#faq_container li.activated > strong').live('click',function(){
		$(this).parent().children('div').slideUp();
		$(this).parent().removeClass('activated');
		$(this).parent().children('label').show();
		$(this).parent().children('strong').hide();
	});
	
});