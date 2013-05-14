<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';


class forgot_passwordView extends applicationsSuperView
{
	public $password;
	
	public function displayMainLayout()
	{
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Forgot_Password/Controllers/forgot_password/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('RESET_PASSWORD'=>$content));
		
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Forgot_Password/Controllers/forgot_password/templates/forgot_password_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
	public function getEmail()
	{
		return $this->renderTemplate('Project/Design/2_Enterprise/Applications/Forgot_Password/Controllers/forgot_password/templates/email_template_1.phtml');
	} 
	
	public function displayPasswordChangedTemplate()
	{
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Forgot_Password/Controllers/forgot_password/templates/password_changed.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
}