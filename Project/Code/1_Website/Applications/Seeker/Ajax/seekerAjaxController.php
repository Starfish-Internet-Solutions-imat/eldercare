<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';
require_once 'Project/Code/System/ZipCodes/zipcode.php';

class seekerAjaxController extends applicationsSuperController
{
	public function isZipcodeValidAction()
	{
		
		$zipcode = new zipcode();
		
		if (isset($_REQUEST['zipcode']))
		{
			$_REQUEST['zipcode'] = ltrim($_REQUEST['zipcode']);
			$zipcode_id = $zipcode->isZipcodeValid($_REQUEST['zipcode']);
			
			//var_dump($_REQUEST['zipcode']);
			//var_dump($zipcode_id);
			if ($zipcode_id)
			{
				jQuery('#invalidZipcode')->html('');
				jQuery('input[name=zipcode_id]')->val($zipcode_id['id']);
			}
			else
			jQuery('#invalidZipcode')->html('Invalid zipcode');
		}
		jQuery::getResponse();
	}
}