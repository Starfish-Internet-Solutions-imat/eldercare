<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'forgot_passwordView.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';

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
		$emailInfo = healthcare_provider::selectByEmail($_POST['email']);
		
		if($emailInfo)
		{
			$emailInfo['verification'] = $this->hashInfo($emailInfo);
			$this->sendMail($emailInfo);
		}
		
		$view = new forgot_passwordView();
		$view->displayInstruction();
	}

	//--------------------------------------------------------------------------------------------------------------
	
	public function resetPasswordAction()
	{
		$email	  = $_GET['email'];
		$userInfo = healthcare_provider::selectByEmail($email);
		
		$verification = $this->hashInfo($userInfo);
		
		if(isset($_POST['resetPassword']))
		{
			if($verification == $_GET['verification'])
			{
				$hcp = new healthcare_provider();
				$hcp->__set('email', $email);
				$update_array = array(
					'password' => sha1(md5($_POST['password']))
				);
				$hcp->updateByEmail($update_array);
				
				$view = new forgot_passwordView();
				$view->displayPasswordChangedTemplate();
			}
			else
			{
				$view = new forgot_passwordView();
				$view->displayInvalidLinkTemplate();
			}
		}
		else
		{
			$view = new forgot_passwordView();
			$view->displayResetPasswordTemplate();
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------
	
	private function sendMail($emailInfo)
	{
		require_once 'Project/Code/1_Website/Applications/User_Account/Modules/mail/email.php';
		
		$view = new forgot_passwordView();
		$view->verification = $emailInfo;
			
		$from_email = "raymond.baldonado@starfi.sh";
		$from_name	= "Raymond";
		$subject	= "Thank you for choosing ElderCare.";
		$body 		= $view->getEmail();
		$to_email 	= $emailInfo['email'];
		$to_name 	= $emailInfo['name'];
		$images = array(
					'banner'	=> 'Project/Design/1_Website/Applications/Seeker/images/eldercare_banner.png',
					'icon'		=> 'Project/Design/1_Website/Applications/Seeker/images/Eldercare-email-template-icon.png',
		);
		email::send_email($to_email, $to_name, $from_email, $from_name, $subject, $body, '', '', $images);
	}
	
	//--------------------------------------------------------------------------------------------------------------
	
	private function hashInfo($hashInfo)
	{
		$pass 		   = $hashInfo['password'];
		$id			   = sha1(md5($hashInfo['password']));
		$date_created  = sha1(md5($hashInfo['date_created']));
		$verification  = sha1(md5($id.'_'.$pass.'_'.$date_created)); 
		return $verification;
	}
}