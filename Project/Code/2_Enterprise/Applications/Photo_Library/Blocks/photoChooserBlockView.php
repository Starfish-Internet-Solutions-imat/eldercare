<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'Project/Code/System/Photo_Library/image/images.php';

class photoChooserBlockView  extends applicationsSuperView
{
	private $template_location;
	
	private $array_of_albums;
	private $array_of_sizes;
	private $array_of_images;
	private $album;
	private $size;
	private $image;
	 
//=================================================================================================

	public function __construct()
	{
		//I added this because I find the file locations hard to read. :D
		$this->template_location = 'Project/Design/'.DOMAIN.'/Applications/Photo_Library/Controllers/templates/photo_chooser/';
	}
	
//-------------------------------------------------------------------------------------------------	

	public function _get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function _set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	 
//=================================================================================================

	public function displayPhotoChooser()
	{
		$content = $this->renderTemplate($this->template_location.'photo_chooser_albums.phtml');
		$content .= $this->renderTemplate($this->template_location.'photo_chooser_images.phtml');
		
		return $content;
	}
	 
//=================================================================================================

	public function displayPhotoChooserImages()
	{
		return $this->renderTemplate($this->template_location.'photo_chooser_images.phtml');
	}
	 
//=================================================================================================

	public function displayPhotoChooserDetails()
	{
		return $this->renderTemplate($this->template_location.'photo_chooser_details.phtml');
	}
	 
}