<?php
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';

class image
{
	private $image_id;
	private $album_id;
	private $size_id;
	private $filename_ext;
	private $image_caption;
	private $filename;
	
	private $full_path;
	private $used_for;
	
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
						images
					WHERE
						image_id = :image_id
					";
			
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':image_id', $this->image_id, PDO::PARAM_INT);
				$pdo_statement->execute();
			
				$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
				
				$this->album_id			= $result['album_id'];
				$this->size_id			= $result['size_id'];
				$this->image_caption	= $result['image_caption'];
				$this->filename_ext		= $result['filename_ext'];
				$this->filename			= $result['filename'];
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
//=================================================================================================

	public static function checkIfFilenameExist($filename)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						COUNT(image_id) as count
					FROM
						images
					WHERE
						filename = :filename"
						;
						
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':filename', $filename, PDO::PARAM_INT);
			$pdo_statement->execute();
			
			$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
			
			return $result['count'];
		}
		catch (PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
//=================================================================================================
	
	public function selectFullPath($is_thumbnail = FALSE)
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "SELECT
						album_folder, dimensions, filename, filename_ext
					FROM
						images i
					INNER JOIN
						album_image_sizes s
					ON
						s.size_id = i.size_id
					INNER JOIN
						albums a
					ON
						s.album_id = a.album_id
					WHERE
						image_id = :image_id
					";
			
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':image_id', $this->image_id, PDO::PARAM_INT);
				$pdo_statement->execute();
			
				$result = $pdo_statement->fetch(PDO::FETCH_ASSOC);
				
				if($result !== FALSE)
				{
					$thumbnail = '';
					
					if($is_thumbnail) $thumbnail = '_thumb';
					
					$this->full_path = $result['album_folder'].'/'.$result['dimensions'].$thumbnail.'/'.$result['filename'].$result['filename_ext'];
				}
				
				else
					$this->full_path = '';
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
						images
						(
							`album_id`,
							`size_id`,
							`filename`,
							`filename_ext`
						)
						VALUES
						(
							:album_id,
							:size_id,
							:filename,
							:filename_ext
						)
					";
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':album_id', $this->album_id, PDO::PARAM_INT);
				$pdo_statement->bindParam(':size_id', $this->size_id, PDO::PARAM_INT);
				$pdo_statement->bindParam(':filename', $this->filename, PDO::PARAM_STR);
				$pdo_statement->bindParam(':filename_ext', $this->filename_ext, PDO::PARAM_STR);
				$pdo_statement->execute();
				
				$this->image_id = $pdo_connection->lastInsertId();
				
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
						images
					SET
						album_id		= :album_id,
						image_caption	= :image_caption,
						filename		= :filename
					WHERE
						image_id = :image_id
					";
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':image_id', $this->image_id, PDO::PARAM_INT);
				$pdo_statement->bindParam(':album_id', $this->album_id, PDO::PARAM_INT);
				$pdo_statement->bindParam(':image_caption', $this->image_caption, PDO::PARAM_STR);
				$pdo_statement->bindParam(':filename', $this->filename, PDO::PARAM_STR);
				$pdo_statement->execute();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
	
//-------------------------------------------------------------------------------------------------

	private function showTables()
	{
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
				
			$sql = "SHOW TABLES FROM ".DATABASE_NAME." WHERE Tables_in_".DATABASE_NAME." LIKE '%_images'";
			
			$pdo_statement = $pdo_connection->query($sql);
			$results = $pdo_statement->fetchAll();
			
			$table_names = array();
			
			foreach($results as $result)
				$table_names[] = $result['Tables_in_'.DATABASE_NAME];
			
			return $table_names;
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
//-------------------------------------------------------------------------------------------------

	public function checkIfUsed($image_id, $column_name)
	{
		$table_names = $this->showTables();
		$union = array();
		
		foreach($table_names as $table)
		{
			$union[] = "
					SELECT
						COUNT(".$table.".".$column_name.") as counter
					FROM
						".$table."
					WHERE
						".$table.".".$column_name."=".$image_id;
		}
		
		try
		{
			$pdo_connection = starfishDatabase::getConnection();
			
			$sql = "
				SELECT
					SUM(counter) as existing
				FROM
					(".implode(' UNION ALL ', $union).") as dbtables
				";
			
			$pdo_statement = $pdo_connection->query($sql);
			
			$result = $pdo_statement->fetch();
			
			return $result['existing'];
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
						images
					WHERE
						image_id = :image_id
					";
			
				$pdo_statement = $pdo_connection->prepare($sql);
				//bindParam is used so that SQL inputs are escaped.
				//This is to prevent SQL injections!
				$pdo_statement->bindParam(':image_id', $this->image_id, PDO::PARAM_INT);
				$pdo_statement->execute();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
}