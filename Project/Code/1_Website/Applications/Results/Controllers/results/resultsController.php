<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';
require_once 'Project/Code/1_Website/Pages/primary/seeker/Modules/seekerSession.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
require_once 'Project/Code/1_Website/Applications/Results/Controllers/results/resultsView.php';
require_once 'Project/Code/1_Website/Applications/Results/Modules/resultsModule.php';


class resultsController extends applicationsSuperController
{
	public function __construct($providers, $query_info = array(), $browse_info = array())
	{
		$view = new resultsView();
		
		if ((count($query_info) != 0) && (is_array($query_info))) //gets info from searching
		{
			$view->_set('state', $query_info['state']);
			$view->_set('city', $query_info['city']);
			$view->_set('housing_type', $query_info['housing_type']);
			$view->_set('zipcode', $query_info['zipcode']);
			$view->_set('zipcode_id', $query_info['zipcode_id']);
			$view->_set('pricing', $query_info['pricing']);
			
			$view->_set('pagination_pages', $query_info['pagination_pages']);
			$view->_set('active_page', $query_info['active_page']);
		}
		elseif(count($browse_info) != 0) //gets info from browsing
		{
			$view->_set('housing_type', 'Assisted Living');
			$view->_set('zipcode', $browse_info['zipcode']);
			$view->_set('zipcode_id', $browse_info['zipcode_id']);
			$view->_set('state', $browse_info['state']);
			$view->_set('city', $browse_info['city']);
			
			$view->_set('pagination_pages', $browse_info['pagination_pages']);
			$view->_set('active_page', $browse_info['active_page']);
		}
		
		$view->_set('array_of_properties', $providers);
		
		$view->showResult();
	}
}