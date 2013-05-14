$(document).ready(function(){

	$('.sprite.remove_list').live('click',function(e){
		e.preventDefault();
		$('div.shortlist_action ul').animate({'margin-left': '-212px'}, 250);
		$(this).css({'visibility':'hidden'});
	});
	$('div.shortlist_action .no').live('click',function(){
		$('div.shortlist_action ul').animate({'margin-left': '0px'}, 250);
		showRemoveButton();
	});
	
	$('#short_list_column form > ul > li').mouseleave(function(){
		$('div.shortlist_action ul').css('margin-left', '0px');
		showRemoveButton();
	});
	
});

function showRemoveButton()
{
	$('.sprite.remove_list').css({'visibility':'visible'});
}