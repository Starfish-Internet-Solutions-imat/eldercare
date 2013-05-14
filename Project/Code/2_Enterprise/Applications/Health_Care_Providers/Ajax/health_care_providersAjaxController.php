<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/2_Enterprise/Applications/Seekers_Manager/Modules/profile/potential_hcpModule.php';
require_once 'Project/Code/System/Placements/placement.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_providers.php';
require_once 'Project/Code/System/Contacts/contact.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/mail/email.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/send_hubModule.php';
require_once 'Project/Code/2_Enterprise/Applications/Health_Care_Providers/Controllers/profile/profileView.php';

class health_care_providersAjaxController extends applicationsSuperController
{
	    public function updatePotentialLeadxAction()
		{
			 if($_REQUEST["potential_hcp_id"]!="")
			{
				$potential_hcp_id = $_REQUEST["potential_hcp_id"];
				$status = $_REQUEST["status"];
			}
		
			$update_array = array('status' => $status);
			
			require_once 'Project/Code/System/Leads/leads_potential_hcp/potential_hcp.php';
			require_once 'Project/Code/System/Leads/lead.php';
			
			$potential_hcp = new potential_hcp();
			$lead = new lead();
			
			$potential_hcp->__set('potential_hcp_id', $potential_hcp_id);
	
			$lead_id = $potential_hcp->singleSelect();
			$lead->__set('lead_id', $lead_id['lead_id']);
			$lead_status = $lead->singleSelect('status');
			
			$potential_hcp->updateGeneric($update_array);
			
			if (strpos($status, 'place') !== false)
			{
				$lead->updateGeneric($update_array);
			}
			else if (($status === 'info_sent') && (strpos($status, 'place')))
			{
				$lead->updateGeneric($update_array);
			}
			else if (($status === 'contacted') && (($lead_status !== 'info_sent') && (strpos($status, 'place') !== false)))
			{
				$update_array = array('status' => 'contact_made');
				$lead->updateGeneric($update_array);
			}
			
			jQuery::getResponse();
		}

//-------------------------------------------------------------------------------------------------	
	
		public function updatePotentialLeadAction()
		{
			
			require_once 'Project/Code/System/Leads/leads_potential_hcp/potential_hcp.php';
			require_once 'Project/Code/System/Leads/leads_potential_hcp/potential_hcps.php';
			require_once 'Project/Code/System/Leads/lead.php';
			
			if($_REQUEST["potential_hcp_id"]!="")
			{
				$potential_hcp_id = $_REQUEST["potential_hcp_id"];
				$status = $_REQUEST["status"];
				
				if($status == 'placed')
				{
					$potential_hcp	= new potential_hcp();
					$potential_hcp->__set('potential_hcp_id', $potential_hcp_id);
					$lead_id = $potential_hcp->singleSelect();
					$hcp_id = $potential_hcp->singleSelect('hcp_id');
				
				
					$placement	= new placement();
					$placement->__set('potential_hcp_id', $potential_hcp_id);
					$placement->__set('seeker_id', $potential_hcp_id);
					$placement->__set('hcp_id', $hcp_id);
				
					$placement->addPlacement();
				}
				else
				{
					$placement	= new placement();
					$placement->__set('potential_hcp_id', $potential_hcp_id);
					$placement->deletePlacement();
				}
				
				
			}
		
			$update_array = array('status' => $status);
		
		
			$potential_hcp	= new potential_hcp();
			$potential_hcps	= new potential_hcps();
			$potential_mcp_module = new potential_hcpModule();
			$lead = new lead();
		
			$potential_hcp->__set('potential_hcp_id', $potential_hcp_id);
		
			$lead_id = $potential_hcp->singleSelect();
		
			$lead->__set('lead_id', $lead_id['lead_id']);
			$lead_status = $lead->singleSelect('status');
			$lead_status = $lead_status['status'];
		
			$potential_hcp->updateGeneric($update_array);
		
			$final_lead_status = potential_hcpModule::statusAnalyzer($potential_hcps->selectAllStatusPerLead($lead_id['lead_id']));
		
			$update_array = array('status' => $final_lead_status);
			$lead->updateGeneric($update_array);
		
			jQuery::getResponse();
		}

//-------------------------------------------------------------------------------------------------	
	
