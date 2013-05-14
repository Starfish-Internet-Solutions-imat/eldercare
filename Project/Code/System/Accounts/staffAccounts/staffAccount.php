<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';

class staffAccount
{
	private $staff_id;
	private $name;
	private $role;
	private $telephone;
	private $email;
	private $password;
	private $last_login;
	
	private $cold_leads_count;
	private $immediate_leads_count;
	private $contacted_leads_count;
	private $info_sent_leads_count;
	private $placed_leads_count;
	private $closed_leads_count;
	private $total_leads_assigned_count;
	
	private $total;
	
	public function getUserAccountID(){ return $this->staff_id; }
	
	public function getUserRoleID(){ return $this->role; }
	
//-------------------------------------------------------------------------------------------------	
//get value of property in this class. happens after select function is executed	
//$field = name of variable
	public function __get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	
//set value of property in the class. executed before update or insert
//$field = name of variable; $value = value of variable
	public function __set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------	

	public function select()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = 
				"SELECT
					*
				FROM
					staffs
				WHERE
					staff_id = :staff_id
				OR
					email = :email
				";
			
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':staff_id', $this->staff_id, PDO::PARAM_INT);
			$pdo_statement->bindParam(':email', $this->email, PDO::PARAM_STR);
			$pdo_statement->execute();
			
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
			
			$this->staff_id		=	$result['staff_id'];
			$this->name			=	$result['name'];
			$this->role			=	$result['role'];
			$this->telephone	=	$result['telephone'];
			$this->email		=	$result['email'];
			$this->password		=	$result['password'];
			$this->last_login	=	$result['last_login'];
			
			return $result;
		
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
//================================================================================================//

	public function selectLogin()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						staff_id
					FROM
						staffs
					WHERE
						email = :email
					AND
						password = :password
					";
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':email', $this->email, PDO::PARAM_STR);
			$pdo_statement->bindParam(':password', $this->password, PDO::PARAM_STR);
			$pdo_statement->execute();
			$result = $pdo_statement->fetch();
			
			$this->staff_id	= $result['staff_id'];
			
			if($result !== FALSE)
				return TRUE;
			
			else
				return FALSE;
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
//================================================================================================//	

	public function insert()
	{
		try{
			$pdo_connection = starfishDatabase::getConnection();
			$pdo_connection->beginTransaction();
			
			$sql = "
					INSERT INTO
						staffs
						(
							`name`,
							`email`,
							`role`,
							`telephone`,
							`password`
						)
					VALUES
						(
							:name,
							:email,
							:role,
							:telephone,
							:password
						)
					";
			
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':email', $this->email, PDO::PARAM_STR);
			$pdo_statement->bindParam(':password', $this->password, PDO::PARAM_STR);
			$pdo_statement->bindParam(':name', $this->name, PDO::PARAM_STR);
			$pdo_statement->bindParam(':role', $this->role, PDO::PARAM_STR);
			$pdo_statement->bindParam(':telephone', $this->telephone, PDO::PARAM_STR);
			$pdo_statement->execute();
			
			$this->staff_id = $pdo_connection->lastInsertId();
			$pdo_connection->commit();
			
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
//================================================================================================//

	public static function getUserName($staff_id)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
				
			$sql = "SELECT
						name
					FROM
						staffs
					WHERE
						staff_id = :staff_id
					";
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
			$pdo_statement->execute();
			$result = $pdo_statement->fetch();
				
			if($result !== FALSE)
				return $result['name'];
				
			else
				return FALSE;
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
//================================================================================================//

	public function update()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$pdo_connection->beginTransaction();
			
			$sql = "UPDATE
						staffs
					SET
						name		= :name,
						email		= :email,
						telephone 	= :telephone,
						role		= :role
					WHERE
						staff_id	= :staff_id
					";
			
			$pdo_statement = $pdo_connection->prepare($sql);
			//bindParam is used so that SQL inputs are escaped.
			//This is to prevent SQL injections!
			$pdo_statement->bindParam(':staff_id', $this->staff_id, PDO::PARAM_INT);
			$pdo_statement->bindParam(':email', $this->email, PDO::PARAM_STR);
			$pdo_statement->bindParam(':telephone', $this->telephone, PDO::PARAM_STR);
			$pdo_statement->bindParam(':name', $this->name, PDO::PARAM_STR);
			$pdo_statement->bindParam(':role', $this->role, PDO::PARAM_STR);
			$pdo_statement->execute();
				
			$pdo_connection->commit();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
	
	public function updateWithPassword()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$pdo_connection->beginTransaction();
				
			$sql = "UPDATE
							staffs
						SET
							name		= :name,
							email		= :email,
							telephone 	= :telephone,
							role		= :role,
							password	= :password
						WHERE
							staff_id	= :staff_id
						";
				
			$pdo_statement = $pdo_connection->prepare($sql);
			//bindParam is used so that SQL inputs are escaped.
			//This is to prevent SQL injections!
			$pdo_statement->bindParam(':staff_id', $this->staff_id, PDO::PARAM_INT);
			$pdo_statement->bindParam(':email', $this->email, PDO::PARAM_STR);
			$pdo_statement->bindParam(':telephone', $this->telephone, PDO::PARAM_STR);
			$pdo_statement->bindParam(':name', $this->name, PDO::PARAM_STR);
			$pdo_statement->bindParam(':role', $this->role, PDO::PARAM_STR);
			$pdo_statement->bindParam(':password', $this->password, PDO::PARAM_STR);
			$pdo_statement->execute();
	
			$pdo_connection->commit();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
//=================================================================================================

	public static function ifEmailExists($email)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
	
			$sql = "SELECT
						COUNT(staff_id) as counter
					FROM
						staffs
					WHERE
						email = :email
					";
	
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':email', $email, PDO::PARAM_STR);
			$pdo_statement->execute();
			$result = $pdo_statement->fetch();
	
			return $result['counter'];
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
//================================================================================================//

	public function update_password()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$pdo_connection->beginTransaction();
			
			$sql = "UPDATE
						staffs
					SET
						password = :password
					WHERE
						staff_id = :staff_id
					";
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':staff_id', $this->staff_id, PDO::PARAM_STR);
				$pdo_statement->bindParam(':password', $this->password, PDO::PARAM_STR);
				$pdo_statement->execute();
				
				$pdo_connection->commit();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
	
