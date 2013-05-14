<?phprequire_once(FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/viewSuperClass_Core/viewSuperClass_Core.php');require_once('headerModel.php');require_once('headerView.php');

class headerController extends controllerSuperClass_Core{	public function getHeader()	{		$headerModel = new headerModel();		$contentXML = $headerModel->getHeaderData();
		$headerView = new headerView();		$headerView->_XMLObj = $contentXML;   		$headerView->getHeader();	}
	
}
?>