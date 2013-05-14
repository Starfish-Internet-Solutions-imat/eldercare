<?php
require_once(FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/viewSuperClass_Core/viewSuperClass_Core.php');

class mainAppView extends viewSuperClass_Core
{
	private $current_application_ID;
	
	public function __construct()
	{
		$this->current_application_ID = applicationsRoutes::getInstance()->getCurrentApplicationID();
	}
	
	public function displayMainApplicationLayout()
	{
		$content = $this->renderTemplate('Project/Design/'.DOMAIN.'/Applications/_Common/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('COMMON_CS_JS'=>$content));
		
		$content = $this->renderTemplate('Project/Design/'.DOMAIN.'/Applications/'.$this->current_application_ID.'/Main_App_Layout/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('SETTINGS_CS_JS'=>$content));
		
		$content = $this->renderTemplate('Project/Design/'.DOMAIN.'/Applications/'.$this->current_application_ID.'/Main_App_Layout/templates/main_app_layout.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
}
?>