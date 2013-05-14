$(document).ready(function(e){var x = 0;
	Cufon.replace('ul#primary_nav a,.gotham_bold,ul#footer_nav a', { fontFamily: 'Gotham Rounded Bold' });
	Cufon.replace('#pagination a,label.error,#footer p,.gotham_book,.mid_section_columns p,.gray_background a, .gotham_rounded', { fontFamily: 'Gotham Rounded Book' });
	Cufon.replace('.mid_section_columns h3 a,.mid_section_columns h2,.helvetica77', { fontFamily: 'Helvetica Neue LT Std' });
	$('#navigation .account').live("click", (function(){
		$('#account_panel').slideDown();
		$('.account').attr('class', 'fright sprite account down');
	}));
	
	$('.down').live("click", (function(){
		$('#account_panel').slideUp();
		$('.account').attr('class', 'fright sprite account');
	}));
	
	  var press = 0;
	  $(window).keydown(function(event){
	
			if(event.keyCode == 40){
				  press++;
			  }
	
		    if(event.keyCode == 13 && $('input[name="test"]').val() != "yes" && press > 1) {
		    	  event.preventDefault();
		    }
	  });
	
	//JQUNIFORM
	$("input:checkbox, input:radio, input:text, input:password, textarea").uniform();
	
	$('input[name=query]').keyup(function(e){ 
		var query = $(this).val();
//		if (query.length >= 3){
		 if (e.keyCode != 40 && e.keyCode != 38 && e.keyCode != 13) 
				$.php('/ajax/health-care-search/query', { query:query });
//			}
			
			php.complete = function() {
				if($('#suggestion_list ul').children().length != 0) 
					$('#suggestion_list').slideDown(10);
					x=0;
			}
			
			php.error = function(xmlEr, typeEr, except) {}
			
			var zipid = $("#suggestion_list li[class*="+query+"]").attr('id');
			//alert(zipid);
			var test = 0;
			$('input[name=zipcode_id]').val(zipid);
			
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
				$('input[name="query"]').val(html);
				$('input[name="test"]').val("yes");
				$('#suggestion_list').slideUp(100);
			}
			 
		
			
		});
	
		$('#suggestion_list ul li').live('click',function(){
			var query = $(this).text();
			var id = $(this).attr('id');
			
			$('input[name=query]').val(query);
			$('input[name=zipcode_id]').val(id);
			$('#suggestion_list').slideUp(100);
			$('input[name="test"]').val("yes");
		});
		
		$('#search_panel').focusout(function(){
				$('#suggestion_list').slideUp('slow');
		});
		
		//Validation
		$('#seekerInfo_column form, form[action*="register"]').validate();
		
		//US Mobile phone format validation
		$('input[name=telephone]').keypress(function(){
			
			var telephone = $(this).val();
			
			if ((telephone.length == 3) || (telephone.length == 7))
			{
				telephone = telephone + '-';
				$(this).val(telephone);
				telephone = $(this).val();
			}
			
		});
		
		$('input[name=telephone]').blur(function(){
			var telephone = $(this).val();
			var result = [];
			
			if ((telephone[3] != "-") && (telephone[7] != "-") && telephone != "Invalid Number")
			{
				for($i=0; $i<telephone.length + 1; $i++)
				{
					result[$i] = telephone[$i];		
				}
				result.splice(3,0, "-")
				result.splice(7,0, "-")
				
				telephone = "";
				for($i=0; $i<12; $i++)
				{
					if(result[$i] != undefined)
					telephone += result[$i];		
				}
				
				$(this).val(telephone);
			}
		});
		
		$('#searchForm').validate({
			rules: {
			    query: "required"
			  }
		});
});


//To hide suggestion list
$(document).mouseup(function (e)
	{
	    var container = $("#suggestion_list");

	    if (container.has(e.target).length === 0)
	    {
	    	
	        container.hide();
	    }
	});

function isNumberKey(evt)
{
   var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;

   return true;
}



function test()
{
	
	alert('sdf');
	    /*var container = $("#suggestion_list");

	    if (container.has(e.target).length === 0)
	    {
	    	
	        container.hide();
	    }*/
}