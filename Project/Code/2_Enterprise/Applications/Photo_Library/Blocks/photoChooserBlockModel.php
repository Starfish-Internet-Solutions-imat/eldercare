<?php
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';

class photoChooserBlockModel
{
	private $album_id;
	private $size_id;
	private $image_id;
	private $image_caption;
	private $album_folder;
	private $dimensions;
	private $filename;
	private $filename_ext;
	
	private $full_path;
	private $used_for;
	
	private $array_of_images = array();
	
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
						album_folder,
						dimensions,
						image_caption,
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
					WHERE
						a.album_id = :album_id
					ORDER BY
						s.size_id ASC
					";
			
				$pdo_statement = $pdo_connection->prepare($sql);
				$pdo_statement->bindParam(':album_id', $this->album_id, PDO::PARAM_INT);
				$pdo_statement->execute();
			
				$results = resultCleaner::cleanResults($pdo_statement->fetchAll(PDO::FETCH_ASSOC));
				
				foreach($results as $result)
				{
					$model = new photoChooserBlockModel();
					
					$model->__set('album_id', $result['album_id']);
					$model->__set('size_id', $result['size_id']);
					$model->__set('image_id', $result['image_id']);
					$model->__set('album_folder', $result['album_folder']);
					$model->__set('dimensions', $result['dimensions']);
					$model->__set('image_caption', $result['image_caption']);
					$model->__set('filename', $result['filename']);
					$model->__set('filename_ext', $result['filename_ext']);
					
					$this->array_of_images[] = $model;
				}
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);	
		}
	}
}