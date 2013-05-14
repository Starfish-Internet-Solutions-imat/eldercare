<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'leadsView.php';

require_once 'Project/Code/System/Accounts/staffAccounts/staffAccount.php';

require_once 'Project/Code/System/Leads/leads.php';
require_once 'Project/Code/System/Accounts/staffAccounts/staffAccounts.php';
require_once 'Project/Code/System/Calendar/calendarEvents.php';
require_once 'Project/Code/System/Placements/hcp_placements.php';
require_once 'Project/Code/System/Placements/placements.php';

class leadsController extends applicationsSuperController
{
	public function indexAction()
	{
		
		$leads = new leads();
		//$leads->applyPagination(2, 0);
		$array_of_leads = $leads->selectUnassignedLeads();
		
		//$array_of_leads = $leads->__get('array_of_leads');
		
		$calendar_events = new calendarEvents();
		//$calendar_events->applyPagination(2, 0);
		$calendar_events->select();
		
		$array_of_calendar_events = $calendar_events->__get('array_of_calendar_events');
		$staff_accounts = new staffAccounts();
		$staffs = $staff_accounts->select();
			
		$placements = new hcp_placements();
		//$placements->applyPagination(2, 0);
		$array_of_placements = $placements->selectByStaff(authorization::getUserID());
	
		$placement = new placements;
		$placement_content = $placement->selectPlacements();
	
		
		$view = new leadsView();
		
		$view->_set('pages_new_leads',$leads->getNumberOfPages());
		$view->_set('pages_calendar_events',$calendar_events->getNumberOfPages());
		$view->_set('pages_new_placements',$placements->getNumberOfPages());
		$view->_set('leads', $array_of_leads);
		$view->_set('staffs', $staffs);
		$view->_set('calendar_events', $array_of_calendar_events);
		$view->_set('placements', $placement_content);
		$view->displayApplicationLayout();
	}
	
	
	
}