<?php
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/ResultCleaner/resultCleaner.php';

class album
{
	private $album_id;
	private $album_title;
	private $album_folder;
	
	private $size_preview;
	private $image_preview;
	private $image_extension;
	
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
						albums
					WHERE
						album_id	= :album_id
					";
			
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':album_id', $this->album_id, PDO::PARAM_INT);
				$pdo_statement->execute();
			
				$result = resultCleaner::cleanSingleResult($pdo_statement->fetch(PDO::FETCH_ASSOC));
				
				$this->album_id		= $result['album_id'];
				$this->album_title	= $result['album_title'];
				$this->album_folder = $result['album_folder'];
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
	
//=================================================================================================
	
	public function selectFirst()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						*
					FROM
						albums
					LIMIT 0,1
					";
			
				$pdo_statement = $pdo_connection->prepare($sql);
				$pdo_statement->execute();
			
				$result = resultCleaner::cleanSingleResult($pdo_statement->fetch(PDO::FETCH_ASSOC));
				
				$this->album_id		= $result['album_id'];
				$this->album_title	= $result['album_title'];
				$this->album_folder = $result['album_folder'];
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
						albums
						(
							`album_title`,
							`album_folder`
						)
						VALUES
						(
							:album_title,
							:album_folder
						)
					";
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':album_title', $this->album_title, PDO::PARAM_STR);
				$pdo_statement->bindParam(':album_folder', $this->album_folder, PDO::PARAM_STR);
				$pdo_statement->execute();
				
				$this->album_id = $pdo_connection->lastInsertId();
				
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
						albums
					SET
						album_title		= :album_title,
						album_folder	= :album_folder
					WHERE
						album_id	= :album_id
					";
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':album_id', $this->album_id, PDO::PARAM_INT);
				$pdo_statement->bindParam(':album_title', $this->album_title, PDO::PARAM_STR);
				$pdo_statement->bindParam(':album_folder', $this->album_folder, PDO::PARAM_STR);
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
						albums
					WHERE
						album_id = :album_id
					";
			
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':album_id', $this->album_id, PDO::PARAM_INT);
				$pdo_statement->execute();
				
				return TRUE;
		}
		catch(PDOException $pdoe)
		{
			return FALSE;
		}
	}
}