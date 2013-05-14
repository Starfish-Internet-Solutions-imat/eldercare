<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class imagesView extends applicationsSuperView
{
	private $provider;
	private $array_of_images;
	
//-------------------------------------------------------------------------------------------------	

	public function _get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function _set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------	

	public function displayImagesTemplate()
	{
		$templates_folder = 'Project/Design/1_Website/Applications/User_Account/Controllers/profile/templates/';
		
		$this->displayJSandCSS();
		
		$content = $this->renderTemplate($templates_folder.'images_main_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
		
		$content = $this->renderTemplate($templates_folder.'leftside_navigation_template.phtml');
		response::getInstance()->addContentToTree(array('LEFTSIDE_NAVIGATION'=>$content));
		
		$content = $this->renderTemplate($templates_folder.'images_template.phtml');
		response::getInstance()->addContentToTree(array('TAB_CONTENT'=>$content));
	}
	
//-------------------------------------------------------------------------------------------------	

	private function displayJSandCSS()
	{
		$templates_folder = 'Project/Design/1_Website/Applications/User_Account/Controllers/profile/templates/';
		
		$content = $this->renderTemplate($templates_folder.'css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('CURRENT SECTION CSS'=>$content));
		
		$content = $this->renderTemplate($templates_folder.'inpage_javascript.js');
		response::getInstance()->addContentToStack('inpage_javascript_top',array('CURRENT SECTION CSS'=>$content));
	}
}
