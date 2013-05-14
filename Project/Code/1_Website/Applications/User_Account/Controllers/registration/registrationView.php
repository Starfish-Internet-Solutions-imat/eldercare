<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class registrationView extends applicationsSuperView
{
	private $has_error;
	private $error="";
	private $password_error_message;
	private $registration_second_part_data;
	private $registration_first_part_data;
//-------------------------------------------------------------------------------------------------	
	
	public function _get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function _set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------	
	
	public function displayRegistrationTemplate()
	{
		$templates_folder = 'Project/Design/1_Website/Applications/User_Account/Controllers/registration/templates/';
		
		$content = $this->renderTemplate($templates_folder.'css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('CURRENT SECTION CSS'=>$content));
		
		$content = $this->renderTemplate($templates_folder.'inpage_javascript.js');
		response::getInstance()->addContentToStack('inpage_javascript_top',array('CURRENT SECTION CSS'=>$content));
		
		$content = $this->renderTemplate($templates_folder.'main_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
		
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/_Common/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('COMMON CSS AND JS'=>$content));
		
	}
	
	public function displayRegistrationFirstStepTemplate($has_error = FALSE)
	{
		$this->has_error = $has_error;
		
		$templates_folder = 'Project/Design/1_Website/Applications/User_Account/Controllers/registration/templates/';
		
		$content = $this->renderTemplate($templates_folder.'css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('CURRENT SECTION CSS'=>$content));
		
		$content = $this->renderTemplate($templates_folder.'inpage_javascript.js');
		response::getInstance()->addContentToStack('inpage_javascript_top',array('CURRENT SECTION CSS'=>$content));
		
		$content = $this->renderTemplate($templates_folder.'main_firststep_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
		
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/_Common/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('COMMON CSS AND JS'=>$content));
	}
	
	public function displayEmailTemplate()
	{
		return $this->renderTemplate('Project/Design/1_Website/Applications/User_Account/Controllers/profile/templates/email_template_2.phtml');
	}
	
}
