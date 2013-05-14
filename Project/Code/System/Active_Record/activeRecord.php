<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/Pagination/pagination.php';
require_once FILE_ACCESS_CORE_CODE.'/Objects/ResultCleaner/resultCleaner.php';

class activeRecord
{
	private $select_array;
	private $from_array;
	private $insert_array;
	private $update_array;
	private $where_array;
	private $join_array;
	private $orderby_array;
	private $groupby_array;
	
	private $pages;
	private $current_page;
	private $posts_per_page;
	
//-------------------------------------------------------------------------------------------------	

	public function __construct()
	{
		$this->select_array		= array('*');
		$this->from_array		= array();
		$this->insert_array		= array();
		$this->update_array		= array();
		$this->where_array		= array('AND'=>array(),'OR'=>array());
		$this->join_array		= array();
		$this->orderby_array	= array();
		$this->groupby_array	= array();
	}
	
//-------------------------------------------------------------------------------------------------	

	public function setSelect($select) { $this->select_array = $select; }
	
//-------------------------------------------------------------------------------------------------	

	public function setFrom($from)
	{
		if(is_array($from))
			$this->from_array = $from;
		
		else
			$this->from_array = array($from);
	}
	
//-------------------------------------------------------------------------------------------------	

	public function setWhere($column, $value, $operator = '=', $function = '')
	{
		$value = addslashes(trim($value));
		
		if($function == '')
			array_push($this->where_array['AND'], "$column $operator '$value'");
		
		else
			array_push($this->where_array['AND'], "$column $operator $function('$value')");
	}
	
//-------------------------------------------------------------------------------------------------	

	public function setWhereOr($column, $value, $operator = '=', $function = '')
	{
		$value = addslashes(trim($value));
		
		if($function == '')
			array_push($this->where_array['OR'], "$column $operator '$value'");
		
		else
			array_push($this->where_array['OR'], "$column $operator $function('$value')");
	}
	
//-------------------------------------------------------------------------------------------------	

	public function setInsert($insert) { $this->insert_array = $insert; }
	
//-------------------------------------------------------------------------------------------------	

	public function setUpdate($update) { $this->update_array = $update; }
	
//-------------------------------------------------------------------------------------------------	

	public function setOrderBy($orderby) { $this->orderby_array = $orderby; }
	
//-------------------------------------------------------------------------------------------------	

	public function setGroupBy($groupby) { $this->groupby_array = $groupby; }
	
//-------------------------------------------------------------------------------------------------	

	public function setJoin($table1, $table2, $on, $type = 'JOIN')
	{ 
		$this->join_array[] = "$type $table2 ON $table1.$on = $table2.$on"; 
	}
	
//-------------------------------------------------------------------------------------------------	

	public function selectOne() { return $this->select('select_one'); }
	
//-------------------------------------------------------------------------------------------------	

	public function selectMany() { return $this->select(); }
	
//-------------------------------------------------------------------------------------------------	

	private function select($command = 'select_many')
	{
		if(count($this->from_array) > 0)
		{
			$select = implode(', ', $this->select_array);
			$from	= implode(', ', $this->from_array);
			
			$sql = "SELECT
						$select
					FROM
						$from";
			
			//JOIN CLAUSE
			$sql .= $this->addJoinClause();
			
			//WHERE CLAUSE
			$sql .= $this->addWhereClause();
			
			//ORDER BY CLAUSE
			$sql .= $this->addOrderByClause();
			
			return $this->executeSQL($command, $sql);
		}
	}
	
//-------------------------------------------------------------------------------------------------	

	public function insert()
	{
		
		if(count($this->from_array) > 0 && count($this->insert_array) > 0)
		{
			$from	= implode(', ', $this->from_array);
			
			$sql = "INSERT INTO
						$from
						(`".implode('`, `', $column_names)."`)
					VALUES
						(:".implode(", :", $column_names).")
					";
			
			return $this->executeSQL('insert', $sql);
		}
	}
	
//-------------------------------------------------------------------------------------------------	

	public function update()
	{
		
		if(count($this->from_array) > 0 && count($this->update_array) > 0)
		{
			$from	= implode(', ', $this->from_array);
			
			$sql = "UPDATE $from";
			
			$update = array();
			
			foreach($this->update_array as $column => $value)
				$update[] = "$column = :$value";
			
			$sql .= " SET".implode(', ', $update);
			
			//WHERE CLAUSE
			$sql .= $this->addWhereClause();
			
			$this->executeSQL('update', $sql);
		}
	}
	
//-------------------------------------------------------------------------------------------------	

	public function delete()
	{
		$from = implode(', ', $this->from_array);
		
		$sql = "DELETE FROM $from";
		
		//WHERE CLAUSE
		$sql .= $this->addWhereClause();
		
		$this->executeSQL('delete', $sql);
	}
	
//-------------------------------------------------------------------------------------------------	

	private function addWhereClause()
	{
		$sql = "";
		
		if(count($this->where_array['AND']) > 0)
			$sql .= implode(' AND ', $this->where_array['AND']);
		
		if(count($this->where_array['OR']) > 0)
			$sql .= implode(' OR ', $this->where_array['OR']);
		
		if(count($this->where_array['AND']) > 0 || count($this->where_array['OR']) > 0)
			$sql = " WHERE ".$sql;
		
		return $sql;
	}
	
//-------------------------------------------------------------------------------------------------	

	private function addJoinClause()
	{
		if(count($this->join_array) > 0)
			return ' '.implode(' ', $this->join_array);
		
		return "";
	}
	
//-------------------------------------------------------------------------------------------------	

	private function addOrderByClause()
	{
		if(count($this->orderby_array) > 0)
		{
			$orderby = array();
			
			foreach($this->orderby_array as $column => $order)
				$orderby[] = "$column $order";
			
			$sql .= " ORDER BY ".implode(', ', $orderby);
		}
		
		return "";
	}
	
//-------------------------------------------------------------------------------------------------	

	private function addGroupByClause()
	{
		if(count($this->groupby_array) > 0)
			return " GROUP BY ".implode(', ', $this->groupby_array);
		
		return "";
	}
	
//-------------------------------------------------------------------------------------------------	

	public function executeSQL($command, $sql)
	{
		try
		{
			$pdo_connection	= starfishDatabase::getConnection();
			$pdo_statement	= $pdo_connection->prepare($sql);
			
			if($command == 'insert' && count($this->insert_array) > 0)
				foreach($this->insert_array as $column => $value)
					$pdo_statement->bindValue(':'.$column, $value, is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
			
			if($command == 'update' && count($this->update_array) > 0)
				foreach($this->update_array as $column => $value)
					$pdo_statement->bindValue(':'.$column, $value, is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
			
			$pdo_statement->execute();
					
			if($command == 'select_many')
				return resultCleaner::cleanResults($pdo_statement->fetchAll(PDO::FETCH_ASSOC));
			
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