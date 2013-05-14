<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperView.php';

//----------------------------------------------------------
class imagesView  extends applicationsSuperView
{
	private $template_location;
	private $array_of_albums;
	private $album;
	private $array_of_sizes;
	private $size;
	private $array_of_images;
	private $image;
//==============================================================================================	

	public function __construct()
	{
		//i did this because i find long lines hard to read
		$this->template_location = 'Project/Design/'.DOMAIN.'/Applications/Photo_Library/Controllers/templates/images/';
	}
	
//-------------------------------------------------------------------------------------------------	

	public function _get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function _set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//==============================================================================================
	
	public function displayContentColumn()
	{
		$this->displayImagesHeader();
		
		$this->displayImages();
		
		$this->displayUploadDialog();
		
		$this->displayAddSizeDialog();
		
		$this->displayResizeDialog();

		$this->displayImagesSideBar();
		
		$content = $this->renderTemplate($this->template_location.'images_content_column.phtml');
		response::getInstance()->addContentToTree(array('CONTENT_COLUMN'=>$content));
	}
	
//==============================================================================================
	//Content Below is put into Content Column
	private function displayImagesHeader()
	{
		$content = $this->renderTemplate($this->template_location.'images_header.phtml');
		response::getInstance()->addContentToTree(array('APPLICATION_HEADER'=>$content));
	}
	
//==============================================================================================	
	
	private function displayImages()
	{
		$content = $this->renderTemplate($this->template_location.'images_listing.phtml');
		response::getInstance()->addContentToTree(array('IMAGES'=>$content));
	}
	
//==============================================================================================
		
	private function displayImagesSideBar()
	{
		$content = $this->renderTemplate($this->template_location.'images_sidebar.phtml');
		response::getInstance()->addContentToTree(array('IMAGES_SIDEBAR'=>$content));
	}
	
//==============================================================================================
		
	private function displayUploadDialog()
	{
		$content = $this->renderTemplate($this->template_location.'upload_dialog.phtml');
		response::getInstance()->addContentToTree(array('UPLOAD_DIALOG'=>$content));
	}
	
//==============================================================================================
		
	private function displayAddSizeDialog()
	{
		$content = $this->renderTemplate($this->template_location.'add_size_dialog.phtml');
		response::getInstance()->addContentToTree(array('ADD_SIZE_DIALOG'=>$content));
	}
	
//==============================================================================================
		
	private function displayResizeDialog()
	{
		$content = $this->renderTemplate($this->template_location.'resize_dialog.phtml');
		response::getInstance()->addContentToTree(array('RESIZE_DIALOG'=>$content));
	}
}