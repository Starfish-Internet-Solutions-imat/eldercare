<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class loginView extends applicationsSuperView
{
	private $login_error;
	
//-------------------------------------------------------------------------------------------------	

	public function _get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function _set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------	

	public function showLoginForm($login_error)
	{
		$this->login_error = $login_error;
		$templates_folder = 'Project/Design/1_Website/Applications/User_Account/Controllers/login/templates/';
		
		$content = $this->renderTemplate($templates_folder.'css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('CURRENT SECTION CSS'=>$content));
		
		$content = $this->renderTemplate($templates_folder.'inpage_javascript.js');
		response::getInstance()->addContentToStack('inpage_javascript_top',array('CURRENT SECTION CSS'=>$content));
		
		$content = $this->renderTemplate($templates_folder.'main_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
}