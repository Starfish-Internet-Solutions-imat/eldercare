<?php
require_once(FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/viewSuperClass_Core/viewSuperClass_Core.php');
class view extends viewSuperClass_Core 
{
	private $zipcodes;
	private $provider;
	private $hcp_id_viewed;
	private $array_of_homes;
	private $hcp_info;
	
//-------------------------------------------------------------------------------------------------	

	public function _get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function _set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
	public function showInfoForm()
	{
		$this->displayCSS();
		$this->displayJS();
		$content = $this->renderTemplate('Project/Design/1_Website/Pages/primary/seeker/templates/seeker_information_form_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
	public function viewList()
	{
		$this->displayCSS();
		
		$content = $this->renderTemplate('Project/Design/1_Website/Pages/primary/seeker/templates/seeker_cart_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
	private function displayCSS()
	{
		$templates_folder = 'Project/Design/1_Website/Pages/primary/seeker/templates/';
	
		$content = $this->renderTemplate($templates_folder.'css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('CURRENT SECTION CSS'=>$content));
	
	}
	
	private function displayJS()
	{
		$templates_folder = 'Project/Design/1_Website/Pages/primary/seeker/templates/';
		
		$content = $this->renderTemplate($templates_folder.'inpage_javascript.js');
		response::getInstance()->addContentToStack('inpage_javascript_top',array('CURRENT SECTION CSS'=>$content));
	}

}
?>