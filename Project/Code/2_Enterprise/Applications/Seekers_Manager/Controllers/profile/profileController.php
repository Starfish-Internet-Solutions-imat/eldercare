<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'profileView.php';

require_once 'Project/Code/System/Accounts/staffAccounts/staffAccount.php';

require_once 'Project/Code/System/Leads/lead.php';
require_once 'Project/Code/System/Seeker/seeker.php';
require_once 'Project/Code/System/Calendar/calendarEvent.php';
require_once 'Project/Code/System/Conversations/lead_conversations.php';
require_once 'Project/Code/System/Leads/leads_potential_hcp/potential_hcps.php';
require_once 'Project/Code/System/Contacts/contact.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/send_hubModule.php';

class profileController extends applicationsSuperController
{
	public function indexAction()
	{
		$seeker_id = $this->getValueOfURLParameterPair('view');
		
		$view = new profileView();
		
		if($seeker_id  == '0')
			$view->has_leads = 'no';
				
		
		if(is_numeric($seeker_id))
		{
			//get seeker info
			$seeker = new seeker();
			$seeker->__set('seeker_id', $seeker_id);
			$seeker->selectWithSeeker();
			
			//get lead info
			$lead = new lead();
			$lead->__set('lead_id', $seeker_id);
			$lead->select();
			if($lead->__get('status')=='cold_new')
			{
				$update = array('status' => 'cold_read');
				$lead->updateGeneric($update);
			}
			$lead->select();	
			
			//get potential hcps
			$potential_hcps = new potential_hcps();
			$potential_hcps->applyPagination(2, 0);
			$potential_hcps->selectAllPerLead($seeker_id);
			
			$array_of_potential_hcps = $potential_hcps->__get('array_of_potential_hcps');
			
			//get seeker conversations
			$conversations = new lead_conversations();
			$array_of_conversations = $conversations->selectByStaffIDAndLeadID(authorization::getUserID(), $seeker_id);
			
			//$array_of_conversations = $conversations->__get('array_of_conversations');
			
			$view->_set('seeker', $seeker);
			$view->_set('lead', $lead);
			$view->_set('potential_hcps', $array_of_potential_hcps);
			$view->_set('conversations', $array_of_conversations);
			$view->_set('pages_potential_hcps', $potential_hcps->getNumberOfPages());
			$view->displayApplicationLayout();
		}
		else
		{
			$seeker_id = seeker::selectFirst(TRUE);
			
			if(is_numeric($seeker_id))
			{
				if (routes::getInstance()->getCurrentTopLevelURLName() == 'your_leads')
					header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/view/'.$seeker_id);
				else
					header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/profile/view/'.$seeker_id);
			}
			else
			{
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/profile/view/0');
			}
		}
			
	}

//-------------------------------------------------------------------------------------------------	
	
	public function editAction()
	{
		$seeker_id = $this->getValueOfURLParameterPair('edit');
		
		if(is_numeric($seeker_id))
		{
		
			$seeker = new seeker();
			$seeker->__set('seeker_id', $seeker_id);
			$seeker->__set('name', $_POST['name']);
			$seeker->__set('email', $_POST['email']);
			$seeker->__set('telephone', $_POST['telephone']);
			
			$seeker->__set('house_type', $_POST['house_type']);
			$seeker->__set('zipcode_id', $_POST['zipcode_id']);
			$seeker->__set('staff_id', $_POST['staff_id']==='null'?null:$_POST['staff_id']);
			

			$seeker->__set('seeker_id', $seeker_id);
			$seekerTelehpone = $seeker->selectTelephone();
			
			if($_POST['telephone'] != $seekerInfo['telephone'])
			{
				$contact = new contact();
				$contact->__set('contact_number', $_POST['telephone']);
				$contact->__set('user_id', $seeker_id);
				$contact->__set('client_type', 'seeker');
				$contact->updateContactNumber();
				
				$sendHubContactID = $contact->selectSendHubIdByUserId('seeker', $seeker_id);
				
				$sendhub = new sendhub();
				$sendhub->editSenhubContact($sendHubContactID, $_POST['name'], $_POST['telephone']);
			}	
				
			$seeker->update();
		
			
		
			if (routes::getInstance()->getCurrentTopLevelURLName() == 'your_leads')
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/view/'.$seeker_id);
			else
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/profile/view/'.$seeker_id);
		}
		
		else
			header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName());
	}

//-------------------------------------------------------------------------------------------------	
	
	public function deleteAction()
	{
		if(isset($_POST['seeker_id']) && is_numeric($_POST['seeker_id']))
		{
			$seeker_id = $_POST['seeker_id'];
			
			$seeker = new seeker();
			$seeker->__set('seeker_id', $seeker_id);
			$seeker->selectWithSeeker();
			$seeker->delete($seeker_id);
			
			//if($seeker->__get('status') == 'closed')
		}
		
		header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName());
	}