	public function calendar_events_paginationAction()
	{
		require_once 'Project/Code/2_Enterprise/Applications/Calendar/Blocks/calendarBlockController.php';
		
		$offset = ($_REQUEST['page'] - 1) * 2;
		
		$staff_id = authorization::getUserID();
		
		$calendarBlock = new calendarBlockController($staff_id, NULL, $_REQUEST['hcp_id']);
		
		$content = $calendarBlock->getCalendarRow(2, $offset);
		
		jQuery('.table_container table.'.$_REQUEST['tab'].' tbody')->html($content);
		jQuery::getResponse();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function potential_leads_paginationAction()
	{
		require_once 'Project/Code/2_Enterprise/Applications/Health_Care_Providers/Controllers/contact_history/contact_historyView.php';
		require_once 'Project/Code/System/Leads/leads_potential_hcp/potential_hcps.php';
		
		$offset = ($_REQUEST['page'] - 1) * 2;
		
		$potential_hcps = new potential_hcps();
		$potential_hcps->applyPagination(2, $offset);
		$potential_hcps->selectAllPerHCP($_REQUEST['hcp_id'], true);
		
		$array_of_potential_hcps = $potential_hcps->__get('array_of_potential_hcps');
		
		$view = new contact_historyView();
		$view->_set('potential_leads', $array_of_potential_leads);
		
		$content = $view->displayPotentialLeads();
		
		jQuery('.table_container table.'.$_REQUEST['tab'].' tbody')->html($content);
		jQuery::getResponse();
		
	}
	
	public function searchAction()
	{
		require_once 'Project/Code/System/HealthCare_Provider/healthcare_providers.php';
		
		$hcps = new healthcare_providers();
		$prev_content = $_POST['prev_content'];
		unset($_POST['prev_content']);
		$searchList = $hcps->listingSearch($_POST);
		
		for($i = 0; $i<count($searchList); $i++)
		{
			foreach($searchList as $item)
			{
				if($item['published'] == 1)
				$searchList[$i]['published'] = "Published";
				else
				$searchList[$i]['published'] = "Unpublished";
			}
		}
		
		
		$content = "";
		if ($searchList != null)
			foreach ($searchList as $searchItem)
			{

				$content .= '<tr>';
				
				foreach ($searchItem as $field => $value)
				{
					
					if (strpos($field, 'date_updated') !== false)
						$value = $value!=""?date('M-d-y h:i:s A',strtotime($value)):'Never been updated';
					elseif (strpos($field, 'date_updated') !== false)
						$value = $value !=""?date('M-d-y h:i:s A',strtotime($value)):'';
					
					if (strpos($field, 'hcp_id') === false)
						$content .= '<td>'.$value.'</td>';
				}
				
				$content .=
				'<td><a class="underline" href="/hcps/profile/view/'.$searchItem['hcp_id'].'">view</a> |
				<a class="underline" href="/hcps/profile/contact_history/'.$searchItem['hcp_id'].'">view</a></td>
				<td><a class="recommend" href="#" id ="'.$searchItem['hcp_id'].'">Recommend</a><input type = "hidden" value = "'.$searchItem['hcp_id'].'"></td>
				';
				
				$content .= '</tr>';
			}
		else
			$content = $prev_content;	 
			
		jQuery('tbody')->html($content);
		jQuery::getResponse();
	}
	
	public function sortAction()
	{
		print 'here';
		jQuery::getResponse();
	}

	public function changeHcpNewStatusAction()
	{
		$hcp_id 	 =  $_REQUEST['hcp_id'];
		$status 	 =  1;
		$text_status =  'approved';
		
		$healthcare_providers = new healthcare_providers();
		$healthcare_providers->__set('hcp_id', $hcp_id);
		$healthcare_providers->__set('status', $status);
		$healthcare_providers->udpateApprovedStatus();
	
	 	if($status == 1)
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
		$message = 'Thank you for using Looking for Eldercare to find the best care for your loved one. \n \n '.$results['name'].' \n Looking for Eldercare has approved your account \n Wishing you the best success, \n \n Looking for Eldercare Team ';
		if($sendhubID != 'Invalid Number')
		$sendhub->sendSms($sendhubID, $message);   
		
	   $this->sendMail($emailInfo); 
		jQuery::getResponse();
	}
	
	//-------------------------------------------------------------------------------------------------
	
	private function sendMail($emailInfo)
	{
		require_once 'Project/Code/1_Website/Applications/User_Account/Modules/mail/email.php';
		
		$view = new profileView();
		
		$from_email = "raymond.baldonado@starfi.sh";
		$from_name	= "Raymond";
		$body	    = $view->displayEmailTemplate();
		$subject	= "Thank you for choosing Eldercare.";
		$to_email 	= $emailInfo['email'];
		$to_name 	= $emailInfo['name'];
		$images = array(
							'banner'	=> 'Project/Design/1_Website/Applications/Seeker/images/eldercare_banner.png',
							'icon'		=> 'Project/Design/1_Website/Applications/Seeker/images/Eldercare-email-template-icon.png',
		);
		email::send_email($to_email, $to_name, $from_email, $from_name, $subject, $body, '', '', $images);
	}
}
	
	

