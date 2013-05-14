<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';

require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Controllers/photo_library/images/imagesController.php';
require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Controllers/photo_library/album_sizes/album_sizesController.php';

require_once 'Project/Code/System/Photo_Library/album/album.php';
require_once 'Project/Code/System/Photo_Library/album_size/album_size.php';

class albumsController extends applicationsSuperController
{	

	public function addAlbum()
	{	
	 	$album_title		= $_POST['album_title'];
	 	$height				= $_POST['height'];
	 	$width				= $_POST['width'];
	 	$album_folder		= str_replace(' ', '-', $album_title).'_album';
	 	$album_size_folder	= $height.'x'.$width;
	 	
		//start SQL transaction
		/* NOTE: SQL transactions should be used when a combination of insert, update
		 * and delete commands is executed
		 */
		$pdo_connection = starfishDatabase::getConnection();
		$pdo_connection->beginTransaction();
	 	
	 	//add album to database
	 	$album = new album();
	 	
	 	$album->__set('album_title', $album_title);
	 	$album->__set('album_folder', $album_folder);
	 	$album->insert();
	 	
	 	//add album size to database
	 	$album_size = new album_size();
	 	
	 	$album_size->__set('album_id', $album->__get('album_id'));
	 	$album_size->__set('dimensions', $album_size_folder);
	 	$album_size->insert();
			
		$pdo_connection->commit();
	 	
	 	//add album in Data/Images folder
	 	$data_handler = new dataHandler();

	 	//replace spaces in folder names with underscores so there won't be a problem with the URLs
	 	$data_handler->create_directory(STAR_SITE_ROOT.'/Data/Images/'.$album_folder);
	 	$data_handler->create_directory(STAR_SITE_ROOT.'/Data/Images/'.$album_folder.'/'.$album_size_folder);
	 	$data_handler->create_directory(STAR_SITE_ROOT.'/Data/Images/'.$album_folder.'/'.$album_size_folder.'_thumb');
	 	
	 	$data_handler->create_directory(STAR_SITE_ROOT.'/Data/Images/'.$album_folder.'/original');
	 	//do we still need image cropping?
	 	$data_handler->create_directory(STAR_SITE_ROOT.'/Data/Images/'.$album_folder.'/crop_tool_images');
	 	
	 	header('Location: /image_library');
	}
	
//=======================================================================================================================

	public function renameAlbum()
	{
		//start SQL transaction
		/* NOTE: SQL transactions should be used when a combination of insert, update
		 * and delete commands is executed
		 */
		$pdo_connection = starfishDatabase::getConnection();
		$pdo_connection->beginTransaction();
		
		$album_id		= $_POST['album_id'];
		$album_title	= $_POST['album_title'];
		//replace spaces in folder names with underscores so there won't be a problem with the URLs
		$album_folder	= str_replace(' ', '-', $album_title).'_album';
		 
		//select album from database
		$album = new album();
		$album->__set('album_id', $album_id);
		$album->select();
		
		//recursively copies the files and directories, then deletes the original folder
		$original_path	= 'Data/Images/'.$album->__get('album_folder');
		$new_path		= 'Data/Images/'.$album_folder;
		
		require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Modules/directories/directoryHandler.php';
		
		$directory_handler = new directoryHandler();
		
		$directory_handler->rename_directory($original_path, $new_path);
		
		$album->__set('album_title', $album_title);
		$album->__set('album_folder', $album_folder);
		$album->update();
			
		$pdo_connection->commit();
		
		if (isset($_POST['redirect_to_albums']))
			header('Location: /image_library');
		
		$imagesController = new imagesController();
		$imagesController->getImagesInAlbum($_POST['album_id']);
	}
	
//=======================================================================================================================

	public function deleteAlbum()
	{
		//start SQL transaction
		/* NOTE: SQL transactions should be used when a combination of insert, update
		 * and delete commands is executed
		 */
		$pdo_connection = starfishDatabase::getConnection();
		$pdo_connection->beginTransaction();
		
	 	$album_id = $_POST['album_id'];
	 	
	 	//select album from database
	 	$album = new album();
	 	$album->__set('album_id', $album_id);
	 	$album->select();
	 	
	 	//delete from database
	 	$album->delete();
			
		$pdo_connection->commit();
	 	
	 	//delete album
	 	$data_handler = new dataHandler();
	 	$data_handler->delete_directory_recurse_svn('Data/Images/'.$album->__get('album_folder'));
	 	
	 	header('Location: /image_library');
	}
}