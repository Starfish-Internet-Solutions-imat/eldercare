<?php
require_once 'photoChooserBlockView.php';
require_once 'photoChooserBlockModel.php';

require_once 'Project/Code/System/Photo_Library/album/album.php';
require_once 'Project/Code/System/Photo_Library/album/albums.php';
require_once 'Project/Code/System/Photo_Library/album_size/album_size.php';
require_once 'Project/Code/System/Photo_Library/album_size/album_sizes.php';
require_once 'Project/Code/System/Photo_Library/image/images.php';

class photoChooserBlockController
{
	public function getPhotoChooser($image_id)
	{
		$image = new image();
		$image->__set('image_id', $image_id);
		$image->select();
		
		//select albums for select input
		$albums = new albums();
		$albums->select();
		
		//get album details of image
		$album = new album();
		
		if($image_id == NULL) $album->selectFirst();
		
		else
		{
			$album->__set('album_id', $image->__get('album_id'));
			$album->select();
		}
		
		//get images
		$model = new photoChooserBlockModel();
		$model->__set('album_id', $album->__get('album_id'));
		$model->select();
		
		$array_of_images = $model->__get('array_of_images');
		
		//load details in view
		$photoChooserBlockView = new photoChooserBlockView();
		$photoChooserBlockView->_set('array_of_albums', $this->spliceNonExistentAlbums($albums->__get('array_of_albums')));
		$photoChooserBlockView->_set('array_of_images', $array_of_images);
		$photoChooserBlockView->_set('album', $album);
		$photoChooserBlockView->_set('image', $image);
		
		return $photoChooserBlockView->displayPhotoChooser();
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function getPhotoChooserImages($album_id, $image_id)
	{
		//select albums for select input
		$album = new album();
		$album->__set('album_id', $album_id);
		$album->select();
		
		$image = new image();
		$image->__set('image_id', $image_id);
		$image->select();
		
		//get images
		$model = new photoChooserBlockModel();
		$model->__set('album_id', $album->__get('album_id'));
		$model->select();
		
		$array_of_images = $model->__get('array_of_images');
		
		//load details in view
		$photoChooserBlockView = new photoChooserBlockView();
		$photoChooserBlockView->_set('array_of_images', $array_of_images);
		$photoChooserBlockView->_set('album', $album);
		$photoChooserBlockView->_set('image', $image);
		
		return $photoChooserBlockView->displayPhotoChooserImages();
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function getImageDetails($image_id)
	{	
		$image = new image();
		$image->__set('image_id', $image_id);
		$image->select();
		
		//select albums for select input
		$album = new album();
		$album->__set('album_id', $image->__get('album_id'));
		$album->select();
		
		//album size
		$album_size = new album_size();
		$album_size->__set('size_id', $image->__get('size_id'));
		$album_size->select();
			
		//load details in view
		$photoChooserBlockView = new photoChooserBlockView();
		$photoChooserBlockView->_set('album', $album);
		$photoChooserBlockView->_set('size', $album_size);
		$photoChooserBlockView->_set('image', $image);
		
		return $photoChooserBlockView->displayPhotoChooserDetails();
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function getFirstAlbumID()
	{
		//get album details of image
		$album = new album();
		$album->selectFirst();
		
		return $album->__get('album_id');
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function getFirstSizeID()
	{
		//get album details of image
		$album = new album();
		$album->selectFirst();
		
		//get sizes available for album
		$album_sizes = new album_sizes();
		$album_sizes->__set('album_id', $album->__get('album_id'));
		$album_sizes->select();
		
		$array_of_sizes = $album_sizes->__get('array_of_sizes');
		
		if(count($array_of_sizes) > 0)
			return $array_of_sizes[0]->__get('size_id');
		
		else
			return '';
	}
	
//-------------------------------------------------------------------------------------------------	
	
	private function spliceNonExistentAlbums($album_array)
	{
		
		//let's not show non-existent albums folders in the photo chooser.
		$splice_array	= $album_array;
		$temp_array		= array();
		
		foreach($album_array as $key=>$album)
			if(file_exists(STAR_SITE_ROOT.'/Data/Images/'.$album->__get('album_folder')))
				$temp_array[] = $album;
		
        $splice_array = $temp_array;    
		
        return $splice_array;    
	}
	
}