//-------------------------------------------------------------------------------------------------	
	
	public function closeAction()
	{
		$seeker_id = $this->getValueOfURLParameterPair('close');
		
		if(is_numeric($seeker_id))
		{
			$lead = new lead();
			$lead->__set('lead_id', $seeker_id);
			$lead->__set('status', 'closed');
			$lead->update();
			
			if (routes::getInstance()->getCurrentTopLevelURLName() == 'your_leads')
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/view/'.$seeker_id);
			else
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/profile/view/'.$seeker_id);
		}
		
		else
			header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName());
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function openAction()
	{
		$seeker_id = $this->getValueOfURLParameterPair('open');
	
		if(is_numeric($seeker_id))
		{
			$lead = new lead();
			$lead->__set('lead_id', $seeker_id);
			$lead->__set('status', 'cold_new');
			$lead->update();
			
			if (routes::getInstance()->getCurrentTopLevelURLName() == 'your_leads')
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/view/'.$seeker_id);
			else
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/profile/view/'.$seeker_id);
		}
		
		else
			header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName());
	}

//-------------------------------------------------------------------------------------------------	
	
	public function urgentAction()
	{
		$seeker_id = $this->getValueOfURLParameterPair('urgent');
		
		if(is_numeric($seeker_id))
		{
			$update_array = array(
				'urgent' => 1
			);
			
			$lead = new lead();
			$lead->__set('lead_id', $seeker_id);
			$lead->updateGeneric($update_array);
			
			if (routes::getInstance()->getCurrentTopLevelURLName() == 'your_leads')
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/view/'.$seeker_id);
			else
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/profile/view/'.$seeker_id);
		}
		
		else
			header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName());
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function remove_urgentAction()
	{
		$seeker_id = $this->getValueOfURLParameterPair('remove_urgent');
	
		if(is_numeric($seeker_id))
		{
			$update_array = array(
					'urgent' => 0
			);
				
			$lead = new lead();
			$lead->__set('lead_id', $seeker_id);
			$lead->updateGeneric($update_array);
			
			if (routes::getInstance()->getCurrentTopLevelURLName() == 'your_leads')
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/view/'.$seeker_id);
			else
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/profile/view/'.$seeker_id);
		}
		
		else
			header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName());
	}

//-------------------------------------------------------------------------------------------------	
	
	public function add_alarmAction() 
	{
		$application_id	= routes::getInstance()->getCurrentTopLevelURLName();
		
		$seeker_id = $this->getValueOfURLParameterPair('add_alarm');

		if(is_numeric($seeker_id))
		{
			$calendar = new calendarEvent();
			$calendar->__set('staff_id', authorization::getUserID());
			$calendar->__set('action', $_POST['action']);
			$calendar->__set('set_for_date', date('Y-m-d H:i:s',strtotime($_POST['set_for_date'])));
			$calendar->__set('lead_id', $_POST['lead_id']);
			$calendar->insert();
			
			if ($application_id == "your_leads")
				header('Location: /'.$application_id.'/view/'.$seeker_id);
			else
				header('Location: /'.$application_id.'/profile/view/'.$seeker_id);
			
			$lead = new lead();
			$lead->__set('lead_id', $_POST['lead_id']);
			$update = array('urgent' => 1);
			$lead->updateGeneric($update);
			
			if (routes::getInstance()->getCurrentTopLevelURLName() == 'your_leads')
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/view/'.$seeker_id);
			else
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/profile/view/'.$seeker_id);
		}
		
		else
			header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName());
	}
	
	//--------------------------------------------------------------------------------------------
	
	public function delete_alarmAction()
	{
		$application_id	= routes::getInstance()->getCurrentTopLevelURLName();
		$seeker_id = $this->getValueOfURLParameterPair('delete_alarm');

		if(is_numeric($seeker_id))
		{
			$calendar = new calendarEvent();
	
			$calendar->__set('calendar_alert_id', $_GET['calendar_alert_id']);
			$calendar->delete();
			
			$lead = new lead();
			$lead->__set('lead_id', $seeker_id);
			$update = array('urgent' => 0);
			$lead->updateGeneric($update);
			
			if (routes::getInstance()->getCurrentTopLevelURLName() == 'your_leads')
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/view/'.$seeker_id);
			else
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/profile/view/'.$seeker_id);
		}
		
		else
			header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName());
	}

//-------------------------------------------------------------------------------------------------	
	
	public function add_conversationAction()
	{
		require_once 'Project/Code/System/Conversations/lead_conversation.php';
		
		$seeker_id = $this->getValueOfURLParameterPair('add_conversation');
		
		if(is_numeric($seeker_id))
		{
			$lead_conversation = new lead_conversation();
			
			$lead_conversation->__set('staff_id', authorization::getUserID());
			$lead_conversation->__set('lead_id', $seeker_id);
			$lead_conversation->__set('conversation', $_POST['conversation']);
			$lead_conversation->insert();
			
			if (routes::getInstance()->getCurrentTopLevelURLName() == 'your_leads')
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/view/'.$seeker_id);
			else
				header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName().'/profile/view/'.$seeker_id);
		}
		
		else
			header('Location: /'.routes::getInstance()->getCurrentTopLevelURLName());
	}
	
	public function testAction()
	{
		/* require_once 'Project/Code/2_Enterprise/Applications/Seekers_Manager/Modules/profile/potential_hcpModule.php';
		
		$array = array('info_sent', 'info_sent', 'contacted');
		
		echo potential_hcpModule::statusAnalyzer($array); */
		$sql = "SELECT * FROM hello_world";
		$sql = substr_replace(ltrim($sql), "COUNT(", 7, 0);
		$sql = substr_replace($sql, ")", stripos($sql, "FROM")-1, 0);
		
		echo $sql;
		
	}
	
	
}