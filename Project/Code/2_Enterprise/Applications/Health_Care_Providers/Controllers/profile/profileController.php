<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'profileView.php';

require_once 'Project/Code/System/House_Type/house_types.php';
require_once 'Project/Code/System/Placements/hcp_placements.php';
require_once 'Project/Code/System/HealthCare_Provider/image/image.php';
require_once 'Project/Code/System/HealthCare_Provider/ammenity/amenity.php';
require_once 'Project/Code/System/HealthCare_Provider/ammenity/amenities.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
require_once 'Project/Code/System/Calendar/calendarEvent.php';
require_once 'Project/Code/System/Placements/placements.php';

class profileController extends applicationsSuperController
{
	public function indexAction()
	{
		$hcp_id = $this->getValueOfURLParameterPair('view');
		if(is_numeric($hcp_id))
		{
			$hcp = new healthcare_provider();
			$hcp->__set('hcp_id', $hcp_id);
			$hcp->select(FALSE);
			
			$placements = new placements();
			$array_of_placements = $placements->selectPlacementsByHcp($hcp_id);

			$amenities_category = new hcp_amenities();
			$array_of_amenities_categories = $amenities_category->selectAllamenitiesCategories();
			
			$amenities = new hcp_amenity();
			$array_of_amenities = $amenities->selectHcpAmenities($hcp_id);
			
			$image = new hcp_image();
			$image->__set('hcp_id', $hcp_id);
			$image_filename = $image->selectFileOfNameHCPImage();
			
			$view = new profileView();
			$view->_set('image_filename', $image_filename);
			$view->_set('hcp', $hcp);
			$view->_set('amenities_categories', $array_of_amenities_categories);
			$view->_set('amenities', $array_of_amenities);
			$view->_set('placements', $array_of_placements);
			$view->displayApplicationLayout();
			$view->displayMainLayout();
		}
		
		else
			header('Location: /hcps');
	}

//-------------------------------------------------------------------------------------------------	
	
	public function editAction()
	{
		$hcp_id = $this->getValueOfURLParameterPair('edit');
		
		if(is_numeric($hcp_id))
		{
			$hcp = new healthcare_provider();
			$hcp->__set('hcp_id', $hcp_id);
			$hcp->select(FALSE);
			
			$hcp->__set('name', $_POST['name']);
			$hcp->__set('email', $_POST['email']);
			$hcp->update();
		
			header('Location: /hcps/profile/view/'.$hcp_id);
		}
		
		else
			header('Location: /hcps');
	}

//-------------------------------------------------------------------------------------------------	
	
	public function deleteAction()
	{
		$hcp_id = $this->getValueOfURLParameterPair('delete');
		
		if(is_numeric($hcp_id))
		{
			healthcare_provider::delete($hcp_id);
		}
		
		header('Location: /hcps');
	}

//-------------------------------------------------------------------------------------------------	
	
	public function suspendAction()
	{
		$hcp_id = $this->getValueOfURLParameterPair('suspend');
		
		if(is_numeric($hcp_id))
		{
			$hcpdata = new healthcare_provider();
			$hcpdata->__set('hcp_id', $hcp_id);
			$hcpdata->select(false);
			$update;
			if($hcpdata->__get('suspended')==0)
			{
				$hcpdata->__set('suspended', '1');
				$hcpdata->__set('published', '0');
				$update = array('suspended' => '1', 'published' => '0');
			}
			else if($hcpdata->__get('suspended')==1)
			{
				$hcpdata->__set('suspended', '0');
				$hcpdata->__set('published', '1');
				$update = array('suspended' => '0', 'published' => '1');
			}
			$hcpdata->updateGeneric($update);
			header('Location: /hcps/profile/view/'.$hcp_id);
		}
		
		else
			header('Location: /hcps');
	}

//-------------------------------------------------------------------------------------------------	
	
	public function unpublishAction()
	{
		$hcp_id = $this->getValueOfURLParameterPair('unpublish');
		
		if(is_numeric($hcp_id))
		{
			$hcpdata = new healthcare_provider();
			$hcpdata->__set('hcp_id', $hcp_id);
			$hcpdata->select(false);
			$update;
			if($hcpdata->__get('published')==0)
			{
				$hcpdata->__set('suspended', '0');
				$hcpdata->__set('published', '1');
				$update = array('suspended' => '0', 'published' => '1');
			}
			else if($hcpdata->__get('published')==1)
			{
				$hcpdata->__set('suspended', '1');
				$hcpdata->__set('published', '0');
				$update = array('suspended' => '0', 'published' => '0');
			}
			$hcpdata->updateGeneric($update);
			header('Location: /hcps/profile/view/'.$hcp_id);
		}
		
		else
			header('Location: /hcps');
	}

//-------------------------------------------------------------------------------------------------	
	
	public function contact_historyAction()
	{
		require_once 'contact_history/contact_historyController.php';
		
		$controller = new contact_historyController();
		$controller->indexAction();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function add_alarmAction()
	{
		$hcp_id = $this->getValueOfURLParameterPair('add_alarm');
		if(is_numeric($hcp_id))
		{
			$calendar = new calendarEvent();
			
			$calendar->__set('staff_id', authorization::getUserID());
			$calendar->__set('action', $_POST['action']);
			$calendar->__set('set_for_date', date('Y-m-d H:i:s',strtotime($_POST['set_for_date'])));
			$calendar->__set('hcp_id', $_POST['hcp_id']);
			$calendar->insert();
			
			header('Location: /hcps/profile/contact_history/'.$hcp_id);
		}
		
		else
			header('Location: /hcps');
	}
	
	//-----------------------------------------------------------------------------------------
	
	public function delete_alarmAction()
	{
		$hcp_id = $this->getValueOfURLParameterPair('delete_alarm');
		$calendar_alert_id = $_GET['calendar_alert_id'];
		
		if(is_numeric($hcp_id))
		{
			$calendar = new calendarEvent();
				
			$calendar->__set('calendar_alert_id', $_GET['calendar_alert_id']);
			$calendar->delete();
				
			header('Location: /hcps/profile/contact_history/'.$hcp_id);
		}
	}

//-------------------------------------------------------------------------------------------------	
	
	public function add_conversationAction()
	{
		require_once 'Project/Code/System/Conversations/hcp_conversation.php';
		
		$hcp_id = $this->getValueOfURLParameterPair('add_conversation');
		
		if(is_numeric($hcp_id))
		{
			$hcp_conversation = new hcp_conversation();
			
			$hcp_conversation->__set('staff_id', authorization::getUserID());
			$hcp_conversation->__set('hcp_id', $hcp_id);
			$hcp_conversation->__set('conversation', $_POST['conversation']);
			$hcp_conversation->insert();
			
			header('Location: /hcps/profile/contact_history/'.$hcp_id);
		}
		
		else
			header('Location: /hcps');
	}
}