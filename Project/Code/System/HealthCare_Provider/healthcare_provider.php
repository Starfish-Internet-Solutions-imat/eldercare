<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';

class healthcare_provider
{
	private $hcp_id;
	private $name;
	private $contact_person_name;
	private $contact_person_position;
	private $email;
	private $password;
	private $telephone;
	private $zipcode;
	private $location;
	private $description;
	private $price_from;
	private $price_to;
	private $pricing;
	private $image_id;
	private $approved;
	private $published;
	private $suspended;
	private $accommodation_type;
	private $number_can_accommodate;
	private $number_of_bedrooms;
	
	private $zipcode_id;
	private $city;
	private $state;
	
	
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
					hcp.*,
					cp.*
				FROM
					health_care_providers hcp
				INNER JOIN
					hcp_contact_persons cp
				ON
					hcp.contact_person_id = cp.contact_person_id
				WHERE
					hcp_id = :hcp_id
				";

		if ($restrict)
			$sql .= " AND (approved = 1 OR approved = 2) AND published = 1 AND suspended = 0";

		$db			= new dbQuery();
		$bindParams	= array('hcp_id' => $this->hcp_id);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);



		$this->hcp_id					= $result['hcp_id'];
		$this->name						= $result['name'];
		$this->contact_person_name 		= $result['contact_person_name'];
		$this->contact_person_position	= $result['contact_person_position'];
		$this->email					= $result['email'];
		$this->telephone 				= $result['telephone'];
		$this->location					= $result['location'];

		if ($result['zipcode'] != "")
		{
			$zip = $this->set_zip($result['zipcode']);
			$this->zipcode				= $result['zipcode_id'] = $zip['zipcode'];
			$this->zipcode_id 			= $result['zipcode'];
			$this->state				= $result['state'] = $zip['state'];
			$this->city					= $result['city'] = $zip['city'];
		}

		//$this->house_type				= $result['house_type'];
		$this->password					= $result['password'];
		$this->description				= $result['description'];
		$this->price_to					= $result['price_to'];
		$this->price_from				= $result['price_from'];
		$this->pricing					= $result['pricing'];
		$this->image_id					= $result['image_id'];
		$this->approved					= $result['approved'];
		$this->published				= $result['published'];
		$this->suspended				= $result['suspended'];

		return $result;
	}
	
	
//-------------------------------------------------------------------------------------------------	

	public function selectGeneric($columns_array=array(), $restrict = false)
	{
		$columns_array = array_filter($columns_array);
		$columns = (count($columns_array)>1)?implode(', ', $columns_array): $columns_array[0];
		
		$sql = "SELECT
							". $columns
						."
				 FROM
							health_care_providers hcp
				INNER JOIN
					hcp_contact_persons cp
				ON
					hcp.contact_person_id = cp.contact_person_id
				WHERE
					hcp_id = :hcp_id
						";
		if ($restrict)
			$sql .= " AND (approved = 1 or approved = 2) AND published = 1 AND suspended = 0";
		
		$db			= new dbQuery();
		$bindParams	= array('hcp_id' => $this->hcp_id);
		
		$result 	= $db->executeSQL('select_one', $sql, $bindParams);
		
		foreach($columns_array as $column)
		{
			$this->__set($column, $result[$column]);	
		}
		
		return $result;
	}
	
//-------------------------------------------------------------------------------------------------	

	public static function selectLogin($email, $password)
	{
		$sql = "SELECT
					hcp_id, name, hcp.contact_person_id
				FROM
					health_care_providers as hcp
				INNER JOIN 
					hcp_contact_persons cp
				ON
					hcp.contact_person_id = cp.contact_person_id
				WHERE
					email = :email
				AND
					password = :password
				";
		
		$bindParams	= array(
			'email'		=> $email,
			'password' 	=> $password
		);
		
		$db			= new dbQuery();
		$result		= $db->executeSQL('select_one', $sql, $bindParams);

		return $result;
	}
	
//================================================================================================//	

	public function insert()
	{
		$insert_array = array(
			'name'						=> $this->name,
			'email'						=> $this->email,
			'contact_person_name'		=> $this->contact_person_name,
			'contact_person_position'	=> $this->contact_person_position,
			'telephone'					=> $this->telephone,
			'password'					=> $this->password
		);
		
		$db			= new dbQuery();
		$this->hcp_id = $db->insert('health_care_providers', $insert_array);
	}
	
//================================================================================================//
	
	public function insertGeneric($insert_array)
	{
		$db			= new dbQuery();
		$this->hcp_id = $db->insert('health_care_providers', $insert_array);
	}
	
