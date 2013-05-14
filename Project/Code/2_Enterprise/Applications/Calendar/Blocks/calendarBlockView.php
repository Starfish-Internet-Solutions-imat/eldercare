<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class calendarBlockView  extends applicationsSuperView
{
	private $templates_location;
	
	private $pages_calendar_events;
	private $calendar_events;
	private $lead_id;
	private $hcp_id;
	
//-------------------------------------------------------------------------------------------------	

	public function __construct()
	{
		$this->templates_location = 'Project/Design/2_Enterprise/Applications/Calendar/Blocks/templates/';
		
		$content = $this->renderTemplate($this->templates_location.'js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('CALENDAR_CSS'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'inpage_javascript.js');
		response::getInstance()->addContentToStack('inpage_javascript_bottom',array('CALENDAR_JS'=>$content));
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
		$content = $this->displayCalendarRow();
		response::getInstance()->addContentToTree(array('CALENDAR_ROW'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'calendar.phtml');
		response::getInstance()->addContentToTree(array('CALENDAR'=>$content));
	}
	
//-------------------------------------------------------------------------------------------------	

	public function displayCalendarRow()
	{
		return $this->renderTemplate($this->templates_location.'calendar_row.phtml');
	}
	
//-------------------------------------------------------------------------------------------------	

	public function displayCalendarAlarmForm()
	{
		$content = $this->renderTemplate($this->templates_location.'add_alarm_form.phtml');
		response::getInstance()->addContentToTree(array('ADD_ALARM_FORM'=>$content));
	}
}