<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'leads.php';

class lead
{
	private $lead_id;
	private $seeker_id;
	private $staff_id;
	private $date_of_inquiry;
	private $house_type_id;
	private $house_type;
	private $relationship;
	private $status;
	private $urgent;
	private $name;
	private $areastate;
	
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
		$sql = "SELECT
					*
				FROM
					leads
				WHERE
					lead_id	=	:lead_id  
				";
		
		$db			= new dbQuery();
		$bindParams	= array('lead_id'		=>		$this->lead_id);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
		
		$this->date_of_inquiry	= $result['date_of_inquiry'];
		$this->lead_id			= $result['lead_id'];
		$this->staff_id			= $result['staff_id'];
		$this->status			= $result['status'];
		$this->urgent			= $result['urgent'];
		
		return $result;
	}
	
//-------------------------------------------------------------------------------------------------

	public function insert($lead_id)
	{
		$insert_array = array(
			'lead_id'						=> $lead_id
		);
		
		$db			= new dbQuery();
		$db->insert('leads', $insert_array);
	}
	
//-------------------------------------------------------------------------------------------------

	public function update()
	{
		$update_array = array(
			'status' => $this->status,
		);
		
		$where_array	= array('lead_id' => $this->lead_id);
		$sql_where		=	" WHERE lead_id = :lead_id ";
		
		$db			= new dbQuery();
		$db->update('leads', $update_array, $sql_where, $where_array);
	}

//-------------------------------------------------------------------------------------------------
	
	public function updateGeneric($update_array)
	{
		$where_array	= array('lead_id' => $this->lead_id);
		$sql_where		=	" WHERE lead_id = :lead_id ";
	
		$db			= new dbQuery();
		$db->update('leads', $update_array, $sql_where, $where_array);
	}
	
//-------------------------------------------------------------------------------------------------

	public function delete()
	{
		
		$where_array 		=  array(
			'lead_id'		=> $this->lead_id
		);
		
		$sql_where			= "WHERE
									lead_id	=	:lead_id";
		
		$db					= new dbQuery();
		$db->delete('leads', $sql_where, $where_array);
		
	}
	
	public function singleSelect($column = 'lead_id')
	{
		$sql = "SELECT $column FROM leads WHERE lead_id = :lead_id";
	
		$db			= new dbQuery();
		$bindParams	= array('lead_id'		=>		$this->lead_id);
	
		return $db->executeSQL('select_one', $sql, $bindParams);
	}
}
?>