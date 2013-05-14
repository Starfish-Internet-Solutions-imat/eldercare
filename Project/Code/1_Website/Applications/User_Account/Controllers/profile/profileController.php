<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
require_once 'Project/Code/System/HealthCare_Provider/ammenity/amenities.php';
require_once 'Project/Code/System/House_Type/house_types.php';
require_once 'profileView.php';
require_once 'Project/Code/System/HealthCare_Provider/image/image.php';
require_once 'Project/Code/1_Website/Applications/Search/Modules/handler.php';
require_once 'Project/Code/System/Contacts/contact.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/send_hubModule.php';
require_once 'Project/Code/System/HealthCare_Provider/hcp_contact_persons/hcp_contact_person.php';


class profileController extends applicationsSuperController
{
	public function __construct()
	{
		parent::__construct();

		if(userSession::areWeLoggedIn() == FALSE)
		header('Location: /account/login');
	}

	//=====================================================================================================================

	public function indexAction()
	{
		
		$view = new profileView();

		if(userSession::isTemporarySessionSet())
		{
			$view->_set('is_new', TRUE);
			$view->displayThankYouPage();
			userSession::unsetTemporarySession();
		}
		else
		{
			$view->_set('is_new', FALSE);
		}
		
		$healthcare_provider = new healthcare_provider();

		//handles the changing of hcps profile
		/* if(isset($_POST['choose_hcp']))
		{
			$healthcare_provider->__set('hcp_id', $_POST['choose_hcp']);
			$healthcare_provider->selectIfOwn(userSession::getContactPersonID(), userSession::getUserID());
		}  */
		
		$template = $this->getValueOfURLParameterPair('tab');

		$healthcare_provider->__set('hcp_id', userSession::getUserID());
		//$healthcare_provider->select(false);
		
		$columns_array = array('name','description', 'suspended', 'approved', 'published');
		$healthcare_provider->selectGeneric($columns_array);

		$view->_set('is_suspended_notif', $healthcare_provider->__get('suspended'));

		$uri = parse_url($_SERVER['REQUEST_URI']);
		$uri = explode('/', $uri['path']);
		if($healthcare_provider->__get('suspended') == 1 && $uri[3] == strtotime('now'))
		{
			$view->_set('is_suspended', $healthcare_provider->__get('suspended'));
			$view->displaySuspendedPopup();
		}
		
		if($healthcare_provider->__get('approved') == 2)
		{
			$view->_set('is_approved', 2);
			$view->displayApprovedPopup();
		}
		
		if($healthcare_provider->__get('approved') == 1)
			$view->_set('is_approved', 1);
		
		

		$house_types = new house_types();
		$house_types->__set('hcp_id', userSession::getUserID());

		$amenities = new hcp_amenities();
		$amenity = new hcp_amenity();
		$amenity->__set('hcp_id', userSession::getUserID());

		$view->_set('current_tab', $template);
		$view->_set('is_completed_mark',$this->isCompleted());

		$view->_set('provider', $healthcare_provider);

		if ((strpos('name_description_tab', $template) !== false) || ($template == null))
		{
			$view->_set('template', 'name_description_tab');
		}
		elseif (strpos('house_details_tab', $template) !== false)
		{
			require_once 'Project/Code/System/ZipCodes/zipcode.php';

			$house_types = new house_types();
			$house_types->__set('hcp_id', userSession::getUserID());

			$columns_array = array('location','zipcode', 'accommodation_type', 'number_can_accommodate', 'number_of_bedrooms', 'price_from', 'price_to', 'pricing');
			$healthcare_provider->selectGeneric($columns_array);

			$zipcodeModel = new zipcode();

			$zipcodeModel->__set('zipcode_id', $healthcare_provider->__get('zipcode'));
			$zipcodeModel->selectById();

			$view->_set('zipcode',$zipcodeModel->__get('zipcode'));
			$view->_set('city_state',$zipcodeModel!=null?$zipcodeModel->__get('city').", ".$zipcodeModel->__get('state'):"");
			//$view->_set('provider', $healthcare_provider);
			$view->_set('hcp_house_types_array', $house_types->selectHcpHouseType(userSession::getUserID()));
			$view->_set('house_types', $house_types->select());
			$view->_set('template', 'house_details_tab');
		}
		elseif (strpos('amenities_tab', $template) !== false)
		{
			$amenity = new hcp_amenity();
			$amenities = new hcp_amenities();
			$amenity->__set('hcp_id', userSession::getUserID());

			$view->_set('hcp_amenities', $amenity->selectHcpAmenities(userSession::getUserID()));
				
			if ($view->_get('hcp_amenities') == null)
			{
				$view->_set('hcp_amenities', array('x','y','z'));//for default
			}

			$view->_set('amenities_by_category', self::amenitiesSetUpByCategory());
			$view->_set('amenities_categories', $amenities->selectAllamenitiesCategories());
			$view->_set('template', 'amenities_tab');
		}
		elseif (strpos('photos_tab', $template) !== false)
		{
			require_once 'Project/Code/System/HealthCare_Provider/image/image.php';
			require_once 'Project/Code/System/HealthCare_Provider/image/images.php';

			$images = new hcp_images();
			$images->__set('hcp_id', userSession::getUserID());
			$array_of_images = $images->select();
			
			$healthcare_provider->selectGeneric(array('image_id'));
			$image_id = $healthcare_provider->__get('image_id');

			//var_dump();die;
			
			if(count($array_of_images) != 0 && $image_id == null)
			{
				$healthcare_provider->updateGeneric(array('image_id' => $array_of_images[0]->__get('image_id')));
			}
			
			//$healthcare_provider->__set('hcp_id', userSession::getUserID());
			//$healthcare_provider->select();

			$view->_set('array_of_images', $array_of_images);
			//$view->_set('provider', $healthcare_provider);

			$view->_set('template', 'photos_tab');
		}
		elseif (strpos('my_account_details_tab', $template) !== false)
		{
			$columns_array = array('contact_person_name','contact_person_position', 'telephone', 'email', 'password');
			$healthcare_provider->selectGeneric($columns_array);
			
			$view->_set('provider', $healthcare_provider);
			$view->_set('template', 'my_account_details_tab');
		}
		else
		{
			header('location: /account/profile/tab/');
		}

			
		$view->_set('provider', $healthcare_provider);
		$view->displayProfileTemplate();

	}
	
