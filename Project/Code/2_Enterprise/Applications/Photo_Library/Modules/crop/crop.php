<?php
class crop_uploads
{
	public function crop_image($original_image_path, $resized_image_path, $image_filename,
		$original_width, $original_height, $ratio, $group_width, $group_height)
	{	
		$new_width	= 0;
		$new_height = 0;
			
		//the new dimensions follow whichever is smaller (width or height)
		//and then follow the ratio of the original image
		if($original_width > $original_height)
		{
			$new_height = $group_height;
			$new_width	= $group_height * $ratio;
		}
		else
		{
			$new_width	= $group_width;
			$new_height = $group_width / $ratio;
		}
		
		require_once FILE_ACCESS_CORE_CODE.'/Objects/Image/imageCrop.php';
			
		$image_crop = new crop();
		
		$image_crop->CropImg
		(
			$original_image_path,
			$image_filename,
			$resized_image_path,
			$image_filename,
			0, 0,
			$original_width, $original_height,
			$new_width, $new_height
		);
	}
}
?>