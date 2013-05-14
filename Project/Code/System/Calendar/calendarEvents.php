<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Database/starfishDatabase.php';
require_once 'Project/Code/System/Active_Record/dbQuery.php';
require_once 'calendarEvent.php';

class calendarEvents extends dbQuery
{
	private $array_of_calendar_events; 
	
	private $select_statement;
	
//-------------------------------------------------------------------------------------------------	

	public function __get($field)
	{
		if(property_exists($this, $field)) return $this->{$field};
		
		else return NULL;
	}
	
//-------------------------------------------------------------------------------------------------	

	public function __set($field, $value) { if(property_exists($this, $field)) $this->{$field} = $value; }
	
//-------------------------------------------------------------------------------------------------

	public function __construct()
	{
		$this->deleteOnLapse();
		$this->triggerAlarm();
		
		$this->select_statement = 
			"SELECT
				c.calendar_alert_id,
				c.lead_id,
				c.hcp_id,
				c.set_for_date,
				c.status,
				c.action,
				c.staff_id,
				se.name as seeker_name,
				s.name as staff_name,
				h.name as hcp_name
			FROM
				calendar_alerts c
			INNER JOIN
				staffs s
			ON
				c.staff_id = s.staff_id
			LEFT JOIN
				health_care_providers h
			ON
				c.hcp_id = h.hcp_id
			LEFT JOIN
				seekers se
			ON
				c.lead_id = se.seeker_id
			";
	}
	
//-------------------------------------------------------------------------------------------------

	public function select()
	{
		$sql 		= $this->select_statement;
		
		$results	= $this->executeSQL('select_many', $sql);
		
		$this->saveResults($results);
	}
	
//-------------------------------------------------------------------------------------------------

	public function selectBy($field, $value)
	{
		$sql = "$this->select_statement
				WHERE
					`$field` = :$field
				";
		
		$bindParams	= array(
			$field	=> $value
		);
		
		$results	= $this->executeSQL('select_many', $sql, $bindParams);
		
		$this->saveResults($results);  
	}
	
//-------------------------------------------------------------------------------------------------

	public function selectByStaff($staff_id)
	{
		$sql = "$this->select_statement
				WHERE
					c.staff_id = :staff_id
				";
		
		$bindParams	= array(
			'staff_id'	=> $staff_id
		);
		
		$results	= $this->executeSQL('select_many', $sql, $bindParams);
		
		$this->saveResults($results);  
	}
	
//-------------------------------------------------------------------------------------------------

	public function selectByLead($staff_id, $lead_id)
	{
		$sql = "$this->select_statement
				WHERE
					s.role = 'admin'
				OR
				(
						c.staff_id = :staff_id
					AND
						c.lead_id = :lead_id
				)
				";
		
		$bindParams	= array(
			'staff_id'	=> $staff_id,
			'lead_id'	=> $lead_id
			);
		
		$results	= $this->executeSQL('select_many', $sql, $bindParams);
		
		$this->saveResults($results);
	}
	
	
	public function selectByLeadCalendar($lead_id)
	{
		$sql = "$this->select_statement
					WHERE
						s.role = 'admin'
					OR
					(
							c.lead_id = :lead_id
					)
					";
	
		$bindParams	= array(
				'lead_id'	=> $lead_id
		);
	
		$results	= $this->executeSQL('select_many', $sql, $bindParams);
	
		$this->saveResults($results);
	}
	
	
	//-------------------------------------------------------------------------------------------------
	
	public function selectByLeadAdmin()
	{
	$sql = "$this->select_statement
			";
			
	$bindParams	= array(
	);
	$results	= $this->executeSQL('select_many', $sql, $bindParams);
	$this->saveResults($results);
	}
	
//-------------------------------------------------------------------------------------------------

	public function selectByHCP($staff_id, $hcp_id)
	{
		$sql = "$this->select_statement
				WHERE
					c.staff_id = :staff_id
				AND
					c.hcp_id = :hcp_id
				";
		
		
		$bindParams	= array(
			'staff_id'	=> $staff_id,
			'hcp_id'	=> $hcp_id
			);
		
		$results	= $this->executeSQL('select_many', $sql, $bindParams);
		
		$this->saveResults($results);
	}
	
//-------------------------------------------------------------------------------------------------

	public function deleteOnLapse()
	{
		
		$sql			= "DELETE FROM 
								calendar_alerts 
							WHERE
								DATEDIFF(DATE(set_for_date), CURDATE()) < 0";
		
		$results	= $this->executeSQL('delete', $sql);
	}
	
//-------------------------------------------------------------------------------------------------

	public function triggerAlarm()
	{
		
		$sql			= "UPDATE
								calendar_alerts
							SET
								status = 'active'
							WHERE
								DATE(set_for_date) < CURRENT_TIMESTAMP()
						";
		
		$results	= $this->executeSQL('update', $sql);
	}
	
//-------------------------------------------------------------------------------------------------

	private function saveResults($results)
	{
		$this->array_of_calendar_events = array();
		
		foreach($results as $result)
		{
			$calendar = new calendarEvent();
			
			$calendar->__set('calendar_alert_id', $result['calendar_alert_id']);
			$calendar->__set('lead_id', $result['lead_id']);
			$calendar->__set('hcp_id', $result['hcp_id']);
			$calendar->__set('set_for_date', $result['set_for_date']);
			$calendar->__set('status', $result['status']);
			$calendar->__set('action', $result['action']);
			$calendar->__set('staff_id', $result['staff_id']);
			$calendar->__set('staff_name', $result['staff_name']);
			$calendar->__set('seeker_name', $result['seeker_name']);
			$calendar->__set('hcp_name', $result['hcp_name']);
			
			$this->array_of_calendar_events[] = $calendar;
		}
	}
}
