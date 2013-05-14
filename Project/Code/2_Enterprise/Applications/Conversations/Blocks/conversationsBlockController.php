<?php 
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'conversationsBlockView.php';


class conversationsBlockController extends applicationsSuperController
{
	private $seeker_id;
	private $hcp_id;
	private $view;

//-------------------------------------------------------------------------------------------------	
	
	public function __construct($staff_id, $seeker_id, $hcp_id)
	{
		$this->seeker_id	= $seeker_id;
		$this->hcp_id		= $hcp_id;
		
		$this->view			= new conversationsBlockView();
		$this->view->_set('seeker_id', $seeker_id);
		$this->view->_set('hcp_id', $hcp_id);
	}

//-------------------------------------------------------------------------------------------------	
	
	public function getConversationHistory()
	{
		$this->view->displayApplicationLayout();
	}
}
?>