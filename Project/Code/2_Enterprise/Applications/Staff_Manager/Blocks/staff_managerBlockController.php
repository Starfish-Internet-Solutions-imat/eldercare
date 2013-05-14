<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/2_Enterprise/Applications/Staff_Manager/Blocks/staff_managerBlockView.php';
require_once 'Project/Code/System/Accounts/staffAccounts/staffAccount.php';

class staff_managerBlockController extends applicationsSuperController
{
	public function getStaffSideBar()
	{

		if(isset($_REQUEST['staff_id']))
			$staff_id = $_REQUEST['staff_id'];

		if($staff_id != NULL)
		{
				
			$staffAccount = new staffAccount();
				
			$staffAccount->__set('staff_id', $staff_id);
			$view = new staff_managerBlockView();
			$view->_set('staff_detail', $staffAccount->select());
			return $view->displaySearchSideBar();
		}
	}
}
?>
