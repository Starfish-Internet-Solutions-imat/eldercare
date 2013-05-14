<?php 
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'calendarBlockView.php';

class calendarBlockController extends applicationsSuperController
{
	private $staff_id;
	private $lead_id;
	private $hcp_id;
	
	private $view;

//-------------------------------------------------------------------------------------------------	
	
	public function __construct($staff_id, $lead_id, $hcp_id)
	{
		$this->staff_id	= $staff_id;
		$this->lead_id	= $lead_id;
		$this->hcp_id	= $hcp_id;
		
		$this->view			= new calendarBlockView();
		
		$this->view->_set('lead_id', $lead_id);
		$this->view->_set('hcp_id', $hcp_id);
	}

//-------------------------------------------------------------------------------------------------	
	
	public function getCalendar()
	{
		$this->getCalendarData(2, 0);
		$this->view->displayApplicationLayout();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function getCalendarRow($limit, $offset)
	{
		$this->getCalendarData($limit, $offset);
		
		return $this->view->displayCalendarRow();
	}

//-------------------------------------------------------------------------------------------------	
	
	public function getAlarmForm()
	{
		$this->view->displayCalendarAlarmForm();
	}

//-------------------------------------------------------------------------------------------------	
	
	private function getCalendarData($limit, $offset)
	{
		require_once 'Project/Code/System/Calendar/calendarEvents.php';
		
		$calendar_events = new calendarEvents();
		//$calendar_events->applyPagination($limit, $offset);
		
		if($this->hcp_id === NULL)
				$calendar_events->selectByLeadCalendar($this->lead_id);
		else
			$calendar_events->selectByHCP($this->staff_id, $this->hcp_id);
		
		$array_of_calendar_events = $calendar_events->__get('array_of_calendar_events');
		
		$this->view->_set('calendar_events', $array_of_calendar_events);
		$this->view->_set('pages_calendar_events', $calendar_events->getNumberOfPages());
	}
}
?>