<?php
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'amenity.php';

class hcp_amenities
{
	
	public function selectAllamenities()
	{
		$sql = "SELECT
								*
							FROM
								amenities a
							INNER JOIN
								amenities_categories ac
							ON
								a.amenities_category_id = ac.amenities_category_id
							ORDER BY
								a.amenities_category_id ASC, amenity ASC
							";
		
		$db			= new dbQuery();
		$bindParams	= array();
		$result		= $db->executeSQL('select_many', $sql, $bindParams);

		return $result;
		
	}
	
	public function selectAllamenitiesCategories()
	{
		$sql = "SELECT
									*
							FROM
								amenities_categories
								";
	
		$db			= new dbQuery();
		$bindParams	= array();
		$result		= $db->executeSQL('select_many', $sql, $bindParams);
	
		return $result;
	
	}
	
	public function selectAllHcpAmenities()
	{
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
									";
	
		$db			= new dbQuery();
		$bindParams	= array();
		$result		= $db->executeSQL('select_many', $sql, $bindParams);
	
		return $result;
	
	}
	
	public function selectAllHcpamenitiesPerCategory($amenity_category)
	{
		$sql = "SELECT
											*
									FROM 
												amenities a
										INNER JOIN
											amenities_categories ac
										ON
											ac.amenities_category_id = a.amenities_category_id
										WHERE
											amenity_category = $amenity_category
										";
	
		$db			= new dbQuery();
		$bindParams	= array();
		$result		= $db->executeSQL('select_many', $sql, $bindParams);
	
		return $result;
	
	}
	
}