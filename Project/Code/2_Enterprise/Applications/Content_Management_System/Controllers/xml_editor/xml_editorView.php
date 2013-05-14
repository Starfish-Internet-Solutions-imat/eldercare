<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'xml_editor_View_Renderer.php';

//----------------------------------------------------------
class xml_editorView  extends applicationsSuperView
{
	
//====================================================================================================================	
	public function displayXMLEditor($pageXML) 
	{
		//display the options
		//Is there a way for the prarent class to know what this file is automatically
		$xml_editor_view_renderer = new XML_editor_View_Renderer();
		$content = $xml_editor_view_renderer->get_xml_file($pageXML);
		
		response::getInstance()->addContentToTree(array('CONTENT_COLUMN'=>$content));
	}
//====================================================================================================================	
}