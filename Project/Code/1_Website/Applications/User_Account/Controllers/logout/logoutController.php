<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';

class logoutController extends applicationsSuperController
{
	public function indexAction()
	{
		userSession::unsetUserSession();
		header('Location: /');
	}
}