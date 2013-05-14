<?php
class xmlHandler
{
	public function transfer_xml_to_array($xmlObj, $xml_array = array())
	{
		if(count($xmlObj->children()) > 0)
			foreach($xmlObj->children() as $child)
			{
				$xml_array = $this->transfer_xml_to_array($child, $xml_array);
		  	}
		  	
		else
			$xml_array[] = (string)$xmlObj;
		
		return $xml_array;
	}
}
?>