<?php
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';

class hcp_amenity
{
	private $amenities_category_id;
	private $amenities_category;
	private $amenity_id;
	private $amenity;
	private $hcp_id;

//-------------------------------------------------------------------------------------------------
	
	public function __get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
	
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function __set($field, $value) {
		if(property_exists($this, $field)) $this->{$field} = $value;
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function selectHcpAmenities($hcp_id)
	{
		$array_of_hcp_amenities = array();
		
		$sql = "SELECT
										*
								FROM 
									(
											amenities a
										INNER JOIN
											hcp_amenities ha
										ON
											a.amenity_id = ha.amenity_id
									)
									INNER JOIN
										amenities_categories ac
									ON
										ac.amenities_category_id = a.amenities_category_id
									WHERE
										ha.hcp_id = $hcp_id
									";
	
		$db			= new dbQuery();
		$bindParams	= array();
		$results	= $db->executeSQL('select_many', $sql, $bindParams);
		
		foreach($results as $result)
		{
			$amenity = new hcp_amenity();
			
			$amenity->__set('amenities_category_id', $result['amenities_category_id']);
			$amenity->__set('amenities_category', $result['amenities_category']);
			$amenity->__set('amenity_id', $result['amenity_id']);
			$amenity->__set('amenity', $result['amenity']);
			$amenity->__set('hcp_id', $result['hcp_id']);
			
			$array_of_hcp_amenities[] = $amenity;
		}
		
		return $array_of_hcp_amenities;
	
	}
	
	public function deleteHcpAmenity()
	{
	
		$where_array	= array('hcp_id' => $this->hcp_id);
		$sql_where		= " WHERE hcp_id = :hcp_id ";
	
		$db			= new dbQuery();
		$db->delete('hcp_amenities', $sql_where, $where_array);
	
	}
	
	public function insertHcpAmenity()
	{
		$insert_array = array(
					'hcp_id'					=> $this->hcp_id,
					'amenity_id'				=> $this->amenity_id
		);
	
		$db	= new dbQuery();
		$db->insert('hcp_amenities', $insert_array);
	}
	
	public function countAmenityPerHcp($hcp_id)
	{
		$sql = "SELECT
					COUNT(`amenity_id`) as count
				FROM 
					hcp_amenities
				WHERE
					hcp_id = :hcp_id
							";
		
		$bindParams = array('hcp_id' => $hcp_id);
		$db			= new dbQuery();
		$results	= $db->executeSQL('select_one', $sql, $bindParams);
		
		return $results;
	}
	
}