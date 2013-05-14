<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'lead_conversation.php';

class lead_conversations
{
	private $array_of_conversations;
	
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
					lead_conversations lc
				";
		
		$db			= new dbQuery();
		$results	= $db->executeSQL('select_many', $sql);
		
		$this->saveResults($results);
		
		return $results;
	}
	
//-------------------------------------------------------------------------------------------------

	public function selectByStaffIDAndLeadID($staff_id, $lead_id)
	{
		$sql = "SELECT
					lc.*, s.*
				FROM
					lead_conversations lc
				INNER JOIN
					staffs s
				ON
					s.staff_id = lc.staff_id
				WHERE
						lc.lead_id = :lead_id
				ORDER BY timestamp DESC
				";
		
		$db			= new dbQuery();
		
		$bindParams	= array(
		//	'staff_id'	=> $staff_id,
			'lead_id'	=> $lead_id
		);
		
		$results		= $db->executeSQL('select_many', $sql, $bindParams);
		$this->saveResults($results);
		
		return $results;
	}
	
//-------------------------------------------------------------------------------------------------

	private function saveResults($results)
	{
		$this->array_of_conversations = array();
		
		foreach($results as $result)
		{
			$conversation = new lead_conversation();
			
			$conversation->__set('lead_id', $result['lead_id']);
			$conversation->__set('staff_id', $result['staff_id']);
			$conversation->__set('conversation', $result['conversation']);
			$conversation->__set('timestamp', $result['timestamp']);
			
			$this->array_of_conversations[] = $conversation;
		}
	}
	
	
	
}
