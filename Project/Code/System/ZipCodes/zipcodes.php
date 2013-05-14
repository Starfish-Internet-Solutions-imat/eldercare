<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'zipcode.php';

class zipcodes extends dbQuery
{
	private $array_of_results = array();
	
//-------------------------------------------------------------------------------------------------	

	public function __get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function __set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------	

	public function selectZipCodeSearch($zipcode)
	{
		$sql = "SELECT 
					*
				FROM
					zipcodes z
				INNER JOIN
					health_care_providers h
				ON
					h.zipcode = z.id
				WHERE
					z.zipcode LIKE :zipcode
				GROUP BY
					city
				";
		
		$zipcode = "$zipcode%";
		
		$db			= new dbQuery();
		$bindParams	= array('zipcode' => $zipcode);
		$results	= $db->executeSQL('select_many', $sql, $bindParams);
		
		return $this->saveResults($results);
	}
	
//-------------------------------------------------------------------------------------------------	

	public static function selectAllZipCodes()
	{
		$sql = "SELECT DISTINCT
					z.zipcode
				FROM
					zipcodes z
				INNER JOIN
					health_care_providers h
				ON
					h.zipcode = z.id
				";
		
		$db			= new dbQuery();
		$results	= $db->executeSQL('select_many', $sql);
		
		return $results;
	}
	
//-------------------------------------------------------------------------------------------------	

	public static function selectAllStates()
	{
		$sql = "SELECT DISTINCT
					z.state
				FROM
					zipcodes z
				INNER JOIN
					health_care_providers h
				ON
					h.zipcode = z.id
				";
		
		$db			= new dbQuery();
		$results	= $db->executeSQL('select_many', $sql);
		
		return $results;
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function getProviderPerZipcode($zipcode, $restrict = true)
	{
		$sql = "SELECT
						*
					FROM
						health_care_providers h
					INNER JOIN
						zipcodes z
					ON
						h.zipcode = z.id
					WHERE
						z.zipcode = :zipcode
					";
		
		if ($restrict)
			$sql .= " AND (h.approved = 1 OR h.approved = 2) AND h.published = 1 AND h.suspended = 0 ";
	
		$zipcode = "$zipcode";
	
		$db			= new dbQuery();
		$bindParams	= array('zipcode' => $zipcode);
		$results	= $db->executeSQL('select_many', $sql, $bindParams);
	
		$this->saveResults($results);
		return $results;
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function getProviderPerZipcodeId($zipcode_id, $restrict = true)
	{
		$sql = "SELECT
							*
						FROM
							health_care_providers h
						INNER JOIN
							zipcodes z
						ON
							h.zipcode = z.id
						WHERE
							h.zipcode = :zipcode
						";
	
		if ($restrict)
		$sql .= " AND (h.approved = 1 OR h.approved = 2) AND h.published = 1 AND h.suspended = 0 ";
	
		$zipcode = "$zipcode_id";
	
		$db			= new dbQuery();
		$bindParams	= array('zipcode' => $zipcode);
		$results	= $db->executeSQL('select_many', $sql, $bindParams);
	
		$this->saveResults($results);
		return $results;
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function getProviderPerStateCity($state, $city, $house_type, $restrict = true)
	{
		$sql = "SELECT DISTINCT
							h.*, z.state, z.city
						FROM
							health_care_providers h
						INNER JOIN
							hcp_house_types hht
						ON
							hht.hcp_id = h.hcp_id
						INNER JOIN
							house_types ht
						ON
							ht.house_type_id = hht.house_type_id
						INNER JOIN
							zipcodes z
						ON
							h.zipcode = z.id
						WHERE
							z.city = :city
						AND
							z.state = :state
						AND
							ht.house_type = :house
						";
		
		if ($restrict)
			$sql .= " AND (h.approved = 1 OR h.approved = 2) AND h.published = 1 AND h.suspended = 0 ";
		
		$bindParams	= array('city' => $city, 'state' => $state, 'house' => $house_type);
		$results	= $this->executeSQL('select_many', $sql, $bindParams);
		
		$this->saveResults($results);
		
		return $results;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function selectCityOrStateSearch($select, $where_column, $where_value)
	{
		$sql = "SELECT DISTINCT
					$select
				FROM
					zipcodes z
				INNER JOIN
				(
						health_care_providers h
					INNER JOIN
					(
							hcp_house_types hht
						INNER JOIN
							house_types ht
						ON
							ht.house_type_id = hht.house_type_id
					)
					ON
						h.hcp_id = hht.hcp_id
				)
				ON
					h.zipcode = z.id
				WHERE
					LOWER($where_column) LIKE LOWER(:$where_column)
					
					AND hht.house_type_id = 1
					
					AND (h.approved = 1 OR h.approved = 2) AND h.published = 1 AND h.suspended = 0
					
				ORDER BY
					$select ASC
				";
		
		$where_value = "%$where_value%";
		$db			= new dbQuery();
		$bindParams	= array($where_column => $where_value);
		$results	= $db->executeSQL('select_many', $sql, $bindParams);
			
		$this->saveResults($results);
		
		return $results;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function selectCities()
	{
		$sql = "SELECT DISTINCT
					city
				FROM
					zipcodes z
				INNER JOIN
					health_care_providers h
				ON
					h.zipcode = z.id
				GROUP BY
					city
				";
		
		$results	= $this->executeSQL('select_many', $sql);
			
		return $this->saveResults($results);
	}
	
//-------------------------------------------------------------------------------------------------	

	public function selectStates($restrict = true)
	{
		$where = "";
		
		if ($restrict)
			$where = " WHERE (h.approved = 1 OR h.approved = 2) AND h.published = 1 AND h.suspended = 0 ";
		
		$sql = "SELECT DISTINCT
					state
				FROM
					zipcodes z
				INNER JOIN
					health_care_providers h
				ON
					h.zipcode = z.id
				INNER JOIN
					hcp_house_types hht
				ON
					hht.hcp_id = h.hcp_id 
				$where
				AND 
					hht.house_type_id = 1
				AND
			    	 z.id != 43630
				GROUP BY
					state
				";
		//print $sql; die;
		$results	= $this->executeSQL('select_many', $sql);
		
		$this->saveResults($results);
		return $results;
	}
	
//-------------------------------------------------------------------------------------------------	

	private function saveResults($results)
	{
		$this->array_of_results = array();
		
		foreach($results as $result)
		{
			$zipcode = new zipcode();
			
			if(isset($result['zipcode']))
				$zipcode->__set('zipcode', $result['zipcode']);
			
			if(isset($result['state']))
				$zipcode->__set('state', $result['state']);
			
			if(isset($result['city']))
				$zipcode->__set('city', $result['city']);
			
			if(isset($result['county']))
				$zipcode->__set('county', $result['county']);
			
			$this->array_of_results[] = $zipcode;
		}
		
		return $this->array_of_results;
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function selectLike($zip_code)
	{
		
		$sql = "SELECT
						*
					FROM
						zipcodes
					WHERE 
						zipcode LIKE '$zip_code%'
					AND id != 43630
					LIMIT 10
				";
	
	
		$db			= new dbQuery();
		$results	= $db->executeSQL('select_many', $sql);
	
		return $results;
	}

//-------------------------------------------------------------------------------------------------
	
	public function selectExactlyById($zip_code)
	{
		$sql = "SELECT
							*
						FROM
							zipcodes
						WHERE 
							id = $zip_code
					";
	
	
		$db			= new dbQuery();
		$results	= $db->executeSQL('select_one', $sql);
	
		return$results ;
	}
	
}

?>