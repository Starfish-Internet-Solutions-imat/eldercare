<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'healthcare_provider.php';

class healthcare_providers extends dbQuery
{
	private $array_of_results = array();
	private $hcp_id, $status;
	
//-------------------------------------------------------------------------------------------------	

	public function __get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function __set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------	

	public function select($restrict = true)
	{
		$sql = "SELECT
					*
				FROM
					health_care_providers as hcp
				INNER JOIN 
					hcp_contact_persons cp
				ON
					hcp.contact_person_id = cp.contact_person_id
				";
		
		if ($restrict)
			$sql .= " WHERE (approved = 1 OR approved = 2) AND published = 1 AND suspended = 0";
		
		$results	= $this->executeSQL('select_many', $sql);
		
		return $this->saveResults($results);
	}
	
//-------------------------------------------------------------------------------------------------	

	public function selectMany($hcp_id = array())
	{
		$where = implode(" OR hcp_id = ", $hcp_id);
		$where = "WHERE hcp_id = $where";
		
		//echo $where; die;
		//$where = "";
		$sql = "SELECT
						h.*, z.city, z.state
					FROM
						health_care_providers h
					LEFT JOIN
						zipcodes z
					ON
						h.zipcode = z.id
					$where
					
					ORDER BY name
					";
	
		$db			= new dbQuery();
		$results	= $db->executeSQL('select_many', $sql);
	
		$this->saveResults($results);
		return $results;
	}
	
//-------------------------------------------------------------------------------------------------

	public function selectByCityOrZipCode($where_column, $where_value, $restrict = true)
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
					LOWER(z.$where_column) LIKE LOWER(:$where_column)
				";
		if ($restrict)
			$sql .= " AND h.approved = 1 AND h.published = 1 AND suspended = 0";
		
		$where_value = "%$where_value%";
		
		$bindParams	= array($where_column => $where_value);
		$results	= $this->executeSQL('select_many', $sql, $bindParams);
		
		return $this->saveResults($results);
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function selectByZipCode($where_column, $where_value, $restrict = true)
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
						z.zipcode = (:$where_column)
					";
		
		if ($restrict)
			$sql .= " AND (h.approved = 1 OR h.approved = 2) AND h.published = 1 AND h.suspended = 0";
	
		$where_value = "$where_value";
	
		$bindParams	= array($where_column => $where_value);
		$results	= $this->executeSQL('select_many', $sql, $bindParams);
	
		return $this->saveResults($results);
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function selectForSearchAutoSuggest($search_query = "", $restrict = true)
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
							z.zipcode LIKE :query
						OR
							z.state LIKE :query
						OR 
							z.city LIKE :query
						";
	
		if ($restrict)
			$sql .= " AND (h.approved = 1 OR h.approved = 2) AND h.published = 1 AND h.suspended = 0";
	
		$search_query = "%$search_query%";
		$query = "query";
	
		$bindParams	= array($query => $search_query);
		$results	= $this->executeSQL('select_many', $sql, $bindParams);
	
		 $this->saveResults($results);
		 
		 return $results;
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function selectForSearch($zipcode_id = "", $house_type = "", $pricing = "", $restrict = true)
	{
		$house_type_clause = "";
		$pricing_clause = "";
		
		if (($house_type != '') AND ($house_type != 'All'))
			$house_type_clause = " AND ht.house_type = :house_type";
		
		if ($pricing != '')
			$pricing_clause = " AND h.pricing = :pricing";
		
		$sql = "SELECT DISTINCT
								h.*, z.state, z.city
							FROM
								health_care_providers h
							";
		if ($house_type != 'All')
			$sql .= " 
							INNER JOIN 
								hcp_house_types hht
							ON
								hht.hcp_id = h.hcp_id
							INNER JOIN
								house_types ht
							ON 
								hht.house_type_id  = ht.house_type_id	";
		
		$sql .=	" INNER JOIN
								zipcodes z
							ON	
								h.zipcode = z.id							
							WHERE
								h.zipcode = :zipcode_id
							";
		
		if ($restrict)
			$sql .= " AND (h.approved = 1 OR h.approved = 2) AND h.published = 1 AND h.suspended = 0 ";
		
		
		$sql .= $house_type_clause;
		$sql .= $pricing_clause;
		
		
		$bindParam = array('zipcode_id'	=> $zipcode_id);
		
		if ($pricing != '')
			$bindParam['pricing'] 	= $pricing;
		
		if (($house_type != '') && ($house_type != 'All'))
			$bindParam['house_type'] = $house_type;
		//echo $sql; die;
		$results	= $this->executeSQL('select_many', $sql, $bindParam);
	
		$this->saveResults($results);
			
		return $results;
	}
	
