<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'contact_historyView.php';

require_once 'Project/Code/System/Accounts/staffAccounts/staffAccount.php';

require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
require_once 'Project/Code/System/Conversations/hcp_conversations.php';
require_once 'Project/Code/System/Leads/leads_potential_hcp/potential_hcps.php';

class contact_historyController extends applicationsSuperController
{
	public function indexAction()
	{
		$hcp_id = $this->getValueOfURLParameterPair('contact_history');
		
		$hcp = new healthcare_provider();
		$hcp->__set('hcp_id', $hcp_id);
		$hcp->select(FALSE);
			
		//get potential hcps
		$potential_hcps = new potential_hcps();
		$potential_hcps->applyPagination(2, 0);
		$potential_hcps->selectAllPerHCP($hcp_id, true);
		
		$array_of_potential_leads = $potential_hcps->__get('array_of_potential_hcps');
			
		//get hcp conversations
		$conversations = new hcp_conversations();
		$array_of_conversations = $conversations->selectByStaffIDAndHCPID(authorization::getUserID(), $hcp_id);
		
		//$array_of_conversations = $conversations->__get('array_of_conversations');
		
		$view = new contact_historyView();
		$view->_set('hcp', $hcp);
		$view->_set('potential_leads', $array_of_potential_leads);
		$view->_set('conversations', $array_of_conversations);
		$view->_set('pages_potential_leads', $potential_hcps->getNumberOfPages());
		$view->displayApplicationLayout();
	}
	
	public function deleteAction()
	{
		
	}
}