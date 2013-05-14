<?php 
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'leads_managerBlockView.php';

require_once 'Project/Code/System/Leads/lead.php';
require_once 'Project/Code/System/Leads/leads.php';
require_once 'Project/Code/System/Seeker/seeker.php';

class leads_managerBlockController extends applicationsSuperController
{

//-------------------------------------------------------------------------------------------------	
	
	public function getLeadsNavigation()
	{
		$view = new leads_managerBlockView();
		$leads = new leads();
		$leads->selectWithSeekerViaStaff();
		$view->_set('array_of_leads',$leads->__get('array_of_leads'));
		$view->displayApplicationLayout();
		
	}
}
?>