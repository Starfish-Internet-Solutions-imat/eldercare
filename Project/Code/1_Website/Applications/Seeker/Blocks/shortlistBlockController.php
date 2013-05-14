<?php 
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'shortlistBlockView.php';
require_once 'Project/Code/1_Website/Pages/primary/seeker/Modules/seekerSession.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_provider.php';
require_once 'Project/Code/System/HealthCare_Provider/healthcare_providers.php';
require_once 'Project/Code/1_Website/Applications/Search/Modules/handler.php';

class shortlistBlockController extends applicationsSuperController
{
	public function __construct()
	{
		require_once 'Project/Code/System/HealthCare_Provider/image/image.php';
	
		$shortlistBlockView = new shortlistBlockView();
		$healthcare_providers = new healthcare_providers();
	
		if (count(seekerSession::getSeekerSession()->getList()) != 0)
			$shortlistBlockView->_set('array_of_homes',$healthcare_providers->selectMany(seekerSession::getSeekerSession()->getList()));
	
		$shortlistBlockView->getCart();
	}
	
}
?>