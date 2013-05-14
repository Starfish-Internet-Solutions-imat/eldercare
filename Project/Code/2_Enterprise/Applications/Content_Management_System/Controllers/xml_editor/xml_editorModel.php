<?php

require_once FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/modelSuperClass_Core/modelSuperClass_Core.php';

class xml_editorModel extends modelSuperClass_Core
{
	private  $fileNameOfPageXML;
	
	public function getPageXML($pagesXML)
	{
		if (isset($_GET['page_selected']))
		{
			$page_selected = $_GET['page_selected'];
	
			//load the correct page
	
			$navigationNamesArray = $pagesXML->xpath("/pages/navigation_group/page[page_id='".$page_selected."']/../navigation_group_id");
			$dataHandler = new dataHandler();
			
			if(count($navigationNamesArray) < 1)
			{
				$navigationNamesArray	= $this->getNavigationNameGroupName($pagesXML, $page_selected);
				$currentNavigationGroup = strval($navigationNamesArray[0]);
				$parent_pageArray		= $pagesXML->xpath("//page[page_id='".$page_selected."']/../page_id");
				$parent_page			= strval($parent_pageArray[0]);
				
				$this->fileNameOfPageXML = 'Data/'.PRIMARY_DOMAIN.'/Content/Pages/'.$currentNavigationGroup.'/'.$parent_page.'/'.$page_selected.'.xml';
				$pageXML = $dataHandler->loadDataSimpleXML($this->fileNameOfPageXML);

			}
			
			else
			{
				$currentNavigationGroup = strval($navigationNamesArray[0]);
				$this->fileNameOfPageXML = 'Data/'.PRIMARY_DOMAIN.'/Content/Pages/'.$currentNavigationGroup.'/'.$page_selected.'/data.xml';
				$pageXML = $dataHandler->loadDataSimpleXML($this->fileNameOfPageXML);
			}
		}
		else
		{
			//default page
			$navigationNamesArray = $pagesXML->xpath("/pages/navigation_group/page[@default='true']/../navigation_group_id");
			$currentNavigationGroup = strval($navigationNamesArray[0]);
	
			$defaultPageXML = $pagesXML->xpath("/pages/navigation_group/page[@default='true']");
			$defaultPage = strval($defaultPageXML[0]->page_id);
	
	
			$this->fileNameOfPageXML = 'Data/'.PRIMARY_DOMAIN.'/Content/Pages/'.$currentNavigationGroup.'/'.$defaultPage.'/data.xml';
	
			$dataHandler = new dataHandler();
			$pageXML = $dataHandler->loadDataSimpleXML('Data/'.PRIMARY_DOMAIN.'/Content/Pages/'.$currentNavigationGroup.'/'.$defaultPage.'/data.xml');
		}
		return $pageXML;
	}
	
//----------------------------------------------------------------------------------------------------------------------	
	
	public function getNavigationNameGroupName($pagesXML, $page_selected, $parent_xpath = '')
	{
		$parent_xpath .= '/..';
		$navigationNamesArray = $pagesXML->xpath("//page[page_id='{$page_selected}']{$parent_xpath}/navigation_group_id");
			
		if(count($navigationNamesArray) < 1)
			return $this->getNavigationNameGroupName($pagesXML, $page_selected, '/..');
		
		else
			return $navigationNamesArray;
	}
	
	public function getFileNameOfPageXML()
	{
		//getPageXML must be run first
		return $this->fileNameOfPageXML;
	}
}
?>