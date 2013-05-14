<?
//====================================================================================================
//Define the ENVIRONMENT
define ('ENVIRONMENT', 'live');
//====================================================================================================
//Define the LANGUAGE 
//Comment out this whole block if the website is not Multidomain AND multilingual
switch ($_SERVER['SERVER_NAME'])
{
	case "starfish.es":
		define('LANGUAGE','es');
		break;
	case "starfish.ph":
		define('LANGUAGE','ph');
		break;
	case "starfish.co.uk":
		define('LANGUAGE','uk');
		break;
	
}

//====================================================================================================
//Define the DOMAIN


//this is used so that Starfish Enterprise can reference the main website
//i.e. one domain can reference another domain
define('PRIMARY_DOMAIN','1_Website');
define('SECONDARY_DOMAIN','2_Enteprise');

switch ($_SERVER['SERVER_NAME'])
{
	case "starfish.es":
	case "starfish.ph":
	case "starfish.co.uk":
		define('DOMAIN','1_Website');
		break;
		
	case "starfish_enterprise":
	case "amiteh":
		define('DOMAIN','2_Enterprise');
		break;
}

//====================================================================================================
//CORE CONNECTION
// used client side
define('HTTP_ACCESS_CORE','http://StarfishCore_v3');
//---------------------------
// used server side
define('FILE_ACCESS_CORE','StarfishCore_V3');
//====================================================================================================
#for database connection
define ('DATABASE_NAME', 'starfish_dev');
define ('DATABASE_HOSTNAME', 'localhost');
define ('DATABASE_USERNAME', 'root');
define ('DATABASE_PASSWORD', '');
#end

?>