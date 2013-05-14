<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class settingsView extends applicationsSuperView
{
	private $templates_location;
	private $array_of_results;
	
	public function __construct()
	{
		$this->templates_location = 'Project/Design/2_Enterprise/Applications/Settings_Manager/Controllers/templates/settings/';
	}
	 
//=================================================================================================

	public function getArrayOfResults() { return $this->array_of_results; }
	 
//=================================================================================================

	public function setArrayOfResults($array_of_results) { $this->array_of_results = $array_of_results; }
	 
//=================================================================================================

	public function displaySettingsContentColumn()
	{
		$this->displaySettingsHeader();
		
		$content = $this->renderTemplate($this->templates_location.'settings_content_column.phtml');
		response::getInstance()->addContentToTree(array('CONTENT_COLUMN'=>$content));
	}
	 
//=================================================================================================

	private function displaySettingsHeader()
	{
		$content = $this->renderTemplate($this->templates_location.'settings_header.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_HEADER'=>$content));
	}	
	 
}


