<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class profileView extends applicationsSuperView
{
	private $provider;
	private $zipcodes;
	private $house_types;
	private $hcp_house_types_array  = array();
	private $zipcode;
	private $amenities_by_category;
	private $amenities_categories;
	private $hcp_amenities;
	private $template = "";	
	private $array_of_images;
	private $city_state = null;
	private $current_tab = null;
	private $is_completed_mark;
	private $is_new;
	private $is_suspended;
	private $is_suspended_notif;
	private $is_approved;
	private $is_approved_publish;
	private $account_settings;
//-------------------------------------------------------------------------------------------------	

	
	public function _get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function _set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------	

	public function displayProfileTemplate()
	{
		$templates_folder = 'Project/Design/1_Website/Applications/User_Account/Controllers/profile/templates/';
		
		$this->displayJSandCSS();
		
		$content = $this->renderTemplate($templates_folder.'main_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
		
		$content = $this->renderTemplate($templates_folder.'facilities_popup.phtml');
		response::getInstance()->addContentToTree(array('FACILITIES_POPUP'=>$content));
		
		$content = $this->renderTemplate($templates_folder.'leftside_navigation_template.phtml');
		response::getInstance()->addContentToTree(array('LEFTSIDE_NAVIGATION'=>$content));
		
		if($this->template === "")
			$content = $this->renderTemplate($templates_folder.'name_description_tab_template.phtml');
		else
			$content = $this->renderTemplate($templates_folder.$this->template."_template.phtml");
		
		response::getInstance()->addContentToTree(array('TAB_CONTENT'=>$content));
	}
	
	
	public function displayAccountSettingsTemplate()
	{
		$templates_folder = 'Project/Design/1_Website/Applications/User_Account/Controllers/profile/templates/';
		
		$this->displayJSandCSS();
		
		$content = $this->renderTemplate($templates_folder.'main_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
		
		$content = $this->renderTemplate($templates_folder.'my_account_details_tab_template.phtml');
		response::getInstance()->addContentToTree(array('TAB_CONTENT'=>$content));
		
	}
	
	
	public function displayTabContentTemplate()
	{
		return $content = $this->renderTemplate($this->template);
	}
	
//-------------------------------------------------------------------------------------------------	

	public function displayCancelSuccessTemplate()
	{
		$templates_folder = 'Project/Design/1_Website/Applications/User_Account/Controllers/profile/templates/';
		
		$this->displayJSandCSS();
		
		$content = $this->renderTemplate($templates_folder.'cancel_success_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
//-------------------------------------------------------------------------------------------------	

	private function displayJSandCSS()
	{
		$templates_folder = 'Project/Design/1_Website/Applications/User_Account/Controllers/profile/templates/';
		
		$content = $this->renderTemplate($templates_folder.'css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('CURRENT SECTION CSS'=>$content));
		
		$content = $this->renderTemplate($templates_folder.'inpage_javascript.js');
		response::getInstance()->addContentToStack('inpage_javascript_top',array('CURRENT SECTION CSS'=>$content));
		
		$content = $this->renderTemplate(FILE_ACCESS_CORE_DESIGN.'/Libraries/JCustomScrollBar/templates/JCustomScrollBar_script_link.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('JCustomScrollBar SCRIPT'=>$content));
		
		$content = $this->renderTemplate(FILE_ACCESS_CORE_DESIGN.'/Libraries/JCustomScrollBar/templates/JCustomScrollBar_css_link.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('JCustomScrollBar CSS'=>$content));
		
		$content = $this->renderTemplate(FILE_ACCESS_CORE_DESIGN.'/Libraries/JEasing/templates/jeasing_link.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('JEASING SCRIPT'=>$content));
		
		$content = $this->renderTemplate(FILE_ACCESS_CORE_DESIGN.'/Libraries/JMouseWheel/templates/JMouseWheel_script_link.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('JMOUSEWHEEL SCRIPT'=>$content));
		
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function displayThankYouPage()
	{
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/User_Account/Controllers/profile/templates/thank_you_template.php');
		response::getInstance()->addContentToTree(array('THANK_YOU'=>$content));
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function displaySuspendedPopup()
	{
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/User_Account/Controllers/profile/templates/suspended_popup.phtml');
		response::getInstance()->addContentToTree(array('SUSPENDED'=>$content));
	}
	
//-------------------------------------------------------------------------------------------------

	public function displayApprovedPopup()
	{
		$content = $this->renderTemplate('Project/Design/1_Website/Applications/User_Account/Controllers/profile/templates/approved_popup.phtml');
		response::getInstance()->addContentToTree(array('APPROVED_POPUP'=>$content));
	}
	
}
