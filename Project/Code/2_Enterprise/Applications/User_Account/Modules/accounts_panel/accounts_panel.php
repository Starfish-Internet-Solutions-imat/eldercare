<?php
require_once FILE_ACCESS_CORE_CODE.'/Objects/Authorization/authorization.php';

class accountPanel 
{
	public static function show_account_panel()
	{
		$content ='';
		$content .= self::showLogInLinkIfLoggedOut();	
		$content .= self::showLogOutLinkIfLoggedIn();
		
		return $content;
	}
	
	
	
	//===================================================================================================================================
	private function showLogOutLinkIfLoggedIn()
	{
		if (true==authorization::areWeLoggedIn())
		{
			$cleanpath = self::cleanPath(routes::getInstance()->getWholePath());
			return '<a href="/user/logout'.$cleanpath.'" class="left">Logout</a>';
		}
		else {
			return null;
		}
	}
	//===================================================================================================================================
	private function showLogInLinkIfLoggedOut()
	{
	
		if (false==authorization::areWeLoggedIn()){
			$cleanpath = self::cleanPath(routes::getInstance()->getWholePath());
			return '<a href="/user/login'.$cleanpath.'" class="left">Floting Login</a>';
		}
		else
		{
			return null;
		}
	}
	
	private function cleanPath($path)
	{
		//to prevent long urls, or possible errors when a user presses login/logout etc repetively
		$path = str_replace("/userlogin", "", $path);
		$path = str_replace("/userlogout", "", $path);
		$path = str_replace("/register", "", $path);
		$path = str_replace("/userprofile", "", $path);
		return $path;
	}
	
}