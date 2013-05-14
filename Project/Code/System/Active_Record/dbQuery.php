<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/Pagination/pagination.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/ResultCleaner/resultCleaner.php';

class dbQuery
{	
	private $pages;
	private $current_page;
	private $posts_per_page;
	
//-----------------------pagination and sorting
	
	private $paginate;
	private $sort;
	
	private $total_row_count;
	public $total_number_of_pages;
	private $limit;
	private $offset;
	private $order_by_column;
	private $order_by;
	private $extra_query_postfix;
	
//-------------------------------------------------------------------------------------------------	

	public function insert($table, $insert_array)
	{
		if($table !== NULL && count($insert_array) > 0)
		{
			$column_names = array();
			
			foreach($insert_array as $column=>$values)
				$column_names[] = $column;
				
			$sql = "INSERT INTO
						$table
						(`".implode('`, `', $column_names)."`)
					VALUES
						(:".implode(", :", $column_names).")
					";
			
			return $this->executeSQL('insert', $sql, $insert_array);
		}
	}

//-------------------------------------------------------------------------------------------------	

	public function update($table, $update_array, $where = '', $where_array = array())
	{
		if($table !== NULL && count($update_array) > 0)
		{
			$sql = "UPDATE $table ";
			
			$update = array();
			
			foreach($update_array as $column => $value)
				$update[] = "$column = :$column";
			
			$sql .= " SET ".implode(', ', $update);
			
			//WHERE CLAUSE
			if(strlen($where) > 0)
				$sql .= $where;
			
			$this->executeSQL('update', $sql, array_merge($update_array, $where_array));
		}
	}
	
//-------------------------------------------------------------------------------------------------	

	public function delete($table, $where = '', $where_array = array())
	{
		if($table !== NULL)
		{
			$sql = "DELETE FROM $table ";
				
			//WHERE CLAUSE
			if(strlen($where) > 0)
				$sql .= $where;
			
			$this->executeSQL('delete', $sql, $where_array);
		}
	}
	
//==========================================================Pagination and Sorting Block===========================================================

	public function applyPagination($limit, $offset, $order_by_column = "", $order_by = "ASC", $extra_query_postfix = "")
	{
		$this->paginate = true;
		$this->limit = $limit;
		$this->offset = $offset;
		$this->order_by_column = $order_by_column;
		$this->order_by = $order_by;
		$this->extra_query_postfix = $extra_query_postfix;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function applySort($order_by_column = "", $order_by = "ASC", $extra_query_postfix = "")
	{
		$this->sort = true;
		$this->order_by_column = $order_by_column;
		$this->order_by = $order_by;
		$this->extra_query_postfix = $extra_query_postfix; 
	}
	
//-------------------------------------------------------------------------------------------------	

	private function paginate($sql = "")
	{
		if ($this->order_by_column != "")
			$sql = $this->sort($sql);
		
		if ($this->extra_query_postfix != "")
			$sql .= " $this->extra_query_postfix";
		
		$sql .= " LIMIT $this->limit OFFSET $this->offset ";
		return $sql;
	}
	
//-------------------------------------------------------------------------------------------------	

	private function sort($sql = "")
	{
		$sql .= " ORDER BY $this->order_by_column $this->order_by ";
		
		if ($this->extra_query_postfix != "")
			$sql .= " $this->extra_query_postfix";
		
		return $sql;
	}
	
//-------------------------------------------------------------------------------------------------	

	private function countAllRows($sql, $bindParams)
	{
		$sql = "SELECT COUNT(*) as row_count FROM ({$sql}) as thingy";
		//$sql = substr_replace($sql, ")", stripos($sql, "FROM")-1, 0);
	//	print($sql); die;
		$this->total_row_count = $this->executeSQL('select_one', $sql, $bindParams);
		$this->total_row_count = $this->total_row_count['row_count']; 
		$this->total_number_of_pages = ceil($this->total_row_count/$this->limit);
	}
	
//-------------------------------------------------------------------------------------------------	

	public function getNumberOfPages()
	{
		if (isset($this->total_number_of_pages))
			return $this->total_number_of_pages;
	}

//=====================================================================================================================

	public function executeSQL($command, $sql, $bindParams = array())
	{
		try
		{
			$pdo_connection	= starfishDatabase::getConnection();
			
			################################################################
			
			if ($this->paginate)
			{
				$this->paginate = false; //Resets paginate status to avoid recursion
				$this->countAllRows($sql, $bindParams);
				$sql = $this->paginate($sql);
			}
			elseif ($this->sort)
				$sql = $this->sort($sql);
			
			//print($sql);die;
			################################################################
			
			$pdo_statement	= $pdo_connection->prepare($sql);
			
			if(count($bindParams) > 0)
				foreach($bindParams as $column => $value)
					$pdo_statement->bindValue(':'.$column, $value, is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
			$pdo_statement->execute();
					
			if($command == 'select_many')
				return resultCleaner::cleanResults($pdo_statement->fetchAll(PDO::FETCH_ASSOC));

			elseif($command == 'fetchAllOnly')
				return $pdo_statement->fetchAll();
			
			elseif($command == 'select_one')
				return resultCleaner::cleanSingleResult($pdo_statement->fetch(PDO::FETCH_ASSOC));
			
			return $pdo_connection->lastInsertId();
		}
		catch(PDOException $pdoe)
		{
			throw new Exception($pdoe);
		}
	}
	
}

?>