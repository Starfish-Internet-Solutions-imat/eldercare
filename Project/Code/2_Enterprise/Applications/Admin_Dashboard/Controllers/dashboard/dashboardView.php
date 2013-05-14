<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class dashboardView  extends applicationsSuperView
{
	private $templates_location;
	private $pages_new_leads;
	private $pages_new_placements;
	private $pages_calendar_events;
	private $pages_new_chp;
	
	private $array_of_new_hcp = array();
	private $array_of_new_leads = array();
	private $array_of_new_placements = array();
	private $array_of_calendar_events = array();
	private $placements  = array();
	
	public $emailInfo;
//-------------------------------------------------------------------------------------------------	

	public function __construct()
	{
		$this->displayCommonCSSAndJS();
		
		$this->templates_location = 'Project/Design/2_Enterprise/Applications/Admin_Dashboard/Controllers/dashboard/templates/';
		
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
		//$this->getCSSAndJS();
		
		$content = $this->displayNewLeads();
		response::getInstance()->addContentToTree(array('NEW_LEADS'=>$content));
		
		$content = $this->displayCalendarAlerts();
		response::getInstance()->addContentToTree(array('CALENDAR_ALERTS'=>$content));
		
		$content = $this->displayNewPlacements();
		response::getInstance()->addContentToTree(array('NEW_PLACEMENTS'=>$content));
		
		$content = $this->displayNewPlacementsAndPaymentStatus();
		response::getInstance()->addContentToTree(array('NEW_PLACEMENTS_AND_PAYMENT_STATUS'=>$content));
		
		$content = $this->displayNewHcp();
		response::getInstance()->addContentToTree(array('NEW_HCP'=>$content));
		
		$content = $this->displayPopUpDialog();
		response::getInstance()->addContentToTree(array('POP_UP'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'dashboard.phtml');
		response::getInstance()->addContentToTree(array('DASHBOARD'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'dashboard_container.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
//-------------------------------------------------------------------------------------------------	

	public function displayNewLeads()
	{
		//this one's for ajax calls
		return $this->renderTemplate($this->templates_location.'new_leads.phtml');
	}
	
//-------------------------------------------------------------------------------------------------	

	public function displayCalendarAlerts()
	{
		//this one's for ajax calls
		return $this->renderTemplate($this->templates_location.'calendar_alerts.phtml');
	}
	
//-------------------------------------------------------------------------------------------------	

	public function displayNewPlacements()
	{
		//this one's for ajax calls
		return $this->renderTemplate($this->templates_location.'new_placements.phtml');
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function displayNewPlacementsAndPaymentStatus()
	{
		//this one's for ajax calls
		return $this->renderTemplate($this->templates_location.'new_placements_and_payment_status.phtml');
	}

//-------------------------------------------------------------------------------------------------
	
	public function displayNewHcp()
	{
		//this one's for ajax calls
		return $this->renderTemplate($this->templates_location.'new_hcp.phtml');
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function displayPopUpDialog()
	{
		return $this->renderTemplate($this->templates_location.'popup_dialog.phtml');
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function displayEmailTemplate()
	{
		return $this->renderTemplate('Project/Design/2_Enterprise/Applications/Admin_Dashboard/Controllers/dashboard/templates/email_template_2.phtml');
	}
	
	
}