	//=========================================================THIS FUNCTION ACCEPTS THE VALUES FROM THE FORMS FOR SAVING============================================================
	
	
	public function changehcpAction()
	{
		$health_care_provider = new healthcare_provider();
		$healthcare_provider->__set('hcp_id', $_POST['hcp_id']);
		$health_care_provider->select(false);
		
		userSession::saveUserSession($_POST['hcp_id'], $healthcare_provider->__get('name'), userSession::getContactPersonID());
		header("Location: /account/profile");
	}
	
	public function newhcpAction()
	{
		$health_care_provider = new healthcare_provider();
		$insert_array = array('contact_person_id' => userSession::getContactPersonID());
		$health_care_provider->insertGeneric($insert_array);
		
		userSession::saveUserSession($health_care_provider->__get('hcp_id'), "New Care Homes", userSession::getContactPersonID());
		
		header("Location: /account/profile");
	}
	
	
	public function accountSettingsAction()
	{
		$columns_array = array('contact_person_name','contact_person_position', 'telephone', 'email', 'password');
		$healthcare_provider = new healthcare_provider();
		$healthcare_provider->__set('hcp_id', userSession::getUserAccountID());
		$healthcare_provider->selectGeneric($columns_array);
		
		$view = new profileView();
		$view->_set('provider', $healthcare_provider);
		$view->_set('template', 'my_account_details_tab');
		$view->_set('account_settings', TRUE);
		$view->displayAccountSettingsTemplate();
	}
	
	

