<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'Project/Code/2_Enterprise/Applications/Calendar/Blocks/calendarBlockController.php';
require_once 'Project/Code/2_Enterprise/Applications/Conversations/Blocks/conversationsBlockController.php';

class profileView  extends applicationsSuperView
{
	private $templates_location;
	
	private $seeker;
	private $lead;
	private $potential_hcps;
	private $conversations;
	private $pages_potential_hcps;
	public $has_leads;
	
//-------------------------------------------------------------------------------------------------	

	public function __construct()
	{
		$this->displayCommonCSSAndJS();
		
		$application_id = applicationsRoutes::getInstance()->getCurrentApplicationID();
		$controller_id = applicationsRoutes::getInstance()->getCurrentControllerID();
		
		$this->templates_location = 'Project/Design/2_Enterprise/Applications/Seekers_Manager/Controllers/profile/templates/';
		
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
		
		$calendarBlock = new calendarBlockController($staff_id, $this->seeker->__get('seeker_id'), NULL);
		$calendarBlock->getAlarmForm();
		$calendarBlock->getCalendar();
		
		$this->displayApplicationBlock('Leads_Manager', 'getLeadsNavigation');
		
		/* $conversationsBlock = new conversationsBlockController($staff_id, $this->seeker->__get('seeker_id'), NULL);
		$conversationsBlock->getConversationHistory(); */
		
		$content = $this->renderTemplate($this->templates_location.'conversation_history.phtml');
		response::getInstance()->addContentToTree(array('CONVERSATION_HISTORY'=>$content));
		
		$content = $this->displayPotentialHCPS();
		response::getInstance()->addContentToTree(array('POTENTIAL_HCPS'=>$content));
		
		$content = $this->displayPotentialHCPSDialog();
		response::getInstance()->addContentToTree(array('POP_UP_DIALOG'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'profile.phtml');
		response::getInstance()->addContentToTree(array('PROFILE'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'profile_container.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
//-------------------------------------------------------------------------------------------------	

	public function displayPotentialHCPS()
	{
		return $this->renderTemplate($this->templates_location.'potential_hcps.phtml');
	}
	
	public function displayPotentialHCPSDialog()
	{
		return $this->renderTemplate($this->templates_location.'pop_dialog.phtml');
	}
	
}