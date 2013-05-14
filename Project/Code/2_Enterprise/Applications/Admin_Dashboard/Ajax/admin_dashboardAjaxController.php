<?php 
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/2_Enterprise/Applications/Admin_Dashboard/Controllers/dashboard/dashboardView.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/Authorization/authorization.php';

require_once 'Project/Code/System/Leads/lead.php';
require_once 'Project/Code/System/Leads/leads.php';
require_once 'Project/Code/System/Seeker/seeker.php';
require_once 'Project/Code/System/Calendar/calendarEvents.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_providers.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/send_hubModule.php';
require_once 'Project/Code/System/Contacts/contact.php';

require_once 'Project/Code/System/Placements/hcp_placements.php';
require_once 'Project/Code/System/Placements/placement.php';
 

class admin_dashboardAjaxController extends applicationsSuperController
{
	public function new_leads_paginationAction()
	{	
		$offset = ($_REQUEST['page'] - 1) * 2;
		
		$leads = new leads();
		$leads->applyPagination(2, $offset);
		
		if(authorization::getUserSession()->user_role == 'admin')
			$leads->selectWithSeeker(false, "l.status = 'cold_new'");
		else
			$leads->selectWithSeeker(true, "l.status = 'cold_new'");
		
		$view = new dashboardView();
		$view->_set('array_of_new_leads',$leads->__get('array_of_leads'));
		$content = $view->displayNewLeads();
		
		jQuery('.table_container table.'.$_REQUEST['tab'].' tbody')->html($content);
		jQuery::getResponse();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function calendar_alerts_paginationAction()
	{
		$calendar_events = new calendarEvents();
		$calendar_events->select();
		
		$view = new dashboardView();
		$view->_set('array_of_calendar_events',$calendar_events->__get('array_of_calendar_events'));
		$content = $view->displayCalendarAlerts();
		
		jQuery('.table_container table.'.$_REQUEST['tab'].' tbody')->html($content);
		jQuery::getResponse();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function new_placements_paginationAction()
	{
		$offset = ($_REQUEST['page'] - 1) * 2;
		
		$leads = new leads();
		$leads->applyPagination(2, $offset);
		
		if(authorization::getUserSession()->user_role == 'admin')
			$leads->selectWithSeeker(false, "l.status = 'placed'");
		else
			$leads->selectWithSeeker(true, "l.status = 'placed'");
		
		$view = new dashboardView();
		$view->_set('array_of_new_placements',$leads->__get('array_of_leads'));
		$content = $view->displayNewPlacements();
		
		jQuery('.table_container table.'.$_REQUEST['tab'].' tbody')->html($content);
		jQuery::getResponse();
	}

//-------------------------------------------------------------------------------------------------	

	public function changeHcpNewStatusAction()
	{
		$hcp_id 	 =  $_REQUEST['hcp_id'];
		$status 	 =  $_REQUEST['status'];
		$text_status =  $_REQUEST['text_status'];
	
		
		$healthcare_providers = new healthcare_providers();
		$healthcare_providers->__set('hcp_id', $hcp_id);
		$healthcare_providers->__set('status', $status);
		$healthcare_providers->udpateApprovedStatus();
		
		
		if($status == 2)
		$healthcare_providers->udpatePublishedStatus();
		
		$healthcare_provider = new healthcare_provider();
		$healthcare_provider->__set('hcp_id', $hcp_id);
		$results = $healthcare_provider->select(false);
		$emailInfo['email'] = $results['email'];
		$emailInfo['name']  = $results['contact_person_name'];
		$emailInfo['hcp_name']  = $results['name'];
		$emailInfo['status']  = $text_status;
		
		
		$contact = new contact();
		$sendhubID = $contact->selectSendHubIdByUserId('hcp', $hcp_id);
		
		$sendhub = new sendhub();
		$message = 'Dear '.$results['contact_person_name'].', \n Looking for Eldercare has approved your account. \n '.$results['name'].' \n \n Wishing you the best success, \n Looking for Eldercare Team ';
		if($sendhubID != 'Invalid Number')
			$sendhub->sendSms($sendhubID, $message);
		
		$this->sendMail($emailInfo);
		
		jQuery::getResponse();
	}
	
//-------------------------------------------------------------------------------------------------	

	private function sendMail($emailInfo)
	{
		require_once 'Project/Code/1_Website/Applications/User_Account/Modules/mail/email.php';
		
		$dashboardView = new dashboardView();
		$dashboardView->_set('emailInfo', $emailInfo);
		
		$from_email = "raymond.baldonado@starfi.sh";
		$from_name	= "Raymond";
		
		if($emailInfo['status'] == 'approved')
			$subject	= "Your account has been approved.";
		else 
			$subject	= "Your account has been disapproved.";
		
		$body 		= $dashboardView->displayEmailTemplate();
		$to_email 	= $emailInfo['email'];
		$to_name 	= $emailInfo['name'];
		$images = array(
					'banner'	=> 'Project/Design/1_Website/Applications/Seeker/images/eldercare_banner.png',
					'icon'		=> 'Project/Design/1_Website/Applications/Seeker/images/Eldercare-email-template-icon.png',
		);
		email::send_email($to_email, $to_name, $from_email, $from_name, $subject, $body, '', '', $images);
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
	
	
}
?>