<?php
require_once(FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/viewSuperClass_Core/viewSuperClass_Core.php');

class mainAppView extends viewSuperClass_Core
{
	
	public function display_Main_Application_Layout()
	{
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/_Common/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('COMMON CS JS'=>$content));
		
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Content_Management_System/Main_App_Layout/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('CMS CS JS'=>$content));
		
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Content_Management_System/Controllers/pages_editor/templates/inpage_javascript.js');
		response::getInstance()->addContentToStack('inpage_javascript_bottom',array('CURRENT SECTION CSS'=>$content));
		
		$content = $this->renderTemplate('Project/Design/'.DOMAIN.'/Applications/Content_Management_System/Main_App_Layout/templates/main_app_layout.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
		
	}
	
	
	public function display_Viewing_Type_Option_Links()
	{
		require_once('Project/Code/'.DOMAIN.'/Applications/Content_Management_System/Navigation/view_type_Navigation.php');
		$content = viewTypeNavigation::displayControllersAsNavigation('Content_Management_System'); 
		response::getInstance()->addContentToTree(array('APPLICATION_HEADER'=>$content));
	}
	
	
	public function display_Static_Pages_for_Navigation()
	{
		require_once('Project/Code/'.DOMAIN.'/Applications/Content_Management_System/Navigation/static_pages_Navigation.php');
		$content = staticPagesNavigation::displayStaticPagesNavigation('Content_Management_System');
		response::getInstance()->addContentToTree(array('APPLICATION_LEFT_COLUMN'=>$content));
	}
	
	
	
}
?>