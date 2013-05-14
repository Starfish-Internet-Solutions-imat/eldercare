<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';

class potential_hcp
{
	private $potential_hcp_id;
	private $lead_id;
	private $hcp_id;
	private $status;
	private $position;
	
	private $hcp_name;
	private $hcp_suspend_status;
	private $seeker_name;
	
	private $potential_hcp_array = array();
	
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
					potential_hcp_id,
					lead_id,
					l.hcp_id,
					status,
					position,
					s.name as seeker_name,
					h.name as hcp_name
				FROM
					leads_potential_hcp l
				INNER JOIN
					seekers s
				ON
					s.seeker_id = l.lead_id
				INNER JOIN
					health_care_providers h
				ON
					l.hcp_id = h.hcp_id
				WHERE
					lead_id = :lead_id
				";
		
		$db			= new dbQuery();
		$bindParams	= array('lead_id'		=>		$this->lead_id);
		
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
		
		$this->potential_hcp_id	= $result['potential_hcp_id'];
		$this->lead_id			= $result['lead_id'];
		$this->hcp_id			= $result['hcp_id'];
		$this->status			= $result['status'];
		$this->position			= $result['position'];
		$this->seeker_name		= $result['seeker_name'];
		$this->hcp_name			= $result['hcp_name'];
	
		return $result;
	}
	

//-------------------------------------------------------------------------------------------------

	public function insert()
	{
		$insert_array = array(
			'lead_id'						=> $this->lead_id,
			'hcp_id'						=> $this->hcp_id
		);
		
		$db			= new dbQuery();
		$db->insert('leads_potential_hcp', $insert_array);
	}
	
	
	
//-------------------------------------------------------------------------------------------------

	public function update()
	{
		$update_array = array(
			'status' => $this->status
		);
		
		$where_array	= array('lead_id' => $this->lead_id);
		$sql_where		=	" WHERE lead_id = :lead_id ";
		
		$db			= new dbQuery();
		$db->update('leads_potential_hcp', $update_array, $sql_where, $where_array);
	}
	
//-------------------------------------------------------------------------------------------------
	
	public function updateGeneric($update_array)
	{
		$where_array	= array('potential_hcp_id' => $this->potential_hcp_id);
		$sql_where		=	" WHERE potential_hcp_id = :potential_hcp_id";
	
		$db			= new dbQuery();
		$db->update('leads_potential_hcp', $update_array, $sql_where, $where_array);
	}
	
//-------------------------------------------------------------------------------------------------

	public function delete()
	{
		
		$where_array 		=  array(
		
			'potential_hcp_id'		=> $this->potential_hcp_id
		
		);
		
		$sql_where			= "WHERE
									potential_hcp_id	=	:potential_hcp_id";
		
		$db					= new dbQuery();
		$db->delete('leads_potential_hcp', $sql_where, $where_array);
		
	}
	
	public function singleSelect($column = 'lead_id')
	{
		$sql = "SELECT $column FROM leads_potential_hcp WHERE potential_hcp_id = :potential_hcp_id";
		
		$db			= new dbQuery();
		$bindParams	= array('potential_hcp_id'		=>		$this->potential_hcp_id);
		
		return $db->executeSQL('select_one', $sql, $bindParams);
	}
	
}
?>