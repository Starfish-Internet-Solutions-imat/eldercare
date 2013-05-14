<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';

class hcp_conversation
{
	private $hcp_id;
	private $staff_id;
	private $conversation;
	private $timestamp;
	
//-------------------------------------------------------------------------------------------------	

	public function __get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function __set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------

	/* public function select()
	{
		$sql = "SELECT
					*
				FROM
					hcp_conversations lc
				WHERE
					hcp_id	= :hcp_id  
				";
		
		$db			= new dbQuery();
		$bindParams	= array('hcp_id'		=>		$this->hcp_id);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
		
		$this->hcp_id			= $result['hcp_id'];
		$this->staff_id			= $result['staff_id'];
		$this->conversation		= $result['conversation'];
		$this->timestamp		= $result['timestamp'];
		
		return $result;
	} */
	
//-------------------------------------------------------------------------------------------------

	public function insert()
	{
		$insert_array = array(
			'hcp_id'		=> $this->hcp_id,
			'staff_id'		=> $this->staff_id,
			'conversation'	=> $this->conversation
		);
		
		$db			= new dbQuery();
		$db->insert('hcp_conversations', $insert_array);
	}
	
//-------------------------------------------------------------------------------------------------

	/* public static function delete($staff_id, $hcp_id)
	{
		
		$where_array 		=  array(
			'hcp_id'		=> $hcp_id,
			'staff_id'		=> $staff_id,
		);
		
		$sql_where			= "WHERE
									hcp_id	=	:hcp_id";
		
		$db					= new dbQuery();
		$db->delete('hcps', $sql_where, $where_array);
		
	} */
}
?>