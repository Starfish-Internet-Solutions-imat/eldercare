<?php
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'image.php';

class hcp_images
{
	private $hcp_id;
	private $array_of_images;
	
//-------------------------------------------------------------------------------------------------	

	public function __get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function __set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//=================================================================================================
	
	public function select()
	{
		$this->array_of_images = array();
		
		$sql = "SELECT
					*
				FROM
					hcp_images
				WHERE
					hcp_id = :hcp_id
				";
		
		$db			= new dbQuery();
		$bindParams	= array('hcp_id' => $this->hcp_id);
		$results		= $db->executeSQL('select_many', $sql, $bindParams);

		foreach($results as $result)
		{
			$image = new hcp_image();
			
			$image->__set('image_id', $result['image_id']);
			$image->__set('hcp_id', $result['hcp_id']);
			$image->__set('filename', $result['filename']);
			
			$this->array_of_images[] = $image;
		}
		
		return $this->array_of_images;
	}
	
//---------------------------------------------------------------------------------------------	
	
	public function selectFileName()
	{
		$this->array_of_images = array();
		
		$sql = "SELECT
							filename
						FROM
							hcp_images
						WHERE
							hcp_id = :hcp_id
						";
		
		$db			= new dbQuery();
		$bindParams	= array('hcp_id' => $this->hcp_id);
		$results		= $db->executeSQL('select_many', $sql, $bindParams);
		return $results;
	}
	
}