$(document).ready(function(){
		//validation
	
	  $(window).keydown(function(event){
		    if(event.keyCode == 13 && $('input[name="test"]').val() != "yes") {
		    	  event.preventDefault();
		    }
		  });
	
	
		$('#seekerInformation').validate({
			rules: {
			    query: {
			      required: true,
			     // required: "#zipid:filled"
			    },
			    zipcode_id: {
			    	required:true,
			    	min: 5
			    },
			    telephone: {
			    		required: true,
			    		phoneUS: true
			    },
			    email: {
			    	required: true,
			    	email: true
			    }
			  }
		});	
		
		jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
		    phone_number = phone_number.replace(/\s+/g, ""); 
		    phone_number = phone_number.replace('-', ""); 
			return this.optional(element) || phone_number.length > 9 &&
				phone_number.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
		}, "Please specify a valid phone number");
		
		$('input[name=query]').live('keyup', function(){
			$('#invalidZipcode').html('');
		});
		
		/*$('input[name=query]').live('focusout' ,function (e)
		{
			if ($('input[name=zipcode_id]').val() == '')
			{
				$('#invalidZipcode').html('Invalid zipcode');
			}
			else
				$('#invalidZipcode').html('');
		});*/
		

		$('input[name=query]').live('focusout', function(){
			
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
		
		$('#suggestion_list ul li').live('click',function(){
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