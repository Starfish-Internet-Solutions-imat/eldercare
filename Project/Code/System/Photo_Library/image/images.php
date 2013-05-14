<?php
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'image.php';

class images
{
	private $array_of_images = array();
	private $album_id;
	private $size_id;
	
//-------------------------------------------------------------------------------------------------	

	public function __get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function __set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//=================================================================================================
	
	public function selectByAlbum()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						*
					FROM
						images
					WHERE
						album_id = :album_id
					";
			
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':album_id', $this->album_id, PDO::PARAM_INT);
				$pdo_statement->execute();
			
				$results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
				
				//since this part of the code is getting redundant,
				//i made a private function that stores the results in $array_of_images
				$this->saveResults($results);
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
	
//=================================================================================================
	
	public function selectBySize()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						*
					FROM
						images
					WHERE
						size_id = :size_id
					";
			
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':size_id', $this->size_id, PDO::PARAM_INT);
				$pdo_statement->execute();
			
				$results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
				
				$this->saveResults($results);
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
	
//=================================================================================================
	
	public function select()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						*
					FROM
						images
					";
			
				$pdo_statement = $pdo_connection->prepare($sql);
				$pdo_statement->execute();
			
				$results = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
				
				$this->saveResults($results);
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
	
//=================================================================================================
	
	public static function selectCount()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						count(image_id) as image_count
					FROM
						images
					";
			
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->execute();
		
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
			
			return $result['image_count'];
		}		
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
	
//=================================================================================================
	
	public static function selectCountInAlbum($album_id)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						count(image_id) as image_count
					FROM
						images
					WHERE
						album_id = :album_id
					";
			
			$pdo_statement = $pdo_connection->prepare($sql);
			//bindParam is used so that SQL inputs are escaped.
			//This is to prevent SQL injections!
			$pdo_statement->bindParam(':album_id', $album_id, PDO::PARAM_INT);
			$pdo_statement->execute();
		
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
			
			return $result['image_count'];
		}		
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
	
//=================================================================================================
	
	private function saveResults($results)
	{		
		foreach($results as $result)
		{
			$image = new image();
			
			$image->__set('image_id', $result['image_id']);
			$image->__set('album_id', $result['album_id']);
			$image->__set('size_id', $result['size_id']);
			$image->__set('filename_ext', $result['filename_ext']);
			$image->__set('image_caption', $result['image_caption']);
			$image->__set('filename', $result['filename']);
			
			$this->array_of_images[] = $image;
		}
	}
}