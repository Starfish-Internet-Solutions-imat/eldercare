<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
require_once 'loginView.php';


class loginController extends applicationsSuperController
{
	public function __construct()
	{
		parent::__construct();
		
		if(userSession::areWeLoggedIn() == TRUE)
			header('Location: /account/profile');
	}
	
	public function indexAction()
	{
		$basePath 		= request::getInstance()->getPathInfo(); //Zend function]
		$login_error	= FALSE;
		
		if (isset($_POST['login']))
		{
			$email		= $_POST['email'];
			$password	= sha1(md5($_POST['password']));
			
			$user_info = healthcare_provider::selectLogin($email, $password);

			if(is_numeric($user_info['hcp_id']))
			{
				userSession::saveUserSession($user_info['hcp_id'], $user_info['name'], $user_info['contact_person_id']);
				if (userSession::isTemporarySessionSet())
				{
					userSession::unsetTemporarySession();
					header('Location: /account/profile/');
				}
				else
					header('Location: /account/profile/'.strtotime("now").'');
			}
			
			else
				$login_error = TRUE;
			
		}
		$loginView = new loginView();
		$loginView->showLoginForm($login_error);
		
		
	}
	
}