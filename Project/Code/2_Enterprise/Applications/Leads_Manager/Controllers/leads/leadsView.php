<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class leadsView  extends applicationsSuperView
{
	private $templates_location;
	
	private $leads;
	private $staffs;
	private $calendar_events;
	private $placements;
	private $pages_new_leads;
	private $pages_new_placements;
	private $pages_calendar_events;
	
//-------------------------------------------------------------------------------------------------	

	public function __construct()
	{
		$this->displayCommonCSSAndJS();
		
		$this->templates_location = 'Project/Design/2_Enterprise/Applications/Leads_Manager/Controllers/leads/templates/';
		
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
		$content = $this->displayNewLeads();
		response::getInstance()->addContentToTree(array('NEW_LEADS'=>$content));
		
		$content = $this->displayCalendarAlerts();
		response::getInstance()->addContentToTree(array('CALENDAR_ALERTS'=>$content));
		
		$content = $this->displayNewPlacements();
		response::getInstance()->addContentToTree(array('NEW_PLACEMENTS'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'new_placements.phtml');
		response::getInstance()->addContentToTree(array('NEW_PLACEMENT'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'leads.phtml');
		response::getInstance()->addContentToTree(array('LEADS'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'leads_container.phtml');
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
		return $this->renderTemplate($this->templates_location.'upcoming_calendar_alerts.phtml');
	}
	
//-------------------------------------------------------------------------------------------------	

	public function displayNewPlacements()
	{
		//this one's for ajax calls
		return $this->renderTemplate($this->templates_location.'new_placements.phtml');
	}
}