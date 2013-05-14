<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';

class lead_conversation
{
	private $lead_id;
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
					lead_conversations lc
				WHERE
					lead_id	= :lead_id  
				";
		
		$db			= new dbQuery();
		$bindParams	= array('lead_id'		=>		$this->lead_id);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
		
		$this->lead_id			= $result['lead_id'];
		$this->staff_id			= $result['staff_id'];
		$this->conversation		= $result['conversation'];
		$this->timestamp		= $result['timestamp'];
		
		return $result;
	} */
	
//-------------------------------------------------------------------------------------------------

	public function insert()
	{
		$insert_array = array(
			'lead_id'		=> $this->lead_id,
			'staff_id'		=> $this->staff_id,
			'conversation'	=> $this->conversation
		);
		
		$db			= new dbQuery();
		$db->insert('lead_conversations', $insert_array);
	}
	
//-------------------------------------------------------------------------------------------------

	/* public static function delete($staff_id, $lead_id)
	{
		
		$where_array 		=  array(
			'lead_id'		=> $lead_id,
			'staff_id'		=> $staff_id,
		);
		
		$sql_where			= "WHERE
									lead_id	=	:lead_id";
		
		$db					= new dbQuery();
		$db->delete('leads', $sql_where, $where_array);
		
	} */
}
?>