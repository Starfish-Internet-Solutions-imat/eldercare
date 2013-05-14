<?php 
require_once FILE_ACCESS_CORE_CODE.'/Objects/Authorization/authorization.php';

class userSession extends authorization
{
	public static function saveUserSession($user_account_id, $name, $contact_person_id) 
	{
		Zend_Session::regenerateId();
		Zend_Session::rememberMe(172800);
		
		$user_session = new Zend_Session_Namespace("user_session");
		$user_session->logged_in = TRUE;
		$user_session->user_account_id = $user_account_id;
		$user_session->name = $name;
		$user_session->contact_person_id = $contact_person_id;
		$user_session->last_logged_in = date("Y-m-d");
	}
	
//=================================================================================================

	public static function getUserName()
	{
		$user_info = new Zend_Session_Namespace('user_session');
		$user_info = unserialize(serialize($user_info));
		return $user_info->name;
	}
	
	public static function getUserAccountID()
	{
		$user_info = new Zend_Session_Namespace('user_session');
		$user_info = unserialize(serialize($user_info));
		return $user_info->user_account_id;
	}
	
	public static function getContactPersonID()
	{
		$user_info = new Zend_Session_Namespace('user_session');
		$user_info = unserialize(serialize($user_info));
		return $user_info->contact_person_id;
	}
	
	//===========================================TEMPORARY SESSION=================================
	
	public static function temporarySession($user_account_id='')
	{
		$user_session = new Zend_Session_Namespace("temp_session");
		$user_account_id = session_id();
		$user_session->user_account_id = $user_account_id;
	}
	
	public static function getTemporaryId()
	{
		$user_session = new Zend_Session_Namespace("temp_session");
		return $user_session->user_account_id;
	}
	
	public static function unsetTemporarySession()
	{
		$user_session = new Zend_Session_Namespace("temp_session");
		$user_session->unsetAll();
	}
	
	public static function isTemporarySessionSet()
	{
		if(isset($_SESSION['temp_session']))
			return true;
		else
			return false;
	}
	
	public static function setTemporarySessionData($field, $value)
	{
		if (self::isTemporarySessionSet())
			$_SESSION['temp_session'][$field] = $value;
	}
	
	public static function getTemporarySessionData($field)
	{
		if (self::isTemporarySessionSet())
			if (isset($_SESSION['temp_session'][$field]))
				return $_SESSION['temp_session'][$field];
	}
	
	public static function testAction()
	{
		var_dump($_SESSION['temp_session']);
		
	}
}