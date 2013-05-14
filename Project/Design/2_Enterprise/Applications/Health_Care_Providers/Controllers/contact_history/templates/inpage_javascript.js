$(window).load(function() {

	
	$('input:checkbox[value="info_sent"]').click(function() {
		
		if ($(this).attr('checked') == 'checked')
		{
			var status = "info_sent";
			var status_link = 	"[<a href = '#' class = 'status_link'> yes </a>]" +
			" [<a href = '#' class = 'status_link'> no </a>]";
			$(this).parents('td').next().html($.trim(status_link)); 
			
		}
		else
		{
			var status = null;
		}
		
		var potential_hcp_id = $(this).parents('tr').attr('id');
		
		$(this).parents('td').prev().children().removeAttr("checked");
		
		$.php('/ajax/hcps/updatePotentialLead', {potential_hcp_id: potential_hcp_id, status: status});
	});
	
	
	$('input:checkbox[value="contacted"]').click(function() {
		 
		if ($(this).attr('checked') == 'checked')
		{	
			var status = "contacted";
			var status_link = 	"[<a href = '#' class = 'status_link'> yes </a>]" +
			" [<a href = '#' class = 'status_link'> no </a>]";
			$(this).parents('td').next().next().html($.trim(status_link)); 
		}
		else
		{
			var status = null;
		}
		
		var potential_hcp_id = $(this).parents('tr').attr('id');
		
		$(this).parents('td').next().children().removeAttr("checked");
	
		$.php('/ajax/hcps/updatePotentialLead', {potential_hcp_id: potential_hcp_id, status: status});
		
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
		
		
		$.php('/ajax/hcps/updatePotentialLead', {potential_hcp_id: potential_hcp_id, status: status});
});

//-------------------------------------------------------------------------------------------------
	//popups open action
	$('span.addAlarmBtn').live('click',function(){
		$('.popupDialog.addAlarm,#popUp_background').fadeIn();
	}); 
	
	//popups close action


});

function callAjaxPagination(application_id, active_tab, page)
{
	var seeker_id = $('input[name=hcp_id]').val();
	$.php('/ajax/'+application_id+'/'+active_tab+'_pagination', {page:page, tab:active_tab, hcp_id:hcp_id});
}


