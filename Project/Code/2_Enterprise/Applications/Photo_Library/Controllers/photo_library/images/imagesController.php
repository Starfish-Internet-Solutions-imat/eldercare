<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'imagesView.php';

require_once 'Project/Code/System/Photo_Library/image/image.php';

class imagesController extends applicationsSuperController
{
	private $characters = array(' ','_',',','\'','.',':',';','?','!');
	
	public function getImagesInAlbum($album_id, $size_id = 0, $image_id = 0)
	{
		//$size_group_index is the array element in $array_of_image_sizes

		require_once 'Project/Code/System/Photo_Library/album/albums.php';
		require_once 'Project/Code/System/Photo_Library/album/album.php';
		require_once 'Project/Code/System/Photo_Library/album_size/album_sizes.php';
		require_once 'Project/Code/System/Photo_Library/image/images.php';
		
		//select albums for select input
		$albums = new albums();
		$albums->select();
		
		//get album details of image
		$album = new album();
		$album->__set('album_id', $album_id);
		$album->select();
		
		//get sizes available for album
		$album_sizes = new album_sizes();
		$album_sizes->__set('album_id', $album_id);
		$album_sizes->select();
		
		//get images by size_id
		$images = new images();
		
		$array_of_sizes		= array();
		$array_of_albums	= array();
		$array_of_images	= array();
		$album_size			= new album_size();
			
		$image = NULL;
		
		$array_of_albums= $albums->__get('array_of_albums');
		$array_of_sizes	= $album_sizes->__get('array_of_sizes');
		
		if(count($array_of_sizes) > 0)
		{ 
			//default image size group to be shown
			if($size_id == 0)
			{
				//so that only one image size group is loaded.
				$album_size = $array_of_sizes[0];
				$images->__set('size_id', $album_size->__get('size_id'));
			}
			
			else
			{
				$images->__set('size_id', $size_id);

				$album_size->__set('size_id', $size_id);
				$album_size->select();
			}
			
			//get size details of image
			$images->selectBySize();
			$array_of_images = $images->__get('array_of_images');
			
			//default image info to be shown
			if($image_id != 0)
			{
				$image = new image();
				$image->__set('image_id', $image_id);
				$image->select();
			}
			elseif(count($array_of_images) > 0)
				$image = $array_of_images[0];
		}
		//load details in view
		
		$images_array	= $array_of_images;
		$splice_array	= $images_array;
		$temp_array		= array();
		
		foreach($images_array as $key=>$image_element)
		if(file_exists(STAR_SITE_ROOT.'/Data/Images/'.$album->__get('album_folder').'/'.$album_size->__get('dimensions').'/'.$image_element->__get('filename').$image_element->__get('filename_ext')))
			$temp_array[] = $image_element;
			
		$splice_array = $temp_array;
		
		$imagesView = new imagesView();
		$imagesView->_set('array_of_albums', $array_of_albums);
		$imagesView->_set('array_of_sizes', $array_of_sizes);
		$imagesView->_set('array_of_images', $splice_array);
		$imagesView->_set('album', $album);
		$imagesView->_set('size', $album_size);
		$imagesView->_set('image', $image);
		
		$imagesView->displayContentColumn();
	}
	
//=======================================================================================================================

