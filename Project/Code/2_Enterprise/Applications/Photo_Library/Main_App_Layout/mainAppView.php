<?php
require_once(FILE_ACCESS_CORE_CODE.'/Framework/MVC_superClasses_Core/viewSuperClass_Core/viewSuperClass_Core.php');

class mainAppView extends viewSuperClass_Core
{
	public function display_Main_Application_Layout()
	{
		$this->displayNewAlbumDialog();
		
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/_Common/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('COMMON_CS_JS'=>$content));
		
		$content = $this->renderTemplate('Project/Design/2_Enterprise/Applications/Photo_Library/Main_App_Layout/templates/js_and_css_links.phtml');
		response::getInstance()->addContentToStack('css_and_javascript_links_for_this_page_only',array('PHOTOLIBRARY_CS_JS'=>$content));
		
		$currentApplicationID = applicationsRoutes::getInstance()->getCurrentApplicationID();
		$content = $this->renderTemplate('Project/Design/'.DOMAIN.'/Applications/'.$currentApplicationID.'/Main_App_Layout/templates/main_app_layout.phtml');
		response::getInstance()->addContentToTree(array('MAIN_CONTENT'=>$content));
	}
	
	
	public function display_Photo_and_Albums_Navigation()
	{
		require_once('Project/Code/'.DOMAIN.'/Applications/Photo_Library/Navigation/photos_and_albumsNavigation.php');
		$content = photos_and_albumsNavigation::displayPhotoAndAlbumsNavigation();
		response::getInstance()->addContentToTree(array('APPLICATION_LEFT_COLUMN'=>$content));
	}
	
//=================================================================================================
	
	private function displayNewAlbumDialog()
	{
		$content = $this->renderTemplate('Project/Design/'.DOMAIN.'/Applications/Photo_Library/Controllers/templates/albums/new_album_dialog.phtml');
		response::getInstance()->addContentToTree(array('NEW_ALBUM_DIALOG'=>$content));
	}
	
}
?>