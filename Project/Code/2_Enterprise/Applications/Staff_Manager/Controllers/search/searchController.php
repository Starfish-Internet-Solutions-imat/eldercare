<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'searchView.php';
require_once 'Project/Code/System/Accounts/staffAccounts/staffAccounts.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/Authorization/authorization.php';

class searchController extends applicationsSuperController
{
	public function indexAction()
	{
		
		if(isset($_GET['page']))
			$page = $_GET['page'] - 1;
		else
			$page = 0;
		
		$staff_id = $this->getValueOfURLParameterPair('id');
		
		$view = new searchView();
		
		$staffAccount = new staffAccount();
		
		$staffAccounts = new staffAccounts();
		$staffAccounts->applyPagination(10, $page * 10);
		
		if($staff_id != NULL)
		{
			$staffAccount->__set('staff_id', $staff_id);
			$view->_set('staff_detail', $staffAccount->select());
		}
		
		$view->_set('staff_information', $staffAccounts->selectOrderbyUserID(authorization::getUserID()));
		$view->setCurrentPage($page+1);
		$view->setNumberOfPages($staffAccounts->getNumberOfPages());
		$view->displayPagination($view->getNumberOfPages(), $view->getCurrentPage());
		$view->displayApplicationLayout();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function addAction()
	{
		if (isset($_POST['add_staff']))
		{
			$staffAccount = new staffAccount();
			if (strcmp($_POST['password'], $_POST['confirm_password']) == 0)
			{
				
				$staffAccount = new staffAccount();
				
				$staffAccount->__set('name', $_POST['name']);
				$staffAccount->__set('password', sha1(md5($_POST['password'])));
				$staffAccount->__set('role', $_POST['role']);
				$staffAccount->__set('email', $_POST['email']);
				$staffAccount->__set('telephone', $_POST['telephone']);
				
				$staffAccount->insert();
				
			}
			
			$emailInfo = array(
				'email'	   => $_POST['email'],
				'name'	   => $_POST['name'],
				'password' => $_POST['password']
			);
		
			$this->sendMail($emailInfo);
			header('location:/staff');
			
		}
		else
		{
			$view = new searchView();
			$view->displayAddStaffLayout();
		}
		
	}

//-------------------------------------------------------------------------------------------------	
	
	public function updateAction()
	{
		$staffAccount = new staffAccount();
		
		$staffAccount->__set('staff_id', $_POST['staff_id']);
		$staffAccount->__set('name', $_POST['name']);
		$staffAccount->__set('role', $_POST['role']);
		$staffAccount->__set('email', $_POST['email']);
		$staffAccount->__set('telephone', $_POST['telephone']);
		
		if($_POST['password'] != "")
		{
			$staffAccount->__set('password', sha1(md5($_POST['password'])));
			$staffAccount->updateWithPassword();
		}
		else
			$staffAccount->update();

		header('location: /staff');
	}

//-------------------------------------------------------------------------------------------------	
	
	public function deleteAction()
	{
		$staffAccount = new staffAccount();
		
		$staffAccount->__set('staff_id', $_POST['staff_id']);
		$staffAccount->delete();
		
		header('location: /staff');
	}
	
//-------------------------------------------------------------------------------------------------

	public function display_detailAction()
	{
		$staff_id = $this->getValueOfURLParameterPair('id');
		if($staff_id != NULL)
		{
			
			$staffAccount = new staffAccount();
			
			$staffAccount->__set('staff_id', $staff_id);
			$view = new searchView();
			$view->_set('staff_detail', $staffAccount->select());
			$view->displayApplicationLayout();
		}
	}
	
	//-------------------------------------------------------------------------------------------------
	
	private function sendMail($emailInfo)
	{
		
		$view = new searchView();
		require_once 'Project/Code/1_Website/Applications/User_Account/Modules/mail/email.php';
		$from_email = "raymond.baldonado@starfi.sh";
		$from_name	= "Raymond";
		$subject	= "Thank you for choosing Eldercare.";
		$body 		= $view->displayEmailTemplate();
		$to_email 	= $emailInfo['email'];
		$to_name 	= $emailInfo['name'];
		$images = array(
		'banner'	=> 'Project/Design/1_Website/Applications/Seeker/images/eldercare_banner.png',
							'icon'		=> 'Project/Design/1_Website/Applications/Seeker/images/Eldercare-email-template-icon.png',
				);
		email::send_email($to_email, $to_name, $from_email, $from_name, $subject, $body, '', '', $images);
	}
}