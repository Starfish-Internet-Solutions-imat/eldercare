<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'pages_editorModel.php';
require_once 'pages_editorView.php';
//----------------------------------------------------------
class pages_editorController extends applicationsSuperController
{
	
	public function indexAction()
	{
		$pages_editorModel = new pages_editorModel();
		
		
		$dataHandler = new dataHandler();
		$pagesXML = $dataHandler->loadDataSimpleXML('Project/Code/'.PRIMARY_DOMAIN.'/Pages/pages_navigation.xml');
		$pageXML = $pages_editorModel->getPageXML($pagesXML);
		
		if (isset($_POST['save'])) 
		{
			$domObj = $pages_editorModel->updateDOMObjectWithPOST($pageXML,1);
			$xmlObj = simplexml_import_dom($domObj);
			
			$fileNameOfPageXML = $pages_editorModel->getFileNameOfPageXML();
			
			
			$dataHandler->saveDataXML($fileNameOfPageXML,$xmlObj);
		}
		
		$pages_editor_View = new pages_editor_View();
		$pages_editor_View->displayPageEditor($pageXML);
		
	}
	
	//================================================================================================================================================
}