<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/1_Website/Applications/User_Account/Modules/userSession.php';
require_once 'Project/Code/1_Website/Pages/primary/seeker/Modules/seekerSession.php';


class resultsModule
{
	
	public static function isShortListed($hcp_id)
	{
		$seekerSession = new seekerObject();
		return $seekerSession->isListed($hcp_id) ? TRUE : FALSE;
	}
	
	public static function pricingMarking($pricing)
	{
		return $pricing == 'expensive' ? '<span class="sprite price_expensive"></span>' : ($pricing == 'moderate' ? '<span class="sprite price_moderate"></span>' : ( $pricing == 'conservative' ? '<span class="sprite price_conservative"></span>' : ''));
		
	}
	
	
	
}