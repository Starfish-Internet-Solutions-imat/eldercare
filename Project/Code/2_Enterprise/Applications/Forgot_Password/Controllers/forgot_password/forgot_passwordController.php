<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'forgot_passwordView.php';
require_once 'Project/Code/System/Accounts/staffAccounts/staffAccount.php';

class forgot_passwordController extends applicationsSuperController
{
	public function indexAction()
	{
		$view = new forgot_passwordView();
		$view->displayMainLayout();
	}
	
	//--------------------------------------------------------------------------------------------------------------
	
	public function requestPasswordAction()
	{
		$email = $_POST['email'];
		$emailInfo = staffAccount::selectByEmail($email);
		if($emailInfo)
		{
			$password = rand();
			
			$view = new forgot_passwordView();
			$view->password = $password;
			
			$hashedPassword = sha1(md5($password));
			
			$staffAccount = new staffAccount();
			$staffAccount->updatePasswordByEmail($email, $hashedPassword);
			
			$this->sendmail($emailInfo, $view);
		}		
		$view->displayPasswordChangedTemplate();	
			
	}
	
	private function sendMail($emailInfo, $view)
	{
		require_once 'Project/Code/1_Website/Applications/User_Account/Modules/mail/email.php';
	
		$from_email = "raymond.baldonado@starfi.sh";
		$from_name	= "Raymond";
		$subject	= "Thank you for choosing Eldercare.";
		$body 		= $view->getEmail();
		$to_email 	= $emailInfo['email'];
		$to_name 	= $emailInfo['name'];
		$images = array(
						'banner'	=> 'Project/Design/1_Website/Applications/Seeker/images/eldercare_banner.png',
						'icon'		=> 'Project/Design/1_Website/Applications/Seeker/images/Eldercare-email-template-icon.png',
		);
		email::send_email($to_email, $to_name, $from_email, $from_name, $subject, $body, '', '', $images);
	}
}