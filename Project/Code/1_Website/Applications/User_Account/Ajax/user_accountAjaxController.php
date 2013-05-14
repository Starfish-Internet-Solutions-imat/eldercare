<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
require_once 'Project/Code/System/HealthCare_Provider/image/image.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';

class user_accountAjaxController extends applicationsSuperController
{
	
	public function zipcode_listAction()
	{
		$content = "";
		
		if (isset($_REQUEST['zip']))
		{
			
			$zipcode = $_REQUEST['zip'];
			$x = 0;
			
			require_once 'Project/Code/System/ZipCodes/zipcodes.php';
			
			$zipcodes = new zipcodes();
			
			$content = '<ul id="zip_list">';
			
			foreach($zipcodes->selectLike($zipcode) as $zip)
			{
				$content .= '<li id="'.$zip['id'].'"> '.$zip['zipcode'].' - '.$zip['city'].', '.$zip['state'].'</li>';
				$x++;
			}
			$content .= '</ul>';
		}
		$content .= "<input type='hidden' name='max_li' value='{$x}' />  ";
		$content .= "<input type='hidden' name='test'/>  ";
		
		
		//print $content;die;
		jQuery('#zipcode_list')->html($content);
		jQuery::getResponse();
		
	}
	
	public function photo_uploadAction()
	{
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
				
		$pdo_connection	= starfishDatabase::getConnection();
		$pdo_connection->beginTransaction();
		
		foreach($file_array as $file)
		{
			if ( ($file["type"] == "image/bmp") || ($file["type"] == "image/gif") ||
			($file["type"] == "image/jpeg") || ($file["type"] == "image/png")
			 && ($file["size"] < 5242880 ) && ($file["error"] == 0))
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
							
					//insert!
					$image->insert();
				}
			}
		}
		
		$pdo_connection->commit();
		jQuery("#preview")->html("<img src='".$original_image_path.$image_filename."' >");
		header('location:/account/profile/tab/photos_tab');
		jQuery::getResponse();
	}
	
	public function photo_deleteAction()
	{
		$image = new hcp_image();
		$image->__set('image_id', $_REQUEST['image_id']);
		$image->select();
		 
		$image->delete();
		 
		$data_handler = new dataHandler();
		$original_path	= HEALTHCARE_PROVIDER_IMAGES_SITE.'/'.userSession::getUserID().'/';
		$data_handler->delete_file(ltrim($original_path, '/').$image->__get('filename'));
		header('location:/account/profile/tab/photos_tab');
		jQuery::getResponse();
	}
	
	public function isEmailUniqueAction()
	{
		if (isset($_REQUEST['email']))
		{
			if (filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL))
			{
				require_once 'Project/Code/System/Accounts/userAccounts/userAccounts.php';
				$hcp = new healthcare_provider();
				if ($hcp->isEmailUnique($_REQUEST['email']))
				{
					jQuery('#email_checker_response')->html('Email address is already taken.');
				}
				else
				jQuery('#email_checker_response')->html('');
				
			}
			else
			{
				//jQuery('#email_checker_response')->html('Email address is invalid');
				jQuery('#email_checker_response')->html('');
			}
				
			
		}
		else
		{
			jQuery('#email_checker_response')->html('');
		}
			
		jQuery::getResponse();
			
	}
	
	public function testAction()
	{
		echo 'asd';
		jQuery::getResponse();
	}
	
	
	public function updateStatusAction()
	{
		$healthcare_provider = new healthcare_provider();
		$update_array = array(
			'approved' => 1
		);
		$healthcare_provider->__set('hcp_id', userSession::getUserID());
		$healthcare_provider->updateGeneric($update_array);

		jQuery::getResponse();
	}
	
	public function checkPasswordAction()
	{
		$password = $_REQUEST['old_password'];
		$hcp_id = userSession::getUserAccountID();
		$healthcare_provider = new	healthcare_provider();
	
		$columns_array = array("password");
		$healthcare_provider->__set('hcp_id', $hcp_id);
		$result = $healthcare_provider->selectGeneric($columns_array);
		
		if($result['password'] != sha1(md5($password)))
		{
			jQuery('#wrong_password')->html('Wrong Password');	
		}
		else
		{
			jQuery("input[name='profile']")->removeAttr('disabled');	
			jQuery('#wrong_password')->html("");
		}
		
		
		jQuery::getResponse();
	
	}
	
	
	
	
	
}