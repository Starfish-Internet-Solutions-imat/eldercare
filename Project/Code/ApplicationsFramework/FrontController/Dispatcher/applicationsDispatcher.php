<?php 	
class applicationsDispatcher
{
	
	private $_ApplicationController;
	
	//====================================================================================================================
	//===			 PRE DISPATCH
	//====================================================================================================================	
	public function predispatch() {
		
		//might put login/logout stuff here
		//clean out the project front controller a little
	
	}
	//====================================================================================================================
	//===			 DISPATCH
	//====================================================================================================================	
	public function dispatch() {
		
		$currentApplicationID = applicationsRoutes::getInstance()->getCurrentApplicationID();
		
		
	
		$currentControllerID = applicationsRoutes::getInstance()->getCurrentControllerID();
		
		
		if (is_null($currentApplicationID))
		{
			header('Location: /');
		}
		
		
		if (globalRegistry::getInstance()->getRegistryValue('modifier','ajax')===true)	
		{
				require_once('Project/Code/'.DOMAIN.'/Applications/'.$currentApplicationID.'/Ajax/'.strtolower($currentApplicationID).'AjaxController.php');
				$applicationControllerString = strtolower($currentApplicationID).'AjaxController';
		}
		else
		{
			require_once('Project/Code/'.DOMAIN.'/Applications/'.$currentApplicationID.'/Controllers/'.$currentControllerID.'/'.$currentControllerID.'Controller.php');
			$applicationControllerString = $currentControllerID.'Controller';
		}
		$this->_ApplicationController = new $applicationControllerString;
		
		
		if ((applicationsRoutes::getInstance()->getRequiresLogin()=="yes") && ($currentControllerID != "login"))
		{
			//if the user is not going to login on his own accord then go to the login page if the page 
			// he is trying to go to is restricted
		
			require_once FILE_ACCESS_CORE_CODE.'/Objects/Authorization/authorization.php';
			require_once 'Zend/Session.php';
			
			if(authorization::areWeLoggedIn()== FALSE)
			{
			//we do it this way so that certain applicatiob classes can override the super Login action
				globalRegistry::getInstance()->setRegistryValue('event','login_application_grabs_control','true');
				$this->_ApplicationController->doLogin();
			}
	
		}
		
		//This function won't be reached if the control goes to doLogin, as that function does a redirect.
		
		if (globalRegistry::getInstance()->getRegistryValue('event','login_application_grabs_control')=='false'){
			
			$parametersArray = request::getInstance()->getParametersArray();
			
			if (!empty($parametersArray))
			{
				//speculative try at first parameter in string as Action name
				$this->_ApplicationController->speculativeDispatch();
			}
			else
			{
				$this->_ApplicationController->normalDispatch();
			}
		}
		
		//pre dispatch
		//dispatch		
		
		
	}
	//====================================================================================================================
	//===			 POST DISPATCH
	//====================================================================================================================	
	public function postdispatch() 
	{
		if (globalRegistry::getInstance()->getRegistryValue('event','login_application_grabs_control')=='false'){
			$this->_ApplicationController->postdispatch();
		}
	}
}
	
?>