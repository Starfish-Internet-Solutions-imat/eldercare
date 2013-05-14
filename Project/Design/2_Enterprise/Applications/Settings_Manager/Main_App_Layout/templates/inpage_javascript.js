$(document).ready(function(){
	getSMTPState();
	$('select[name="use_smtp"]').change(function(){
		getSMTPState();
	});
	$('select[name=category_id]').change(function(){
		$.php('/ajax/transactions/load_subcategory',{category_id:$(this).val()});
	});
	$('input[name="date_from"],input[name="date_to"]').datepicker({ dateFormat: "mm-dd" });
	
});
function getSMTPState(){
	var state = $('select[name="use_smtp"]').val();
	if(state == 0){
		$('#smtp').find('input[name*="smtp_"]').attr('readonly',true);
	}
	else{
		$('#smtp').find('input[name*="smtp_"]').removeAttr('readonly');
	}
}