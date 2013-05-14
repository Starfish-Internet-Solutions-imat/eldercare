<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';

class searchAjaxController extends applicationsSuperController
{
	public function indexAction()
	{
		
	}
	
	public function zipcodeAction()
	{
		$city = "";
		$state = "";
		
	}
	
	public function queryAction()
	{
		$content = "";
		$x = 0;
		
		if (isset($_REQUEST['query']))
		{
			
			require_once 'Project/Code/System/ZipCodes/zipcodes.php';
			
			$query = $_REQUEST['query'];
			
			$zipcodes = new zipcodes();
			
			$content = '<ul id="zip_list">';
			
			foreach($zipcodes->selectLike($query) as $zip)
			{
				$content .= '<li id="'.$zip['id'].'" class="'.$zip['zipcode'].'"> '.$zip['zipcode'].' - '.$zip['city'].', '.$zip['state'].'</li>';
				$x++;
			}
			$content .= '</ul>';
		}
		$content .= "<input type='hidden' name='max_li' value='{$x}' />  ";
		$content .= "<input type='hidden' name='test'/>  ";
		
		
		jQuery('#suggestion_list')->html($content);
		jQuery::getResponse();
		
		
	}
	
}