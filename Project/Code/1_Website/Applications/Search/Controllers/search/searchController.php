<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';
require_once 'Project/Code/1_Website/Pages/primary/seeker/Modules/seekerSession.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
require_once 'Project/Code/1_Website/Applications/Results/Controllers/results/resultsController.php';
require_once 'Project/Code/1_Website/Applications/Search/Modules/handler.php';
require_once 'searchView.php';
require_once 'Project/Code/System/ZipCodes/zipcode.php';


class searchController extends applicationsSuperController
{
	
	public function indexAction()
	{
		
		if (isset($_GET['search_panel']) || isset($_GET['get_started'])) //this block is creating sort of nice URL structure
		{
			if (isset($_GET['search_panel'])) //checks if query field from homepage is empty
				if(trim($_GET['query']) == "")
					header("location: /");
			
			if (!isset($_GET['housing_type']) || $_GET['housing_type'] == "") //if price is not indicated set 'all' as parameter
				$_GET['housing_type'] = 'all';

			if (((isset($_GET['zipcode_id'])) && (isset($_GET['query']) && (isset($_GET['price_range'])))))
			{
				$zipcodeModel = new zipcode();
				$uri = "health-care-search?query=".$_GET['query']."&zipcode_id=".$_GET['zipcode_id']."&housing_type=".$_GET['housing_type']."&price_range=".$_GET['price_range']."&get_started=+";
				
				header('location: health-care-search'.handler::encodePrettyUrl($uri).'/1');
			}
			elseif (($_GET['zipcode_id'] == "") && (isset($_GET['query'])))
			{
				$zipcodeModel = new zipcode();
				$zipcodeModel->__set('zipcode', $_GET['query']);
				$_GET['zipcode_id'] = $zipcodeModel->selectIdLikeZipcode();
				$uri = "health-care-search?query=".$_GET['query']."&zipcode_id=".$_GET['zipcode_id']."&housing_type=".$_GET['housing_type']."&get_started=+";

				header('location: health-care-search'.handler::encodePrettyUrl($uri).'/1');
			}
			elseif ($_GET['zipcode_id'] != "")
			{
				$uri = "health-care-search?query=".$_GET['query']."&zipcode_id=".$_GET['zipcode_id']."&housing_type=".$_GET['housing_type']."&get_started=+";
				header('location: health-care-search'.handler::encodePrettyUrl($uri).'/1');
			}
			else 
				header('location: /');
				
		}
		else
		{
			
			$old_error_handler = set_error_handler(array($this, 'searchErrorHandler')); //Calls error handler function
			
			try
			{	
				require_once 'Project/Code/System/HealthCare_Provider/healthcare_providers.php';
				require_once 'Project/Code/1_Website/Applications/Search/Modules/handler.php';
				
				$uri_array = handler::decodeUrl();
				$healthcare_providers	= new healthcare_providers();
				$zipcodeModel = new zipcode();
				$view = new searchView();
				
				if (count($uri_array) > 2)
				{
					$query_info['zipcode'] = $uri_array[2];
						
					$zipcodeModel->__set('zipcode', $query_info['zipcode']);
					$zipcodeModel->selectByZipcode();
						
					$query_info['zipcode_id'] = $zipcodeModel->__get('zipcode_id');
					
					$query_info['state'] = $zipcodeModel->__get('state');
					$query_info['city'] = $zipcodeModel->__get('city');
					$query_info['housing_type'] = $uri_array[1];
					
					if (count($uri_array) > 3)
						$page_number = $uri_array[4];
					
					if (!is_numeric($uri_array[3]))
						$query_info['pricing'] = $uri_array[3];
					else
					{
						$page_number = $uri_array[3];
						$query_info['pricing'] = "";					
					}
				}
				else
					header('location: /');
			
				if (seekerSession::ifSeekerSessionExists())
				{	
					if (!is_numeric($page_number))
						$page_number = 1;
					
					$query_info['housing_type'] = str_replace("-", " ", $query_info['housing_type']);
					$query_info['housing_type'] = ucwords($query_info['housing_type']);
					
					$query_info['active_page'] = $page_number;

					$page_number = $page_number - 1;
					$page_number = $page_number * 2;
					
					$healthcare_providers->applyPagination(10, $page_number, 'name');
					$providers = $healthcare_providers->selectForSearch((int)($query_info['zipcode_id']), $query_info['housing_type'], $query_info['pricing']);
					
					$query_info['pagination_pages'] = $healthcare_providers->__get('total_number_of_pages');
					
					
					new resultsController($providers, $query_info);
				}
				else
				{
					header("location:/seeker/get-information?housing_type=".str_replace("-", "+", $query_info['housing_type'])."&zipcode=".$query_info['zipcode']."&zid=".$query_info['zipcode_id']);
				}
			}
			catch (Exception $e)
			{
				print '<pre>';
				die($e);
			
			}
		}
	}
	
	/**
	 * 
	 * Error handler in case search encounter failure in DB or URL structure
	 * @param unknown_type $errno
	 * @param unknown_type $errstr
	 * @param unknown_type $errfile
	 * @param unknown_type $errline
	 */
	
	function searchErrorHandler($errno, $errstr, $errfile, $errline) //We should put this somewhere global
	{
		switch ($errno)
		{
			case E_USER_ERROR:
				die('user');
				break;
			case E_USER_WARNING:
				die('user_warning');
				break;
			case E_USER_NOTICE:
				die('user_notice');
				break;
		}
	
		return true;
	}
	
	public function testAction()
	{
	}
}
