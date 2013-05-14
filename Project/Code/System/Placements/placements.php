<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'placement.php';

class placements
{
	private $array_of_placements;
	
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
					placements
				";
		
		$db			= new dbQuery();
		$results	= $db->executeSQL('select_many', $sql);
		
		foreach($results as $result)
		{
			$placement = new placement();
			
			$placement->__set('placement_id', $result['placement_id']);
			$placement->__set('seeker_id', $result['seeker_id']);
			$placement->__set('hcp_id', $result['hcp_id']);
			$placement->__set('invoice_number', $result['invoice_number']);
			$placement->__set('status', $result['status']);
			
			$this->array_of_placements[] = $placements;
		}
		
		return $this->$results;
	}
	
	public function selectPlacements()
	{
		$sql = 'SELECT 
					p.placement_id,
					p.date_and_time,
					p.status,
					p.invoice_number,
					p.potential_hcp_id,
					l.hcp_id,
					l.lead_id,
					s.name as seekers_name,
					h.name as hcp_name,
					cp.contact_person_name
				FROM
					placements as p
				INNER JOIN leads_potential_hcp as l
					ON p.potential_hcp_id = l.potential_hcp_id
				INNER JOIN health_care_providers as h
					ON l.hcp_id = h.hcp_id
				INNER JOIN hcp_contact_persons as cp
					ON h.contact_person_id = cp.contact_person_id
				INNER JOIN seekers as s
					ON l.lead_id = s.seeker_id
				INNER JOIN leads as le
					ON le.lead_id = l.lead_id
				WHERE 
					le.status != "closed"
		';
		
		$db = new dbQuery();
		$result = $db->executeSQL('select_many', $sql);
		return $result;		
	}
	
	
	public function selectPlacementsByHcp($hcp_id)
	{
		$sql = 'SELECT
							p.placement_id,
							p.date_and_time,
							p.status,
							p.invoice_number,
							p.potential_hcp_id,
							l.hcp_id,
							l.lead_id,
							s.name as seekers_name,
							h.name as hcp_name,
							cp.contact_person_name
						FROM
							placements as p
						INNER JOIN leads_potential_hcp as l
							ON p.potential_hcp_id = l.potential_hcp_id
						INNER JOIN health_care_providers as h
							ON l.hcp_id = h.hcp_id
						INNER JOIN hcp_contact_persons as cp
							ON h.contact_person_id = cp.contact_person_id
						INNER JOIN seekers as s
							ON l.lead_id = s.seeker_id
						WHERE
							l.hcp_id = '.$hcp_id.'';
				
		$db = new dbQuery();
		$result = $db->executeSQL('select_many', $sql);
		return $result;
	}
	
	
	public function selectPlacementsViaConsultant($staff_id)
	{
		$sql = "SELECT 
					p.placement_id,
					p.date_and_time,
					p.status,
					p.invoice_number,
					p.potential_hcp_id,
					l.hcp_id,
					l.lead_id,
					s.name as seekers_name,
					h.name as hcp_name,
					cp.contact_person_name
				FROM
					placements as p
				INNER JOIN leads_potential_hcp as l
					ON p.potential_hcp_id = l.potential_hcp_id
				INNER JOIN health_care_providers as h
					ON l.hcp_id = h.hcp_id
				INNER JOIN seekers as s
					ON l.lead_id = s.seeker_id
				INNER JOIN leads as le
					ON le.lead_id = l.lead_id
				INNER JOIN hcp_contact_persons as cp
					ON h.contact_person_id = cp.contact_person_id
				WHERE le.staff_id = $staff_id
				AND
					le.status != 'closed'
		";
		$db = new dbQuery();
		$result = $db->executeSQL('select_many', $sql);
		return $result;
	}
	
	public function selectPlacementsAdmin()
	{
		$sql = "SELECT
						p.placement_id,
						p.date_and_time,
						p.status,
						p.invoice_number,
						p.potential_hcp_id,
						l.hcp_id,
						cp.contact_person_name,
						l.lead_id,
						s.name as seekers_name,
						h.name as hcp_name
					FROM
						placements as p
					INNER JOIN leads_potential_hcp as l
						ON p.potential_hcp_id = l.potential_hcp_id
					INNER JOIN health_care_providers as h
						ON l.hcp_id = h.hcp_id
					INNER JOIN seekers as s
						ON l.lead_id = s.seeker_id
					INNER JOIN leads as le
						ON le.lead_id = l.lead_id
					INNER JOIN hcp_contact_persons as cp
						ON h.contact_person_id = cp.contact_person_id
					WHERE
						le.status != 'closed'
			";
		$db = new dbQuery();
		$result = $db->executeSQL('select_many', $sql);
		return $result;
	}
	
	
	
	
	
}
