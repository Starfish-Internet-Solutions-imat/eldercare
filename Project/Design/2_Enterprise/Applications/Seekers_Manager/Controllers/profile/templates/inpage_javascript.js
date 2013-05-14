$(window).load(function() {
	$(".check").click(function ()
	{
		var hcp_id = $(this).closest('tr').attr('id');
		var status = $(this).val();
		$.php('/ajax/seekers/sendData', {hcp_id: hcp_id, status: status});
	});
	
	$('input:checkbox[value="info_sent"]').click(function() {
		
		if ($(this).attr('checked') == 'checked')
		{
			var status = "info_sent";
			var status_link = 	"[<a href = '#' class = 'status_link'> yes </a>]" +
			" [<a href = '' class = 'status_link'> no </a>]";
			$(this).parents('td').next().html($.trim(status_link)); 
			
		}
		else
		{
			var status = null;
		}
		
		var potential_hcp_id = $(this).parents('tr').attr('id');
		
		$(this).parents('td').prev().children().removeAttr("checked");
			
			$.php('/ajax/seekers/updatePotentialHcp', {potential_hcp_id: potential_hcp_id, status: status});
	});
		
		$('input:checkbox[value="contacted"]').click(function() {
			 
			if ($(this).attr('checked') == 'checked')
			{
				var status = "contacted";
				var status_link = 	"[<a href = '#' class = 'status_link'> yes </a>]" +
				" [<a href = '' class = 'status_link'> no </a>]";
				$(this).parents('td').next().next().html($.trim(status_link)); 
			}
			else
			{
				var status = null;
			}
			
			var potential_hcp_id = $(this).parents('tr').attr('id');
			
			$(this).parents('td').next().children().removeAttr("checked");
		
			$.php('/ajax/seekers/updatePotentialHcp', {potential_hcp_id: potential_hcp_id, status: status});
			
	});
		
		$('.status_link').live('click',function(e) {
			e.preventDefault();
			var status = $(this).text();
			var potential_hcp_id = $(this).parents('tr').attr('id');
			
			$(this).parents('td').prev().children().removeAttr("checked");
			$(this).parents('td').prev().prev().children().removeAttr("checked");
			
			if (status == ' yes ')
			{
				status = 'placed';
				$(this).parent().text('placed');
				
			}
			else if (status == ' no ')
			{
				status = 'not_placed';
				$(this).parent().text('not placed');
			}
			else if (status == '??')
			{
				status = 'pending_placed';
				$(this).parent().text('pending');
			}

			$.php('/ajax/seekers/updatePotentialHcp', {potential_hcp_id: potential_hcp_id, status: status});
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

//-------------------------------------------------------------------------------------------------
	//popups open action
	$('span.editSeeker').live('click',function(){
		$('.popupDialog.editSeeker,#popUp_background').fadeIn();
	}); 

//-------------------------------------------------------------------------------------------------
	//popups open action
	$('span.addAlarmBtn').live('click',function(){
		$('.popupDialog.addAlarm,#popUp_background').fadeIn();
	}); 
	
	$('span.cancelBtn').live('click',function(){
		$('.popupDialog.addAlarm,#popUp_background').fadeOut();
	}); 
		
});

function callAjaxPagination(application_id, active_tab, page)
{
	var seeker_id = $('input[name=seeker_id]').val();
	$.php('/ajax/'+application_id+'/'+active_tab+'_pagination', {page:page, tab:active_tab, seeker_id:seeker_id});
}


