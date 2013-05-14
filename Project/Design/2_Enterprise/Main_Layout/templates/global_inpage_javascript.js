$(document).ready(function(){
	
	$(".changeTitle").click(function(){
		$(".box").removeClass("hide").addClass("show");
		$(".changeTitle").addClass("hide");
	});
	$(".cancel").click(function(){
		$(".box").removeClass("show").addClass("hide");
		$(".changeTitle").removeClass("hide");
		
	});
	$('.image_holder').bind('click',function(){
	    $('.image_holder > div.active').removeClass('active');
	    $(this).find('> div').addClass('active');
	});
	
	//TABLE SORTER
	 
	//JQUNIFORM
	//$("select, input:checkbox, input:text, input:password, textarea, input:radio").uniform();
	
	$("#staff_update_form").validate();
	
	$('#table_tabs > div').live('click',function(){
		if(!$(this).hasClass('active')){
			$('#table_tabs > div,.table_container table').removeClass('active');
			var table_id = $(this).attr('id');
			$(this).addClass('active');
			$('.table_container table.'+table_id).addClass('active');
		}
		
	});
	
	$(".tablesorter").tablesorter();   
    
//-------------------------------------------------------------------------------------------------
    //popups close action
    $('.sprite.cancel, #button_container span.sprite, .cancel, .cancelBtn').live('click',function(){
    	$('.popupDialog,#popUp_background').fadeOut();
    }); 
//-------------------------------------------------------------------------------------------------
    
//--------------------------------------------------------------------------------------------------
    
  //US Mobile phone format validation
	$('input[name=telephone]').live('keypress',function(){
		//alert(document.selection);
		var telephone = $(this).val();
		
		if (telephone.length > 11)
		{
			$(this).val(telephone.substring(0, 11));
			//alert(telephone.substring(0, 12));
		}
		else
		{
			
			if ((telephone.length == 3) || (telephone.length == 7))
			{
				telephone = telephone + '-';
				$(this).val(telephone);
				telephone = $(this).val();
			}
		}
	});
    
//--------------------------------------------------------------------------------------------------
    
    
});

