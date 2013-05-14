<?phprequire_once FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/controllerSuperClass_Core/controllerSuperClass_Core.php';require_once('Model.php');require_once('View.php');
class controller extends controllerSuperClass_Core
{	public function indexAction()	{				header('location:Data/1_Website/Content/Pages/secondary/privacy_policy/Eldercare_Privacy Policy.pdf');	}}?>