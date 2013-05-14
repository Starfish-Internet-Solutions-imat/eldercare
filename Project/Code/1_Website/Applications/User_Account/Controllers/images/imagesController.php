<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';
require_once 'imagesView.php';

require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
require_once 'Project/Code/System/HealthCare_Provider/image/image.php';
require_once 'Project/Code/System/HealthCare_Provider/image/images.php';

class imagesController extends applicationsSuperController
{
	public function indexAction()
	{
		$images = new hcp_images();
		$images->__set('hcp_id', userSession::getUserID());
		$array_of_images = $images->select();
		
	 	$healthcare_provider = new healthcare_provider();
	 	$healthcare_provider->__set('hcp_id', userSession::getUserID());
	 	$healthcare_provider->select();
		
		$view = new imagesView();
		$view->_set('array_of_images', $array_of_images);
		$view->_set('provider', $healthcare_provider);
		$view->displayImagesTemplate();
	}
	
//-------------------------------------------------------------------------------------------------

	public function uploadAction()
	{die;
		$file_array = array();
		$file_count = count($_FILES['image_file']['name']);
		
		//i just rearranged the array :)
		for($i = 0; $i < $file_count; $i++)
			$file_array[] = array(
				'name'=>$_FILES['image_file']['name'][$i],
				'type'=>$_FILES['image_file']['type'][$i],
				'tmp_name'=>$_FILES['image_file']['tmp_name'][$i],
				'error'=>$_FILES['image_file']['error'][$i],
				'size'=>$_FILES['image_file']['size'][$i]
			);
		
		require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
		require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Modules/crop/crop.php';
		
		$pdo_connection	= starfishDatabase::getConnection();
		$pdo_connection->beginTransaction();
		
		foreach($file_array as $file)
		{
			if ( ($file["type"] == "image/bmp") || ($file["type"] == "image/gif") ||
				 ($file["type"] == "image/jpeg") || ($file["type"] == "image/png")
				 && ($file["size"] < 5242880 ) && ($file["error"] == 0)
				)
			{
				$image_filename = time().str_replace(' ', '_', $file["name"]);
				
				//insert image data into database
				$image = new hcp_image();
				$image->__set('hcp_id', userSession::getUserID());
				$image->__set('filename', $image_filename);
				
				//set image paths
				$original_image_path	= HEALTHCARE_PROVIDER_IMAGES.'/'.userSession::getUserID().'/';
				
				if(!file_exists($original_image_path.$image_filename))
				{
					//save photo in original folder
					copy($file['tmp_name'], $original_image_path.$image_filename);
					
					$image_size	= getimagesize($file['tmp_name']);
					$width		= $image_size[0];
					$height		= $image_size[1];
					$ratio		= ($width * 1.0) / ($height * 1.0);
					
					$image_crop = new crop_uploads();
					
					//crop for image size thumbnail
					$image_crop->crop_image
					(
						$original_image_path, 
						$original_image_path.'/thumb/', 
						$image_filename, 
						$width,
						$height,
						$ratio,
						305,
						205
					);
					
					//insert!
					$image->insert();
				}
			}
		}
		
		$pdo_connection->commit();
	 	
	 	//header('Location: /account/images');
		//header('location: /account/profile/tab/photos_tab');
	}
	
//=======================================================================================================================	

	public function update_logoAction()
	{	
		require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
		
		//select image from database
	 	$healthcare_provider = new healthcare_provider();
	 	$healthcare_provider->__set('hcp_id', userSession::getUserID());
	 	$healthcare_provider->__set('image_id', $_POST['image_id']);
	 	$healthcare_provider->updateImage();
	 	
	 	header('Location: /account/images');
	}
	
//=======================================================================================================================	

	public function deleteAction()
	{	
		//select image from database
	 	$image = new hcp_image();
	 	$image->__set('image_id', $_POST['image_id']);
	 	$image->select();
	 	
	 	$image->delete();
	 	
	 	$data_handler = new dataHandler();
	 	$original_path	= HEALTHCARE_PROVIDER_IMAGES_SITE.'/'.userSession::getUserID().'/';
	 	$data_handler->delete_file(ltrim($original_path, '/').$image->__get('filename'));
	 	
	 	header('Location: /account/images');
	}

}