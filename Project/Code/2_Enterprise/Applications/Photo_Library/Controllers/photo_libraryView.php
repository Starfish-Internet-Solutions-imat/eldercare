<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';
require_once 'Project/Code/System/Photo_Library/image/images.php';

class photo_libraryView  extends applicationsSuperView
{
	private $template_location;
	
	private $array_of_albums;
	private $album;
	 
//=================================================================================================

	public function __construct()
	{
		//I added this because I find the file locations hard to read. :D
		$this->template_location = 'Project/Design/'.DOMAIN.'/Applications/Photo_Library/Controllers/templates/albums/';
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

	public function displayContentColumn()
	{
		$this->displayAlbumHeader();
	
		$this->displayAlbums();
		
		
	
		$content = $this->renderTemplate($this->template_location.'albums_content_column.phtml');
		response::getInstance()->addContentToTree(array('CONTENT_COLUMN'=>$content));
	}
	 
//=================================================================================================

	public function displayAlbumHolder()
	{//maybe i should put this in block controller...
		return $this->renderTemplate($this->template_location.'album_holder.phtml');
	}
	 
//=================================================================================================

	//Content Below is put into Content Column
	private function displayAlbumHeader()
	{
		$content = $this->renderTemplate($this->template_location.'album_header.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_HEADER'=>$content));
	}
	 
//=================================================================================================

	private function displayAlbums()
	{
		$content = $this->renderTemplate($this->template_location.'albums_listing.phtml');
		response::getInstance()->addContentToTree(array('ALBUMS'=>$content));
	}
	 

	
}