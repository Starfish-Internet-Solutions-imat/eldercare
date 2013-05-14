<?php 
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'dashboardView.php';

require_once 'Project/Code/System/Leads/lead.php';
require_once 'Project/Code/System/Leads/leads.php';
require_once 'Project/Code/System/Seeker/seeker.php';
require_once 'Project/Code/System/Calendar/calendarEvents.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_providers.php';
require_once 'Project/Code/System/Placements/placements.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/Authorization/authorization.php';

class dashboardController extends applicationsSuperController
{
	public function indexAction()
	{
		$view = new dashboardView();
		
		$calendar_events = new calendarEvents();
		//$calendar_events->applyPagination(2, 0);
		if(authorization::getUserSession()->user_role == 'admin')
			$calendar_events->select();
		else
			$calendar_events->selectByStaff(authorization::getUserID());
			
		$healthcare_providers = new healthcare_providers();
		$view->_set('array_of_new_hcp', $healthcare_providers->selectNewHcps());
		
		$view->_set('pages_calendar_events',$calendar_events->getNumberOfPages());
		$view->_set('array_of_calendar_events',$calendar_events->__get('array_of_calendar_events'));
		
		$leads = new leads();
		//$leads->applyPagination(2, 0);
		
		if(authorization::getUserSession()->user_role == 'admin')
			$leads->selectWithSeeker(false, "l.status = 'cold_new'");
		else
			$leads->selectWithSeeker(true, "l.status = 'cold_new'");
		
		$view->_set('array_of_new_leads',$leads->__get('array_of_leads'));
		$view->_set('pages_new_leads',$leads->getNumberOfPages());
		
		$leads = new leads();
		//$leads->applyPagination(2, 0);
		
		if(authorization::getUserSession()->user_role == 'admin')
			$leads->selectWithSeeker(false, "l.status = 'placed'");
		else
			$leads->selectWithSeeker(true, "l.status = 'placed'");
		
		$placements = new placements();
		
		//check first if the use is admin or consultant
		if($_SESSION['user_session']['user_role'] != 'admin')
			$arrayOfPlacements = $placements->selectPlacementsViaConsultant(authorization::getUserID());
		else 
			$arrayOfPlacements = $placements->selectPlacementsAdmin();
			
		$view->_set('placements', $arrayOfPlacements);
		$view->_set('pages_new_placements',$leads->getNumberOfPages());
		$view->_set('array_of_new_placements',$leads->__get('array_of_leads'));
		$view->displayApplicationLayout();
	}
}
?>