	//Used in CRM
	public function listingSearch($search = array())
	{
		$sql = "SELECT DISTINCT
										h.hcp_id,
										h.name, 
										h.date_updated,
										h.date_created,
										z.state, 
										z.zipcode,
										h.pricing, 
										h.published
										
									FROM
										health_care_providers h
									LEFT JOIN 
										zipcodes z
									ON	
										h.zipcode = z.id							
									";
		
		$sql_where_clause =  ' WHERE ';
		$flag = 0;
		
		if ($search['name'] != "")
			$search['name'] = " h.name LIKE '%".$search['name']."%'";
		
		if ($search['state'] != "")
			$search['state'] = " z.state LIKE '%".$search['state']."%'";
		
		if ($search['zipcode'] != "")
			$search['zipcode'] = " z.zipcode LIKE '%".$search['zipcode']."%'";
		
		if ($search['price_to'] != 0)
			$search['price_to'] = " h.price_to LIKE '%".$search['price_to']."%'";
		else 	
			$search['price_to'] = '';
		
		if ($search['price_from'] != 0)
			$search['price_from'] = " h.price_from LIKE '%".$search['price_from']."%'";
		else
			$search['price_from'] = '';
		
		foreach($search as $field)
			if ($field != '')
			{
				$sql_where_clause .= $field.' AND ';
				$flag = 1; //flag is used to know if every field is empty
			}
			
		if ($flag === 0)
			$sql_where_clause = rtrim(' WHERE ', $sql_where_clause);
		
		$sql .= rtrim($sql_where_clause, 'AND ');
		
		$results	= $this->executeSQL('select_many', $sql);
		
		//$this->saveResults($results);
		if ($flag)
			return $results;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function saveResults($results)
	{
		$this->array_of_results = array();
		
		foreach($results as $result)
		{
			$healthcare_provider = new healthcare_provider();
			
			$healthcare_provider->__set('hcp_id', $result['hcp_id']);
			$healthcare_provider->__set('name', $result['name']);
			$healthcare_provider->__set('contact_person_name', $result['contact_person_name']);
			$healthcare_provider->__set('contact_person_position', $result['contact_person_position']);
			$healthcare_provider->__set('email', $result['email']);
			$healthcare_provider->__set('password', $result['password']);
			$healthcare_provider->__set('telephone', $result['telephone']);
			$healthcare_provider->__set('zipcode', $result['zipcode']);
			$healthcare_provider->__set('location', $result['location']);
			$healthcare_provider->__set('description', $result['description']);
			$healthcare_provider->__set('price_from', $result['price_from']);
			$healthcare_provider->__set('price_to', $result['price_to']);
			$healthcare_provider->__set('pricing', $result['pricing']);
			$healthcare_provider->__set('accommodation_type', $result['accommodation_type']);
			$healthcare_provider->__set('number_can_accommodate', $result['number_can_accommodate']);
			$healthcare_provider->__set('number_of_bedrooms', $result['number_of_bedrooms']);
			$healthcare_provider->__set('image_id', $result['image_id']);
			
			$this->array_of_results[] = $healthcare_provider;
		}
		
		return $this->array_of_results;
	}
	
//-------------------------------------------------------------------------------------------------
	public function selectNewHcp()
	{
		$sql = 'SELECT
					hcp_id,
					name,
					date_created,
					location,
					zipcode,
					price_from,
					price_to,
					published
				FROM 
					health_care_providers
				WHERE
					approved IS NULL';
		$db = new dbQuery;
		return $db->executeSQL('select_many', $sql);
	}

//-------------------------------------------------------------------------------------------------	

	public function udpateApprovedStatus()
	{
		$update_array = array(
			'approved' => $this->status
		);
			
		$where_array = array(
					'hcp_id' => $this->hcp_id
		);
	
		$where = " WHERE hcp_id=:hcp_id ";
	
		$db = new dbQuery();
		$db->update('health_care_providers', $update_array, $where, $where_array );
	}
	
	public function udpatePublishedStatus()
	{
		$update_array = array(
				'published' => 1
		);
			
		$where_array = array(
						'hcp_id' => $this->hcp_id
		);
	
		$where = " WHERE hcp_id=:hcp_id ";
	
		$db = new dbQuery();
		$db->update('health_care_providers', $update_array, $where, $where_array );
	}
	
//-------------------------------------------------------------------------------------------------

	public function selectNewHCPs()
	{
		$sql = 'SELECT
							hcp.hcp_id,
							hcp.name,
							hcp.date_created,
							hcp.location,
							hcp.published,
							hcp.pricing,
							z.zipcode,
							z.city,
							z.state
						FROM 
							health_care_providers hcp
						LEFT JOIN	
							zipcodes z
						ON	
							hcp.zipcode = z.id
						WHERE
							hcp.approved IS NULL';
		$db = new dbQuery;
		return $db->executeSQL('select_many', $sql);
	}
	
	//-------------------------------------------------------------------------------------------------
	public function selectInfoForSms($hcp_id)
	{
		$sql = "SELECT
					hcp.name,
					cp.contact_person_name,
					cp.telephone,
					cp.email,
					hcp.location,
					z.city,
					z.state
				FROM 
					health_care_providers hcp
				INNER JOIN
					hcp_contact_persons cp
				ON
					hcp.contact_person_id = cp.contact_person_id
				INNER JOIN	
					zipcodes z
				ON	
					hcp.zipcode = z.id
				WHERE
					hcp_id = {$hcp_id} ";
		$db = new dbQuery;
		return $db->executeSQL('select_one', $sql);
	}
	
	
}


?>