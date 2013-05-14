<?php
require_once 'Dispatcher/applicationsDispatcher.php';
require_once 'Router/applicationsRoutes.php';


//Pages Controllers call this when they want to pass control to Applications

class applicationsFrontController 
{
	private $_application_type; //simple or complex, for now
	
	//=======================================================================================================================
	public function runApplicationFromWithinPage($application_id)
	{
		
		
		//$application_id - the application id must match the <application_id> stored in the applications_routing.xml
		$parametersLeftAfterApplicationRouting  = applicationsRoutes::getInstance()->setUpApplicationRouting($application_id);
		request::getInstance()->setparametersArray($parametersLeftAfterApplicationRouting);
		
		$this->run();
	}
	//=======================================================================================================================	
	public function runFloatingApplication()
	{
		//runs an Application directly from a URL String
		//A floating application is one that isn't anchored to a Page 
		//The URL calls Main, Header and Footer but the MAIN_CONTENT is 
		//returned directly from the application rather than going via a Page Controller
		//it can be called by another application so you can link applications together
		$this->run(); 
		globalRegistry::getInstance()->setRegistryValue('event','halt_page_controller_processing','true');
	}
//=======================================================================================================================	
	public function run()
	{
		
		//called from a Page Conroller or from the function above
		
		//dispatch the correct Suite Controller
		$applicationsDispatcher = new applicationsDispatcher();
		
		$applicationsDispatcher->preDispatch();
		
		$applicationsDispatcher->dispatch();
		
		$applicationsDispatcher->postDispatch();
		
	}
	
//=======================================================================================================================
}
