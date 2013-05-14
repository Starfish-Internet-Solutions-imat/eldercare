<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'seekers_managerBlockView.php';

require_once 'Project/Code/System/Seeker/seeker.php';
require_once 'Project/Code/System/ZipCodes/zipcodes.php';
require_once 'Project/Code/System/Accounts/staffAccounts/staffAccounts.php';	
	 	
class seekers_managerBlockController extends applicationsSuperController
{
	public function getSeekerSideBar()
	{
		
		if(isset($_REQUEST['seeker_id']))
			$seeker_id = $_REQUEST['seeker_id'];
		
		else
			$seeker_id = seeker::selectFirst(TRUE);
		
		$seeker = new seeker();
		$seeker->__set('seeker_id', $seeker_id);
		$seeker->selectWithSeeker();
		
		$staff = new staffAccounts();
		
		$view = new seekers_managerBlockView();
		$view->_set('seeker', $seeker);
		
		$view->_set('staff', $staff->select());
		
		return $view->displaySearchSideBar();
	}
	
//-------------------------------------------------------------------------------------------------	

	public function getStateSelectInput()
	{
		$options = array();
		
		foreach(zipcodes::selectAllStates() as $zipcode)
			$options[$zipcode['state']] = $zipcode['state'];
		
		$view = new seekers_managerBlockView();
		$content = $view->displaySelectInput($options, 'state');
		
		response::getInstance()->addContentToTree(array('STATE_SELECT'=>$content));
	}
	
//-------------------------------------------------------------------------------------------------	

	public function getZipCodeSelectInput()
	{
		$options = array();
		
		foreach(zipcodes::selectAllZipCodes() as $zipcode)
			$options[$zipcode['zipcode']] = $zipcode['zipcode'];
		
		$view = new seekers_managerBlockView();
		$content = $view->displaySelectInput($options, 'zipcode');
		
		response::getInstance()->addContentToTree(array('ZIPCODE_SELECT'=>$content));
	}		
}