	//=========================================================THIS FUNCTION ACCEPTS THE VALUES FROM THE FORMS FOR SAVING============================================================

	public function saveAction()
	{
		$tab = $this->getValueOfURLParameterPair('tab');
		$healthcare_provider = new healthcare_provider();
		$healthcare_provider->__set('hcp_id', userSession::getUserID());
		$hcp = $healthcare_provider->selectGeneric(array('telephone', 'name'));

		if ($tab === 'name_description')
		{
			$update_array = array(
				'name'			=>	$_POST['name'],
				'description'	=>	$_POST['description']
			);
		}
		elseif ($tab === 'house_details')
		{
			$house_types = new house_types();
			$house_types->__set('hcp_id', userSession::getUserID());
				
			$update_array = array(
				'location'				=>	$_POST['location'],
				'zipcode'				=>	$_POST['zipcode_id'],
				'accommodation_type'	=>	$_POST['accommodation_type'],
				'number_can_accommodate'=>	intval($_POST['number_can_accommodate']),
				'number_of_bedrooms'	=>	intval($_POST['number_of_bedrooms']),
			//'price_from'			=>	$_POST['price_from'],
			//'price_to'				=>	$_POST['price_to']
				'pricing'				=>	$_POST['pricing']
			);
				
			$house_types->deleteHcpHouseType();

			foreach($_POST['house_type'] as $house_type)
			{
				$house_types->__set('house_type_id', $house_type);
				$house_types->updateHcpHouseType();
			}
		}
		elseif ($tab === 'amenities')
		{
			$amenities = new hcp_amenities();
			$amenity = new hcp_amenity();
			$amenity->__set('hcp_id', userSession::getUserID());
				
			$amenity->deleteHcpAmenity();
				
			for ($i = 1; $i <= $_POST['amenity_count']; $i++)
			{
				if (isset($_POST['amenity_id_'.$i]))
				{
					$amenity->__set('amenity_id', $_POST['amenity_id_'.$i]);
					$amenity->insertHcpAmenity();
				}
			}
				
			header('location: /account/profile/tab/'.$tab);
		}
		elseif ($tab === 'photos_tab')
		{
			echo "photos";
		}
		elseif ($tab === 'my_account_details')
		{
			$hcp_contact_person = new hcp_contact_person();
			$hcp_contact_person->__set('contact_person_id', userSession::getContactPersonID());
			
			if(($_POST['email'] !== '') && ($_POST['contact_person_name'] !== '') && ($_POST['contact_person_position']))
			{
				$update_array = array(
								'contact_person_name'		=>	$_POST['contact_person_name'],
								'contact_person_position'	=>	$_POST['contact_person_position'],
								'email'						=>	$_POST['email'],
				);
				$hcp_contact_person->updateGeneric($update_array);
			}
			else
			header('location: /account/profile/accountSettings');

			if (($_POST['old_password'] !== '') && ($_POST['password'] === $_POST['confirm']))
			{
				$update_array = array('password' => sha1(md5($_POST['confirm'])));
				$hcp_contact_person->updateGeneric($update_array);
			}
				
			if($_POST['telephone'] != $hcp['telephone'])
			{
				if($hcp['telephone'] == 'Invalid Number')
				{
					$sendhub = new sendhub();
					$sendHubContactID = $sendhub->addSmsContacts($hcp['name'], $_POST['telephone']);
					$contact = new contact();
					$update_array = array(
						'contact_number' 	  =>  $_POST['telephone'],
						'sendhub_contact_id'  =>  $sendHubContactID
					);
					$contact->__set('client_type', 'hcp');
					$contact->__set('user_id', userSession::getUserID());
					$contact->updateGeneric($update_array);
						
					if($sendHubContactID != 'Invalid Number')
					$update_array = array('telephone' => $_POST['telephone']);
					else
					$update_array = array('telephone' => $sendHubContactID);
						
					$hcp_contact_person->updateGeneric($update_array);
						
				}
				else
				{
					$contact = new contact();
					$sendHubContactID = $contact->selectSendHubIdByUserId('hcp', userSession::getUserID());
					$sendhub = new sendhub();
					$status = $sendhub->editSenhubContact($sendHubContactID, $hcp['name'], $_POST['telephone']);
					if(!$status)
					{
						print 'Invalid Phone Number';
					}
					else
					{
						$contact->__set('contact_number', $_POST['telephone']);
						$contact->__set('user_id', userSession::getUserID());
						$contact->__set('client_type', 'hcp');
						$contact->updateContactNumber();

						$update_array = array('telephone' => $_POST['telephone']);
						$hcp_contact_person->updateGeneric($update_array);
					}
				}
			}
		}

		if(isset($_POST['prev']))
		{
			$tab = $_POST['prev'];
		}

		if(isset($_POST['next']))
		{
			$tab = $_POST['next'];
		}

		if($tab != 'my_account_details')
		{
			$healthcare_provider->updateGeneric($update_array); 
			header('location: /account/profile/tab/'.$tab);
		}
		else
			header('location: /account/profile/accountSettings');
	}

