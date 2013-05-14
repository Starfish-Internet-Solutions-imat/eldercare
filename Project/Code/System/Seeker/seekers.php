<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
//require_once 'Project/Code/System/Active_Record/activeRecord.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'seeker.php';
require_once 'Project/Code/System/Accounts/staffAccounts/staffAccount.php';

class seekers extends dbQuery
{
	private $array_of_seekers = array();
	
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
					seekers
				";
		
		$results	= $this->executeSQL('select_many', $sql);
		
		foreach($results as $result)
		{
			$seeker = new seeker();
			
			$seeker->__set('seeker_id', $result['seeker_id']);
			$seeker->__set('name', $result['name']);
			$seeker->__set('email', $result['email']);
			$seeker->__set('telephone', $result['telephone']);
			$seeker->__set('relationship_to_patient', $result['relationship_to_patient']);
			$seeker->__set('house_type', $result['house_type']);
			$seeker->__set('zipcode', $result['zipcode']);
			
			$this->array_of_seekers[] = $seeker;
		}
		
		return $this->array_of_seekers;
	}
	
	public function selectLike($query)
	{
		$sql = "SELECT
					*
				FROM
					seekers
				WHERE
					name LIKE '%".$query."%'
				";
		
		$results	= $this->executeSQL('select_many', $sql);
		
		foreach($results as $result)
		{
			$seeker = new seeker();
			
			$seeker->__set('seeker_id', $result['seeker_id']);
			$seeker->__set('name', $result['name']);
			$seeker->__set('email', $result['email']);
			$seeker->__set('telephone', $result['telephone']);
			$seeker->__set('relationship_to_patient', $result['relationship_to_patient']);
			$seeker->__set('house_type', $result['house_type']);
			$seeker->__set('zipcode', $result['zipcode']);
			
			$this->array_of_seekers[] = $seeker;
		}
		
		return $this->array_of_seekers;
	}
	
	public function selectWithLeads($restriction = false)
	{
		$sql = "SELECT
						s.seeker_id,
						s.name,
						s.email,
						s.telephone,
						s.relationship_to_patient,
						s.house_type,
						z.zipcode,
						l.date_of_inquiry,
						l.status,
						l.staff_id,
						z.state
					FROM
						seekers s
					INNER JOIN
						leads l
					ON
						s.seeker_id = l.lead_id
					LEFT JOIN
						zipcodes z
					ON
						s.zipcode = z.id
					";
		
		if($restriction)
		{
			$sql .= "WHERE staff_id = ".authorization::getUserID();
		}
	
		$results	= $this->executeSQL('select_many', $sql);
	
		foreach($results as $result)
		{
			$seeker = new seeker();
			$staffAccount = new staffAccount();
			$staffAccount->__set('staff_id', $result['staff_id']);
			$staffAccount->select();
				
			$seeker->__set('seeker_id', $result['seeker_id']);
			$seeker->__set('name', $result['name']);
			$seeker->__set('email', $result['email']);
			$seeker->__set('telephone', $result['telephone']);
			$seeker->__set('relationship_to_patient', $result['relationship_to_patient']);
			$seeker->__set('house_type', $result['house_type']);
			$seeker->__set('zipcode', $result['zipcode']);
			$seeker->__set('date_of_inquiry', $result['date_of_inquiry']);
			$seeker->__set('status', $result['status']);
			$seeker->__set('state', $result['state']);
			$seeker->__set('consultant', $staffAccount->__get('name'));
				
			$this->array_of_seekers[] = $seeker;
		}
	
		return $this->array_of_seekers;
	}
	
	public function selectWithLeadsOnOrder($restriction = false,$order_by)
	{
		$sql = "SELECT
							s.seeker_id,
							s.name,
							s.email,
							s.telephone,
							s.relationship_to_patient,
							s.house_type,
							z.zipcode,
							l.date_of_inquiry,
							l.status,
							l.staff_id,
							z.state,
							st.name as consultant,
							st.name IS NULL AS is_null
						FROM
							seekers s
						INNER JOIN
							leads l
						ON
							l.lead_id = s.seeker_id 
						LEFT OUTER JOIN
							staffs st
						ON
							l.staff_id = st.staff_id
						LEFT OUTER JOIN
							zipcodes z
						ON
							s.zipcode = z.id
						ORDER BY
							$order_by ASC, is_null DESC 
						";
	
		if($restriction)
		{
			$sql .= "WHERE staff_id = ".authorization::getUserID();
		}
	
		$results	= $this->executeSQL('select_many', $sql);
	
		foreach($results as $result)
		{
			$seeker = new seeker();
			
			$seeker->__set('seeker_id', $result['seeker_id']);
			$seeker->__set('name', $result['name']);
			$seeker->__set('email', $result['email']);
			$seeker->__set('telephone', $result['telephone']);
			$seeker->__set('relationship_to_patient', $result['relationship_to_patient']);
			$seeker->__set('house_type', $result['house_type']);
			$seeker->__set('zipcode', $result['zipcode']);
			$seeker->__set('date_of_inquiry', $result['date_of_inquiry']);
			$seeker->__set('status', $result['status']);
			$seeker->__set('state', $result['state']);
			$seeker->__set('consultant', $result['consultant']);
	
			$this->array_of_seekers[] = $seeker;
		}
	
		return $this->array_of_seekers;
	}
	
}

?>