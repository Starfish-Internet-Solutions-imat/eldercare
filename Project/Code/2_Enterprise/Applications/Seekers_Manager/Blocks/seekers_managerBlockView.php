<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class seekers_managerBlockView  extends applicationsSuperView
{
	private $templates_location;
	
	private $seeker;
	private $staff;
	
//-------------------------------------------------------------------------------------------------	

	public function __construct()
	{
		$this->templates_location = 'Project/Design/2_Enterprise/Applications/Seekers_Manager/Blocks/';
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

	public function displaySearchSideBar()
	{
		$content = $this->renderTemplate($this->templates_location.'search_sidebar.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_SIDEBAR'=>$content));
		
		return $content;
	}
}