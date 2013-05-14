<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'Project/Code/System/Leads/leads_potential_hcp/potential_hcp.php';

class potential_hcps extends dbQuery
{
	private $array_of_potential_hcps;
	
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
		$sql = "SELECT
					*
				FROM
					leads_potential_hcp l
				INNER JOIN
					health_care_providers h
				ON
					l.hcp_id = h.hcp_id
				";
		
		$results	= $this->executeSQL('select_many', $sql);
		
		$this->saveResults($results);
		
		return $this->results;
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function selectAllPerLead($lead_id)
	{
		$sql = "SELECT
					potential_hcp_id,
					lead_id,
					l.hcp_id,
					status,
					position,
					s.name as seeker_name,
					h.name as hcp_name,
					h.suspended as suspended_status
				FROM
					leads_potential_hcp l
				INNER JOIN
					seekers s
				ON
					s.seeker_id = l.lead_id
				INNER JOIN
					health_care_providers h
				ON
					l.hcp_id = h.hcp_id
				WHERE
					lead_id	=	:lead_id  
					";
	
		$bindParams	= array('lead_id' => $lead_id);
	
		$results	= $this->executeSQL('select_many', $sql, $bindParams);
		$this->saveResults($results);
		
		return $results;
	}
	
//-------------------------------------------------------------------------------------------------
	public function selectAllStatusPerLead($lead_id)
	{
		$sql = "SELECT DISTINCT
						status
					FROM
						leads_potential_hcp
					WHERE
						lead_id	=	:lead_id  
						";
	
		$bindParams	= array('lead_id' => $lead_id);
	
		$results	= array();
		
		foreach($this->executeSQL('select_many', $sql, $bindParams) as $result)
			foreach($result as $value)
				$results[] = $value;
	
		return $results;
	}
//-------------------------------------------------------------------------------------------------
	
	public function selectAllPerHCP($hcp_id,$restriction = false)
	{
		$sql = "SELECT
					potential_hcp_id,
					l.lead_id,
					le.staff_id,
					l.hcp_id,
					l.status,
					position,
					s.name as seeker_name,
					h.name as hcp_name
				FROM
					leads_potential_hcp l
				INNER JOIN
					seekers s
				ON
					s.seeker_id = l.lead_id
				INNER JOIN
					leads le
				ON
					le.lead_id = l.lead_id	
				
				INNER JOIN
					health_care_providers h
				ON
					l.hcp_id = h.hcp_id
				WHERE
					l.hcp_id	=	:hcp_id 
				";
		
		if($restriction)
		{
			$sql .= "AND staff_id = ".authorization::getUserID();
		}
	
		$bindParams	= array('hcp_id' => $hcp_id);
	
		$results	= $this->executeSQL('select_many', $sql, $bindParams);
		
		$this->saveResults($results);
		
		return $this->results;
	}
	
//-------------------------------------------------------------------------------------------------
	
	private function saveResults($results)
	{
		$this->array_of_potential_hcps = array();
		
		foreach ($results as $result)
		{
			$potential_hcp = new potential_hcp();
			
			$potential_hcp->__set('potential_hcp_id', $result['potential_hcp_id']);
			$potential_hcp->__set('lead_id', $result['lead_id']);
			$potential_hcp->__set('hcp_id', $result['hcp_id']);
			$potential_hcp->__set('status', $result['status']);
			$potential_hcp->__set('position', $result['position']);
			
			if(isset($result['seeker_name']))
				$potential_hcp->__set('seeker_name', $result['seeker_name']);
			
			if(isset($result['hcp_name']))
				$potential_hcp->__set('hcp_name', $result['hcp_name']);
			
			if(isset($result['suspended_status']))
				$potential_hcp->__set('hcp_suspend_status', $result['suspended_status']);
			
			$this->array_of_potential_hcps[] = $potential_hcp;
		}
	}
	
	
}
