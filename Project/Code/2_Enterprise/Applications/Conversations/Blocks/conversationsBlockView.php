<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class conversationsBlockView  extends applicationsSuperView
{
	private $templates_location;
	
//-------------------------------------------------------------------------------------------------	

	public function __construct()
	{
		$this->templates_location = 'Project/Design/2_Enterprise/Applications/Conversations/Blocks/templates/';
		
		$content = $this->renderTemplate($this->templates_location.'js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('CONVERSATION_HISTORY_CSS'=>$content));
		
		$content = $this->renderTemplate($this->templates_location.'inpage_javascript.js');
		response::getInstance()->addContentToStack('inpage_javascript_bottom',array('CONVERSATION_HISTORY_JS'=>$content));
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
		$content = $this->renderTemplate($this->templates_location.'conversation_history.phtml');
		response::getInstance()->addContentToTree(array('CONVERSATION_HISTORY'=>$content));
	}
}