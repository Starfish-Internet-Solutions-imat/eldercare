<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/Pagination/pagination.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'lead.php';

class leads extends dbQuery
{
	private $array_of_leads;
	
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
					leads l
				INNER JOIN
					seekers s
				ON
					l.lead_id = s.seeker_id
				";
		
		$results	= $this->executeSQL('select_many', $sql);
		
		foreach($results as $result)
		{
			$lead = new lead();
			
			$lead->__set('lead_id', $result['lead_id']);
			$lead->__set('staff_id', $result['staff_id']);
			$lead->__set('date_of_inquiry', $result['date_of_inquiry']);
			$lead->__set('status', $result['status']);
			$lead->__set('urgent', $result['urgent']);
			
			$this->array_of_leads[] = $lead;
		}
		
		return $this->results;
	}
	
	public function selectWithSeeker($restriction = false, $where_clause = '')
	{
		$sql = "SELECT
						l.lead_id,
						l.staff_id,
						l.date_of_inquiry,
						l.status,
						l.urgent,
						s.name,
						s.relationship_to_patient,
						ht.house_type_id,
						ht.house_type,
						z.city,
						z.state
					FROM
						leads l
					INNER JOIN
						seekers s
					ON 
						l.lead_id=s.seeker_id
					INNER JOIN
						house_types ht
					ON
						s.house_type=ht.house_type_id
					LEFT JOIN
						zipcodes z
					ON
						s.zipcode = z.id
					";
		
		if($restriction)
			$sql .= " WHERE staff_id = ".authorization::getUserID();
		
		if($restriction && !empty($where_clause))
			$sql .= " AND ".$where_clause;
		
		elseif(!empty($where_clause))
			$sql .= " WHERE ".$where_clause;
		
		$sql .= " ORDER BY l.date_of_inquiry DESC ";
		
		//print($sql);die;
		$results = $this->executeSQL('select_many', $sql);
	
		foreach($results as $result)
		{
			$lead = new lead();
	
			$lead->__set('lead_id', $result['lead_id']);
			$lead->__set('staff_id', $result['staff_id']);
			$lead->__set('date_of_inquiry', $result['date_of_inquiry']);
			$lead->__set('status', $result['status']);
			$lead->__set('areastate', $result['city'].', '.$result['state']);
			$lead->__set('urgent', $result['urgent']);
			$lead->__set('name', $result['name']);
			$lead->__set('house_type', $result['house_type']);
			$lead->__set('relationship', $result['relationship_to_patient']);
	
			$this->array_of_leads[] = $lead;
		}
	
		return $results;
	}
	
	public function selectColdNew()
	{
		$sql = "SELECT
				l.lead_id,
				l.staff_id,
				l.date_of_inquiry,
				l.status,
				l.urgent,
				s.name,
				z.city,
				z.state,
				s.relationship_to_patient,
				ht.house_type_id,
				ht.house_type
			FROM
				leads l
			INNER JOIN
				seekers s
			ON 
				l.lead_id=s.seeker_id
			INNER JOIN
				house_types ht
			ON
				s.house_type=ht.house_type_id
			LEFT JOIN
					zipcodes z
				ON
					s.zipcode = z.id
					WHERE
						status = 'cold_new'
					";
		
		$results	= $this->executeSQL('select_many', $sql);
		
		foreach($results as $result)
		{
			$lead = new lead();
		
			$lead->__set('lead_id', $result['lead_id']);
			$lead->__set('staff_id', $result['staff_id']);
			$lead->__set('date_of_inquiry', $result['date_of_inquiry']);
			$lead->__set('status', $result['status']);
			$lead->__set('urgent', $result['urgent']);
			$lead->__set('name', $result['name']);
			$lead->__set('house_type', $result['house_type']);
			$lead->__set('relationship', $result['relationship_to_patient']);
		
			$this->array_of_leads[] = $lead;
		}
		
		return $results;
	}
	
	public function selectWithSeekerViaStaff()
	{
		$sql = "SELECT
					l.lead_id,
					l.staff_id,
					l.date_of_inquiry,
					l.status,
					l.urgent,
					s.name,
					s.relationship_to_patient,
					ht.house_type_id,
					ht.house_type
				FROM
					leads l
				INNER JOIN
					seekers s
				ON 
					l.lead_id=s.seeker_id
				INNER JOIN
					house_types ht
				ON
					s.house_type=ht.house_type_id
						WHERE
							staff_id = '".authorization::getUserID()."';
						";
	
		$results	= $this->executeSQL('select_many', $sql);
	
		foreach($results as $result)
		{
			$lead = new lead();
	
			$lead->__set('lead_id', $result['lead_id']);
			$lead->__set('staff_id', $result['staff_id']);
			$lead->__set('date_of_inquiry', $result['date_of_inquiry']);
			$lead->__set('status', $result['status']);
			$lead->__set('urgent', $result['urgent']);
			$lead->__set('name', $result['name']);
			$lead->__set('house_type', $result['house_type']);
			$lead->__set('relationship', $result['relationship_to_patient']);
				
			$this->array_of_leads[] = $lead;
		}
	
		return $results;
	}
	
	public function selectByStaffID($staff_id)
	{
		$sql = "SELECT
					l.date_of_inquiry,
					s.seeker_id,
					s.name,
					z.county,
					z.state,
					s.relationship_to_patient,
					ht.house_type
				FROM
					leads l
				INNER JOIN
					seekers s
				ON 
					l.lead_id = s.seeker_id
				INNER JOIN
					house_types ht
				ON
					s.house_type = ht.house_type_id
				LEFT JOIN
					zipcodes z
				ON
					s.zipcode = z.id
				WHERE
					l.staff_id = $staff_id
				";
	
		$results	= $this->executeSQL('select_many', $sql);
	
		return $results;
	}
	
	public function selectBy($field, $value)
	{
		
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
		
			$sql = "SELECT
						*
					FROM
						leads l
					INNER JOIN
						leads_potential_hcp lph
					ON
						l.lead_id = lph.lead_id
					INNER JOIN
						seekers s
					ON
						l.lead_id = s.seeker_id
					WHERE
						l.$field = $value	
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
	
	
	public function selectUnassignedLeads()
	{
		$sql = "SELECT
					l.lead_id,
					l.staff_id,
					l.date_of_inquiry,
					l.status,
					l.urgent,
					s.name,
					z.city,
					z.state,
					s.relationship_to_patient,
					ht.house_type_id,
					ht.house_type
				FROM
					leads l
				INNER JOIN
					seekers s
				ON 
					l.lead_id=s.seeker_id
				INNER JOIN
					house_types ht
				ON
					s.house_type=ht.house_type_id
				LEFT JOIN
						zipcodes z
					ON
						s.zipcode = z.id
						WHERE
							staff_id IS NULL
						";
	
		$results	= $this->executeSQL('select_many', $sql);
	
		foreach($results as $result)
		{
			$lead = new lead();
	
			$lead->__set('lead_id', $result['lead_id']);
			$lead->__set('staff_id', $result['staff_id']);
			$lead->__set('date_of_inquiry', $result['date_of_inquiry']);
			$lead->__set('status', $result['status']);
			$lead->__set('urgent', $result['urgent']);
			$lead->__set('name', $result['name']);
			$lead->__set('house_type', $result['house_type']);
			$lead->__set('relationship', $result['relationship_to_patient']);
	
			$this->array_of_leads[] = $lead;
		}
	
		return $results;
	}
	
	
	
}
