<?php
require_once(FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/viewSuperClass_Core/viewSuperClass_Core.php');

class view extends viewSuperClass_Core 
{
public function displayPage()
	{
		$content = $this->renderTemplate('Project/Design/1_Website/Pages/primary/about/templates/main_template.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}

}
?>