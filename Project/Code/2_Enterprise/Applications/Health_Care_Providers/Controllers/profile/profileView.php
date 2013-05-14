<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class profileView  extends applicationsSuperView
{
	private $templates_location;
	private $hcp;
	private $amenities_categories;
	private $amenities;
	private $placements;
	private $image_filename;
//-------------------------------------------------------------------------------------------------	

	public function displayMainLayout()
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
		$this->displayApplicationBlock('Leads_Manager', 'getLeadsNavigation');
		
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Health_Care_Providers/Controllers/profile/templates/profile.phtml');
		response::getInstance()->addContentToTree(array('PROFILE'=>$content));
		
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Health_Care_Providers/Controllers/profile/templates/profile_container.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
	public function displayEmailTemplate()
	{
		return $this->renderTemplate('Project/Design/2_Enterprise/Applications/Health_Care_Providers/Controllers/profile/templates/email_template_2.phtml');
	}
	
}