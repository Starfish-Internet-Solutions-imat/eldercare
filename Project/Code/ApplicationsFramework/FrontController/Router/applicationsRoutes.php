<?php
require_once FILE_ACCESS_CORE_CODE.'/Objects/DataHandler/dataHandler.php';

class applicationsRoutes
{
	private $_applicationXML;
	
	private $_requires_login;
	private $_has_main_layout;
	
	//$this->_currentApplicationID should be set by the calling Page Controller 
	//if its a floating application thjen its set by "isThereAnApplicationRoute"
	private $_currentApplicationID;
	
	
	//for <application>
	//holds the top controller and possibly the sub page information
	//Since there are not sub controllers, this seems to be pointless to store as array
	private $_ControllerIDs_in_current_path;
	private $_ControllerLinkTexts_in_current_path;
	private $_ControllerURLNames_in_current_path;
	
	
	protected static $_instance = null;
	
	//====================================================================================================================
	public static function getInstance()
	{
		if (null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	//====================================================================================================================
	//====================================================================================================================

	public function isThereAFloatingApplicationRoute($currentRootofURL)
	{
		//USED ONLY FOR FLOATING APPLICATIONS. A floating application is an application that isn't associated with a Page
		$FullFilename = 'Project/Code/'.DOMAIN.'/Applications/applications_routing.xml';
		$dataHandler = new dataHandler();
		$this->_applicationXML = $dataHandler->loadDataSimpleXML($FullFilename);
		
		$application_id = $this->_applicationXML->xpath("/applications/application[floating_url_name='".$currentRootofURL."']/application_id");
				
		if (!empty($application_id))
		{
			$application_id = strval($application_id[0]);
			
			
			
			$parametersLeftAfterApplicationRouting  = $this->setUpApplicationRouting($application_id);
			//set up the paramaters string as normal
			request::getInstance()->setparametersArray($parametersLeftAfterApplicationRouting);
			
			return true;
		}
		else return false;
	}
	//====================================================================================================================	
	public function setUpApplicationRouting($currentApplicationID)
	{
		
		$this->setCurrentApplicationID($currentApplicationID);
		
		if (globalRegistry::getInstance()->getRegistryValue('modifier','ajax')===true)	
		{
			//Ajax calls are dealt with by the the Ajax controller in the Ajax folder.
			// It doesnt follow the Controller logic set out in application_routing.xml
			
			$path = routes::getInstance()->_pathInfoArray;
			
			array_shift ($path);
			return $path;
		}
		else 
		{
			$FullFilename = 'Project/Code/'.DOMAIN.'/Applications/applications_routing.xml';
			$dataHandler = new dataHandler();
			$this->_applicationXML = $dataHandler->loadDataSimpleXML($FullFilename);
			
			//----------------------------------------------------------------------------------------------------
			//find out if the application requires the user to be logged in or not.
			$this->setRequiresLogin($this->_applicationXML);
			
			
			//find out if the application requires a main layout to hold it together (for enterprise apps)
			$this->setHasMainLayout($this->_applicationXML);
			
			
			$path = routes::getInstance()->_pathInfoArray;
			//knock of the first element of the path as this is dealt with by Routes and Pages
			array_shift ($path);
			
				$ParametersLeftAfterRouting = array();
				if (empty($path))
				{
					//empty path i.e. just the domain name, go to home page
					$this->goToDefaultController();
				}
				else
				{
					$controller_url_name = $path[0];
					
					//$controller_url_name = 'images';
					//$this->_currentApplicationID = 'Media';
										
					$controller_id_in_an_array = $this->_applicationXML->xpath("/applications/application[application_id='".$this->_currentApplicationID."']/controller[url_name='".$controller_url_name."']/controller_id");
					
					
					if (!empty($controller_id_in_an_array))
					{
						
						$ParametersLeftAfterRouting =  $this->setNormalFrontEndRouting($path,$controller_url_name,$controller_id_in_an_array);
					}
					else
					{
						//var_dump("/applications/application[application_id='".$this->_currentApplicationID."']/controller[url_name='".$controller_url_name."']/controller_id");
						//die('NOTHING FOUND IN APPLICATION ROUTE- WILL GO HOME');
						//nothing found in the routing
						$this->goToDefaultController();
						$ParametersLeftAfterRouting = $path;
					}
				}
				
				return $ParametersLeftAfterRouting;
		}
	}
	//====================================================================================================================
	
	//====================================================================================================================	
	private function goToDefaultController() 
	{
		//If the path
		$this->_controllerXML = $this->_applicationXML->xpath("/applications/application[application_id='".$this->_currentApplicationID."']/controller[@default='true']");
		
		if (isset($this->_controllerXML[0]->url_name)) 
		{
			$this->_ControllerIDs_in_current_path			= strval($this->_controllerXML[0]->controller_id);
			$this->_ControllerLinkTexts_in_current_path		= strval($this->_controllerXML[0]->link_text);
			$this->_ControllerURLNames_in_current_path		= strval($this->_controllerXML[0]->url_name);
		}
		else	{
			die("no default page set in Applications or Application: ".$this->_currentApplicationID);
		}
	}
	//====================================================================================================================
	private function setNormalFrontEndRouting($path)
	{
		$ParametersLeftAfterPage = array();
		
		foreach ($path as $key => $page)	
		{				

			$this->_controllerXML = $this->_applicationXML->xpath("/applications/application/controller[url_name='".$page."']");
			if (isset($this->_controllerXML[0]->url_name))
			{
				$this->_ControllerIDs_in_current_path			= strval($this->_controllerXML[0]->controller_id);
				$this->_ControllerLinkTexts_in_current_path 	= strval($this->_controllerXML[0]->link_text);
				$this->_ControllerURLNames_in_current_path		= strval($this->_controllerXML[0]->url_name);
			}
			else {
				$ParametersLeftAfterPage[] =  $page;
			}
		}
		return $ParametersLeftAfterPage;
	}
	//====================================================================================================================
	//===			 APPLICATION  ID
	//====================================================================================================================
	//Used in dispatcher
	public function getCurrentApplicationID()	{
		return $this->_currentApplicationID;
	}
	
	public function setCurrentApplicationID($currentApplicationID)	{
		$this->_currentApplicationID = $currentApplicationID;
	}
	
	
	public function getControllersForApplication($current_application_id)
	{
		// I dont think that can be optimised
		// It gets the sublevels too...
		//$tabs = array();
		$controllersXML = $this->_applicationXML->xpath('/applications/application[application_id="'.$current_application_id.'"]/controller');
		//returns the whole XML object
		//var_dump($current_application_id);
		return  $controllersXML;
	}
	
	//====================================================================================================================
	//===			 Controller URL NAME
	//====================================================================================================================
	public function getCurrentControllerURLName()
	{
		//A section page is a top level section page which is used as the Page folder in the framework too
		if (isset($this->_ControllerURLNames_in_current_path)){
			return $this->_ControllerURLNames_in_current_path;
		}
		else return null;
	}
	//====================================================================================================================
	//===			 Controller ID
	//====================================================================================================================
	public function getCurrentControllerID()	{
		return $this->_ControllerIDs_in_current_path;
	}	
	
	public function setCurrentControllerID($currentControllerID)	{
		$this->_ControllerIDs_in_current_path = $currentControllerID;
	}
	
	//====================================================================================================================
	//===			 HAS MAIN LAYOUT
	//====================================================================================================================
	private function setHasMainLayout($applicationXML)
	{
		//Should only accept "yes" and "no" until I know what I am doing
	
		$has_main_layout = $applicationXML->xpath("/applications/application[application_id='".$this->_currentApplicationID."']/has_main_layout");
	
		if (empty($has_main_layout)){
			$this->_has_main_layout = "no";
		}
		else {
			$this->_has_main_layout  = strval($has_main_layout[0]);
		}
	}
	//--------------------------------------------------------------------------------------------------------------------
	public function getHasMainLayout()
	{
		//Should only accept "yes" and "no" until I know what I am doing
		return $this->_has_main_layout;
	}
	//====================================================================================================================
	//===			 LOGIN 
	//====================================================================================================================
	private function setRequiresLogin($applicationXML)
	{
		//Should only accept "yes" and "no" until I know what I am doing
		$requires_login = $applicationXML->xpath("/applications/application[application_id='".$this->_currentApplicationID."']/requires_login");
		
		if (empty($requires_login)){
			$this->_requires_login = "no";
		}
		else {
			$this->_requires_login  = strval($requires_login[0]);
		}
	}
	//--------------------------------------------------------------------------------------------------------------------	
	public function getRequiresLogin()
	{
		//Should only accept "yes" and "no" until I know what I am doing
		return $this->_requires_login;
	}
	//====================================================================================================================
}