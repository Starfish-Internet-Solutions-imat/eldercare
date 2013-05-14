
<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once('mainAppModel.php');
require_once('mainAppView.php');

class mainAppController extends controllerSuperClass_Core
{
	
	public function getMainLayout()
	{	
		$mainAppView = new mainAppView();
		$mainAppView->displayMainApplicationLayout();
	}
}

?>