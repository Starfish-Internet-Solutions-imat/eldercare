<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'Project/Code/1_Website/Applications/Seeker/Blocks/shortlistBlockController.php';

class browseView extends applicationsSuperView
{
	private $array_of_cities;
	private $array_of_properties;
	private $state;
	private $uri;
	
//-------------------------------------------------------------------------------------------------	

	public function _get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function _set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------	

	public function showCities()
	{
		$this->displayJSandCSS();
		
		$shortlist = new shortlistBlockController();
		
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/Browse/templates/css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('BROWSE SECTION CSS'=>$content));
		
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/Browse/templates/inpage_javascript.js');
		response::getInstance()->addContentToStack('inpage_javascript_top',array('BROWSE SECTION JS'=>$content));
		
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/Browse/templates/cities_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
	public function showResult()
	{
		$this->displayJSandCSS();
		
		$shortlist = new shortlistBlockController();
		
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/Results/templates/main_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
		
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/Results/templates/search_panel_template.phtml');
		response::getInstance()->addContentToTree(array('SEARCH_PANEL'=>$content));
		
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/Results/templates/results_template.phtml');
		response::getInstance()->addContentToTree(array('RESULTS'=>$content));
	}
	
	private function displayJSandCSS()
	{
		$templates_folder = 'Project/Design/1_Website/Applications/Results/templates/';
		
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/_Common/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('COMMON CSS AND JS'=>$content));

		$content = $this->renderTemplate($templates_folder.'css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('RESULTS SECTION CSS'=>$content));
	
		$content = $this->renderTemplate($templates_folder.'inpage_javascript.js');
		response::getInstance()->addContentToStack('inpage_javascript_top',array('RESULTS SECTION JS'=>$content));
	}
	
	
}