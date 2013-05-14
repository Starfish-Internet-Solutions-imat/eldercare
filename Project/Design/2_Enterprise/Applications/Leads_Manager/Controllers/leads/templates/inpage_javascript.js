$(window).load(function() {
	
	//-------------------Leads Manager Tabs Indicator---------------------------------------
	var new_leads_count = $('.new_leads tr').length-1;
	var calendar_alerts_count = $('.calendar_alerts tr').length-1;
	var immediate_leads_count = $('.immediate_leads tr').length-1;
	
	if (new_leads_count != 0)
		$('#unassigned_leads_count').html(new_leads_count);
	else
		$('#unassigned_leads_count').remove();
	
	if (calendar_alerts_count != 0)
		$('#upcoming_calendar_alert_count').html(calendar_alerts_count);
	else
		$('#upcoming_calendar_alert_count').remove();
	
	if (immediate_leads_count != 0)
		$('#new_placements_payment_count').html(immediate_leads_count);
	else
		$('#new_placements_payment_count').remove();
	//----------------------------------------------------------
	
	$(".changeStaff").live("change", (function ()
	{
		var lead_id = $(this).closest('tr').attr('id');
		var staff_id = $(this).val();
		
		var staff_name = $('.changeStaff option:selected', $('#'+lead_id+'')).text();
		var seeker_name = $(this).parents('td').prev().prev().prev().prev().html();
		
		$('.popupDialog > .deleteText').html(seeker_name + ' is assigned to '+ staff_name );
		$('.popupDialog').fadeIn();
		
		$.php('/ajax/leads/updateAssignedToStaff', {staff_id: staff_id, lead_id: lead_id});
	}));
	
	$('#closePopup').live("click", (function(){
		$('.popupDialog').fadeOut();
	}));
	
	
	
	//-----------------------------------------------------------------------------------------
	//for placements used
	$(".invoice_number").on("keyup",(function(){
		var invoice = $(this).val();
		var placement_id = $(this).parents('tr').attr('id');
		var status = "<select class='status'><option value='pending_payment'>pending_payment</option><option value='paid'>paid</option></select>";
		
		$(this).parents('td').next().html(status);
		$.php('ajax/leads/udpate_invoice', {invoice:invoice, placement_id:placement_id});
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
		//alert (invoice);
		$.php('ajax/leads/udpateStatus', {invoice:invoice, placement_id:placement_id, status:status});
	
	}));
	
});

function callAjaxPagination(application_id, active_tab, page)
{
	$.php('/ajax/'+application_id+'/'+active_tab+'_pagination', {page:page, tab:active_tab});
}