//=================================================================================================
	
	public function delete()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$pdo_connection->beginTransaction();
	
			$sql = "DELETE FROM
						staffs
					WHERE
						staff_id = :staff_id
					";
	
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':staff_id', $this->staff_id, PDO::PARAM_INT);
			$pdo_statement->execute();
	
			$pdo_connection->commit();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
//=================================================================================================

	public static function updateLastLogin($staff_id)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$pdo_connection->beginTransaction();
	
			$sql = "UPDATE
							staffs
						SET
							last_login	= NOW()
						WHERE
							staff_id = :staff_id
								";
			$pdo_statement = $pdo_connection->prepare($sql);
			//bindParam is used so that SQL inputs are escaped.
			//This is to prevent SQL injections!
			$pdo_statement->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
			$pdo_statement->execute();
	
			$pdo_connection->commit();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
	//=================================================================================================
	
	public static function selectByEmail($email)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
		
			$sql = "SELECT
						email,
						name
					FROM
						staffs
					WHERE
						email = :email
							";
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':email', $email, PDO::PARAM_STR);
			$pdo_statement->execute();
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
			return $result;		
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
	
	//=================================================================================================
	
	public function updatePasswordByEmail($email, $password)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
				
			$sql = "UPDATE
							staffs
						SET
							password = :password
						WHERE
							email = :email
						";
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':email',    $email,    PDO::PARAM_STR);
			$pdo_statement->bindParam(':password', $password, PDO::PARAM_STR);
			$pdo_statement->execute();
	
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
}

?>