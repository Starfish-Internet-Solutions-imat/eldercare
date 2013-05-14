<?php
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/ResultCleaner/resultCleaner.php';

class album_size
{
	private $size_id;
	private $dimensions;
	private $album_id;
	
//-------------------------------------------------------------------------------------------------	

	public function __get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function __set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//=================================================================================================
	
	public function select()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						*
					FROM
						album_image_sizes
					WHERE
						size_id		= :size_id
					";
			
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':size_id', $this->size_id, PDO::PARAM_INT);
				$pdo_statement->execute();
			
				$result = resultCleaner::cleanSingleResult($pdo_statement->fetch(PDO::FETCH_ASSOC));
				
				$this->size_id		= $result['size_id'];
				$this->dimensions	= $result['dimensions'];
				$this->album_id		= $result['album_id'];
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
	
//=================================================================================================
	
	public function insert()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "INSERT INTO
						album_image_sizes
						(
							`dimensions`,
							`album_id`
						)
						VALUES
						(
							:dimensions,
							:album_id
						)
					";
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':dimensions', $this->dimensions, PDO::PARAM_STR);
				$pdo_statement->bindParam(':album_id', $this->album_id, PDO::PARAM_INT);
				$pdo_statement->execute();
				
				$this->size_id = $pdo_connection->lastInsertId();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
		
	}
	
//=================================================================================================
	
	public function update()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "UPDATE
						album_image_sizes
					SET
						dimensions	= :dimensions,
						album_id	= :album_id
					WHERE
						size_id		= :size_id
					";
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':size_id', $this->size_id, PDO::PARAM_INT);
				$pdo_statement->bindParam(':dimensions', $this->dimensions, PDO::PARAM_STR);
				$pdo_statement->bindParam(':album_id', $this->album_id, PDO::PARAM_INT);
				$pdo_statement->execute();
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
			
			$sql = "DELETE FROM
						album_image_sizes
					WHERE
						size_id	= :size_id
					";
			
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':size_id', $this->size_id, PDO::PARAM_INT);
				$pdo_statement->execute();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
}