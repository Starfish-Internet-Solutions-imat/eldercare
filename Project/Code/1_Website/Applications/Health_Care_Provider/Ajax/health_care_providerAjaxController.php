<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';

class health_care_providerAjaxController extends applicationsSuperController
{

//-------------------------------------------------------------------------------------------------	
	
	public function load_imageAjaxAction()
	{
		require_once 'Project/Code/System/HealthCare_Provider/image/image.php';
		
		$image_path = '';
		
		if(isset($_REQUEST['image_id']))
			$image_path = HEALTHCARE_PROVIDER_IMAGES_SITE.'/'.$_REQUEST['hcp_id'].'/'.hcp_image::selectFilename($_REQUEST['image_id']);
		
		//place the image location on the src attribute of #image_full
		
		jQuery('#img_full')->attr('style',"background-image:url($image_path)");
		jQuery::getResponse();
		
	}	
}
?>