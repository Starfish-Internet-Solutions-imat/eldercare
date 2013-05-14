<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'photo_libraryView.php';

require_once 'albums/albumsController.php';
require_once 'album_sizes/album_sizesController.php';
require_once 'images/imagesController.php';

require_once 'Project/Code/System/Photo_Library/album/album.php';
require_once 'Project/Code/System/Photo_Library/album_size/album_size.php';

class photo_libraryController extends applicationsSuperController
{
	public function indexAction()
	{
		if(isset($_POST['album_action']) && $_POST['album_action'] == 'view')
		{
			$imagesController = new imagesController();
			$imagesController->getImagesInAlbum($_POST['album_id']);	
		}
	//---------------------------------------------------------------------------------------------------------------
		
		elseif(isset($_POST['add_album']))
		{
			$albumsController = new albumsController();
			$albumsController->addAlbum();	
		}
	//---------------------------------------------------------------------------------------------------------------
		
		elseif(isset($_POST['rename_album']))
		{
			$albumsController = new albumsController();
			$albumsController->renameAlbum();		
		}
	//---------------------------------------------------------------------------------------------------------------
		
		elseif(isset($_POST['album_action']) && $_POST['album_action'] == 'delete')
		{
			$albumsController = new albumsController();
			$albumsController->deleteAlbum();		
		}
	//---------------------------------------------------------------------------------------------------------------
		
		elseif(isset($_POST['view_size']))
		{
			$imagesController = new imagesController();
			$imagesController->getImagesInAlbum($_POST['album_id'], $_POST['size_id']);
		}
	//---------------------------------------------------------------------------------------------------------------
		
		elseif(isset($_POST['add_size']))
		{
			$album_sizesController = new album_sizesController();
			$album_sizesController->addAlbumSize();
		}
	//---------------------------------------------------------------------------------------------------------------
		
		elseif(isset($_POST['resize']))
		{
			$album_sizesController = new album_sizesController();
			$album_sizesController->resizeImages();
		}
	//---------------------------------------------------------------------------------------------------------------
		
		elseif(isset($_POST['deleteSize']))
		{
			$album_sizesController = new album_sizesController();
			$album_sizesController->deleteAlbumSize();
		}
	//---------------------------------------------------------------------------------------------------------------
		
		elseif(isset($_POST['upload_photo']))
		{
			$imagesController = new imagesController();
			$imagesController->uploadImage();
		}
	//---------------------------------------------------------------------------------------------------------------
		
		elseif(isset($_POST['view_image_info_x']) || isset($_POST['view_image_info']))
		{
			$imagesController = new imagesController();
			$imagesController->getImagesInAlbum($_POST['album_id'], $_POST['size_id'], $_POST['image_id']);
		}
	//---------------------------------------------------------------------------------------------------------------
		
		elseif(isset($_POST['edit_photo']) && $_POST['edit_photo'] == 'edit')
		{
			$imagesController = new imagesController();
			$imagesController->editImageInfo();
		}
	//---------------------------------------------------------------------------------------------------------------
		
		elseif(isset($_POST['edit_photo']) && $_POST['edit_photo'] == 'delete')
		{
			$imagesController = new imagesController();
			$imagesController->deleteImage();
		}
	//---------------------------------------------------------------------------------------------------------------
		else
		{
			$this->getAlbumsList();
		}
	}
	
//---------------------------------------------------------------------------------------------------------------
		
	private function getAlbumsList()
	{
		require_once 'Project/Code/System/Photo_Library/album/albums.php';
		
		$albums = new albums();
		$albums->select();
		
		$album_array	= $albums->__get('array_of_albums');
		$splice_array	= $album_array;
		$temp_array		= array();
		
		foreach($album_array as $key=>$album)
			if(!file_exists(STAR_SITE_ROOT.'/Data/Images/'.$album->__get('album_folder')))
			{
				$temp_array = array_splice($splice_array, $key, 1);
				$splice_array = $temp_array;
			}
			
		$photo_libraryView = new photo_libraryView();
		$photo_libraryView->_set('array_of_albums', $splice_array);
		$photo_libraryView->displayContentColumn();
	}
	
	
}