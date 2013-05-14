<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'loginView.php';

require_once FILE_ACCESS_CORE_CODE.'/Objects/Authorization/authorization.php';
require_once 'Project/Code/System/Settings/settings.php';
require_once 'Project/Code/System/Accounts/staffAccounts/staffAccount.php';

class loginController extends applicationsSuperController
{
	public function indexAction()
	{
		$basePath  = request::getInstance()->getPathInfo(); //Zend function
		
		if (isset($_POST['login']))
		{
			$email = $_POST['email'];
			$password = sha1(md5($_POST['password']));
		
			$staff = new staffAccount();
			$staff->__set('email', $email);
			$staff->__set('password', $password);
			
			if($staff->selectLogin() == TRUE)
			{
				$staff->select();
				 
				authorization::saveUserSession($staff);
				
				header('Location: /');
			}
		
			else
			{
				$loginView = new loginView();
				$loginView->showLoginForm(TRUE);
			}
		}
		
		else
		{
			$loginView = new loginView();
			$loginView->showLoginForm();
		}
	}
}