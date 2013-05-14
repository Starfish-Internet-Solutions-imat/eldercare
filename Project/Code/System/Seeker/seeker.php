<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'Project/Code/System/House_Type/house_types.php';

class seeker
{
	private $seeker_id;
	private $name;
	private $telephone;
	private $email;
	private $relationship_to_patient;
	private $house_type;
	private $house_type_name;
	private $zipcode;
	private $date_of_inquiry;
	private $status;
	private $zipcode_id;
	private $state;
	private $city;
	private $consultant;
	private $staff_name;
	private $staff_id;
	
	private $house_types = array();
	
	private $array_of_provider_id;
	
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
					s.*,
					z.city,
					z.state,
					z.county
				FROM
					seekers s
				LEFT JOIN	
					zipcodes z
				ON
					s.zipcode = z.id
				WHERE
					seeker_id	=	:seeker_id
				";
		
		$db			= new dbQuery();
		$bindParams	= array('seeker_id'		=>		$this->seeker_id);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
		
		$this->email					= $result['email'];
		$this->name						= $result['name'];
		$this->telephone				= $result['telephone'];
		$this->relationship_to_patient	= $result['relationship_to_patient'];
		$this->zipcode					= $result['zipcode'];
		
		return $result;
		
	}
	
	//----------------------------------------------------------------------------------------------
	
	public function selectWithSeeker()
	{
		$sql = "SELECT
							s.seeker_id,
							s.name,
							s.email,
							s.telephone,
							s.relationship_to_patient,
							z.zipcode,
							z.id as zipcode_id,
							z.state,
							z.city,
							s.house_type,
							l.status,
							l.date_of_inquiry,
							l.staff_id,
							st.name as staff_name,
							ht.house_type as house,
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
						INNER JOIN
							house_types ht
						ON
							ht.house_type_id = s.house_type
						LEFT OUTER JOIN
							staffs st
						ON
							st.staff_id = l.staff_id
						WHERE
							s.seeker_id	= :seeker_id 
						";
	
		$db			= new dbQuery();
		$bindParams	= array('seeker_id'		=>		$this->seeker_id);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
	
		$house_types = new house_types();
		$data = $house_types->select();
		
			$this->__set('seeker_id', $result['seeker_id']);
			$this->__set('name', $result['name']);
			$this->__set('email', $result['email']);
			$this->__set('house_type', $result['house_type']);
			$this->__set('house_types', $data);
			$this->__set('house_type_name', $result['house']);
			$this->__set('telephone', $result['telephone']);
			$this->__set('zipcode', $result['zipcode']);
			$this->__set('relationship_to_patient', $result['relationship_to_patient']);
			$this->__set('zipcode_id', $result['zipcode_id']);
			$this->__set('state', $result['state']);
			$this->__set('city', $result['city']);
			$this->__set('status', $result['status']);
			$this->__set('date_of_inquiry', $result['date_of_inquiry']);
			$this->__set('staff_name', $result['staff_name']);
			$this->__set('staff_id', $result['staff_id']);
	
	}
	
//-------------------------------------------------------------------------------------------------	

	public static function selectFirst($restriction = false)
	{
		$staff_id = authorization::getUserID();
		
		if($restriction && is_numeric($staff_id))
			$where = "WHERE staff_id = ".$staff_id;
		
		$sql = "SELECT
					s.seeker_id
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
				{$where}
				LIMIT 1
				";
		
		$db			= new dbQuery();
		$result		= $db->executeSQL('select_one', $sql);
		
		return $result['seeker_id'];
	}
	
	//-------------------------------------------------------------------------------------------
	
	public function update()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$pdo_connection->beginTransaction();
			
			$sql = "UPDATE
						seekers s, leads l
					SET
						s.name 			= :name,
						s.email			= :email,
						s.telephone 	= :telephone,
						s.house_type	= :house_type,
						s.zipcode		= :zipcode,
						l.staff_id		= :staff_id
					WHERE
						s.seeker_id		= :seeker_id
					AND
						l.lead_id 		= :seeker_id 
					";
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':email', $this->email, PDO::PARAM_STR);
			$pdo_statement->bindParam(':name', $this->name, PDO::PARAM_STR);
			$pdo_statement->bindParam(':telephone', $this->telephone, PDO::PARAM_STR);
			$pdo_statement->bindParam(':house_type', $this->house_type, PDO::PARAM_INT);
			$pdo_statement->bindParam(':zipcode', $this->zipcode_id, PDO::PARAM_INT);
			$pdo_statement->bindParam(':seeker_id', $this->seeker_id, PDO::PARAM_INT);
			$pdo_statement->bindParam(':staff_id', $this->staff_id, PDO::PARAM_INT);
			$pdo_statement->execute();

			$pdo_connection->commit();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
//-------------------------------------------------------------------------------------------------
	
	/* public function insertMany()
	{
		$cons_values = "";
		
		foreach ($this->array_of_provider_id as $provider_id)
		{
			
			$cons_values .= "($provider_id, '$this->name', '$this->email', '$this->telephone', '$this->relationship_to_patient'), ";
			
		}
		
		$values = rtrim($cons_values, ", ");
		
		
	$sql = "INSERT 
				INTO leads
					(healthcare_provider_id, seeker_name, seeker_email, seeker_mobile_number, seeker_relationship_to_patient)
				VALUES
					$values
			"; 
	
		$db			= new dbQuery();
		$result		= $db->executeSQL('insert', $sql);
	} */
	
//================================================================================================//	

	public function insert()
	{
		$house_type = house_types::getHouseTypeID($this->house_type);
		$house_type = $house_type['housing_type'];
		
		$insert_array = array(
			'name'						=> $this->name,
			'email'						=> $this->email,
			'telephone'					=> $this->telephone,
			'relationship_to_patient'	=> $this->relationship_to_patient,
			'zipcode'					=> $this->zipcode,
			'house_type'				=> $house_type
		);
		
		$db			= new dbQuery();
		return $db->insert('seekers', $insert_array);
	}
	
//=================================================================================================
	
	/* public function delete()
	{
		$where_array	= array(
			'name'					=> $this->name,
			'email'					=> $this->email
		);
		
		$sql_where = " WHERE
							healthcare_provider_id = :healthcare_provider_id
						AND
							name = :name";
		
		$db			= new dbQuery();
		$db->delete('seekers', $sql_where, $where_array);
	}
	
} */
	


	public static function delete($seeker_id)
	{
		
		$where_array 		=  array(
			'seeker_id'		=> $seeker_id
		);
		
		$sql_where			= "WHERE
									seeker_id	=	:seeker_id";
		
		$db					= new dbQuery();
		$db->delete('seekers', $sql_where, $where_array);
		
		
	}
	
	//=================================================================================================
	
	public function selectTelephone()
	{
		$sql = "SELECT
					telephone
				FROM
					seekers
				WHERE
					seeker_id	=	:seeker_id  
				";
		
		$db			= new dbQuery();
		$bindParams	= array('seeker_id'		=>		$this->seeker_id);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
	}
	

}