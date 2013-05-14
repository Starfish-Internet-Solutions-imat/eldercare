<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/activeRecord.php';

class zipcode
{
	private $zipcode;
	private $zipcode_id;
	private $state;
	private $city;
	private $county;
	
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
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						zipcode, city, state, county
					FROM
						zipcodes
					WHERE
						zipcode = :zipcode
					";
			
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':zipcode', $this->zipcode, PDO::PARAM_INT);
			$pdo_statement->execute();
			
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
			
			$this->zipcode 		= $result['zipcode'];
			$this->state		= $result['state'];
			$this->city			= $result['city'];
			$this->county		= $result['county'];
			
			return $result;
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function selectById()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
				
			$sql = "SELECT
							*
						FROM
							zipcodes
						WHERE
							id = :zipcode_id
						";
				
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':zipcode_id', $this->zipcode_id, PDO::PARAM_INT);
			$pdo_statement->execute();
				
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
				
			$this->zipcode 		= $result['zipcode'];
			$this->state		= $result['state'];
			$this->city			= $result['city'];
			$this->county		= $result['county'];
				
			return $result;
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
	public function selectByZipcode()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
	
			$sql = "SELECT
								*
							FROM
								zipcodes
							WHERE
								zipcode = :zipcode
							";
	
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':zipcode', $this->zipcode, PDO::PARAM_INT);
			$pdo_statement->execute();
	
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
	
			$this->zipcode_id 	= $result['id'];
			$this->state		= $result['state'];
			$this->city			= $result['city'];
			//$this->county		= $result['county'];
	
			return $result;
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
	public function selectIdLikeZipcode($zipcode="")
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
	
			$sql = "SELECT
									id
								FROM
									zipcodes
								WHERE
									zipcode LIKE :zipcode
								";
			$zipcode = $this->zipcode.'%';
	
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':zipcode', $zipcode, PDO::PARAM_INT);
			$pdo_statement->execute();
	
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
	
			$this->zipcode_id 	= $result['id'];
	
			return $result['id'];
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
	public function selectByCityAndState()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
	
			$sql = "SELECT
									*
								FROM
									zipcodes
								WHERE
									city = :city
								AND
									state = :state
								";
	
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':city', $this->city, PDO::PARAM_STR);
			$pdo_statement->bindParam(':state', $this->state, PDO::PARAM_STR);
			$pdo_statement->execute();
	
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
	
			$this->zipcode_id 	= $result['id'];
			$this->zipcode 		= $result['zipcode'];
			$this->state		= $result['state'];
			$this->city			= $result['city'];
			$this->county		= $result['county'];
	
			return $result;
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
	public function isZipcodeValid($zipcode)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
	
			$sql = "SELECT
									id
								FROM
									zipcodes
								WHERE
									zipcode = '$zipcode'
								";
	
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->execute();
	
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
			return $result;
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	
	}
	
	public static function selectZipcodeById($zipcode_id)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
		
			$sql = "SELECT
									zipcode
								FROM
									zipcodes
								WHERE
									id = :zipcode_id
								";
		
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':zipcode_id', $zipcode_id, PDO::PARAM_INT);
			$pdo_statement->execute();
		
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
		
			return $result['zipcode'];
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
}

?>