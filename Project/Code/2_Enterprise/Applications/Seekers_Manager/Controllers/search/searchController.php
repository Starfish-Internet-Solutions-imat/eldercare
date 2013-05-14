<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'searchView.php';

require_once 'Project/Code/ApplicationsFramework/FrontController/Router/applicationsRoutes.php';
require_once 'Project/Code/System/Seeker/seekers.php';

class searchController extends applicationsSuperController
{
	public function indexAction()
	{
		if(isset($_GET['page']))
			$page = $_GET['page'] - 1;
		else
			$page = 0;
		
		$seekers = new seekers();
		$seekers->applyPagination(15, $page * 15);
		
		if(authorization::getUserSession()->user_role == 'admin')
			$array_of_seekers = $seekers->selectWithLeads(false);
		else 
			$array_of_seekers = $seekers->selectWithLeads(true);
		
		$view = new searchView();
		$view->setCurrentPage($page+1);
		$view->setNumberOfPages($seekers->getNumberOfPages());
		$view->_set('seekers', $array_of_seekers);
		$view->displayPagination($view->getNumberOfPages(), $view->getCurrentPage());
		$view->displayApplicationLayout();
	}

//-------------------------------------------------------------------------------------------------	

	public function sort_byAction()
	{
		if(isset($_GET['page']))
			$page = $_GET['page'] - 1;
		else
			$page = 0;
		
		$seekers = new seekers();
		$seekers->applyPagination(15, $page * 15);
		
		$order_by = $this->getValueOfURLParameterPair('sort_by');
		
		if($order_by=="join")
			$order_by = "date_of_inquiry";
		else if($order_by=="status")
			$order_by = "status";
		else if($order_by=="consultant")
			$order_by = "consultant";
		else if($order_by=="seeker_name")
			$order_by = "name";
		
		if(authorization::getUserSession()->user_role == 'admin')
			$array_of_seekers = $seekers->selectWithLeadsOnOrder(false,$order_by);
		else
			$array_of_seekers = $seekers->selectWithLeadsOnOrder(true,$order_by);
		
		$view = new searchView();
		$view->setCurrentPage($page+1);
		$view->setNumberOfPages($seekers->getNumberOfPages());
		$view->_set('seekers', $array_of_seekers);
		$view->displayPagination($view->getNumberOfPages(), $view->getCurrentPage());
		$view->displayApplicationLayout();
	}
	
	
}