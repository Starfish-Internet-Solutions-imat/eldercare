<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';

class hcp_placements extends dbQuery
{
	private $placement_id;
	private $seeker_id;
	private $hcp_id;
	private $seeker_name;
	private $hcp_name;
	private $date_and_time;
	private $invoice_number;
	private $status;
	
	private $select_statment;
	
	public function __construct()
	{
		$this->select_statment =
			"SELECT
				p.placement_id,
				p.seeker_id,
				p.hcp_id,
				s.name as seeker_name,
				h.name as hcp_name,
				p.date_and_time,
				p.invoice_number,
				p.status
			FROM
				placements p
			INNER JOIN
				seekers s
			ON
				p.seeker_id = s.seeker_id
			INNER JOIN
				health_care_providers h
			ON
				p.hcp_id = h.hcp_id
			INNER JOIN
				leads l
			ON
				s.seeker_id = l.lead_id
				";
	}
	
//-------------------------------------------------------------------------------------------------	

	public function __get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function __set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------

	public function selectByStaff($staff_id)
	{
		$array_of_placements = array();
		
		$sql = "$this->select_statment
				WHERE
					l.staff_id = :staff_id
				";
		
		$bindParams	= array(
			'staff_id'	=> $staff_id
		);
		
		$results	= $this->executeSQL('select_many', $sql, $bindParams);
		
		return $this->saveResults($results);
	}
	
//-------------------------------------------------------------------------------------------------

	public function selectByHCP($hcp_id)
	{
		$array_of_placements = array();
		$sql = "$this->select_statment
				WHERE
					p.hcp_id = $hcp_id
				";
		
		$results	= $this->executeSQL('select_many', $sql);
		return $this->saveResults($results);
	}
	
//-------------------------------------------------------------------------------------------------

	private function saveResults($results)
	{
		$array_of_placements = array();
		
		foreach($results as $result)
		{
			$hcp_placement = new hcp_placements();
			
			$hcp_placement->__set('placement_id', $result['placement_id']);
			$hcp_placement->__set('seeker_id', $result['seeker_id']);
			$hcp_placement->__set('hcp_id', $result['hcp_id']);
			$hcp_placement->__set('hcp_name', $result['hcp_name']);
			$hcp_placement->__set('seeker_name', $result['seeker_name']);
			$hcp_placement->__set('date_and_time', $result['date_and_time']);
			$hcp_placement->__set('invoice_number', $result['invoice_number']);
			$hcp_placement->__set('status', $result['status']);
			
			$array_of_placements[] = $hcp_placement;
		}
		
		return $array_of_placements;
	}
	
	
	
}