	public function uploadImage()
	{
		$file_array = array();
		$file_count = count($_FILES['image_file']['name']);
		
		//i just rearranged the array :)
		for($i = 0; $i < $file_count; $i++)
			$file_array[] = array(
				'name'=>$_FILES['image_file']['name'][$i],
				'type'=>$_FILES['image_file']['type'][$i],
				'tmp_name'=>$_FILES['image_file']['tmp_name'][$i],
				'error'=>$_FILES['image_file']['error'][$i],
				'size'=>$_FILES['image_file']['size'][$i]
			);
		
		foreach($file_array as $file)
		{
			if ( ($file["type"] == "image/bmp") || ($file["type"] == "image/gif") ||
				 ($file["type"] == "image/jpeg") || ($file["type"] == "image/png")
				 && ($file["size"] < 5242880 ) && ($file["error"] == 0)
				)
			{
				require_once 'Project/Code/System/Photo_Library/album_size/album_size.php';
	
				
		 		$image_filename = str_replace(' ', '_', $file["name"]);
		 		
				//select album details
				$album = new album();
				$album->__set('album_id', $_POST['album_id']);
				
				//select image size details
				$album_size = new album_size();
				$album_size->__set('size_id', $_POST['size_id']);
				$album->select();
				$album_size->select();
				
				//get file extension
				$ext = ".";
				$ext .= substr(strrchr($image_filename, '.'), 1); 
				
				
				
				//insert image data into database
				$image = new image();
				$image->__set('album_id', $album_size->__get('album_id'));
				$image->__set('size_id', $_POST['size_id']);
				$image->__set('filename', str_replace($ext,"", $image_filename));
				$image->__set('filename_ext', $ext);
				$image->__set('image_caption', '');
				
				
				//set image paths
				$original_image_path	= STAR_SITE_ROOT.'/Data/Images/'.$album->__get('album_folder').'/original/';
				$resized_image_path		= STAR_SITE_ROOT.'/Data/Images/'.$album->__get('album_folder').'/'.$album_size->__get('dimensions').'/';
				$thumbnail_image_path	= STAR_SITE_ROOT.'/Data/Images/'.$album->__get('album_folder').'/'.$album_size->__get('dimensions').'_thumb/';
				
				if(!file_exists($original_image_path.$image_filename) && !file_exists($resized_image_path.$image_filename) &&
				   !file_exists($thumbnail_image_path.$image_filename))
				{
					//save photo in original folder
					copy($file['tmp_name'], $original_image_path.$image->__get('filename').$image->__get('filename_ext'));
					
					//find the ratio to prevent the black part from showing
					$image_size	= getimagesize($file['tmp_name']);
					$width		= $image_size[0];
					$height		= $image_size[1];
					$ratio		= ($width * 1.0) / ($height * 1.0);
					
					$size_group_dimensions	= explode('x', $album_size->__get('dimensions'));
					$group_width			= $size_group_dimensions[0];
					$group_height			= $size_group_dimensions[1];
					
					require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Modules/crop/crop.php';
					
					$image_crop = new crop_uploads();
					
					//crop for image size group
					$image_crop->crop_image
					(
						$original_image_path, 
						$resized_image_path, 
						$image->__get('filename').$image->__get('filename_ext'), 
						$width,
						$height,
						$ratio,
						$group_width,
						$group_height
					);
					
					//crop for image size thumbnail
					$image_crop->crop_image
					(
						$original_image_path, 
						$thumbnail_image_path, 
						$image->__get('filename').$image->__get('filename_ext'), 
						$width,
						$height,
						$ratio,
						203,
						164
					);
					
					//insert!
					$image->insert();
				}
			}
		}
	 	
	 	$this->getImagesInAlbum($_POST['album_id'], $_POST['size_id'], $image->__get('image_id'));
	}
	
//=======================================================================================================================	

