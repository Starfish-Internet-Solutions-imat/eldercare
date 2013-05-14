$(document).ready(function(){
	
	$('input[name="old_password"]').live('blur', function(e){
		var old_password = $(this).val();
		$.php('/ajax/account/checkPassword', {old_password:old_password});
		
		php.complete = function(){
			if($('#wrong_password').html())
				$("input[name='profile']").attr('disabled', 'disabled');
		}
	});
	
	$('.popupDialog').fadeIn();
	
	$('.popupDialog .close').click(function(){
		$('.popupDialog').fadeOut();
	});
	
	$('#proceed').click(function(){
		$('.popupDialog').fadeOut();
	});
	
	$('#proceedApproved').click(function(){
		$.php('/ajax/account/updateStatus', {});
		$('.popupDialog').fadeOut();
		$('#blocker').remove();
	});
	
	$('.viewOnline').click(function(e){
		if($('#published').val() == "")
		{
			e.preventDefault();
			$('.popupDialogNotApproved').attr('class', 'popupDialog');
			$('.popupDialog').fadeIn();
		}
	});
	
	
	$('input[name=zipcode]').keyup(function(){
		var zipcode = $(this).val();
		if (zipcode.length >= 3){
				$.php('/ajax/account/zipcode_list', { zip:zipcode });
			}
			
			php.complete = function() {
				if($('#zipcode_list ul').children().length == 0) {
					$('#zipcode_list').slideUp(100);
					
				} else {
					
					$('#zipcode_list').slideDown(10);
				}
			}
		});
	
	$(".fileInput").click(function () {
	     $("input.fileInputField").trigger('click');
	});
		
		$('#zipcode_list ul li').live('click',function(){
			var zipcode = $(this).text();
			var id = $(this).attr('id');
			var citystate = zipcode.substring(zipcode.indexOf('- ')+2);
			var zipcode = zipcode.substring(0, zipcode.indexOf('- '));
			
			$('input[name=zipcode]').val(zipcode);
			$('#city_state').html(citystate);
			$('input[name=zipcode_id]').val(id);
			$('#zipcode_list').slideUp(100);
		});
		
		$('#zipcode_field').focusout(function(){
			$('#zipcode_list').focusout(function(){
				$('#zipcode_list').slideUp('slow');
			});
		});
		
		/*$('.leftside_navigation_tab_name').click(function(e){
				e.preventDefault();
				var template = $(this).attr("id");
				$.php('/ajax/account/render_tab_template', { template:template });
		});*/
		
		$('#image_file').live('change', function(){ 
	         	$("#preview").html('');
	         	$("#imageform").submit();
	         	
		});
		
		$('input[name="telephone"]').click(function(){
			if ($(this).val() == "Invalid Number")
				$(this).va('');
		});
		
});

$(document).mouseup(function (e)
{
	var container = $("#zipcode_list");

	if (container.has(e.target).length === 0)
	{
		container.hide();
	}
});

//===

$('input[name=zipcode]').live('focusout', function(){
	
	if ($(this).val() != '')
	{
		var zipcode = $(this).val();

			if ((zipcode.length > 5) && (zipcode.substr(6, 3) === ' - '))
			{
				zipcode = zipcode.substr(0, 6);
			}
			
			$.php('/ajax/seek/isZipcodeValid', {zipcode:zipcode});
		
		//php.error = function (){};
		
		php.complete = function () {
			
		}
	}
	
});

$('#zipcode_list ul li').live('click',function(){
	if ($(this).html() != '')
	{
		var zipcode = $(this).html();

			if ((zipcode.length > 5) && (zipcode.substr(6, 3) === ' - '))
			{
				zipcode = zipcode.substr(0, 6);
			}
			
			$.php('/ajax/seek/isZipcodeValid', {zipcode:zipcode});
		
		//php.error = function (){};
		
		php.complete = function () {
			
		}
	}
});



function slideContent(target,botton){
	$('#'+botton).hide();

	$(target).slideDown();
	
	//account details form validation
	$('#accountDetails').validate({
		rules: {
			old_password: "required",
		    password: "required",
		    confirm: {
		    	required: true,
		    	equalTo: "#password"
		    },
		  }

	});
}

