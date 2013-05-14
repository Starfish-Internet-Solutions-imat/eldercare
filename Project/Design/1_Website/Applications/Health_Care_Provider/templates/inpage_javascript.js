var thumbs_width_total = 0;
var panned = 0;
var timer = false;
var imgWidth = 0;
var thumbs_width_total_original = 0;
var activateCarousel = false;

$(window).load(function(){
	
	$('.popupDialog, #text').fadeIn();
	
	$('#proceed').click(function(){
		$('.popupDialog, #text').fadeOut();
	});
	
	imageToBackground();
	
	var thumbnails_container = $('#media_thumbnails_container').width();
	
	//Loop through the li's to determine the width of UL
	$('#media_thumbnails_container ul li').each(function(){
		thumbs_width_total += $(this).outerWidth(true);
	});
	
	//Width of the image thumbnail including its margin
	imgWidth = $('#media_thumbnails_container ul li:first').outerWidth(true);
	
	panned = imgWidth;
	
	//Check if ul's width is greater than thumbnail container
	if(thumbnails_container < thumbs_width_total) {
		activateCarousel = true;
		$('#media_thumbnails_container ul').css({'margin-left':-imgWidth});
		$('#media_thumbnails_container li:first').before($('#media_thumbnails_container li:last'));
	}
	
	//Apply the total width of the li's to UL 'media_thumbnails_container UL'
	$('#media_thumbnails_container ul').css({'width':thumbs_width_total});
	
	//Determine if mouse is hovered on right or left pan
	$('#media_panLeft,#media_panRight').hover(function(){	
		var direction = 'left';
		if($(this).is('#media_panRight')){
			direction = 'right';
		}
		
		//If Carousel is set to True , increment by 10 mili sec depending on hovered direction 
		if(activateCarousel === true) {
			timer = setInterval(function() {
				if(direction == 'right')
					panned++;
				else
					panned--;
				if((panned % imgWidth) == 0) {
					panned = imgWidth;
				}
				//Use Slide function
				slide(panned,direction,imgWidth);	
			}, 10);
		}
	},function(){clearInterval(timer);});
	
	//get image ID from clicked thumbail
	image_id = $('#media_thumbnails_container li.active').attr('id');
	//loadBackground(image_id);
	
	
	$('#media_thumbnails_container li').live('click',function(){
		$('#media_thumbnails_container li.active').removeClass('active');
		$(this).addClass('active');
		image_id = $(this).attr('id');
		hcp_id = $('input[name=home_id]').val();
		loadBackground(image_id, hcp_id);
	});
});

//function to change image on image display area setting it as its background image
function imageToBackground(){
	var img = $('#img_full');
	var imgContainer = $('#image_display_area');
	
		imgContainer.animate({opacity:0},'fast',function(){
			
			//Get attr 'src' of #image_full and fade it in on #image_display_area
			imgContainer.css({'background-image':'url('+img.attr('src')+')'}).animate({opacity:1.0},'fast',function(){
				$('#loader').fadeOut();
			});
		});
}

function loadBackground(image_id){
	//retrieve image through AJAX
	$.php('/ajax/health-care-provider/load_imageAjax',{image_id:image_id, hcp_id:hcp_id});
	$('#loader').fadeIn();
	
	php.complete = function(){
		imageToBackground();
	}
}

function slide(panned,direction,imgWidth){
	
	$('#media_thumbnails_container ul').animate({'margin-left':-panned},10,'swing',function(){
		if(panned == imgWidth){
			
			if(direction == 'right')
				$('#media_thumbnails_container li:last').after($('#media_thumbnails_container li:first'));
			else
				$('#media_thumbnails_container li:first').before($('#media_thumbnails_container li:last'));
		}
	});
}