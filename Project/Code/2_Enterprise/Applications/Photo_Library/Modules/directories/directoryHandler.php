<?php
class directoryHandler
{
	public function rename_directory($old_path, $new_path)
	{
		$data_handler = new dataHandler();
		$data_handler->create_directory($new_path);
		
		$directory_listing = $this->files_list_recurse($old_path.'/');
		
		foreach($directory_listing as $directory)
		{
		//get album sizes in the album
			$path_segments = explode('/', $directory[0]);
			//create those directories in the new album
			$data_handler->create_directory($new_path.'/'.$path_segments[3]);
			
			//then copy the files
			foreach($directory as $file)
			{
				$filename_segments	= explode('/', $file);
				$filename 			= $filename_segments[4];
				
				$data_handler->copy_file($old_path.'/'.$path_segments[3], $filename, $new_path.'/'.$path_segments[3]);
			}
		}
		
		$data_handler->delete_directory_recurse_svn($old_path.'/');
	}
	
//=================================================================================================
	
	public function rename_thumb_directory($old_path, $new_path)
	{
		$data_handler = new dataHandler();
		$data_handler->create_directory($new_path);
		
		$directory_listing = $this->files_list_recurse($old_path.'/');

		foreach($directory_listing as $file)
		{
			$filename_segments	= explode('/', $directory_listing[0]);
			$filename 			= $filename_segments[4];
			
			$data_handler->copy_file($old_path, $filename, $new_path);
		}
	
		$data_handler->delete_directory_recurse_svn($old_path.'/');
	}
	
//=================================================================================================
	
	public function resize_directory($album_folder, $old_width, $old_height, $new_width, $new_height)
	{
		require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Modules/crop/crop.php';
		
		$data_handler	= new dataHandler();
		$image_crop		= new crop_uploads();
		
		$directory = $this->files_list_recurse($album_folder.$old_width.'x'.$old_height.'/');
		
		$data_handler->create_directory($album_folder.'/'.$new_width.'x'.$new_height);
		//$data_handler->create_directory($album_folder.'/'.$new_width.'x'.$new_height.'/'.'original');
		//$data_handler->create_directory($album_folder.'/'.$new_width.'x'.$new_height.'/'.'_thumb');
		//then copy the files
		foreach($directory as $file)
		{
			$filename_segments	= explode('/', $directory[2]);
			var_dump($directory[2]);
			$filename 			= $filename_segments[4];
			
			$data_handler->copy_file($album_folder.$old_width.'x'.$old_height, $filename, $album_folder.'/'.$new_width.'x'.$new_height);
			
			$image_size = getimagesize($album_folder.$new_width.'x'.$new_height.'/'.$filename);
			
			$ratio = ($image_size[0] * 1.0) / ($image_size[1] * 1.0);
			
			//crop for image size group
			$image_crop->crop_image
			(
				$album_folder.$old_width.'x'.$old_height.'/', 
				$album_folder.$new_width.'x'.$new_height.'/', 
				$filename, 
				$old_width,
				$old_height,
				$ratio,
				$new_width,
				$new_height
			);
		}
		
		//$data_handler->delete_directory_recurse_svn($album_folder.$old_width.'x'.$old_height.'/');
	}
	
//=================================================================================================
	
	public function files_list_recurse($folder_path, $array_of_files = array())
	{	
		if (is_dir($folder_path))
		{
			$files = glob($folder_path.'*');
			
			foreach ($files as $file)
			{
				if ($file != $folder_path."." && $file != $folder_path."..")
				{
					if (filetype($file) == "dir") 
					{
						$recursed_files = $this->files_list_recurse($file.'/');
						
						if(count($recursed_files) > 0)
							$array_of_files[] = $recursed_files; 
					}
					
					else
					{
						$array_of_files[] = $file;
					}
				}
			}
		}
		
		return $array_of_files;
	}
}
?>