	public function editImageInfo()
	{	
	 	//select image from database
	 	$image = new image();
	 	$image->__set('image_id', $_POST['image_id']);
	 	$image->select();
	 	
	 	//get album details of image
	 	$album = new album();
	 	$album->__set('album_id', $image->__get('album_id'));
	 	$album->select();
	 		
	 	//select image size details
	 	$album_size = new album_size();
	 	$album_size->__set('size_id', $image->__get('size_id'));
	 	$album_size->select();
	 	
	 	//we decided on not being able to change the album and album size ofthe image
	 	//so the following lines were commented out
		/* //get album details of image
		$album = new album();
		$album->__set('album_id', $_POST['album_id']);
		$album->select(); */
		
		/* //select image size details
		$album_size = new album_size();
		$album_size->__set('size_id', $image->__get('size_id'));
		$album_size->select(); */

		//rename file... let's not use this yet
	 	/* //get the file format
	 	$path_info	= pathinfo($image->__get('filename'));
		$extension	= $path_info['extension'];
		$filename	=	 str_replace(' ', '_', $_POST['filename']);
		
	 	if($image->__get('filename') != $filename.'.'.$extension)
	 	{
		 	//rename the original image, resized image, and thumbnail
		 	$image_path		= 'Data/Images/'.$album->__get('album_folder').'/'.$album_size->__get('dimensions');
		 	$original_path	= 'Data/Images/'.$album->__get('album_folder').'/original';
		 	
		 	$data_handler = new dataHandler();
		 	
		 	$data_handler->rename_file($original_path, $image->__get('filename'), NULL, $filename);
		 	$data_handler->rename_file($image_path, $image->__get('filename'), NULL, $filename);
		 	$data_handler->rename_file($image_path.'_thumb', $image->__get('filename'), NULL, $filename);
		 	
		 	$image->__set('filename', $filename.'.'.$extension);
	 	} */
	 	
	 	//update image details into database
	 	//$image->__set('album_id', $_POST['album_id']);
	 	
	 	//if($_POST['album_id'] != $image->__get('album_id'));
	 	
	 	$image->__set('image_caption', stripslashes($_POST['image_caption']));
	 	
	 	
	 	require_once 'Project/Code/System/Photo_Library/image/image.php';
	 	
	 	if (!image::checkIfFilenameExist($_POST['image_filename']) && $image->__get('filename') != $_POST['image_filename'])
	 	{
				$currentFilename = $image->__get('filename').$image->__get('filename_ext');
				$newFilename = str_replace(" ", "_", $_POST['image_filename']);
				
		 		//rename the original image, resized image, and thumbnail
		 		$image_path		= 'Data/Images/'.$album->__get('album_folder').'/'.$album_size->__get('dimensions');
		 		$original_path	= 'Data/Images/'.$album->__get('album_folder').'/original';
		 		
		 		$data_handler = new dataHandler();
		 		
		 		$data_handler->rename_file($original_path, $currentFilename,NULL, $newFilename);
		 		$data_handler->rename_file($image_path, $currentFilename,NULL, $newFilename);
		 		$data_handler->rename_file($image_path.'_thumb', $currentFilename,NULL, $newFilename);
		 		
		 		$image->__set('filename', str_replace(" ", "_", $_POST['image_filename']));
	 	}
	 	else
	 	{
	 		
	 	}
	 	
		$image->update();
	 	
	 	$this->getImagesInAlbum($image->__get('album_id'), $image->__get('size_id'), $_POST['image_id']);
	}
	
//=======================================================================================================================	

	public function deleteImage()
	{	
		//select image from database
	 	$image = new image();
	 	$image->__set('image_id', $_POST['image_id']);
	 	$image->select();
	 	
		//get album details of image
		$album = new album();
		$album->__set('album_id', $image->__get('album_id'));
		$album->select();
		
		//select image size details
		$album_size = new album_size();
		$album_size->__set('size_id', $image->__get('size_id'));
		$album_size->select();
	 	
	 	//rename the original image, resized image, and thumbnail
	 	$image_path		= 'Data/Images/'.$album->__get('album_folder').'/'.$album_size->__get('dimensions');
	 	$original_path	= 'Data/Images/'.$album->__get('album_folder').'/original';
	 	
	 	$data_handler = new dataHandler();
	 	
	 	$data_handler->delete_file($original_path.'/'.$image->__get('filename').$image->__get('filename_ext'));
	 	$data_handler->delete_file($image_path.'/'.$image->__get('filename').$image->__get('filename_ext'));
	 	$data_handler->delete_file($image_path.'_thumb/'.$image->__get('filename').$image->__get('filename_ext'));
	 	
	 	$image->delete();
	 	
	 	$this->getImagesInAlbum($image->__get('album_id'), $image->__get('size_id'), 0);
	}

}