<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
//require_once 'pages_editor_View_Renderer.php';

class pages_editor_View  extends applicationsSuperView
{
	private $templates_location;
	private $album_id;
	private $size_id;
	
	private $array_of_xml = array();
	
//-------------------------------------------------------------------------------------------------	
	
	public function __construct()
	{
		$this->templates_location = 'Project/Design/2_Enterprise/Applications/Content_Management_System/Controllers/pages_editor/templates/';
		
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Content_Management_System/Controllers/pages_editor/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('PAGE EDITOR CS JS'=>$content));
		
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
	
	public function displayPageEditor($pageXML) 
	{
		require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Blocks/photoChooserBlockController.php';
		
		$photoChooserBlockController = new photoChooserBlockController();
		
		$this->album_id = $photoChooserBlockController->getFirstAlbumID();
		$this->size_id = $photoChooserBlockController->getFirstSizeID();
		
		//$pages_editor_view_renderer = new pages_editor_View_Renderer();
		
		xmlProcessor::getInstance()->traverseDOM($pageXML,$this,'renderDefaultDOM');

		$content = $this->renderTemplate($this->templates_location.'pages_editor_form.phtml');
		response::getInstance()->addContentToTree(array('CONTENT_COLUMN'=>$content));
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function renderDefaultDOM ($nodeName,$nodeValue,$attributes,$dom,$startOrEndTag)
	{
		
		$xml_array = array();
		
		$xml_array['name']			= $nodeName;
		
		//remove CDATA and unnecessary tags
		$nodeValue = str_replace('<![CDATA[', '', $nodeValue);
		$nodeValue = str_replace(']]>', '', $nodeValue);
		
		$xml_array['value'] 		= $nodeValue;
		
		//if node is image id
		if($nodeName == 'image_id' && is_numeric($nodeValue))
		{
			require_once 'Project/Code/System/Photo_Library/image/image.php';
			
			$image = new image();
			$image->__set('image_id', $nodeValue);
			$image->selectFullPath(TRUE);
			
			$xml_array['image_path'] = $image->__get('full_path');
		}
		
		$xml_array['atttributes']	= $attributes;
		$xml_array['tag']			= $startOrEndTag;
		
		$this->array_of_xml[] = $xml_array;
	}
	
}