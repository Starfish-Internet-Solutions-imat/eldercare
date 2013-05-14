<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
require_once 'registrationView.php';
require_once 'Project/Code/System/Contacts/contact.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/Authorization/authorization.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/send_hubModule.php';
require_once 'Project/Code/System/HealthCare_Provider/hcp_contact_persons/hcp_contact_person.php';

class registrationController extends applicationsSuperController
{
	public function __construct()
	{
		parent::__construct();
		
		if(userSession::areWeLoggedIn() == TRUE)
			header('Location: /');
	}
	
	/**
	 * 
	 * 
	 * index action for registration, this is a two phased registration
	 * the first phase is first saved in session, the data are committed to
	 * the database after the final phase is submitted and validated.
	 * 
	 * 
	 */
	
	public function indexAction()
	{
		$view = new registrationView();
		$emptyFlag = 0; //Flag to indicate if there are empty fields in POST 
		
		// this is a japanese code
		if((isset($_POST['proceed_next_step'])))
		{
			try 
			{
				unset($_POST['test']);
				foreach($_POST as $post)
					if(empty($post))
						$emptyFlag = 1;		
				
				if ($emptyFlag === 0)
				{
					$healthcare_provider = new healthcare_provider();
					unset($_POST['max_li']);
						
					userSession::temporarySession();
					userSession::setTemporarySessionData('reg_first_part', $_POST);
					$view->displayRegistrationTemplate();
				}
				else
				{
					echo 'here';					
					$view->displayRegistrationFirstStepTemplate(TRUE);
				}
			}
			catch (Exception $e)
			{
				header('Location: /account/register');
			}
		}
		else if((isset($_POST['register'])))
		{
			try 
			{
				foreach($_POST as $post) //Checks if global post has empty indexes
					if(empty($post))
						$emptyFlag = 1;
				
				if ($_POST['password'] !== $_POST['confirm'])
				{
					$view->_set('password_error_message', 'Password does not match.');
					$view->displayRegistrationTemplate();
				}
				
				// if there's no empty post in form it will execute this
				if($emptyFlag == 0)
				{
					require_once 'Project/Code/System/House_Type/house_types.php';
					
					//setting the temporary session
					$userSession = userSession::getTemporarySessionData('reg_first_part');
					userSession::setTemporarySessionData('reg_second_part', $_POST);
					
					$healthcare_provider = new healthcare_provider();
					
					//adding contact to sendhub api
					$sendhub = new sendhub();
					$contact_id = $sendhub->addSmsContacts($_POST['name'], $_POST['telephone']);
					if($contact_id == 'Invalid Number')
						$telephone = $contact_id;
					else 
						$telephone = $_POST['telephone'];
					
					
					//inserting of contact persons information
					$hcp_contact_person = new hcp_contact_person();
					$hcp_contact_person->__set('contact_person_name', $_POST['contact_person_name']);
					$hcp_contact_person->__set('contact_person_position', $_POST['contact_person_name']);
					$hcp_contact_person->__set('email', $_POST['email']);
					$hcp_contact_person->__set('password', sha1(md5($_POST['password'])));
					$hcp_contact_person->__set('telephone', $telephone);
					$contact_person_id = $hcp_contact_person->insert();
								
					//inserting of hcp information
					$insert_array = array(
						'name'						=>	$_POST['name'],
						//'telephone'					=>	$telephone,
						'contact_person_id'			=>  $contact_person_id,
						'zipcode'					=>	$userSession['zipcode_id'],
						'location'					=>	$userSession['location'],
						'accommodation_type'		=>	$userSession['accommodation_type'],
						//'price_from'				=>	$userSession['price_from'],
						//'price_to'				=>	$userSession['price_to']
						'pricing'					=>	$userSession['pricing']
					);
					
					$healthcare_provider->insertGeneric($insert_array);
					
					$house_type_object = new house_types();
					
					$house_type_object->__set('hcp_id', $healthcare_provider->__get('hcp_id'));
					
					//inserting of housetypes
					foreach($userSession['house_type'] as $house_type)
					{
						$house_type_object->__set('house_type_id', $house_type);
						$house_type_object->insertHcpHouseType();
					}
					
					
					userSession::saveUserSession($healthcare_provider->__get('hcp_id'), $_POST['name']);
					mkdir("Data/Healthcare_Providers/Images/".$healthcare_provider->__get('hcp_id'),0777,true);
					mkdir("Data/Healthcare_Providers/Images/".$healthcare_provider->__get('hcp_id').'/thumb',0777,true);
				
					//this will send as sms to sendhub 	
					$message = 'Welcome and Thank You for joining Looking for Eldercare Community. Your profile will be reviewed and you will be informed via email once its approved for publishing.';
					$sendhub->sendSms($contact_id, $message);
					
					//this will insert the sendhub info to our database
					$contact = new contact();
					$contact->__set('user_id', authorization::getUserID());
					$contact->__set('client_type', 'hcp');
					$contact->__set('sendhub_contact_id', $contact_id);
					$contact->__set('contact_number', $_POST['telephone']);
					$contact->insert(); 

					//sender of email
					$this->sendMail();
					header('Location: /account/profile');
				}
				else
				{
					$view->displayRegistrationTemplate();
				}
			}
			catch(Exception $e)
			{
				header('Location: /account/register');
			}
		}
		else
		{
			$view->displayRegistrationFirstStepTemplate(FALSE);
		}
		
	}	
	
	public function xAction()
	{
		userSession::unsetTemporarySession();
	}

	
	private function sendMail()
	{
		require_once 'Project/Code/1_Website/Applications/User_Account/Modules/mail/email.php';
		$registrationView = new registrationView();
		$from_email = "raymond.baldonado@starfi.sh";
		$from_name	= "Raymond";
		$subject	= "Welcome to Looking For Eldercare.";
		$body 		= $registrationView->displayEmailTemplate();
		$to_email 	= $_POST['email'];
		$to_name 	= $_POST['contact_person_name'];
		
		$images = array(
					'banner'	=> 'Project/Design/1_Website/Applications/Seeker/images/eldercare_banner.png',
					'icon'		=> 'Project/Design/1_Website/Applications/Seeker/images/Eldercare-email-template-icon.png',
		);
		
		email::send_email($to_email, $to_name, $from_email, $from_name, $subject, $body, '', '', $images);
	}
	
}
