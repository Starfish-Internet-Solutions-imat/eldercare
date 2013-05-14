<?phprequire_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';require_once('seekerModel.php');require_once('seekerView.php');require_once 'Project/Code/1_Website/Pages/primary/seeker/Modules/seekerSession.php';require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';require_once 'Project/Code/System/HealthCare_Provider/healthcare_providers.php';require_once 'Project/Code/System/House_Type/house_types.php';require_once 'Project/Code/System/Seeker/seeker.php';require_once 'Project/Code/1_Website/Applications/Search/Modules/handler.php';require_once 'Project/Code/System/Leads/leads_potential_hcp/potential_hcp.php';require_once 'Project/Code/1_Website/Applications/User_Account/Modules/send_hubModule.php';require_once 'Project/Code/System/Contacts/contact.php';
class seekerController extends applicationsSuperController{		public function indexAction()	{				require_once FILE_ACCESS_CORE_CODE.'/Framework/FrontController/Router/routes.php';		require_once 'Project/Code/System/Leads/lead.php';		if(isset($_POST['submit']))		{//header("location: ".$_SERVER['REQUEST_URI']);						$parsed_url = parse_url($_SERVER['REQUEST_URI']);			$flag = 0;			$query_string = '';			//===================================validation================================			foreach($_POST as $index=>$post)			{				$query_string .= $index.'='.$post.'&';				if ($post == "")				{					$flag = 1;				}			}			if ($flag)			{				unset($_POST);				//$query_string = rtrim($query_string, '&');				header("location: ".$parsed_url['path']."?".$query_string.$parsed_url['query']);			}			//============================================================================						$seeker = new seekerObject();			$seeker->__set('name', $_POST['name']);			$seeker->__set('email', $_POST['email']);			$seeker->__set('telephone', $_POST['telephone']);			$seeker->__set('relationship_to_patient', $_POST['relationship_to_patient']);			$seeker->__set('zipcode_id', $_POST['zipcode_id']);			$seeker->__set('house_type_id', $_POST['housing_type']);			$seekerModel = new seeker();			$seekerModel->__set('name', $seeker->__get('name'));			$seekerModel->__set('email', $seeker->__get('email'));			$seekerModel->__set('telephone', $seeker->__get('telephone'));			$seekerModel->__set('relationship_to_patient', $seeker->__get('relationship_to_patient'));			$seekerModel->__set('zipcode', $seeker->__get('zipcode_id'));			$seekerModel->__set('house_type', $seeker->__get('house_type_id'));			$seeker_id = $seekerModel->insert();			$seeker->__set('seeker_id', $seeker_id);						$lead = new lead();			seekerSession::saveSeekerSession($seeker);			$lead->insert($seeker_id);						//sendhub			$contact = new contact();			$contact_number =  $_POST['telephone'];			$contact->__set('contact_number', $contact_number);			$sendHubContactID = $contact->selectSendHubContactID();				if(!$sendHubContactID)			{				$sendhub = new sendhub();				$contact_id = $sendhub->addSmsContacts($seeker->__get('name'), $seeker->__get('telephone'));			}			else			{				$contact_id = $sendHubContactID;			}								$contact->__set('user_id', $seeker_id);			$contact->__set('client_type', 'seeker');			$contact->__set('sendhub_contact_id', $contact_id);			$contact->insert();						//------------			if (isset($_GET['hcp']) && isset($_GET['action']))			{				$provider_id = $_GET['hcp'];				$action = $_GET['action'];				$hcp_model = new healthcare_provider();				$hcp_model->__set('hcp_id', $provider_id);				$hcp_model->select();				$provider_name = $hcp_model->__get('name');							if ($action == 'add-to-list')					$seeker->addToList($provider_id, $provider_id);				header("location: /health-care-provider/".handler::link_builder($provider_name, $provider_id));			}			elseif (isset($_GET['zid']) && isset($_GET['zipcode']))			{				require_once 'Project/Code/1_Website/Applications/Search/Modules/handler.php';				$uri_array = handler::urldecode_to_array();								header("location: /health-care-search/".str_replace(' ', '-', $uri_array['housing_type'])."/".$uri_array['zipcode']."/");			}			else			{				require_once 'Project/Code/1_Website/Applications/Search/Modules/handler.php';				$provider_id = $_GET['hcp'];				$hcp_model = new healthcare_provider();				$hcp_model->__set('hcp_id', $provider_id);				$hcp_model->select();				$provider_name = $hcp_model->__get('name');				$uri_array = handler::urldecode_to_array();				//var_dump($_GET);die;				header("location: /health-care-provider/".handler::link_builder($provider_name, $provider_id).'/'.strtotime('now'));			}		}		else		{			$pageURL = ltrim(rtrim($_SERVER["REQUEST_URI"], '/'),'/');					if (count(explode('/', $pageURL)) >= 2)			{				$pageURL_array = explode('/', $pageURL);				$action = strtolower($pageURL_array[1]);							if (strpos($action, 'get-information') !== false)				{					if(isset($_GET['hcp']))						$this->getInfo($_GET['hcp']);					else 						$this->getInfo('x'); //for checking if is_numeric				}				else					header ('location: /');			}			else				header ('location: /');		}	}		public function getInfo($health_care_provider_id)	{		require_once 'Project/Code/System/HealthCare_Provider/healthcare_providers.php';		require_once 'Project/Code/System/HealthCare_Provider/image/image.php';				$view = new seekerView();				if (is_numeric($health_care_provider_id))		{			$hcp_model = new healthcare_provider();			$hcp_model->__set('hcp_id', $health_care_provider_id);			$health_care_provider = $hcp_model->select();		}		else			$health_care_provider = null;				$view->_set('hcp_info', $health_care_provider);		$view->showInfoForm();	}		public function add_hcp_to_listAction() //adds a Health Care Provider to the user's list	{		if (seekerSession::ifSeekerSessionExists())			{				if(isset($_POST['submit']))				{					require_once 'Project/Code/1_Website/Applications/Search/Modules/handler.php';										$provider_id = $_POST['home_id'];					$provider_name = $_POST['name'];										seekerSession::getSeekerSession()->addToList($provider_id, $provider_id);					header("location: ".seekerObject::instance()->getUri('last_uri'));				}			}		else			{				if(isset($_POST['submit']))					$provider_id = $_POST['home_id'];				header("location:/seeker/get-information?hcp=".$provider_id."&action=add-to-list");			}	}			public function request_hcp_infoAction() //adds the lead information to the database	{		if (seekerSession::ifSeekerSessionExists())		{						if(isset($_POST['submit']))			{				$provider_id = $_POST['home_id'];							$seekerSession = seekerSession::getSeekerSession();				$healthcare_providers = $seekerSession->__get('array_of_homes');							$healthcare_provider = new healthcare_provider();				$healthcare_provider->__set('hcp_id', $provider_id);				$healthcare_provider->select();				require_once 'Project/Code/System/Seeker/seeker.php';								$healthcare_providers[$provider_id] = $healthcare_provider;								$potential_hcp = new potential_hcp();								$potential_hcp->__set('lead_id', $seekerSession->__get('seeker_id'));				$potential_hcp->__set('hcp_id', $provider_id);				$potential_hcp->insert();								//mail for the hcp								$email_info['seeker_name'] = $seekerSession->__get('name');				$email_info['seeker_email'] = $seekerSession->__get('email');				$email_info['seeker_telephone'] = $seekerSession->__get('telephone');				$email_info['seeker_relation'] = $seekerSession->__get('relationship_to_patient');				//print_r($email_info);die;								//mail for the user				$email_info['hcp_name'] = $healthcare_provider->__get('name');								include_once 'Project/Code/System/House_Type/house_types.php';				$house_types = new house_types();								$house_type_temp = '';				foreach($house_types->selectHcpHouseTypeArray($healthcare_provider->__get('hcp_id')) as $house_type)					$house_type_temp .= $house_type['house_type'].', ';				$house_type_temp = rtrim($house_type_temp, ', ');				$email_info['hcp_house_type'] = $house_type_temp;				$email_info['hcp_email'] = $healthcare_provider->__get('email');				$email_info['hcp_telephone'] = $healthcare_provider->__get('telephone');				$email_info['hcp_location'] = $healthcare_provider->__get('location');				$email_info['hcp_description'] = $healthcare_provider->__get('description');			//	echo str_replace(' ', '-', strtolower($healthcare_provider->__get('name')))."-".$provider_id; die;				$this->sendMail($email_info);				$this->sendMail($email_info, "hcp");								//sendhub---------------------------------------------------------				$hcp = new healthcare_providers();				$hcpInfo = $hcp->selectInfoForSms($provider_id);								$seeker = new seeker();				$seeker->__set('seeker_id', $seekerSession->__get('seeker_id'));				$seekerInfo = $seeker->select();							$contact = new contact();								$sendhubIDSeeker = $contact->selectSendHubIdByUserId('seeker', $seekerSession->__get('seeker_id'));				$sendhubIDHcp = $contact->selectSendHubIdByUserId('hcp', $provider_id);								//sms for seeker				$sendhub = new sendhub();				$message = 'Thank you for your interest in '.$hcpInfo['name'].'. Please call '.$hcpInfo['contact_person_name'].' at '.$hcpInfo['telephone'].'';				if($sendhubIDSeeker != 'Invalid Number')					$sendhub->sendSms($sendhubIDSeeker, $message);								//sms for hcp				$message = 'LFE would like to introduce you to  \n\n Contact '.$seekerInfo['name'].' \n Phone Number '.$seekerInfo['telephone'].'';				if($sendhubIDHcp != 'Invalid Number')					$sendhub->sendSms($sendhubIDHcp, $message);												//$seekerSession->__set('array_of_homes', $healthcare_providers);				seekerSession::saveSeekerSession($seekerSession);				header("Location: /health-care-provider/".str_replace(' ', '-', strtolower($healthcare_provider->__get('name')))."-".$provider_id.'/'.strtotime('now'));			}		}		else		{			if(isset($_POST['submit']))				$provider_id = $_POST['home_id'];						header("location: /seeker/get-information?hcp=$provider_id");					}				}		public function submit_listAction()	{		$seekerSession = seekerSession::getSeekerSession();				if(is_array($seekerSession->getList()))		{						$this->sendSmsToShortList();			require_once 'Project/Code/System/Leads/leads_potential_hcp/potential_hcp.php';						$house_types = new house_types();						$potential_hcp = new potential_hcp();									$potential_hcp->__set('lead_id', $seekerSession->__get('seeker_id'));			foreach ($seekerSession->getList() as $hcp_id)			{				$potential_hcp->__set('hcp_id', $hcp_id);				$potential_hcp->insert();			}						$this->fromListSendMailSetUp();						$this->outAction();		}		else		{				$seekerView = new seekerView();				$seekerView->displayThankYouPage();		}			}		private function sendSmsToShortList()	{		$seekerSession = seekerSession::getSeekerSession();		$contact = new contact();		$seeker_sendhub_id = $contact->selectSendHubIdByUserId('seeker', $seekerSession->__get('seeker_id'));				$seeker = new seeker();		$seeker->__set('seeker_id', $seekerSession->__get('seeker_id'));		$seekerInfo = $seeker->select();						$sendhub = new sendhub();						foreach($seekerSession->getList() as $hcp_id)		{			$hcp = new healthcare_providers();				$hcpInfo = $hcp->selectInfoForSms($hcp_id);						//message for seeker			$message = 'Thank you for your interest in '.$hcpInfo['name'].'. Please call '.$hcpInfo['contact_person_name'].' at '.$hcpInfo['telephone'].'';			if($seeker_sendhub_id != 'Invalid Number')				$sendhub->sendSms($seeker_sendhub_id, $message);								$hcp_sendhub_id = $contact->selectSendHubIdByUserId('hcp', $hcp_id);			//message for hcp			$message = 'LFE would like to introduce you to  \n\n Contact '.$seekerInfo['name'].' \n Phone Number '.$seekerInfo['telephone'].'';				if($hcp_sendhub_id != 'Invalid Number')				$sendhub->sendSms($hcp_sendhub_id, $message);						}	}				public function remove_hcpAction()	{		if(isset($_POST['submit']))		{			$provider_id = $_POST['home_id'];						$seeker = seekerSession::getSeekerSession();			$seeker->deleteHome($provider_id);						seekerSession::saveSeekerSession($seeker);		}				$healthcare_providers = new healthcare_providers();		$array_of_homes = $healthcare_providers->select();				$view = new view();		$view->_set('array_of_homes', $array_of_homes);		$view->renderAll('seeker_info_template');				header('Location: /zipcode_search/seeker_info');			}		public function resetListAction()	{		seekerSession::getSeekerSession()->deletelist();	}		public function removeFromListAction()	{		require_once 'Project/Code/1_Website/Applications/Search/Modules/handler.php';				$key = $this->getValueOfURLParameterPair('id'); 		seekerSession::getSeekerSession()->removeFromList($key);				header("location: ".seekerObject::instance()->getUri('last_uri'));	}		private function sendMail($email_info = array(), $to = 'seeker')	{		require_once 'Project/Code/1_Website/Applications/User_Account/Modules/mail/email.php';						$from_email = "raymond.baldonado@starfi.sh";		$from_name	= "Raymond";		$subject	= "New Lead from Looking for Eldercare";				$view = new seekerView();		$view->_set('email_to', $to);		$view->_set('email_info', $email_info);	//	var_dump($email_info); die;						if ($to == 'seeker')		{			$to_email	= $email_info['hcp_email'];			$to_name	= $email_info['hcp_name'];		}				else		{			$to_email	= $email_info['seeker_email'];			$to_name	= $email_info['seeker_name'];		}				$images = array(			'banner'	=> 'Project/Design/1_Website/Applications/Seeker/images/eldercare_banner.png',			'icon'		=> 'Project/Design/1_Website/Applications/Seeker/images/Eldercare-email-template-icon.png',		);		$body = $view->displayEmailTemplateSingleHcp();		email::send_email($to_email, $to_name, $from_email, $from_name, $subject, $body, '', '', $images);			}		/*	 * This sends emails when the user submits his list	 */		private function fromListSendMail($email_info = array(), $seeker_email_info = array(), $to = 'seeker')	{		require_once 'Project/Code/1_Website/Applications/User_Account/Modules/mail/email.php';				$from_email = "raymond.baldonado@starfi.sh";		$from_name	= "Raymond";		$subject	= "New Lead from Looking for Eldercare";						$images = array(			'banner'	=> 'Project/Design/1_Website/Applications/Seeker/images/Eldercare-email-template-banner.png',			'icon'		=> 'Project/Design/1_Website/Applications/Seeker/images/Eldercare-email-template-icon.png',			'logo'		=> 'Project/Design/1_Website/Applications/Seeker/images/Eldercare-email-template-logo.png'		);				if ($to == 'seeker')		{			$view = new seekerView();			$view->_set('email_to', $to);			$view->_set('email_info', $seeker_email_info);						$body = $view->displayEmailTemplate();						foreach ($email_info['hcp'] as $hcp)			{				email::send_email($hcp['email'], $hcp['name'], $from_email, $from_name, $subject, $body, '', '', $images);			}		}				else		{			$view = new seekerView();			$view->_set('email_to', $to);						include_once 'Project/Code/System/House_Type/house_types.php';			$house_types = new house_types();			//var_dump($house_types->selectHcpHouseTypeArray('17'));die;			$counter = 0; //Manual looping to get the house types of each hcp			$house_type_temp = '';			foreach ($email_info['hcp'] as $hcp_info)			{				foreach ($house_types->selectHcpHouseTypeArray($hcp_info['hcp_id']) as $house_type)				{					$house_type_temp .= $house_type['house_type'].', '; 				}				$house_type_temp = rtrim($house_type_temp, ', ');				$email_info['hcp'][$counter]['house_type'] = $house_type_temp;				$counter++;			}						$house_type_temp = '';						$view->_set('email_info', $email_info['hcp']);			$body = $view->displayEmailTemplate();						$to_email	= $seeker_email_info['seeker_email'];			$to_name	= $seeker_email_info['seeker_name'];						email::send_email($to_email, $to_name, $from_email, $from_name, $subject, $body, '', '', $images);		}			}		private function fromListSendMailSetUp($email_info = array())	{		$seekerSession = seekerSession::getSeekerSession();				$healthcare_providers = new healthcare_providers();		$array['hcp'] = array();		if (count(seekerSession::getSeekerSession()->getList()) != 0)		{			$hcp_email_info['hcp'] = $healthcare_providers->selectMany(seekerSession::getSeekerSession()->getList());		}					//var_dump($hcp_email_info['hcp']);die;		$seeker_email_info['seeker_name'] 		= $seekerSession->__get('name');		$seeker_email_info['seeker_email'] 		= $seekerSession->__get('email');		$seeker_email_info['seeker_telephone'] 	= $seekerSession->__get('telephone');		$seeker_email_info['seeker_relation'] 	= $seekerSession->__get('relationship_to_patient');				$this->fromListSendMail($hcp_email_info, $seeker_email_info);		$this->fromListSendMail($hcp_email_info, $seeker_email_info, 'hcp');	}			public function viewListAction()	{		seekerObject::instance()->getUri('last_uri');				require_once 'Project/Code/System/HealthCare_Provider/image/image.php';				$view = new view();				$healthcare_providers = new healthcare_providers();				if (count(seekerSession::getSeekerSession()->getList()) != 0)			$view->_set('array_of_homes',$healthcare_providers->selectMany(seekerSession::getSeekerSession()->getList()));				$view->viewList();			}		public function outAction()	{		seekerSession::getSeekerSession()->deletelist();		//seekerSession::unsetSeekerSession();				$seekerView = new seekerView();		$seekerView->displayThankYouPage();				//place another page here to thank the user for choosing LFE		}		public function mailTestAction()	{		require_once 'Project/Code/1_Website/Applications/User_Account/Modules/mail/email.php';		$view = new seekerView();				$seeker_email_info['seeker_name'] 		= 'Mon Baldonado';		$seeker_email_info['seeker_email'] 		= 'Mariele Mae Parreno';		$seeker_email_info['seeker_telephone'] 	= 'This is a sample mobile number';		$seeker_email_info['seeker_relation'] 	= 'Sample right?';				$view->_set('email_to', 'seeker');		$view->_set('email_info', $seeker_email_info);					$body = $view->displayEmailTemplate();		email::send_email('raymondbaldonado@ymail.com', 'Raymond', 'raymond.baldonado@starfi.sh', 'monb', 'This is a test', $body, '', '');	}		public function testAction()	{		$seekerView = new seekerView();		$seekerView->displayThankYouPage();	}		public function testAddSms()	{		$this->addSmsContact($id, $name, $mobileNumber);	}		public function xAction()	{		seekerSession::getSeekerSession()->deletelist();		seekerSession::unsetSeekerSession();	}
}?>