<?php
require_once 'applicationsSuperView.php';

class applicationsSuperController
{
	
	//====================================================================================================================
	//===			 APPLICATION PARAMATERS
	//====================================================================================================================
	
	private  $_parametersArray = array();
	
	public function __construct()
	{
		$this->_parametersArray = request::getInstance()->getParametersArray();
		//var_dump($this->_parametersArray);
	}
	
	
	public function getParametersArray()
	{
		return request::getInstance()->getParametersArray();
	}
	
	public function getValueOfURLParameterPair($parameterPairKey)
	{
		//DOES NOT WORK WITH AJAX -- USE A &a=b instead
		//PARAMETERS ARE PASSED IN PAIRS eg /page/2/page/introduction/
		//i.e page = 2, page = 'introduction';
	
	
		//this line was added June 25 2009 because if the controller is called
		//againg it forgets the parameters array,
		$this->_parametersArray = request::getInstance()->getParametersArray();
	
		if (in_array($parameterPairKey,$this->_parametersArray))
		{
			$count = array_search($parameterPairKey,$this->_parametersArray);
				
			if(isset($this->_parametersArray[$count+1])) {
				return $this->_parametersArray[$count+1];
			}
			else return null; //parameter wrong or missing
		}
		else
		{
			return null;
		}
	}
	
	
	//====================================================================================================================
	//===			 PRE DISPATCH
	//====================================================================================================================
	public function predispatch()
	{
		//NOTE THIS FUNCTION ISNT CALLED AND CANNOT BE CALLED BY THE DISPATCHER PREDISPATCH FUNCTION
		//BECAUSE THIS CONTROLLER DOESNT EXIST UNTIL AFTER THE DISPATCHER CLASS DISPATCHES IT
		//ITS JUST HERE SO I REMEMBER NOT TO TRY AND PUT THINGS HERE AGAIN!
	}
	//====================================================================================================================
	//===			 DISPATCH
	//====================================================================================================================
	//called from dispatcher
    public function speculativeDispatch() {
    	//there is one final choice to make in the dispatch process
    	//Its a speculative dispatch
    	//Because not all Actions  need to be specified in the routes xml (timeconsuming)
    	//only the ones that need a Site Map, Breadcrumbs, Nav 
    	//There is a time when what is thought of as a parameter is an unspecified action
    	//First try this and if that fails try the given $action,
    	//the action will use
		$content='';
		
    	$speculativeAction = $this->_parametersArray['0'].'Action';
    	if (method_exists($this,$speculativeAction)) {
	    	try {
	    		array_shift($this->_parametersArray);
	    		$content = $this->$speculativeAction();
		    	}
	    	catch (Exception $e) 
	        {
	       		throw $e;
	    	}
	    	return $content;
    	}
    	else {
    		$this->normalDispatch();
    		return $content;
    	}
    }
	//---------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------    
    public function normalDispatch() {
     try {
    			$content = $this->indexAction();
    	}
    	catch (Exception $e){
            throw $e;
    	}
    	return $content;
    }
	//---------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------  
    //---------------------------------------------------------------------------------------------------------------
	private function sanitizePOST() {
		//to prevent MySQL and XSS attacks
		global $_POST;
		if(isset($_POST)) {
			foreach($_POST as $key=>$value) {
				$_POST[$key] = stripslashes($value);
			}
		}
	}
	//=======================================================================================	
	public function doLogin()
	{
		//this PUBLIC function is the standard login but can be over ridden by the child application controller
		//which must be a PUBLIC function for this to work.
		
		require(STAR_SITE_ROOT.'/Project/Code/'.DOMAIN.'/Applications/User_Account/Controllers/login/loginController.php');
		$loginController = new loginController();
		$loginController->indexAction();
	}
	
	
	//====================================================================================================================
	//===			 POST DISPATCH
	//====================================================================================================================
	public function postdispatch()
	{
		
		if (applicationsRoutes::getInstance()->getHasMainLayout()=="yes")
		{
			$currentApplicationID = applicationsRoutes::getInstance()->getCurrentApplicationID();
			require_once('Project/Code/'.DOMAIN.'/Applications/'.$currentApplicationID.'/Main_App_Layout/mainAppController.php');
			$mainAppController = new mainAppController();
			$mainAppController->getMainLayout();
		}
	}
}