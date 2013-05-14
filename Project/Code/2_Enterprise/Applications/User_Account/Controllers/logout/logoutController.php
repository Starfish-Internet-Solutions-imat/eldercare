<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'logoutView.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/Authorization/authorization.php';
/*** load the userAccount class **/
require_once 'Project/Code/System/Accounts/userAccounts/userAccount.php';


class logoutController extends applicationsSuperController
{
	public function indexAction()
	{
		authorization::unsetUserSession();
		header('Location: /');
	}
	
	//AJAX CONFIRMATION etc
	
}