	//=====================================================================================================================

	public function photo_uploadAction()
	{
		$file_array = array();
		$file_count = count($_FILES['image_file']['name']);

		print "<br /> <br />";
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
			$status = 0;
				
			if ( ($file["type"] == "image/bmp") || ($file["type"] == "image/gif") ||
			($file["type"] == "image/jpeg") || ($file["type"] == "image/png")
			&& ($file["size"] < 5242880 ) && ($file["error"] == 0)
			)
			{
				$image_filename = time().'_'.str_replace(' ', '_', $file["name"]);

				//insert image data into database
				$image = new hcp_image();
				$image->__set('hcp_id', userSession::getUserID());
				$image->__set('filename', $image_filename);

				//set image paths
				$original_image_path	= HEALTHCARE_PROVIDER_IMAGES.'/'.userSession::getUserID().'/';

				if (!is_dir($original_image_path))
				{
					mkdir($original_image_path, 0777);
				}

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
				
			else
			{
				if(
				!($file["type"] == "image/bmp"|| $file["type"] == "image/gif" ||
				$file["type"] == "image/jpeg" || $file["type"] == "image/png")
				)
				$status = 9;

				elseif ($file["size"] > 5242880)
				$status = 2;

				else
				$status = $file["error"];
			}
			
		}
		$pdo_connection->commit();
		
		if(!$_FILES)
			$status = 10;
		
		header('location:/account/profile/tab/photos_tab?status='.$status);
	}

	//=====================================================================================================================

	public function update_logoAction()
	{
		require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';

		//select image from database
		$healthcare_provider = new healthcare_provider();
		$healthcare_provider->__set('hcp_id', userSession::getUserID());
		$healthcare_provider->__set('image_id', $_POST['image_id']);
		$healthcare_provider->updateImage();
			
		header('location:/account/profile/tab/photos_tab');
	}

	//=====================================================================================================================

	public function photo_deleteAction()
	{
		$image = new hcp_image();
		$image->__set('image_id', $_REQUEST['image_id']);
		$image->select();
			
		$hcp = new healthcare_provider();
		$image_id = $hcp->selectImageID($_REQUEST['image_id']);
		
		if($image_id)
		{
			$hcp->__set('hcp_id', userSession::getUserID());
			$hcp->updateGeneric(array('image_id' => NULL));
		}

		$image->delete();
			
		$data_handler = new dataHandler();
		$original_path	= HEALTHCARE_PROVIDER_IMAGES_SITE.'/'.userSession::getUserID().'/';
		$data_handler->delete_file(ltrim($original_path, '/').$image->__get('filename'));
		header('location:/account/profile/tab/photos_tab');
		jQuery::getResponse();
	}

