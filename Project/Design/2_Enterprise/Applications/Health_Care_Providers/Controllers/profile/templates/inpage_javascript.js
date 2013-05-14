$(window).load(function() {
	
	
	$('#closePopUp').click(function(){
		$('.popupDialog').fadeOut();
	});
	
	$('.approve_btn').click(function(){
		var hcp_id = $('#hcp_id').val();
		$.php('/ajax/hcps/changeHcpNewStatus', {hcp_id:hcp_id});
		$('.popupDialog').fadeIn();
	});
	
	$('.disapprove_btn').click(function(){
		alert('disapprove');
	});
});