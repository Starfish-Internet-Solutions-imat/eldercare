<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'Project/Code/2_Enterprise/Applications/Health_Care_Providers/Modules/hcpModule.php';

class searchView  extends applicationsSuperView
{
	private $templates_location;
	
	private $hcps;
	
	private $seeker_id;
	private $seeker_name;
	
	public $email_to;
	public $email_info;
	
//-------------------------------------------------------------------------------------------------	

	public function displayCommon()
	{
		$this->displayCommonCSSAndJS();
		
		$application_id = applicationsRoutes::getInstance()->getCurrentApplicationID();
		$controller_id = applicationsRoutes::getInstance()->getCurrentControllerID();
		
		$this->templates_location = 'Project/Design/2_Enterprise/Applications/'.$application_id.'/Controllers/'.$controller_id.'/templates/';
		
		$content = $this->renderTemplate($this->templates_location.'js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('APPLICATION_CSS'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'inpage_javascript.js');
		response::getInstance()->addContentToStack('inpage_javascript_bottom',array('APPLICATION_JS'=>$content));
	}
	
//-------------------------------------------------------------------------------------------------	

	public function _get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function _set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------	

	

	public function displayApplicationLayout()
	{
		$this->displayStateSelectInput();
		$this->displayZipCodeSelectInput();
	
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Health_Care_Providers/Controllers/search/templates/search_navigation.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_LEFT_COLUMN'=>$content));
		 
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Health_Care_Providers/Controllers/search/templates/search_listing.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_CONTENT'=>$content));
		
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Health_Care_Providers/Controllers/search/templates/search_container.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
		
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Health_Care_Providers/Controllers/search/templates/recommend_hcp.phtml');
		response::getInstance()->addContentToTree(array('RECOMMEND_CONTENT'=>$content));
	}
	
//-------------------------------------------------------------------------------------------------	

	private function displayStateSelectInput()
	{
		require_once 'Project/Code/2_Enterprise/Applications/Seekers_Manager/Blocks/seekers_managerBlockController.php';
		
		$block = new seekers_managerBlockController();
		$block->getStateSelectInput();
	}
	
//-------------------------------------------------------------------------------------------------	

	private function displayZipCodeSelectInput()
	{
		require_once 'Project/Code/2_Enterprise/Applications/Seekers_Manager/Blocks/seekers_managerBlockController.php';
		
		$block = new seekers_managerBlockController();
		$block->getZipCodeSelectInput();
	}
	
	public function displayEmailTemplate()
	{
		return $this->renderTemplate('Project/Design/2_Enterprise/Applications/Health_Care_Providers/Controllers/search/templates/email_template_1.phtml');
	}
	
	
}