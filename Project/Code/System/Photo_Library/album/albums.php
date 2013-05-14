<?php
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/ResultCleaner/resultCleaner.php';
require_once 'Project/Code/System/Photo_Library/album_size/album_size.php';
require_once 'Project/Code/System/Photo_Library/image/image.php';
require_once 'album.php';

class albums
{
	private $array_of_albums = array();
	
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
						a.album_id,
						s.size_id,
						i.image_id,
						album_title,
						album_folder,
						dimensions,
						filename,
						filename_ext
					FROM
						images i
					LEFT JOIN
						album_image_sizes s
					ON
						i.size_id = s.size_id
					RIGHT JOIN
						albums a
					ON
						a.album_id = s.album_id
					GROUP BY
						a.album_id
					";
			
				$pdo_statement = $pdo_connection->prepare($sql);
				$pdo_statement->execute();
			
				$results = resultCleaner::cleanResults($pdo_statement->fetchAll(PDO::FETCH_ASSOC));
				
				foreach($results as $result)
				{
					$album		= new album();
					$album_size	= new album_size();
					$image		= new image();
					
					$album->__set('album_id', $result['album_id']);
					$album->__set('album_title', $result['album_title']);
					$album->__set('album_folder', $result['album_folder']);
					
					$album_size->__set('size_id', $result['size_id']);
					$album_size->select();
					
					$image->__set('image_id', $result['image_id']);
					$image->select();
					
					$album->__set('size_preview', $album_size);
					$album->__set('image_preview', $image);
					$album->__set('image_extension', $result['filename_ext']);
					
					$this->array_of_albums[] = $album;
				}
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
}