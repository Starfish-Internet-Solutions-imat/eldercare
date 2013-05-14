<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Controllers/photo_library/images/imagesController.php';

require_once 'Project/Code/System/Photo_Library/album_size/album_size.php';

class album_sizesController extends applicationsSuperController
{
	public function addAlbumSize()
	{	
	 	$album_id			= $_POST['album_id'];
	 	$height				= $_POST['height'];
	 	$width				= $_POST['width'];
	 	$album_size_folder	= $height.'x'.$width;
	 	
		//start SQL transaction
		/* NOTE: SQL transactions should be used when a combination of insert, update
		 * and delete commands is executed
		 */
		$pdo_connection = starfishDatabase::getConnection();
		$pdo_connection->beginTransaction();
	 	
	 	//select album from database
	 	$album = new album();
	 	$album->__set('album_id', $album_id);
	 	$album->select();
	 	
	 	//add album in Data/Images folder
	 	$data_handler = new dataHandler();

	 	//replace spaces in folder names with underscores so there won't be a problem with the URLs
	 	$data_handler->create_directory(STAR_SITE_ROOT.'/Data/Images/'.$album->__get('album_folder').'/'.$album_size_folder);
	 	$data_handler->create_directory(STAR_SITE_ROOT.'/Data/Images/'.$album->__get('album_folder').'/'.$album_size_folder.'_thumb');
	 	
	 	//add album size to database
	 	$album_size = new album_size();
	 	
	 	$album_size->__set('album_id', $album_id);
	 	$album_size->__set('dimensions', $album_size_folder);
	 	$album_size->insert();
			
		$pdo_connection->commit();
	 	
	 	//show images page
	 	$imagesController = new imagesController();
	 	$imagesController->getImagesInAlbum($album_id, $album_size->__get('size_id'));
	}
	
//=======================================================================================================================
	
	public function resizeImages()
	{
		//start SQL transaction
		/* NOTE: SQL transactions should be used when a combination of insert, update
		 * and delete commands is executed
		 */
		$pdo_connection = starfishDatabase::getConnection();
		$pdo_connection->beginTransaction();
		
		$album_size = new album_size();
		$album_size->__set('size_id', $_POST['size_id']);
		$album_size->select();
		
		$album = new album();
		$album->__set('album_id', $_POST['album_id']);
		$album->select();
		
		require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Modules/directories/directoryHandler.php';
		
		$dimensions = explode('x', $album_size->__get('dimensions'));
				
		$directory_handler = new directoryHandler();
		$directory_handler->resize_directory('Data/Images/'.$album->__get('album_folder').'/', $dimensions[0], $dimensions[1], $_POST['width'], $_POST['height']);
		
		$directory_handler->rename_thumb_directory('Data/Images/'.$album->__get('album_folder').'/'.$dimensions[0].'x'.$dimensions[1].'_thumb',
									'Data/Images/'.$album->__get('album_folder').'/'.$_POST['width'].'x'.$_POST['height'].'_thumb');
		
		$album_size->__set('dimensions', $_POST['width'].'x'.$_POST['height']);
		$album_size->update();
			
		$pdo_connection->commit();
	 	
	 	//show images page
	 	$imagesController = new imagesController();
	 	$imagesController->getImagesInAlbum($album->__get('album_id'), $album_size->__get('size_id'));
	}
	
//=======================================================================================================================
	
	public function deleteAlbumSize()
	{
		//start SQL transaction
		/* NOTE: SQL transactions should be used when a combination of insert, update
		 * and delete commands is executed
		 */
		$pdo_connection = starfishDatabase::getConnection();
		$pdo_connection->beginTransaction();
		
	 	$size_id	= $_POST['size_id'];
	 	
	 	//select album from database
	 	$album_size = new album_size();
	 	$album_size->__set('size_id', $size_id);
	 	$album_size->select();
	 	
	 	//select album from database
	 	$album = new album();
	 	$album->__set('album_id', $album_size->__get('album_id'));
	 	$album->select();
	 	
	 	//delete album
	 	$data_handler = new dataHandler();
	 	$data_handler->delete_directory_recurse_svn(STAR_SITE_ROOT.'/Data/Images/'.$album->__get('album_folder').
	 												'/'.$album_size->__get('dimensions'));
	 	$data_handler->delete_directory_recurse_svn(STAR_SITE_ROOT.'/Data/Images/'.$album->__get('album_folder').
	 												'/'.$album_size->__get('dimensions').'_thumb');
	 	//die;
	 	$album_size->delete();
			
		$pdo_connection->commit();
	 	
	 	//show images page
	 	$imagesController = new imagesController();
	 	$imagesController->getImagesInAlbum($album->__get('album_id'));
	}
}