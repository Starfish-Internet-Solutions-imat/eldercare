$(document).ready(function(){
	var x = 0;
	
	  $(window).keydown(function(event){
		    if(event.keyCode == 13 && $('input[name="test"]').val() != "yes") {
		    	  event.preventDefault();
		    }
		  });
	
	jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
	    phone_number = phone_number.replace(/\s+/g, ""); 
	    phone_number = phone_number.replace('-', ""); 
		return this.optional(element) || phone_number.length > 9 &&
			phone_number.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
	}, "Please specify a valid phone number");
	
	$('input[name=zipcode]').keyup(function(e){
		var zipcode = $(this).val();
		
		if(e.keyCode == 8 )
		{
			if($(this).val() == '')
				$('#invalidZipcode').html('');
		}
	
		 if (e.keyCode != 40 && e.keyCode != 38 && e.keyCode != 13) 
			 $.php('/ajax/account/zipcode_list', { zip:zipcode });
			
			php.complete = function() {
				if($('#zipcode_list ul').children().length == 0) {
					$('#zipcode_list').slideUp(100);
					
				} else {
					
					$('#zipcode_list').slideDown(10);
					x=0;
				}
			}
			
			var test = 0;
			
			var max_li = $('input[name="max_li"]').val();
			
			 if (e.keyCode == 40) {
				 if(x != max_li)
				 {
					 $('#zip_list li').eq(x).addClass('activeZip');
					 if(x>0)
						 $('#zip_list li').eq(x-1).removeClass('activeZip');
					 x++;
				 }
			 }
			 
			 if (e.keyCode == 38) {
				 if(x!=1)
				 {
					 x-=1;
					 $('#zip_list li').eq(x-1).addClass('activeZip');
					 $('#zip_list li').eq(x).removeClass('activeZip');
				 }
			 }
			 
			 if(e.keyCode == 13)
				{
					var zipcode_id = $('.activeZip').attr('id'); 
					var html =  $('.activeZip').html();
					$('input[name="zipcode_id"]').val(zipcode_id);
					$('input[name="zipcode"]').val(html);
					$('#zipcode_list').slideUp(100);
				}
			
			
		});
		
		$('#zipcode_list ul li').live('click',function(){
			var zipcode = $(this).text();
			var id = $(this).attr('id');
			
			$('input[name=zipcode]').val(zipcode);
			$('input[name=zipcode_id]').val(id);
			$('#zipcode_list').slideUp(100);
		});
		
		$('#zipcode_field').focusout(function(){
			$('#zipcode_list').focusout(function(){
				$('#zipcode_list').slideUp('slow');
			});
		});
		
		
		$('input[name="proceed_next_step"]').click(function(e){
			var checker  = $('#invalidZipcode').html();
			if(checker)
				e.preventDefault();
		});


		//JQUNIFORM
		$("input:checkbox").uniform();
		
		$('input[name=email]').focusout(function(){
			
			if ($(this).val() != '')
			{
				var email = $(this).val();
				$.php('/ajax/account/isEmailUnique', {email:email});
			}
		});
		
		//registration form validation
		$('#hcpRegistrationSecondPhase').validate({
			rules: {
			    password: "required",
			    confirm: {
			      equalTo: ".password"
			    },
			    name: "required",
			    contact_person_name: "required",
			    contact_person_position: "required",
			    telephone: {
			    		required: true,
			    		phoneUS: true
			    },
			    email: {
			    	required: true,
			    	email: true
			    },
			    agreement: "required",
			    marketing_agreement: "required"
			
			  }
		});
		
		$('#hcpRegistrationFirstPhase').validate({
			rules: {
			    location: "required",
			    zipcode: "required",
			    zipcode_id: "required",
			    accommodation_type: "required",
			    pricing: "required"
			
			  }
		});
		
		$('#hcpRegistrationSecondPhase').submit(function(e){
		//	alert($('#email_checker_response').html());
			if ($('#email_checker_response').html() == 'Email address is already taken.')
				e.preventDefault();
		});
		
		//===========================
		
		$(document).mouseup(function (e)
				{
				    var container = $("#zipcode_list");

				    if (container.has(e.target).length === 0)
				    {
				    	
				        container.hide();
				    }
				});
		
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
});

function scrollTo(target){
	$(target).scrollToMe();
}

jQuery.fn.extend({
	scrollToMe: function () {
	    var x = jQuery(this).offset().top - 100;
	    jQuery('html,body').animate({scrollTop: x}, 500);
	}});