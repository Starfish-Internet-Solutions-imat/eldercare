<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';

class hcp_contact_person
{
	
	private $contact_person_id;
	private $contact_person_name;
	private $contact_person_position;
	private $email;
	private $password;
	private $telephone;
	
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
		$con = starfishDatabase::getConnection();
		$sql = "SELECT
					 *
				FROM
					hcp_contact_persons
				WHERE
					contact_person_id = :contact_person_id";
		
		$pdo_statement = $con->prepare($sql);
		$pdo_statement->bindParam(":contact_person_id", $this->contact_person_id, PDO::PARAM_INT);
		$pdo_statement->execute();
		$results = $pdo_statement->fetch(PDO::FETCH_ASSOC);
		
		foreach($results as $field => $value)
			$this->__set($field, $value);
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function update()
	{
		$con = starfishDatabase::getConnection();
		$sql = "UPDATE
					hcp_contact_persons
				SET
					contact_person_name 	= :contact_person_name,
					contact_person_position = :contact_person_position,
					email 					= :email
					password 				= :password,
					telephone			    = :telephone
				WHERE
					contact_person_id = :contact_person_id
		";
		
		$pdo_statement = $con->prepare($sql);
		$pdo_statement->bindParam(":contact_person_name", $this->contact_person_name, PDO::PARAM_STR);
		$pdo_statement->bindParam(":contact_person_position", $this->contact_person_position, PDO::PARAM_STR);
		$pdo_statement->bindParam(":email", $this->email, PDO::PARAM_STR);
		$pdo_statement->bindParam(":password", $this->password, PDO::PARAM_STR);
		$pdo_statement->bindParam(":telephone", $this->telephone, PDO::PARAM_STR);
		$pdo_statement->bindParam(":contact_person_id", $this->contact_person_id, PDO::PARAM_INT);
		$pdo_statement->execute();
		
	}
	
//-------------------------------------------------------------------------------------------------

	public function delete()
	{
		$con = starfishDatabase::getConnection();
		$sql = "DELETE FROM
					hcp_contact_persons
				WHERE
					contact_person_id = :contact_person_id
				";
		
		$pdo_statement = $con->prepare($sql);
		$pdo_statement->bindParam(":contact_person_id", $this->contact_person_id, PDO::PARAM_INT);
		$pdo_statement->execute();
	}
	
//-------------------------------------------------------------------------------------------------


	public function insert()
	{
		$con = starfishDatabase::getConnection();
		$sql = "INSERT INTO
					hcp_contact_persons
					(
						contact_person_name,
						contact_person_position,
						email,
						password,
						telephone
					)
				VALUES
					(
						:contact_person_name,
						:contact_person_position,
						:email,
						:password,
						:telephone
					)
				";
		
		$pdo_statement = $con->prepare($sql);
		$pdo_statement->bindParam(":contact_person_name", $this->contact_person_name, PDO::PARAM_STR);
		$pdo_statement->bindParam(":contact_person_position", $this->contact_person_position, PDO::PARAM_STR);
		$pdo_statement->bindParam(":email", $this->email, PDO::PARAM_STR);
		$pdo_statement->bindParam(":password", $this->password, PDO::PARAM_STR);
		$pdo_statement->bindParam(":telephone", $this->telephone, PDO::PARAM_STR);
		$pdo_statement->execute();
		return $con->lastInsertId();
	}
	
//-------------------------------------------------------------------------------------------------	
	
	public function updateOneColumn($column, $value)
	{
		$con = starfishDatabase::getConnection();
		$sql = "UPDATE
					hcp_contact_persons
				SET
					$column = $value
				WHERE
					contact_person_id = :contact_person_id
				";
		
		$pdo_statement = $con->prepare($sql);
		$pdo_statement->bindParam(":contact_person_id", $this->contact_person_id, PDO::PARAM_INT);
		$pdo_statement->execute();
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function updateGeneric($update_array)
	{
		$where_array	= array('contact_person_id' => $this->contact_person_id);
		$sql_where		=	" WHERE contact_person_id = :contact_person_id ";
	
		$db			= new dbQuery();
		$db->update('hcp_contact_persons', $update_array, $sql_where, $where_array);
	}
	
}

?>