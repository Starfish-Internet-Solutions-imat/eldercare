<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class searchView  extends applicationsSuperView
{
	private $templates_location;
	private $staff_information;
	private $staff_detail;
	public $email_info;
	
//-------------------------------------------------------------------------------------------------	

	public function __construct()
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
		$content = $this->renderTemplate($this->templates_location.'search_listing.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_CONTENT'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'search_sidebar.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_SIDEBAR'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'search_container.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
	public function displayAddStaffLayout()
	{
		$content = $this->renderTemplate($this->templates_location.'search_listing_add_staff.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_CONTENT'=>$content));
	
		$content = $this->renderTemplate($this->templates_location.'search_sidebar.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_SIDEBAR'=>$content));
	
		$content = $this->renderTemplate($this->templates_location.'search_container.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
	public function displayEmailTemplate()
	{
		return $this->renderTemplate('Project/Design/2_Enterprise/Applications/Staff_Manager/Controllers/search/templates/email_template_2.phtml');
	}
	 

	
}