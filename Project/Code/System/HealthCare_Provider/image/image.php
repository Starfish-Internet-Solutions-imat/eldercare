<?php
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';

class hcp_image
{
	private $image_id;
	private $hcp_id;
	private $filename;
	
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
		$sql = "SELECT
					*
				FROM
					hcp_images
				WHERE
					image_id = :image_id
				";
		
		$db			= new dbQuery();
		$bindParams	= array('image_id' => $this->image_id);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
				
		$this->hcp_id	= $result['hcp_id'];
		$this->filename		= $result['filename'];
	}
	
//=================================================================================================
	
	public static function selectFilename($image_id)
	{
		$sql = "SELECT
					filename
				FROM
					hcp_images
				WHERE
					image_id = :image_id
				";
		
		$db			= new dbQuery();
		$bindParams	= array('image_id' => $image_id);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
				
		return $result['filename'];
	}
	
//=================================================================================================
	
	public function insert()
	{
		$insert_array = array (
			'hcp_id'	=> $this->hcp_id,
			'filename'					=> $this->filename
		);
		
		$db			= new dbQuery();
		$this->provider_id = $db->insert('hcp_images', $insert_array);
	}
	
//=================================================================================================
	
	public function update()
	{
		$update_array = array (
			'hcp_id'	=> $this->hcp_id,
			'filename'		=> $this->filename
		);
		
		$where_array	= array('image_id' => $this->image_id);
		$sql_where		=	" WHERE image_id = :image_id ";
		
		$db			= new dbQuery();
		$db->update('hcp_images', $update_array, $sql_where, $where_array);
	}
	
//=================================================================================================
	
	public function delete()
	{
		$where_array	= array('image_id' => $this->image_id);
		$sql_where		=	" WHERE image_id = :image_id ";
		
		$db			= new dbQuery();
		$db->delete('hcp_images', $sql_where, $where_array);
	}

//=================================================================================================
	public function selectFileOfNameHCPImage()
	{
		$sql = "SELECT
						hi.filename
				FROM
					hcp_images as hi
				INNER JOIN 
					health_care_providers as hcp
				ON
					hi.image_id = hcp.image_id
				WHERE
					hcp.hcp_id = :hcp_id
						";
		
		$db			= new dbQuery();
		$bindParams	= array('hcp_id' => $this->hcp_id);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
		
		return $result['filename'];
	}
}




