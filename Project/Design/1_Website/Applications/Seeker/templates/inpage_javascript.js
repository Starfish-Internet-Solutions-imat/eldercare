$(document).ready(function(){
	
	$('input[name=query]').focusout(function(){
		
		if ($(this).val() != '')
		{
			var zipcode = $(this).val();
			if (zipcode.length > 4)
			{
				if (zipcode.length > 5)
				{
					zipcode = zipcode.substr(0, 5);
				}	
				
				$.php('/ajax/seeker/isEmailUnique', {zipcode:zipcode});
			}
			else
			{
				$('#invalidZipcode').html('Invalid zipcode');
			}
			
			php.error = function (){};
			
			php.complete = function () {
				
			}
			
		}
	});
	
});