$(window).load(function() {

//=================================================================================================
//FORM VALIDATION
    
    jQuery("#new_size_form,#resize_album_form,#add_album_form").validationEngine();

//=================================================================================================
   
//=================================================================================================
//IMAGES JQUERY
		
	$('#upload,#noImage').live('click', function(){
		$('#upload-dialog,#popUp_background').fadeIn();
	});
	
	$('#upload-dialog input[name="upload_photo"]').live('click',function(){
		$('#upload-dialog #loader').fadeIn();
	});
	
	$('#add_album').live('click', function(){
		$('#new_album_dialog,#popUp_background').fadeIn();
	});
	
	$('#add_sizeContainer').live('click', function(){
		$("#available_sizesContainer").slideUp();
		$('#new_size_dialog,#popUp_background').fadeIn();
		
	});
	
	$('.resize').live('click', function(){
		$("#available_sizesContainer").slideUp();
		$(".transparent_background").hide();
		$('#resize_dialog,#popUp_background').fadeIn();
		
	});
	
	$('.delete').live('click', function(){
		$("#available_sizesContainer").slideUp(function(){
			$(this).css({"visibility":"hidden"}).slideDown();});
			$(".transparent_background").hide();
			$(this).closest('li').find('.popUp_delete').fadeIn();
			$('#popUp_background').fadeIn();
			$('.popUp_delete').css({
				"visibility":"visible"
			
		});
		
	});
	$('.keep_size').live('click', function(){
		$('.transparent_background,.popUp_delete,#popUp_background').fadeOut();
		$('.popUp_delete').css({
			"visibility":"hidden"
		});
		$("#available_sizesContainer").hide().slideUp(function(){
			$(this).css({"visibility":"visible"});
		});
		
	});
	$('#deleteAlbum').live('click',function(){
		$(this).closest('div#album_container').find('div.deleteAlbum_popUp').fadeIn();
		$('#popUp_background').fadeIn();
	});
	$('.keep_album').live('click',function(){
		$('.deleteAlbum_popUp,#popUp_background').fadeOut();
	});
	
	$('.deletePhoto').live('click',function(){
		$('#imageAction').val('delete');
		$('.popUp_delete,#popUp_background').fadeIn();
	});
	$('.keep_photo').live('click',function(){
		$('.popUp_delete,#popUp_background').fadeOut();
	});
	
	
	$('#popUp_background,.cancelAlbum,.cancelUpload,.cancelResize,.cancelAddsize').live('click', function(){
		$('.popupDialog,#upload-dialog,#new_album_dialog,#new_size_dialog,#popUp_background,#resize_dialog,.transparent_background,.popUp_delete,.deleteImage_popUp').fadeOut(200, function(){
			$('label.error').remove();
		});
	});
	
	$('#album_select').change(function() {
		album_id = $(this).val();
		$.php('/ajax/image_library/load_sizes',{album_id:album_id});
	});
	
	$('form.image_details_form').submit(function(e){
		e.preventDefault();
		$.php('/ajax/image_library/load_image_details',$(this).serialize());
	});
	//available sizes
    $("#availableSize").click(function(){
        $("#available_sizesContainer").css({"visibility":"visible"}).slideDown("slow");
        $(".transparent_background").show();
    });
    //check if image is in use before delete
    $('#image_sidebar').submit(function(e){
    	if($('#imageAction').val() != 'edit'){
    		e.preventDefault();
    		$.php('/ajax/image_library/if_used',$(this).serialize());
    		
    		php.messages.defaultCallBack = function(msg,params) {
    			if(msg == 'Success.')
    			{
    				document.getElementById("image_sidebar").submit();
    			}
    			else{
    				$('#global_loader').fadeOut();
    				$('.imageId_error').fadeIn();
    				$('.deleteImage_popUp').fadeOut();
    			}
    		}
    	}
    });
  //check if size is in use before delete
    $('input[name=delete_size]').click(function(e){
    	e.preventDefault();
    	
    	parent_form = $(this).closest('form.delete_image_size');
    	
    	$.php('/ajax/image_library/if_size_used',parent_form.serialize());
    	
		php.messages.defaultCallBack = function(msg,params) {
			if(msg == 'Success.')
				parent_form.submit();
			else
			{
				$('#global_loader').fadeOut();
				$('.imageId_error').fadeIn();
				$('.popUp_delete').fadeOut();
			}
		}
    });
    //check if ablbum is in use before delete
    $('input[name=delete_album]').click(function(e){
    	e.preventDefault();
    	
    	showLoader();
    	parent_form = $(this).closest('.album_form');
    	
		$.php('/ajax/image_library/if_album_used',parent_form.serialize());
		
		php.messages.defaultCallBack = function(msg,params) {
			if(msg == 'Success.')
			{
				parent_form.find('#albumActionType').val('delete');
				parent_form.submit();
			}
			else
			{
				$('#global_loader').fadeOut();
				$('.imageId_error').fadeIn();
				$('.deleteAlbum_popUp').fadeOut();
			}
		}
    });
    //abort delete when image or album is in use
    $('.imageId_error span[title*="abort"]').click(function(){
    	$('#imageAction').val('edit');
    	$('.popupDialog,#popUp_background').fadeOut();
    });
    //check if image filename is used
    $('form#image_sidebar').submit(function(e){
    	e.preventDefault();
    	if($('#imageAction').val() == 'edit'){
	    	parent_form = $(this).closest('.album_form');
	    	
			$.php('/ajax/image_library/if_filename_exists',$(this).serialize());
			
			php.messages.defaultCallBack = function(msg,params) {
				if(msg == 'Success.')
				{
					document.getElementById('image_sidebar').submit();
				}
				else{
					$('#global_loader').fadeOut();
					$('.imageName_error').fadeIn();
					$('.deleteAlbum_popUp').fadeOut();
				}
			}
    	}
    });
//=================================================================================================
//FAKE INPUT TYPE FILE
	$('.trueInputFile').change(function(){
		var trueValue = $(this).attr('value');
		trueValue = trueValue.replace('C:\\fakepath\\','');
		var hasValue = $('#fakeInputFile-text').text();
		if(hasValue){
			$('#fakeInputFile-text span').text(' ');
		}
		$('#fakeInputFile-text span').text(trueValue);
		
	});

//=================================================================================================
	
	
});


