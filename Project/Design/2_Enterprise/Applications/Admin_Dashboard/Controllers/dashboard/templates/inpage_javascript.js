$(window).load(function() {
	
	//-------------------Dashboard Tabs Indicator---------------------------------------
	var new_leads_count = $('.new_leads tr').length-1;
	var new_placements_count = $('.new_placements tr').length-1;
	var calendar_alerts_count = $('.calendar_alerts tr').length-1;
	var new_hcp_count = $('.new_hcp tr').length-1;
	var new_count = $('.new tr').length-1;
	
	if (new_leads_count != 0)
		$('#new_leads_count').html(new_leads_count);
	else
		$('#new_leads_count').remove();
		
	if (calendar_alerts_count != 0)
		$('#upcoming_calendar_alerts_count').html(calendar_alerts_count);
	else
		$('#upcoming_calendar_alerts_count').remove();
	
	if (new_placements_count != 0)
		$('#new_placements_count').html(new_placements_count);
	else
		$('#new_placements_count').remove();
	
	if (new_hcp_count != 0)
		$('#new_hcp_count').html(new_hcp_count);
	else
		$('#new_hcp_count').remove();
	
	if (new_count != 0)
		$('#new_placements_and_payment_count').html(new_count);
	else
		$('#new_placements_and_payment_count').remove();
	//----------------------------------------------------------
	
	$('#status').live("change", (function(){
		var hcp_id = $(this).parents('tr').attr('id');
		var status = $(this).children().val();
		
		
		var hcp_name = $(this).parents('tr').children().first().find('a').html();
		var text_status = '';
		
		if (status == 2)
			text_status = 'approved';
		else
			text_status = 'disapproved';
		var popupText = hcp_name + ' is ' + text_status + '.';
		
		$('.popupText').html(popupText);
		$('.popupDialog.status').fadeIn();
		
		$.php('ajax/dashboard/changeHcpNewStatus', {hcp_id:hcp_id, status:status, text_status:text_status});
		$('#'+hcp_id+'').remove();
	}));
	
	$('#popup_FadeOut').live("click", (function(){
		$('.popupDialog.status').fadeOut();
	}));
	
	//-----------------------------------------------------------------------------------------
	//for placements used
	$(".invoice_number").on("keyup",(function(){
		var invoice = $(this).val();
		var placement_id = $(this).parents('tr').attr('id');
		var status = "<select class='status'><option value='pending_payment'>pending_payment</option><option value='paid'>paid</option></select>";
		
		$(this).parents('td').next().html(status);
		$.php('/ajax/dashboard/udpate_invoice', {invoice:invoice, placement_id:placement_id});
	}));


	
	$('select.status').live("change", (function(){
		var placement_id= $(this).parents('tr').attr('id');
		var invoice = $(this).parents('td').prev().children().val();
		var status = $(this).val();
	
		if(status == 'pending_payment')
		{
			invoice = $(this).parents('td').prev().html();
			var invoice_number = '<input type="text" class="invoice_number" value="'+ $(this).parents('td').prev().html() +'">'; 
		}
		else
			var invoice_number = invoice;
		
		$(this).parents('td').prev().html(invoice_number);	
		$.php('/ajax/dashboard/udpateStatus', {invoice:invoice, placement_id:placement_id, status:status});
	
	}));
	
	
	
});

function callAjaxPagination(application_id, active_tab, page)
{
	$.php('/ajax/'+application_id+'/'+active_tab+'_pagination', {page:page, tab:active_tab});
}