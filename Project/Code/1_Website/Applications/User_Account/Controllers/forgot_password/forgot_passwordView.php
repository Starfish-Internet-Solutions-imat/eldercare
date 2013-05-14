<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';


class forgot_passwordView extends applicationsSuperView
{
	public $verification;
	
	
	public function displayMainLayout()
	{
		$this->getCSSAndJSFiles();
		
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/User_Account/Controllers/forgot_password/templates/main_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
	//--------------------------------------------------------------------------------------------------------------
	
	public function displayInstruction()
	{
		 $content = $this->renderTemplate('Project/Design/1_Website/Applications/User_Account/Controllers/forgot_password/templates/instruction_template.phtml');
		 response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
	//--------------------------------------------------------------------------------------------------------------
	
	public function displayResetPasswordTemplate()
	{
		$this->getCSSAndJSFiles();
		
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/User_Account/Controllers/forgot_password/templates/reset_password_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
	//--------------------------------------------------------------------------------------------------------------
	
	public function displayPasswordChangedTemplate()
	{
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/User_Account/Controllers/forgot_password/templates/password_changed.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
	//--------------------------------------------------------------------------------------------------------------
	
	public function displayInvalidLinkTemplate()
	{
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/User_Account/Controllers/forgot_password/templates/invalid_verification_link.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
	//--------------------------------------------------------------------------------------------------------------
	
	public function getEmail()
	{
		return $this->renderTemplate('Project/Design/1_Website/Applications/User_Account/Controllers/forgot_password/templates/email_template_1.phtml');
	}
	
	//--------------------------------------------------------------------------------------------------------------
	
	public function getCSSAndJSFiles()
	{
		$templates_folder = 'Project/Design/1_Website/Applications/User_Account/Controllers/forgot_password/templates/';
		
		$content = $this->renderTemplate($templates_folder.'css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('CURRENT SECTION CSS'=>$content));
		
		$content = $this->renderTemplate($templates_folder.'inpage_javascript.js');
		response::getInstance()->addContentToStack('inpage_javascript_top',array('CURRENT SECTION CSS'=>$content));
	}
}