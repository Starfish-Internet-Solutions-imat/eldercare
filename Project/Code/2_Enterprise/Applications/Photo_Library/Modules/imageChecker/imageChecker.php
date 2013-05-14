<?php
require_once 'Project/Code/2_Enterprise/Applications/Photo_Library/Modules/directories/directoryHandler.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/DataHandler/dataHandler.php';

class imageChecker
{
	public static function checkIfImageIsUsed($image_id)
	{
		$directory_handler = new directoryHandler();
		$files = $directory_handler->files_list_recurse(STAR_SITE_ROOT.'/Data/1_Website/Content/Pages');
		$files = self::xmlDirsRecurse($files);
		
		foreach($files as $file)
		{
			$data_handler = new dataHandler();
			$xml_obj = $data_handler->loadDataSimpleXML($file);
			
			$xml = $xml_obj->xpath('//image_id[text()="'.$image_id.'"]');
			
			if(count($xml) > 0) return TRUE;
		}
		
		return FALSE;
	}
	
//-------------------------------------------------------------------------------------------------	
	
	private static function xmlDirsRecurse($files_list, $images_array = array())
	{
		foreach($files_list as $file)
		{
			if(is_array($file))
			{
				$images_list = self::xmlDirsRecurse($file);
				$images_array = array_merge($images_list, $images_array);
			}
			
			elseif(strpos($file, 'data.xml') !== FALSE)
			{
				$images_array[]	= $file;
			}
		}

		return $images_array;
	}
	
//-------------------------------------------------------------------------------------------------
}
?>