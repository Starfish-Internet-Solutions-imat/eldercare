<?php 
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
require_once 'Project/Code/System/HealthCare_Provider/ammenity/amenities.php';
require_once 'Project/Code/System/House_Type/house_types.php';
require_once 'profileBlockView.php';



class profileBlockController extends applicationsSuperController
{
	
	
	public function __construct()
	{
		parent::__construct();
		
		if(userSession::areWeLoggedIn() == FALSE)
			header('Location: /account/login');
	}
	
	public static function  viewProfilePage($template)
	{
		$healthcare_provider = new healthcare_provider();
		$healthcare_provider->__set('hcp_id', userSession::getUserID());
		$view = new profileBlockView();
		
		if (strpos($template, 'name_description_tab') !== false)
		{
			$columns_array = array('name','description');
			$healthcare_provider->selectGeneric($columns_array);
			
			$view->_set('provider', $healthcare_provider);
			$view->_set('template', $template);
			return $view->displayTabContentTemplate();
		}
		elseif (strpos($template, 'house_details_tab') !== false)
		{
			$house_types = new house_types();
			$house_types->__set('hcp_id', userSession::getUserID());
			
			$columns_array = array('name','description');
			$healthcare_provider->selectGeneric($columns_array);
			
			$view->_set('hcp_house_types_array', $house_types->selectHcpHouseType(userSession::getUserID()));
			$view->_set('house_types', $house_types->select());
			$view->_set('template', $template);
			return $view->displayTabContentTemplate();
		}
		elseif (strpos($template, 'amenities_tab') !== false)
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
			$view->_set('template', $template);
			return $view->displayTabContentTemplate();
		}
		elseif (strpos($template, 'photos_tab') !== false)
		{
			require_once 'Project/Code/System/HealthCare_Provider/image/image.php';
			require_once 'Project/Code/System/HealthCare_Provider/image/images.php';
			
			$images = new hcp_images();
			$images->__set('hcp_id', userSession::getUserID());
			$array_of_images = $images->select();
			
			$healthcare_provider->__set('hcp_id', userSession::getUserID());
			$healthcare_provider->select();
			
			$view->_set('array_of_images', $array_of_images);
			$view->_set('provider', $healthcare_provider);
			
			$view->_set('template', $template);
			return $view->displayTabContentTemplate();
		}
		elseif (strpos($template, 'my_account_details_tab') !== false)
		{
			$view->_set('template', $template);
			return $view->displayTabContentTemplate();
		}
		/* $healthcare_provider->selectGeneric();
		
		$house_types = new house_types();
		$house_types->__set('hcp_id', userSession::getUserID());
		
		$amenities = new hcp_amenities();
		$amenity = new hcp_amenity();
		
		$amenity->__set('hcp_id', userSession::getUserID());
		
			require_once 'Project/Code/System/ZipCodes/zipcode.php';
			
			$house_type = new house_types();

			$zipcode = new zipcode();
			$zipcode->__set('zipcode_id', $healthcare_provider->__get('zipcode_id'));
			
			
			$view->_set('hcp_amenities', $amenity->selectHcpAmenities(userSession::getUserID()));
			
			if ($view->_get('hcp_amenities') == null)
			{
				$view->_set('hcp_amenities', array('x','y','z'));//for default
			}
			
			$view->_set('amenities_by_category', self::amenitiesSetUpByCategory());
			$view->_set('amenities_categories', $amenities->selectAllamenitiesCategories());
			$view->_set('hcp_house_types_array', $house_types->selectHcpHouseType(userSession::getUserID()));
			$view->_set('zipcode', $zipcode->selectById());
			$view->_set('provider', $healthcare_provider);
			$view->_set('house_types', $house_type->select());
			$view->displayProfileTemplate(); */
	}

	public function cancelAction()
	{
		$healthcare_provider = new healthcare_provider();
		$healthcare_provider->__set('hcp_id', userSession::getUserID());
		$healthcare_provider->delete();
		
		userSession::unsetUserSession();
		
		$view = new profileView();
		$view->displayCancelSuccessTemplate();
		
	}
	
	public function loadZip()
	{

		$zipcodes = new zipcodes();
		$view = new profileView();
		
		$view->_set('zipcodes', $zipcodes->select());
		
	}
	
	public static function amenitiesSetUpByCategory()
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
	
	public function testAction()
	{
		$this->amenitiesSetUp();
	}
	
}
