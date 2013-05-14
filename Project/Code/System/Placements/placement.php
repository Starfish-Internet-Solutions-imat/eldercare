<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'placements.php';

class placement
{
	private $placement_id;
	private $seeker_id;
	private $hcp_id;
	private $invoice_number;
	private $status;
	private $potential_hcp_id;
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
					placements
				WHERE
					placement_id	=	:placement_id  
				";
		
		$db			= new dbQuery();
		$bindParams	= array('placement_id'		=>		$this->placement_id);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
		
		$this->placement_id		= $result['placement_id'];
		$this->seeker_id		= $result['seeker_id'];
		$this->hcp_id			= $result['hcp_id'];
		$this->invoice_number	= $result['invoice_number'];
		$this->status			= $result['status'];
		
		return $result;
	}
	
//-------------------------------------------------------------------------------------------------

	public function insert()
	{
		$insert_array = array(
			'seeker_id'			=> $this->seeker_id,
			'hcp_id'			=> $this->hcp_id,
			'invoice_number'	=> $this->invoice_number,
		);
		
		$db			= new dbQuery();
		$db->insert('placements', $insert_array);
	}
	
//-------------------------------------------------------------------------------------------------

	public function update()
	{
		$update_array = array(
			'invoice_number'	=> $this->invoice_number,
			'status'			=> $this->status
		);
		
		$where_array	= array('placement_id' => $this->placement_id);
		$sql_where		=	" WHERE placement_id = :placement_id ";
		
		$db			= new dbQuery();
		$db->update('placements', $update_array, $sql_where, $where_array);
	}
	

	

//-------------------------------------------------------------------------------------------------
	
	public function updateGeneric($update_array)
	{
		$where_array	= array('placement_id' => $this->placement_id);
		$sql_where		=	" WHERE placement_id = :placement_id ";
	
		$db			= new dbQuery();
		$db->update('placements', $update_array, $sql_where, $where_array);
	}
	
//-------------------------------------------------------------------------------------------------

	public function delete()
	{
		
		$where_array 		=  array(
			'placement_id'		=> $this->placement_id
		);
		
		$sql_where			= "WHERE
									placement_id	=	:placement_id";
		
		$db					= new dbQuery();
		$db->delete('placements', $sql_where, $where_array);	
	}
//-------------------------------------------------------------------------------------------------------

	public function addPlacement()
	{
		$insert_array = array(
					'seeker_id'			=> 42,
					'hcp_id'			=> 4,
					'invoice_number'	=> '',
					'date_and_time'		=> date('Y-m-d H:i:s'),
					'potential_hcp_id' => $this->potential_hcp_id
		);
		
		$db			= new dbQuery();
		$db->insert('placements', $insert_array);
	}
	
//---------------------------------------------------------------------------------------------------------

	
	public function deletePlacement()
	{
		$where_array = array(
			'potential_hcp_id'=> $this->potential_hcp_id
		);
		
		$sql_where			= "WHERE
										potential_hcp_id	=	:potential_hcp_id";
				
				$db					= new dbQuery();
		$db->delete('placements', $sql_where, $where_array);
	}
	
	
	
}
?>