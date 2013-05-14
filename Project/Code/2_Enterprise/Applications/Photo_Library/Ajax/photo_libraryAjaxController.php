<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Controllers/photo_library/images/imagesController.php';
require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Modules/imageChecker/imageChecker.php';
require_once 'Project/Code/System/Photo_Library/image/image.php';
require_once 'Project/Code/System/Photo_Library/image/images.php';
	 	
class photo_libraryAjaxController extends applicationsSuperController
{
	private	$characters = array(".","png","jpg","gif");
	
	public function load_sizesAction()
	{
		require_once 'Project/Code/System/Photo_Library/album_size/album_sizes.php';
		
		$album_sizes = new album_sizes();
		$album_sizes->__set('album_id', $_REQUEST['album_id']);
		$album_sizes->select();
		
		$content = '';
		
		foreach($album_sizes->__get('array_of_sizes') as $size)
		{
			$content .= "<option value='{$size->__get('size_id')}'>{$size->__get('dimensions')}</option>";
		}
		
		jQuery('#size_select')->html($content);
		jQuery::getResponse();
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function load_image_detailsAction()
	{
		$image_id = $_REQUEST['image_id'];
		
		$image = new image();
		$image->__set('image_id', $image_id);
		$image->select();
		
		//jQuery("h3.editImageTitle")->html($image->getImageTitle());
		jQuery("textarea.imageTitleEditor")->val(str_replace("_", " ", str_replace($this->characters,"",$image->__get('filename'))));
		jQuery("p.editImageCaption")->html($image->__get('image_caption'));
		jQuery("textarea.imageCaptionEditor")->val($image->__get('image_caption'));
		jQuery("span#sidebar_image_id")->html($image->__get('image_id'));
		jQuery("#photo_details_holder input[name='image_id']")->val($image->__get('image_id'));
		jQuery("#photo_details_holder input[name='album_id']")->val($image->__get('album_id'));
		jQuery::getResponse();
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function photo_chooser_detailsAction()
	{
		$image_id = $_REQUEST['image_id'];
				
		require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Blocks/photoChooserBlockController.php';
				
		$photoChooserBlockController = new photoChooserBlockController();
		$content = $photoChooserBlockController->getImageDetails($image_id);
		
		jQuery('#photo_chooser #applicationContent .actions')->html($content);
		jQuery::getResponse();
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function photo_chooserAction()
	{
		//get div id where node is located
		$node = $_REQUEST['image_div'];
		
		$image_id = NULL;
		
		if(isset($_REQUEST['image_id'])) $image_id = $_REQUEST['image_id'];
		
		require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Blocks/photoChooserBlockController.php';
		
		$photoChooserBlockController = new photoChooserBlockController();
		
		jQuery("div#photo_chooser")->html($photoChooserBlockController->getPhotoChooser($image_id));
		jQuery::getResponse();
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function photo_chooser_imagesAction()
	{
		//get div id where node is located
		$node		= $_REQUEST['image_div'];
		$album_id	= $_REQUEST['album_id'];
		$size_id	= $_REQUEST['size_id'];
		$image_id	= $_REQUEST['image_id'];
		
		require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Blocks/photoChooserBlockController.php';
		
		$photoChooserBlockController = new photoChooserBlockController();
		
		jQuery("div#photo_chooser")->append(
			$photoChooserBlockController->getPhotoChooserImages($album_id, $size_id, $image_id)
		);
		jQuery::getResponse();
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function if_usedAction()
	{
		$image_id = $_REQUEST['image_id'];
		
		$image = new image();
		
		if($image->checkIfUsed($image_id, 'image_id') > 0 || imageChecker::checkIfImageIsUsed($image_id))
			jQuery::addMessage('Image is used by other modules.');
		
		else
			jQuery::addMessage('Success.');
		
		jQuery::getResponse();
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function if_album_usedAction()
	{
		$album_id	= $_REQUEST['album_id'];
		$is_used	= FALSE;
		
		$images = new images();
		$images->__set('album_id', $album_id);
		$images->selectByAlbum();
		
		foreach($images->__get('array_of_images') as $image)
			if($image->checkIfUsed($image->__get('image_id'), 'image_id') > 0 || imageChecker::checkIfImageIsUsed($image->__get('image_id')))
 				$is_used = TRUE;
		
		if($is_used == TRUE)
			jQuery::addMessage('Image is used by other modules.');
		
		else
			jQuery::addMessage('Success.');
		
		jQuery::getResponse();
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function if_size_usedAction()
	{
		$size_id	= $_REQUEST['size_id'];
		$is_used	= FALSE;
		
		$images = new images();
		$images->__set('size_id', $size_id);
		$images->selectBySize();
		
		foreach($images->__get('array_of_images') as $image)
			if($image->checkIfUsed($image->__get('image_id'), 'image_id') > 0 || imageChecker::checkIfImageIsUsed($image->__get('image_id')))
 				$is_used = TRUE;
		
		if($is_used == TRUE)
			jQuery::addMessage('Image is used by other modules.');
		
		else
			jQuery::addMessage('Success.');
		
		jQuery::getResponse();
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function if_filename_existsAction()
	{
		$image_id	= $_REQUEST['image_id'];
		$filename	= $_REQUEST['image_filename'];
		
		$image = new image();
		$image->__set('image_id', $image_id);
		$image->select();
		
		if($image->__get('filename') != $filename)
		
			if(image::checkIfFilenameExist($filename) == TRUE)
				jQuery::addMessage('Fail.');
			else
				jQuery::addMessage('Success.');
		
		else
			jQuery::addMessage('Success.');
		
		jQuery::getResponse();
	}
	
}