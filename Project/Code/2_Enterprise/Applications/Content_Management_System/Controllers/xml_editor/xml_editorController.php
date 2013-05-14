<?php
require_once 'Project/Code/ApplicationsFramework/MVC_superClasses/applicationsSuperController.php';
require_once 'xml_editorModel.php';
require_once 'xml_editorView.php';
//----------------------------------------------------------
class xml_editorController extends applicationsSuperController
{
	
	public function indexAction()
	{
		
		$xml_editorModel = new xml_editorModel();
		
		$dataHandler = new dataHandler();
		$pagesXML = $dataHandler->loadDataSimpleXML('Project/Code/'.PRIMARY_DOMAIN.'/Pages/pages_navigation.xml');
		$pageXML = $xml_editorModel->getPageXML($pagesXML);
		
		
		if (isset($_POST['save'])) {
			
			try
			{
				error_reporting(0);
				$xmlObj = new SimpleXMLElement (stripslashes($_POST['dataString']));
			}
			catch (Exception $e){
				print '<h1>'; print $e->getMessage(); print '<p> Click Back and check XML</p></h1>';
			}
			
				
			$fileNameOfPageXML = $xml_editorModel->getFileNameOfPageXML();
			
			$pageXML = $dataHandler->saveDataXML($fileNameOfPageXML,$xmlObj);
		}
		
		
		$xml_editorView = new xml_editorView();
		$xml_editorView->pagesXML = $pagesXML;
		
		$xml_editorView->displayXMLEditor($pageXML);
	}
}