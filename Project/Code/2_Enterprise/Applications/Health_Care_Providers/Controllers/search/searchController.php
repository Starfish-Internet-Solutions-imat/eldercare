<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'searchView.php';
require_once 'searchModel.php';

require_once 'Project/Code/System/Seeker/seeker.php';
require_once 'Project/Code/System/House_Type/house_types.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_providers.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/mail/email.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/send_hubModule.php';
require_once 'Project/Code/System/Contacts/contact.php';

class searchController extends applicationsSuperController
{
	public function indexAction()
	{
		if(isset($_GET['page']))
			$page = $_GET['page'] - 1;
		else
			$page = 0;
		
		$model = new searchModel();
		$model->applyPagination(15, $page * 15);
		$model->select();
	
		
		$view = new searchView();
		$view->displayCommon();
		$view->setCurrentPage($page+1);
		$view->setNumberOfPages($model->getNumberOfPages());
		$view->displayPagination($model->getNumberOfPages(), $view->getCurrentPage());
		$view->_set('hcps', $model->__get('array_of_results'));
		$view->displayApplicationLayout();
	}
	
//-------------------------------------------------------------------------------------------------	

	public function resultsAction()
	{
		if(isset($_GET['page']))
			$page = $_GET['page'] - 1;
		else
			$page = 0;
		
		$model = new searchModel();
		$model->applyPagination(15, $page * 15);
		
		if(isset($_POST['name']))
			$model->__set('name', $_POST['name']);
		
		if(isset($_POST['state']))
			$model->__set('state', $_POST['state']);
		
		if(isset($_POST['zipcode']))
			$model->__set('zipcode', $_POST['zipcode']);
		
		if(isset($_POST['price_from']))
			$model->__set('price_from', $_POST['price_from']);
		
		if(isset($_POST['price_to']))
			$model->__set('price_to', $_POST['price_to']);
		
		if(isset($_POST['house_type']))
			$model->__set('house_type', $_POST['house_type']);
		
		$model->select();
		
		$view = new searchView();
		$view->displayCommon();
		$view->setCurrentPage($page+1);
		$view->setNumberOfPages($model->getNumberOfPages());
		$view->displayPagination($view->getNumberOfPages(), $view->getCurrentPage());
		$view->_set('hcps', $model->__get('array_of_results'));
		$view->displayApplicationLayout();
	}
	
	public function recommendAction()
	{
		$model = new searchModel();
		
		if(isset($_POST['seeker_id']))
			$model->__set('seeker_id', $_POST['seeker_id']);
		
		if(isset($_POST['hcp_id']))
			$model->__set('hcp_id', $_POST['hcp_id']);
		
		$seeker = new seeker();
		$seeker->__set('seeker_id', $_POST['seeker_id']);
		$seeker->select();
		//mail for the hcp
		$email_info['seeker_name'] = $seeker->__get('name');
		$email_info['seeker_email'] = $seeker->__get('email');
		$email_info['seeker_telephone'] = $seeker->__get('telephone');
		$email_info['seeker_relation'] = $seeker->__get('relationship_to_patient');
		
	
		$healthcare_provider = new healthcare_provider();
		$healthcare_provider->__set('hcp_id', $_POST['hcp_id']);
		$healthcare_provider->select();
		
		$houseType = new house_types();
		$arrayOfhouseTypes = $houseType->selectHCPHouseTypeName($_POST['hcp_id']);
		$house_type = '';
		for($i=0; $i<count($arrayOfhouseTypes); $i++)
		{
			if(count($arrayOfhouseTypes)-1 == $i)
				$house_type .= $arrayOfhouseTypes[$i]. '';
			else
				$house_type .= $arrayOfhouseTypes[$i]. ', ';
		}
	
		//mail for the user
		$email_info['hcp_name'] = $healthcare_provider->__get('name');
		$email_info['hcp_house_type'] = $house_type;
		$email_info['hcp_email'] = $healthcare_provider->__get('email');
		$email_info['hcp_telephone'] = $healthcare_provider->__get('telephone');
		$email_info['hcp_location'] = $healthcare_provider->__get('location');
		$email_info['hcp_description'] = $healthcare_provider->__get('description');
		
		$this->sendMail($email_info);
		$this->sendMail($email_info, "hcp");
		
		$model->insertOnLeadsPotentialHCP();
		
		$contact = new contact();
		$sendhub = new sendhub();
		$message = 'Thank you for using Looking for Eldercare to find the best care for your loved one. \n  We would like to introduce you to  \n\n Facility Name  '.strtoupper($healthcare_provider->__get('name')).' \n  Contact Person '.strtoupper($healthcare_provider->__get('contact_person_name')).' \n  Contact Number '.strtoupper($healthcare_provider->__get('telephone')).' \n\n Wishing you the best success, \n Looking for Eldercare Team ';
		$sendhub->sendSms($contact->selectSendHubIdByUserId('seeker', $_POST['seeker_id']) , $message);
		
		$message = 'Thank you for using Looking for Eldercare to find the best care for your loved one. \n  We would like to introduce you to  \n\n Seeker Name  '.strtoupper($seeker->__get('name')).' \n  Contact Number '.strtoupper($seeker->__get('telephone')).' \n\n Wishing you the best success, \n Looking for Eldercare Team ';
		$sendhub->sendSms($contact->selectSendHubIdByUserId('hcp', $_POST['hcp_id']) , $message);
		
		header('Location: /hcps');
	}	
	
	public function recommend_seekerAction()
	{
		if(isset($_GET['page']))
			$page = $_GET['page'] - 1;
		else
			$page = 0;
		
		$model = new searchModel();
		$model->applyPagination(15, $page * 15);
		$model->select();
		$view = new searchView();
		$view->displayCommon();
		$profile = $this->getValueOfURLParameterPair('recommend_seeker');
		$seeker = new seeker();
		$seeker->__set('seeker_id', $profile);
		$seeker->select();
		

		
		$view->setCurrentPage($page+1);
		$view->setNumberOfPages($model->getNumberOfPages());
		$view->displayPagination($view->getNumberOfPages(), $view->getCurrentPage());
		
		$view->_set('seeker_id', $seeker->__get('seeker_id'));
		$view->_set('seeker_name', $seeker->__get('name'));
		$view->_set('hcps', $model->__get('array_of_results'));
		$view->displayApplicationLayout();
	}
	
	private function sendMail($email_info = array(), $to = 'seeker')
	{
		$from_email = "raymond.baldonado@starfi.sh";
		$from_name	= "Raymond";
		$subject	= "Seeking for Health Care Lead";
		$view = new searchView();
		$view->_set('email_to', $to);
		$view->_set('email_info', $email_info);
		
		$body = $view->displayEmailTemplate();
		
		if ($to == 'seeker')
		{
			$to_email	= $email_info['hcp_email'];
			$to_name	= $email_info['hcp_name'];
		}
		
		else
		{
			$to_email	= $email_info['seeker_email'];
			$to_name	= $email_info['seeker_name'];
		}
		
		$images = array(
			'banner'	=> 'Project/Design/1_Website/Applications/Seeker/images/eldercare_banner.png',
			'icon'		=> 'Project/Design/1_Website/Applications/Seeker/images/Eldercare-email-template-icon.png',
		);
		email::send_email($to_email, $to_name, $from_email, $from_name, $subject, $body, '', '', $images);
	}
	
}