<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';

class userAccount
{
	private $user_account_id;
	private $email;
	private $password;
	private $first_name;
	private $last_name;
	private $last_login;
	
	public function getUserAccountID(){ return $this->user_account_id; }
	
	public function getUserRoleID(){ return 1; }
	
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
			
			$sql = "
				
				SELECT
					*
				FROM
					user_accounts
				WHERE
					user_account_id = :user_account_id
				OR
					email = :email
					";
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':user_account_id', $this->user_account_id, PDO::PARAM_INT);
			$pdo_statement->bindParam(':email', $this->email, PDO::PARAM_STR);
			$pdo_statement->execute();
			
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
			
			$this->user_account_id =	$result['user_account_id'];
			$this->email		=	$result['email'];
			$this->password		=	$result['password'];
			$this->first_name	=	$result['first_name'];
			$this->last_name	=	$result['last_name'];
			$this->last_login	=	$result['last_login'];
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
						user_account_id
					FROM
						user_accounts
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
			
			$this->user_account_id	= $result['user_account_id'];
			
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
						user_accounts
						(
							`email`,
							`password`,
							`first_name`,
							`last_name`
						)
					VALUES
						(
							:email,
							:password,
							:first_name,
							:last_name
						)
					";
			
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':email', $this->email, PDO::PARAM_STR);
			$pdo_statement->bindParam(':password', $this->password, PDO::PARAM_STR);
			$pdo_statement->bindParam(':first_name', $this->first_name, PDO::PARAM_STR);
			$pdo_statement->bindParam(':last_name', $this->last_name, PDO::PARAM_STR);
			$pdo_statement->execute();
			
			$this->user_account_id = $pdo_connection->lastInsertId();
			$pdo_connection->commit();
			
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
//================================================================================================//

	public static function getUserName($user_account_id)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
				
			$sql = "SELECT
						first_name
					FROM
						user_accounts
					WHERE
						user_account_id = :user_account_id
					";
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':user_account_id', $user_account_id, PDO::PARAM_INT);
			$pdo_statement->execute();
			$result = $pdo_statement->fetch();
				
			if($result !== FALSE)
				return $result['first_name'];
				
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
						user_accounts
					SET
						email 				= :email,
						first_name			= :first_name,
						last_name			= :last_name,
					WHERE
						user_account_id = :user_account_id
					";
			
			$pdo_statement = $pdo_connection->prepare($sql);
			//bindParam is used so that SQL inputs are escaped.
			//This is to prevent SQL injections!
			$pdo_statement->bindParam(':user_account_id', $this->user_account_id, PDO::PARAM_INT);
			$pdo_statement->bindParam(':email', $this->email, PDO::PARAM_STR);
			$pdo_statement->bindParam(':first_name', $this->first_name, PDO::PARAM_STR);
			$pdo_statement->bindParam(':last_name', $this->last_name, PDO::PARAM_STR);
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
						COUNT(user_account_id) as counter
					FROM
						user_accounts
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
						user_accounts
					SET
						password	= :password
					WHERE
						user_account_id = :user_account_id
					";
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':user_account_id', $this->user_account_id, PDO::PARAM_STR);
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
						user_accounts
					WHERE
						user_account_id = :user_account_id
					";
	
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':user_account_id', $this->user_account_id, PDO::PARAM_INT);
			$pdo_statement->execute();
	
			$pdo_connection->commit();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
	public static function updateLastLogin($user_account_id)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			$pdo_connection->beginTransaction();
	
			$sql = "UPDATE
							user_accounts
						SET
							last_login	= NOW()
						WHERE
							user_account_id = :user_account_id
								";
			$pdo_statement = $pdo_connection->prepare($sql);
			//bindParam is used so that SQL inputs are escaped.
			//This is to prevent SQL injections!
			$pdo_statement->bindParam(':user_account_id', $user_account_id, PDO::PARAM_INT);
			$pdo_statement->execute();
	
			$pdo_connection->commit();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	
	
	}
	
}

?>