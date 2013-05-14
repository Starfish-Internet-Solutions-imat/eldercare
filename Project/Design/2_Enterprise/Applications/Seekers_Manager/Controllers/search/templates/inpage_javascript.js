$(window).load(function() {
	
	$('tr > td:not(td:last-child)').click(function(){
		var seeker_id = $(this).parent().attr('id');
		$.php('/ajax/seekers/seeker_details', {seeker_id: seeker_id});
	});
	
	$('input[name=zipcode]').keyup(function(){ 
		var zipcode = $(this).val();
		
		$.php('/ajax/seekers/zipcodeQuery', {zipcode:zipcode});
			
		php.complete = function() {
			if($('#suggestion_list ul').children().length == 0) {
				$('#suggestion_list').slideUp(100);
				
			} else {
				
				$('#suggestion_list').slideDown(10);
			}
		}
	});
		
	$('#suggestion_list ul li').live('click',function(){
		var query = $(this).text();
		var id = $(this).attr('id');
		
		$('input[name=zipcode]').val(query);
		$('input[name=zipcode_id]').val(id);
		$('#suggestion_list').slideUp(100);
	});
		
	$('#suggestion_list').focusout(function(){
				$('#suggestion_list').slideUp('slow');
	});
	
	
	$(document).ready(function(){ 
        $("#seeker_table").tablesorter( {sortList: [[1,0]]} ); 
    } ); 
	
//-------------------------------------------------------------------------------------------------
	//popups open action
	$('span.deleteSeeker').live('click',function(){
		$('.popupDialog.deleteSeeker,#popUp_background').fadeIn();
	}); 
	
});