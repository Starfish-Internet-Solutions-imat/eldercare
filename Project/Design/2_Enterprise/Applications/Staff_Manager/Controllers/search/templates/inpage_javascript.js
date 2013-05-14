$(window).load(function() {
	
	
	$("#addStaff").click( function() {
		$(".addStaffPopUp").fadeIn();
	});
	
	$(".addStaffPopUpSubmit").click( function() {
		$(".addStaffPopUp").fadeOut();
	});
	
	$('tr').click(function(){
		var staff_id = $(this).attr('id');
		$.php('/ajax/staff/staff_details', {staff_id: staff_id});
	});
	
//-------------------------------------------------------------------------------------------------
	//popups open action
	$('span.deleteStaff').live('click',function(){
		$('.popupDialog.deleteStaff,#popUp_background').fadeIn();
	}); 
});