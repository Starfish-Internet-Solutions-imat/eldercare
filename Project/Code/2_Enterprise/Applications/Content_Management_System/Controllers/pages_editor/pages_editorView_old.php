<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'pages_editor_View_Renderer.php';

class pages_editor_View  extends applicationsSuperView
{
	private $templates_location;
	private $array_of_xml = array();
	
	public function getArrayOfXML() { return $this->array_of_xml; }
	
//-------------------------------------------------------------------------------------------------	
	
	public function __construct()
	{
		$this->templates_location = 'Project/Design/2_Enterprise/Applications/Content_Management_System/Controllers/pages_editor/templates/';
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function displayPageEditor($pageXML) 
	{
		$pages_editor_view_renderer = new pages_editor_View_Renderer();
		
		xmlProcessor::getInstance()->traverseDOM($pageXML,$this,'renderDefaultDOM');
		
		$content = $this->renderTemplate($this->templates_location.'pages_editor_form.phtml');
		response::getInstance()->addContentToTree(array('CONTENT_COLUMN'=>$content));;
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function renderDefaultDOM ($nodeName,$nodeValue,$attributes,$dom,$startOrEndTag)
	{
		$xml_array = array();
		
		$xml_array['name']			= $nodeName;
		$xml_array['value'] 		= $nodeValue;
		$xml_array['atttributes']	= $attributes;
		$xml_array['tag']			= $startOrEndTag;
		
		$this->array_of_xml[] = $xml_array;
	}
	
}