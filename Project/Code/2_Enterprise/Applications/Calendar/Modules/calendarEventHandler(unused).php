<?php 

require_once 'Project/Code/System/Calendar/calendarEvent.php';

class calendarEventHandler
{

//-------------------------------------------------------------------------------------------------	
	
	public static function insert()
	{
		$calendar = new calendarEvent();
		
		$calendar->__set('action', $_POST['action']);
		$calendar->__set('staff_id', $_POST['staff_id']);

		if(isset($_POST['lead_id']))
			$calendar->__set('lead_id', $_POST['lead_id']);
		
		if(isset($_POST['hcp_id']))
			$calendar->__set('hcp_id', $_POST['hcp_id']);
		
		$calendar->insert();
	}

//-------------------------------------------------------------------------------------------------	
	
	public static function update()
	{
		$calendar = new calendarEvent();
		
		$calendar->__set('action', $_POST['action']);
		$calendar->__set('staff_id', $_POST['staff_id']);

		if(isset($_POST['lead_id']))
			$calendar->__set('lead_id', $_POST['lead_id']);
		
		if(isset($_POST['hcp_id']))
			$calendar->__set('hcp_id', $_POST['hcp_id']);
		
		//$calendar->insert();
	}
}
?>