//================================================================================================//

	public function update()
	{
		$update_array = array(
			'name'						=> $this->name,
			'contact_person_name'		=> $this->contact_person_name,
			'contact_person_position'	=> $this->contact_person_position,
			'email'						=> $this->email,
			'password'					=> $this->password,
			'telephone'					=> $this->telephone,
			'zipcode'					=> $this->zipcode,
			'location'					=> $this->location,
			'description'				=> $this->description,
			'price_to'					=> $this->price_to,
			'price_from'				=> $this->price_from,
			'pricing'					=> $this->pricing,
			'published'					=> $this->published,
			'suspended'					=> $this->suspended
			
		);
		
		$where_array	= array('hcp_id' => $this->hcp_id);
		
		$sql = "UPDATE health_care_providers";
		
		$update = array();
		
		foreach($update_array as $column => $value)
			$update[] = "$column = :$column";
		
		$update[] = "date_updated = NOW()";
		
		$sql .= " SET ".implode(', ', $update);
		$sql .= " WHERE hcp_id = :hcp_id ";
		
		$db	= new dbQuery();
			
		$db->executeSQL('update', $sql, array_merge($update_array, $where_array));
	}
	
//================================================================================================//
	
	public function updateGeneric($update_array)
	{
		$where_array	= array('hcp_id' => $this->hcp_id);
		$sql_where		=	" WHERE hcp_id = :hcp_id ";
	
		var_dump($this->hcp_id);
		$db			= new dbQuery();
		$db->update('health_care_providers', $update_array, $sql_where, $where_array);
	}
	
//================================================================================================//

	public function updateImage()
	{
		$update_array = array(
			'image_id'					=> $this->image_id
		);
		$where_array	= array('hcp_id' => $this->hcp_id);
		$sql_where		=	" WHERE hcp_id = :hcp_id ";
		
		$db			= new dbQuery();
		$db->update('health_care_providers', $update_array, $sql_where, $where_array);
	}
	
//=================================================================================================
	
	public static function delete($hcp_id)
	{
		$where_array	= array('hcp_id' => $hcp_id);
		$sql_where		= " WHERE hcp_id = :hcp_id ";
		
		$db			= new dbQuery();
		$db->delete('health_care_providers', $sql_where, $where_array);
	}
	
//=================================================================================================

	public function set_zip($zipcode)
	{
		require_once 'Project/Code/System/ZipCodes/zipcodes.php';
		
		$zipcodes = new zipcodes();
		
		return $zipcodes->selectExactlyById($zipcode);
		
	}
	
//=================================================================================================
	
	public static function isEmailUnique($email)
	{
		$email = trim($email);
	
		$sql = "SELECT
					email
				FROM
					health_care_providers as hcp
				INNER JOIN
					hcp_contact_persons as cp
				ON
					hcp.contact_person_id = cp.contact_person_id
				WHERE
					email = :email
							";
	
		$bindParams	= array(
			'email'		=> "$email"
		);
		
		$db			= new dbQuery();
		return $db->executeSQL('select_one', $sql, $bindParams);
	}

//=================================================================================================
	
	public static function selectByEmail($email)
	{		
		$columns = 'email, hcp_id, name, password, email, date_created ';
		
		$sql = "SELECT
					$columns
				FROM
					health_care_providers as hcp
				INNER JOIN
					hcp_contact_persons as cp
				ON
					hcp.contact_person_id = cp.contact_person_id
				WHERE
					email = :email
			   ";
		
	
		$db	= new dbQuery();
		$bindParams	= array(
			'email'		=> "$email"
		);
		return $db->executeSQL('select_one', $sql, $bindParams);
	}
	
//=================================================================================================
	
	public function updateByEmail($update_array)
	{
		$where_array	= array('email' => $this->email);
		$sql_where		=	" WHERE email = :email ";
	
		$db	= new dbQuery();
		$db->update('health_care_providers', $update_array, $sql_where, $where_array);
	}
	
//=================================================================================================	
	
	public function selectImageID($image_id)
	{
		
		$sql = "SELECT
										image_id
									FROM
										health_care_providers
									WHERE
										image_id = :image_id
									";
		
		$bindParams	= array(
					'image_id'		=> $image_id
		);
		
		$db			= new dbQuery();
		return $db->executeSQL('select_one', $sql, $bindParams);
	}
	
//=================================================================================================	

	public function selectIfOwn($contact_person_id, $hcp_id)
	{
	
		$sql = "SELECT
					hcp_id
				FROM
					health_care_providers
				WHERE
					contact_person_id = :contact_person_id
				AND
					hcp_id = :hcp_id
				   ";
	
	
		$db	= new dbQuery();
		$bindParams	= array(
				'contact_person_id'		=> $contact_person_id,
				'hcp_id'				=> $hcp_id
		);
		return $db->executeSQL('select_one', $sql, $bindParams);
	}

}

?>