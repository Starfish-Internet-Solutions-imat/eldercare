<?phprequire_once FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/controllerSuperClass_Core/controllerSuperClass_Core.php';require_once('mainModel.php');require_once('mainView.php');
//******************************************************************************************************************************// STATIC CLASSES require_once 'Project/Code/2_Enterprise/Applications/User_Account/Modules/accounts_panel/accounts_panel.php';//******************************************************************************************************************************
class mainController extends controllerSuperClass_Core{	public function getMainLayout()	{		//$mainModel = new mainModel();		//$contentXML = $mainModel->getMainLayoutData();		
		$mainView = new mainView();		//$mainView->_XMLObj = $contentXML;   		$mainView->getMainLayout();
	}
}
?>