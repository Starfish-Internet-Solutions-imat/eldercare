<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

class shortlistBlockView  extends applicationsSuperView
{
	private $zipcodes;
	private $provider;
	private $hcp_id_viewed;
	private $array_of_homes;
	
	//-------------------------------------------------------------------------------------------------
	
	public function _get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
	
		else return NULL;
	}
	
	//-------------------------------------------------------------------------------------------------
	
	public function _set($field, $value) {
		if(property_exists($this, $field)) $this->{$field} = $value;
	}
	
	public function getCart()
	{
		require_once 'Project/Code/1_Website/Applications/Results/Modules/resultsModule.php';
		
		$content = $this->renderTemplate("Project/Design/1_Website/Applications/Seeker/templates/blocks/shortlistBlock_template.phtml");
		response::getInstance()->addContentToTree(array("SHORT_LIST"=>$content));
	}
	
}