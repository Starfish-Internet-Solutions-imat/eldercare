<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';

class searchModel extends dbQuery
{
	private $hcp_id;
	private $name;
	private $date_updated;
	private $date_created;
	private $state;
	private $zipcode;
	private $pricing;
	private $published;
	private $house_type;
	private $seeker_id;
	
	private $array_of_results;
	
//-------------------------------------------------------------------------------------------------	

	public function __construct()
	{
		$this->hcp_id			= '';
		$this->name				= '';
		$this->date_updated		= '';
		$this->date_created		= '';
		$this->state			= '';
		$this->zipcode			= '';
		$this->price_from		= '';
		$this->price_to			= '';
		$this->published		= '';
		$this->house_type		= '';
		
		$this->array_of_results = array();
	}
	
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
		$search_criteria = 0;
		
		$where_columns = array(
			'name'			=> $this->name,
			'state'			=> $this->state,
			'zipcode' 		=> $this->zipcode,
			'price_from' 	=> $this->price_from,
			'price_to'		=> $this->price_to,
			'house_type_id'	=> $this->house_type
		);
		
		try
		{
	
			
			$sql = "SELECT DISTINCT
						h.hcp_id,
						h.name,
						h.date_updated,
						h.date_created,
						z.state,
						z.zipcode,
						h.pricing,
						h.published
					FROM
						health_care_providers h
					LEFT JOIN
						zipcodes z
					ON
						h.zipcode = z.id
					INNER JOIN
						hcp_house_types ht
					ON
						h.hcp_id = ht.hcp_id
					WHERE
						z.id != 43630
						";
					
			$sql_where = array();
			
			foreach($where_columns as $key => $value)
				if(!empty($value))
				{
					$column = $key;
					
					if($key == 'zipcode')
						$column = 'z.'.$key;
					
					if(is_numeric($value))
						$sql_where[] = "$column = :$key";
					
					elseif(is_array($value))
						$sql_where[] = "$column IN (".implode(', ', $value).")";
					
					else
						$sql_where[] = "lower($column) LIKE lower(:$key)";
					
					$search_criteria++;
				}
			
			$sql_where = implode(' AND ', $sql_where);
			
			if($search_criteria > 0)
				$sql .= " WHERE ".$sql_where;
			
			$bindParams	= $where_columns;
			$this->applySort('date_created', 'DESC');
			$results	= $this->executeSQL('select_many', $sql, $bindParams);
			
			foreach ($results as $result)
			{
				$model = new searchModel();
				
				$model->__set('hcp_id', $result['hcp_id']);
				$model->__set('name', $result['name']);
				$model->__set('date_updated', $result['date_updated']);
				$model->__set('date_created', $result['date_created']);
				$model->__set('state', $result['state']);
				$model->__set('zipcode', $result['zipcode']);
				$model->__set('pricing', $result['pricing']);
				$model->__set('published', $result['published']);
				
				$this->array_of_results[] = $model;
			}
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
	public function insertOnLeadsPotentialHCP()
	{
		try{
			$pdo_connection = starfishDatabase::getConnection();
			$pdo_connection->beginTransaction();
				
			$sql = "
							INSERT INTO
								leads_potential_hcp
								(
									`lead_id`,
									`hcp_id`
								)
							VALUES
								(
									:lead_id,
									:hcp_id
								)
							";
				
			$pdo_statement = $pdo_connection->prepare($sql);
			$pdo_statement->bindParam(':lead_id', $this->seeker_id, PDO::PARAM_INT);
			$pdo_statement->bindParam(':hcp_id', $this->hcp_id, PDO::PARAM_INT);
			$pdo_statement->execute();
				
			$this->user_account_id = $pdo_connection->lastInsertId();
			$pdo_connection->commit();
				
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
		
	}
}

?>