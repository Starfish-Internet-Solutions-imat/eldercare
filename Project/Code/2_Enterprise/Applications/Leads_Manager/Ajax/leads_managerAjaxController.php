<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/2_Enterprise/Applications/Leads_Manager/Controllers/leads/leadsView.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/Authorization/authorization.php';

require_once 'Project/Code/System/Accounts/staffAccounts/staffAccount.php';
require_once 'Project/Code/System/Leads/lead.php';
require_once 'Project/Code/System/Leads/leads.php';
require_once 'Project/Code/System/Accounts/staffAccounts/staffAccounts.php';
require_once 'Project/Code/System/Calendar/calendarEvents.php';
require_once 'Project/Code/System/Placements/hcp_placements.php';
require_once 'Project/Code/System/Placements/placement.php';
	 	
class leads_managerAjaxController extends applicationsSuperController
{
	public function updateAssignedToStaffAction()
	{
		if($_REQUEST["staff_id"]!="")
		{
			$lead_id = $_REQUEST["lead_id"];
			$staff_id = $_REQUEST["staff_id"];
		}
		else
		{
			$lead_id = $_REQUEST["lead_id"];
			$staff_id = NULL;
		}
		$lead = new lead();
		$updateArray = array('staff_id' => $staff_id);
		$lead->__set('lead_id', $lead_id);
		$lead->updateGeneric($updateArray);
		
		jQuery::getResponse();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function new_leads_paginationAction()
	{	
		$offset = ($_REQUEST['page'] - 1) * 2;
		
		$leads = new leads();
		$leads->applyPagination(2, $offset);
		$array_of_leads = $leads->selectColdNew();
		
		$staff_accounts = new staffAccounts();
		$staffs = $staff_accounts->select();
		
		$view = new leadsView();
		$view->_set('leads', $array_of_leads);
		$view->_set('staffs', $staffs);
		$content = $view->displayNewLeads();
		
		jQuery('.table_container table.'.$_REQUEST['tab'].' tbody')->html($content);
		jQuery::getResponse();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function calendar_alerts_paginationAction()
	{
		$calendar_events = new calendarEvents();
		$calendar_events->select();
		
		$view = new leadsView();
		$array_of_calendar_events = $calendar_events->__get('array_of_calendar_events');
		$view->_set('calendar_events', $array_of_calendar_events);
		$content = $view->displayCalendarAlerts();
		
		jQuery('.table_container table.'.$_REQUEST['tab'].' tbody')->html($content);
		jQuery::getResponse();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function new_placements_paginationAction()
	{
		$offset = ($_REQUEST['page'] - 1) * 2;
			
		$placements = new hcp_placements();
		$array_of_placements = $placements->selectByStaff(authorization::getUserID());
		
		$view = new leadsView();
		$view->_set('placements', $array_of_placements);
		$content = $view->displayNewPlacements();
		
		jQuery('.table_container table.'.$_REQUEST['tab'].' tbody')->html($content);
		jQuery::getResponse();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function udpate_invoiceAction()
	{
		$invoice_number = $_REQUEST['invoice'];
		$placement_id =$_REQUEST['placement_id']; 
		$placement = new placement();
		$placement->__set('placement_id', $placement_id);
		$placement->__set('invoice_number', $invoice_number);
		$placement->__set('status', 'pending_payment');
		$placement->update();
		jQuery::getResponse();
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function udpateStatusAction()
	{
		$invoice_number = $_REQUEST['invoice'];
		$placement_id =$_REQUEST['placement_id'];
		$status = $_REQUEST['status'];
		$placement = new placement();
		$placement->__set('placement_id', $placement_id);
		$placement->__set('invoice_number', $invoice_number);
		$placement->__set('status', $status);
		$placement->update();

		jQuery::getResponse();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function insertPlacementAction()
	{
		
	}
	
	
	
}






