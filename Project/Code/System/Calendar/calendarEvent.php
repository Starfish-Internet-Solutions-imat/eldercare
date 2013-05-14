<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';

class calendarEvent
{
	private $calendar_alert_id;
	private $lead_id;
	private $hcp_id;
	private $set_for_date;
	private $action;
	private $status;
	private $staff_id;
	private $staff_name;
	private $seeker_name;
	private $hcp_name;
	
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
					calendar_alerts ca
				WHERE
					ca.calendar_alert_id	=	:calendar_alert_id  
				";
		
		$db			= new dbQuery();
		$bindParams	= array('calendar_alert_id'		=>		$this->calendar_alert_id);
		$result		= $db->executeSQL('select_one', $sql, $bindParams);
		
		$this->lead_id			= $result['lead_id'];
		$this->staff_id			= $result['staff_id'];
		$this->hcp_id			= $result['hcp_id'];
		$this->status			= $result['status'];
		$this->set_for_date		= $result['set_for_date'];
		$this->action			= $result['action'];
		
		return $result;
	}
	
//-------------------------------------------------------------------------------------------------

	public function insert()
	{
		$insert_array = array(
			'lead_id'		=> $this->lead_id,
			'hcp_id'		=> $this->hcp_id,
			'staff_id'		=> $this->staff_id,
			'action'		=> $this->action,
			'set_for_date'	=> $this->set_for_date
		);
		
		$db			= new dbQuery();
		$db->insert('calendar_alerts', $insert_array);
	}
	
//-------------------------------------------------------------------------------------------------

	public function update()
	{
		$update_array = array(
			'action' 						=> $this->action,
			'set_for_date'					=> $this->set_for_date,
		);
		
		$where_array	= array('calendar_alert_id' 	=> $this->calendar_alert_id);
		$sql_where		=	" WHERE calendar_alert_id 	= :calendar_alert_id ";
		
		$db			= new dbQuery();
		$db->update('calendar_alerts', $update_array, $sql_where, $where_array);
	}
	
	
	public function updateStatus()
	{
		$update_array = array(
				'status' 						=> $this->status
		);
	
		$where_array	= array('calendar_alert_id' 	=> $this->calendar_alert_id);
		$sql_where		=	" WHERE calendar_alert_id 	= :calendar_alert_id ";
	
		$db			= new dbQuery();
		$db->update('calendar_alerts', $update_array, $sql_where, $where_array);
	}
//-------------------------------------------------------------------------------------------------

	public function delete()
	{
		
		$where_array 				=  array(
			'calendar_alert_id'		=> $this->calendar_alert_id
		);
		
		$sql_where			= "WHERE calendar_alert_id	=	:calendar_alert_id";
		
		$db					= new dbQuery();
		$db->delete('calendar_alerts', $sql_where, $where_array);
		
	}
}
?>