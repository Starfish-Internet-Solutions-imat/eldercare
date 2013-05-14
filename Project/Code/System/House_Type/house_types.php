<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';

class house_types
{
	
	private $house_type;
	private $house_type_id;
	private $hcp_id;
	
//-------------------------------------------------------------------------------------------------	

	public function __get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function __set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------
	
	public function select()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
				
			$sql = "SELECT
								house_type, house_type_id
							FROM
								house_types
					";
				
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->execute();
				
			$result = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
			
			return $result;
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
		
		
	}
	
	public static function selectHcpHouseType($hcp_id)
	{
		$sql = "SELECT
					house_type_id
				FROM
					hcp_house_types
				WHERE
					hcp_id = $hcp_id
				";
		
		$db			= new dbQuery();
		$results	= $db->executeSQL('select_many', $sql);
		
		return $results;
	
	}
	
	public static function selectHcpHouseTypeArray($hcp_id)
	{
		//$where = implode(" OR hcp_id = ", $hcp_id);
		
		$sql = "SELECT
						ht.house_type
					FROM
						house_types ht
						
					LEFT JOIN
						hcp_house_types hht
					ON
						ht.house_type_id = hht.house_type_id
					WHERE
						hcp_id = $hcp_id
					";
	
		$db			= new dbQuery();
		$results	= $db->executeSQL('select_many', $sql);
	
		return $results;
	
	}
	
	public static function selectHCPHouseTypeName($hcp_id)
	{
		$sql = "SELECT
					house_type
				FROM
					hcp_house_types hht
				JOIN
					house_types ht
				ON
					hht.house_type_id = ht.house_type_id
				WHERE
					hcp_id = $hcp_id
				";
		
		$db			= new dbQuery();
		$results	= $db->executeSQL('select_many', $sql);
		
		$house_types = array();
		
		foreach($results as $result)
			$house_types[] = $result['house_type'];
		
		return $house_types;
	
	}
	
	public function countHcpHouseType($hcp_id)
	{
		$sql = "SELECT
							COUNT(house_type_id)
						FROM
							hcp_house_types
						WHERE
							hcp_id = :hcp_id
						";
		
		$bindParams = array('hcp_id' => $hcp_id);
		$db			= new dbQuery();
		$results	= $db->executeSQL('select_many', $sql, $bindParams);
		
		return $results;
	}

//================================================================================================//
	
	/* public function updateHcpHouseType()
	{
		$update_array = array(
			'name'						=> $this->name,
			'contact_person_name'		=> $this->contact_person_name
			
		);
		
		$where_array	= array('hcp_id' => $this->hcp_id);
		$sql_where		=	" WHERE hcp_id = :hcp_id ";
		
		$db			= new dbQuery();
		$db->update('hcp_house_types', $update_array, $sql_where, $where_array);
	}
	 */
//================================================================================================//
	
	public function insertHcpHouseType()
	{
		$insert_array = array(
				'hcp_id'					=> $this->hcp_id,
				'house_type_id'				=> $this->house_type_id
		);
		
		//print_r($insert_array);die;
	
		$db	= new dbQuery();
		$db->insert('hcp_house_types', $insert_array);
	}
	
	public function deleteHcpHouseType()
	{
		
		$where_array	= array('hcp_id' => $this->hcp_id);
		$sql_where		= " WHERE hcp_id = :hcp_id ";
		
		$db			= new dbQuery();
		$db->delete('hcp_house_types', $sql_where, $where_array);
		
	}
	
	public function updateHcpHouseType()
	{
		
		$this->insertHcpHouseType();
	}
	
	public static function getHouseTypeID($house_type = "", $column = "ID")
	{
		
		if ($column === "TYPE")
		{
			$sql = "SELECT
							house_type as housing_type
						FROM
							house_types
						WHERE
							house_type_id = :house_type";
			
		}
		elseif ($column === "ID")
		{
			$sql = "SELECT
								house_type_id as housing_type
							FROM
								house_types
							WHERE
								house_type = :house_type";
		}
		
		$bindParams	= array('house_type' => $house_type);
		$db			= new dbQuery();
		
		$results	= $db->executeSQL('select_one', $sql, $bindParams);
		
		return $results;
	}
	
	
}

