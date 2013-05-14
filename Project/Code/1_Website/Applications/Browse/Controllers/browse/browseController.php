<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';
require_once 'Project/Code/1_Website/Pages/primary/seeker/Modules/seekerSession.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
require_once 'Project/Code/1_Website/Applications/Browse/Controllers/browse/browseView.php';


class browseController extends applicationsSuperController
{
	public function indexAction()
	{
		$pageURL = parse_url($_SERVER['REQUEST_URI']);
		$pageURL = ltrim(rtrim($pageURL['path'], '/'),'/');
		
		$pageURL_array = explode('/', $pageURL);
		if (count(explode('/', $pageURL)) == 2)
		{
			$state = $pageURL_array[1];
			$state = str_replace('-', ' ', $state);
			$this->showCity($state);
			
		}
		elseif (count(explode('/', $pageURL)) == 3)
		{
			$state = $pageURL_array[1];
			$city = $pageURL_array[2];
			
			$state = str_replace('-', ' ', $state);
			$city = str_replace('-', ' ', $city);
			
			$this->showProviders($state, $city, 'Assisted Living');
		}
		elseif (count(explode('/', $pageURL)) == 4)
		{
			$state = $pageURL_array[1];
			$city = $pageURL_array[2];
			$page_number = $pageURL_array[3];

			$state = str_replace('-', ' ', $state);
			$city = str_replace('-', ' ', $city);
				
			$this->showProviders($state, $city, 'Assisted Living', $page_number);
		}
		elseif (count(explode('/', $pageURL)) > 3)
			header('location: /');
		
	}
	
	public function showCity($state)
	{
		require_once 'Project/Code/System/ZipCodes/zipcodes.php';
		
		$zipcodes	= new zipcodes();
		$cities		= $zipcodes->selectCityOrStateSearch('city', 'state', $state);
			
		$arrayOfCities = array();
		foreach($cities as $city)
		{
			if(!array_key_exists(strtoupper($city['city'][0]), $arrayOfCities))
			$arrayOfCities[strtoupper($city['city'][0])] =  array( $city['city'] => $city['city']);
			else
			$arrayOfCities[strtoupper($city['city'][0])][ $city['city']] = $city['city'];
		}
		
		$view = new browseView();
		
		$view->_set('array_of_cities', $arrayOfCities);
		$view->_set('state', $state);
		
		$view->showCities();
	}
	
	public function showProviders($state, $city, $house_type, $page_number = 1)
	{
		require_once 'Project/Code/System/ZipCodes/zipcodes.php';
		require_once 'Project/Code/System/HealthCare_Provider/image/image.php';
		require_once 'Project/Code/1_Website/Applications/Search/Modules/handler.php';
		require_once 'Project/Code/1_Website/Applications/Search/Modules/handler.php';
		require_once 'Project/Code/1_Website/Applications/Results/Controllers/results/resultsController.php';
		
		if (!is_numeric($page_number))
			$page_number = 1;
		
		$zipcodes	= new zipcodes();
		$zipcode = new zipcode();
		
		$browse_info['active_page'] = $page_number;
		
		$page_number = $page_number - 1;
		$page_number = $page_number * 2; //2 is limit
		
		$zipcodes->applyPagination(10, $page_number, 'name');
		$providers = $zipcodes->getProviderPerStateCity($state, $city, $house_type);
		
		$zipcode->__set('city', $city);
		$zipcode->__set('state', $state);
		
		$zipcode->selectByCityAndState(); //To get the zipcode of the city and state
		
		$browse_info['zipcode'] = ''; //$zipcode->__get('zipcode');
		$browse_info['zipcode_id'] = ''; //$zipcode->__get('zipcode_id');
		$browse_info['state'] = $state;
		$browse_info['city'] = $city;
		$browse_info['zipcode_id'] = $city.', '.$state;
		
		$browse_info['pagination_pages'] = $zipcodes->__get('total_number_of_pages');

		new resultsController($providers,"",$browse_info); //Second Parameter is for search
	}
	
	
	private function getArrayOfHomes($column, $value)
	{
		require_once 'Project/Code/System/HealthCare_Provider/healthcare_providers.php';
	
		$healthcare_providers	= new healthcare_providers();
	
		if ($column == 'zipcode')
			$array_of_homes	= $healthcare_providers->selectByZipCode($column, $value);
		else
			$array_of_homes	= $healthcare_providers->selectByCityOrZipCode($column, $value);
		
		$view = new view();
		$view->_set('array_of_homes', $array_of_homes);
	}
	
}