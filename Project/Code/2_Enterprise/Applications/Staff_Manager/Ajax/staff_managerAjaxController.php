<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/2_Enterprise/Applications/Staff_Manager/Blocks/staff_managerBlockController.php';	 	

class staff_managerAjaxController extends applicationsSuperController
{
	public function staff_detailsAction()
	{
		$blockController = new staff_managerBlockController();
	
		$content = $blockController->getStaffSideBar();
	
		jQuery('#applicationSideBar')->html($content);
		jQuery::getResponse();
	
	}
}