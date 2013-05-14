<?php

//helps find zend session

//Load Common Project Paramaters 
//CHANGE THESE SETTINGS FOR EVERY PROJECT
require_once "Parameters/main_project_parameters_and_settings.php";

//-------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------
//DEFINE THE ENVIRONMENT
//Get server address
	$svn = $_SERVER['SERVER_NAME'];
	$sip =  $_SERVER['SERVER_ADDR'];
	$position = strpos($svn, "tarfi.sh");
	// leave it as "tarfi.sh" because if the name is "starfi.sh" the position will be zero
	//echo $sip."- SERVER NAME ".$svn." POS ".$position;

	
	$GLOBALS['domainAndCountryCodes'] = array(
													array('domain' => 'starfish.es', 'countrycode' => 'es'),
													array('domain' => 'starfish.co.uk', 'countrycode' => 'en'),
													array('domain' => 'starfish.ph', 'countrycode' => 'ph')
											);
	
	if (stristr($sip,"127.0.0.1")) 
	{
		require_once "Parameters/developers_environment.php";
	}
	elseif ($position > 0) 
	{
		require_once "Parameters/staging_environment.php";
	}
	else 
	{
		require_once "Parameters/live_environment.php";
	}
	
//-------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------
//DEFINE THE LANGUAGES SETTING 
//Options are: SINGLE_DOMAIN_MULTILINGUAL, MULTIPLE_DOMAIN_MULTILINGUAL, SINGLE_LANGUAGE
define('MULTILINGUAL_SETTING','MULTIPLE_DOMAIN_MULTILINGUAL');	
//-------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------
//DEFINE THE GENERAL FRAMEWORK FOLDERS - hardly ever changes 
require_once "Parameters/framework_directory_structure.php";
//-------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------
// RUN THE WEBSITE
require_once FILE_ACCESS_CORE_CODE.'/Framework/FrontController/frontController.php';
require_once 'Zend/Filter/Input.php';
require_once 'Zend/Loader.php';
require_once 'Zend/Debug.php';

error_reporting(E_ALL);
ini_set('display_errors','On');
try
	{
	    $frontController = frontController::getInstance();
	    $frontController->init();
	    $frontController->dispatch();
	} 
catch (Exception $exp) 
	{
	    $contentType = 'text/html';
	    header("Content-Type: $contentType; charset=utf-8"); 
	    echo 'an unexpected error occured.';
	    echo '<h2>Unexpected Exception: ' . $exp->getMessage() . '</h2><br /><pre>';
	    echo $exp->getTraceAsString();   
	}
?>