	//=====================================================================================================================

	public function cancelAction()
	{
		$healthcare_provider = new healthcare_provider();
		$healthcare_provider->__set('hcp_id', userSession::getUserID());
		$healthcare_provider->delete();

		userSession::unsetUserSession();

		$view = new profileView();
		$view->displayCancelSuccessTemplate();
	}

	//=====================================================================================================================

	public function publishAction()
	{
		$tab = $this->getValueOfURLParameterPair('tab');

		$healthcare_provider = new healthcare_provider();
		$healthcare_provider->__set('hcp_id', userSession::getUserID());
		$update_array = array(
			'published'		=>	1
		);
		$healthcare_provider->updateGeneric($update_array);

		header('location: /account/profile/tab/'.$tab);
	}

	//=====================================================================================================================

	public function unpublishAction()
	{
		$tab = $this->getValueOfURLParameterPair('tab');

		$healthcare_provider = new healthcare_provider();
		$healthcare_provider->__set('hcp_id', userSession::getUserID());
		$update_array = array(
				'published'		=>	0
		);
		$healthcare_provider->updateGeneric($update_array);

		header('location: /account/profile/tab/'.$tab);
	}

	//=====================================================================================================================

	public function loadZip()
	{

		$zipcodes = new zipcodes();
		$view = new profileView();

		$view->_set('zipcodes', $zipcodes->select());

	}

	//==============================================================THIS SETS UP THE CATEGORY OF THE AMENITIES=======================================================

	public function amenitiesSetUpByCategory()
	{
		$result = array();
		$amenities = new hcp_amenities();

		$amenities_categories = $amenities->selectAllamenitiesCategories();

		$amenities_list = $amenities->selectAllamenities();

		foreach ($amenities_categories as $amenities_category)
		{
			foreach ($amenities_list as $amenity)
			{
				if ($amenities_category['amenities_category_id'] == $amenity['amenities_category_id'])
				$result[$amenities_category['amenities_category']][] = $amenity;//echo $amenity['amenity_id'];
			}
		}
		return $result;
	}

	//===============================================isCompleted CHECKS IF THE EVERY FIELD IN THE TAB IS FILLED======================================================================

	public function isCompleted()
	{
		$healthcare_provider = new healthcare_provider();
		$healthcare_provider->__set('hcp_id', userSession::getUserID());

		$house_type = new house_types();

		$hcp_amenity = new hcp_amenity();

		$hcp_amenity->selectHcpAmenities(userSession::getUserID());

		$hcp_info = $healthcare_provider->select(false);

		$array_of_completed = array(1,1,1,1,1);

		if (($hcp_info['description'] == "") || ($hcp_info['description'] == null))
		$array_of_completed[0] = 0;

		if(($hcp_info['zipcode'] == "") || ($hcp_info['zipcode'] == null)
		|| ($house_type->countHcpHouseType(userSession::getUserID()) == 0) || ($hcp_info['accommodation_type'] == '')
		|| ($hcp_info['number_can_accommodate'] == 0) || ($hcp_info['number_of_bedrooms'] == 0) || ($hcp_info['pricing'] == '')
		|| ($hcp_info['location'] == ''))
		$array_of_completed[1] = 0;

		$count = $hcp_amenity->countAmenityPerHcp(userSession::getUserID());
		if ($count['count'] == '0')
		$array_of_completed[2] = 0;

		if ($hcp_info['image_id'] == '')
		$array_of_completed[3] = 0;

		if	(($hcp_info['contact_person_position'] == '') || ($hcp_info['contact_person_name'] == '')
		|| ($hcp_info['telephone'] == '') || ($hcp_info['telephone'] == 'Invalid Number') ||($hcp_info['email'] == ''))
		$array_of_completed[4] = 0;

		return $array_of_completed;
	}

	//=====================================================================================================================

	public function testAction()
	{
		$this->amenitiesSetUp();
	}

}
