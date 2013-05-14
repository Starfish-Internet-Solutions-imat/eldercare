<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'Project/Code/2_Enterprise/Applications/Calendar/Blocks/calendarBlockController.php';

class contact_historyView  extends applicationsSuperView
{
	private $templates_location;
	
	private $hcp;
	private $potential_leads;
	private $conversations;
	private $pages_potential_leads;
	
//-------------------------------------------------------------------------------------------------	

	public function __construct()
	{
		$this->displayCommonCSSAndJS();
		
		$this->templates_location = 'Project/Design/2_Enterprise/Applications/Health_Care_Providers/Controllers/contact_history/templates/';
		
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
		$staff_id = authorization::getUserID();
		
		$calendarBlock = new calendarBlockController($staff_id, NULL, $this->hcp->__get('hcp_id'));
		$calendarBlock->getAlarmForm();
		$calendarBlock->getCalendar();
		
		$this->displayApplicationBlock('Leads_Manager', 'getLeadsNavigation');
		
		$content = $this->displayPotentialLeads();
		response::getInstance()->addContentToTree(array('POTENTIAL_LEADS'=>$content));
		
		$content = $this->displayPotentialLeadsPopUp();
		response::getInstance()->addContentToTree(array('POP_UP_PLACED'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'conversation_history.phtml');
		response::getInstance()->addContentToTree(array('CONVERSATION'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'contact_history.phtml');
		response::getInstance()->addContentToTree(array('CONTACT_HISTORY'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'contact_history_container.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
//-------------------------------------------------------------------------------------------------	

	public function displayPotentialLeads()
	{
		return $this->renderTemplate($this->templates_location.'potential_leads.phtml');
	}
	
	public function displayPotentialLeadsPopUp()
	{
		return $this->renderTemplate($this->templates_location.'pop_up.phtml');
	}
	
	
}