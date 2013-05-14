<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';

require_once 'Project/Code/2_Enterprise/Applications/Seekers_Manager/Blocks/seekers_managerBlockController.php';
require_once 'Project/Code/2_Enterprise/Applications/Seekers_Manager/Modules/profile/potential_hcpModule.php';
require_once 'Project/Code/System/Placements/placement.php';


class seekers_managerAjaxController extends applicationsSuperController
{
	public function seeker_detailsAction()
	{
		$blockController = new seekers_managerBlockController();
		
		$content = $blockController->getSeekerSideBar();
		
		jQuery('#applicationSideBar')->html($content);
		jQuery::getResponse();
		
	}

//-------------------------------------------------------------------------------------------------	
	
	public function sendDataAction()
	{
		if($_REQUEST["hcp_id"]!="")
		{
			$hcp_id = $_REQUEST["hcp_id"];
			$status = $_REQUEST["status"];
		}
		
		echo $status;
		jQuery::getResponse();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function viewSeekersAction()
	{
		$content = "";
		$x = 0;
	
		if (isset($_REQUEST['query']))
		{
				
			require_once 'Project/Code/System/Seeker/seekers.php';
				
			$query = $_REQUEST['query'];
			
			$seekers = new seekers();
				
			$content = '<ul>';
			
			foreach($seekers->selectLike($query) as $seeker)
			{
				$content .= '<li id='.$seeker->__get('seeker_id').'>'.$seeker->__get('name').'</li>';
				$x++;
				if ($x == 10)
				break;
			}
				
			$content .= '</ul>';
		}
	
	
		jQuery('#suggestion_list')->html($content);
		jQuery::getResponse();
	
	}

//-------------------------------------------------------------------------------------------------	
	
	public function updatePotentialHcpAction()
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
				$placement->__set('seeker_id', $lead_id);
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
	
	public function zipcodeQueryAction()
	{
		$content = "";
		$x = 0;
	
		if (isset($_REQUEST['zipcode']))
		{
				
			require_once 'Project/Code/System/ZipCodes/zipcodes.php';
				
			$query = $_REQUEST['zipcode'];
				
			$zipcodes = new zipcodes();
				
			$content = '<ul class="unstyled">';
				
			foreach($zipcodes->selectLike($query) as $zip)
			{
				$content .= '<li id="'.$zip['id'].'"> '.$zip['zipcode'].' - '.$zip['city'].', '.$zip['state'].'</li>';
				$x++;
				if ($x == 10)
				break;
			}
				
			$content .= '</ul>';
				
		}
	
		jQuery('#suggestion_list')->html($content);
		jQuery::getResponse();
	
	}

//-------------------------------------------------------------------------------------------------	
	
	public function updateConsultant()
	{
		
		if (isset($_REQUEST['staff_id']))
		{
			require_once 'Project/Code/System/Leads/lead.php';
			
			$lead = new lead();
			
			$update_array = array('staff_id' => $_REQUEST['staff_id']);
			$lead->__set('lead_id', $_REQUEST['lead_id']);
			
			$lead->updateGeneric($update_array);
			
			$this->seeker_detailsAction();	
		}
		
		jQuery::getResponse();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function calendar_events_paginationAction()
	{
		require_once 'Project/Code/2_Enterprise/Applications/Calendar/Blocks/calendarBlockController.php';
		
		$offset = ($_REQUEST['page'] - 1) * 2;
		
		$staff_id = authorization::getUserID();
		
		$calendarBlock = new calendarBlockController($staff_id, $_REQUEST['seeker_id'], NULL);
		
		$content = $calendarBlock->getCalendarRow(2, $offset);
		
		jQuery('.table_container table.'.$_REQUEST['tab'].' tbody')->html($content);
		jQuery::getResponse();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function potential_hcps_paginationAction()
	{
		require_once 'Project/Code/2_Enterprise/Applications/Seekers_Manager/Controllers/profile/profileView.php';
		require_once 'Project/Code/System/Leads/leads_potential_hcp/potential_hcps.php';
		
		$offset = ($_REQUEST['page'] - 1) * 2;
		
		$potential_hcps = new potential_hcps();
		$potential_hcps->applyPagination(2, $offset);
		$potential_hcps->selectAllPerLead($_REQUEST['seeker_id']);
		
		$array_of_potential_hcps = $potential_hcps->__get('array_of_potential_hcps');
		
		$view = new profileView();
		$view->_set('potential_hcps', $array_of_potential_hcps);
		
		$content = $view->displayPotentialHCPS();
		
		jQuery('.table_container table.'.$_REQUEST['tab'].' tbody')->html($content);
		jQuery::getResponse();
		
	}
	
	
	public function addAction()
	{
		$employee = new employee;
		$employee->__set('lastname', $_REQUEST['lastname']);
		$employee->__set('firstname', $_REQUEST['firstname']);
		$employee->__set('email', $_REQUEST['emailk']);
		$employee->insert();
		
	}
	
	
	
	
}