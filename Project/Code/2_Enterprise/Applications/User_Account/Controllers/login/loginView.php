<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class loginView extends applicationsSuperView
{
	private $login_error;
	
	public function getLoginError() { return $this->login_error; }
	
	public function showLoginForm($login_error = FALSE)
	{
		$this->login_error = $login_error;
		
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/_Common/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('COMMON_CSS_AND_JS'=>$content));
		
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/User_Account/Main_App_Layout/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('LOGIN_CSS_AND_JS'=>$content));
		
        $content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/User_Account/Main_App_Layout/templates/inpage_javascript.js');
        response::getInstance()->addContentToStack('inpage_javascript_top',array('LOGIN SCRIPT'=>$content));
        
		$content = $this->renderTemplate('Project/Design/'.DOMAIN.'/Applications/User_Account/Blocks/login_form.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
}