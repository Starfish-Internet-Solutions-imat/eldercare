<?
//====================================================================================================
//Define the ENVIRONMENT
define ('ENVIRONMENT', 'staging');
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
		define('DOMAIN','2_Enterprise');
		break;
	default:
		define('DOMAIN','1_Website');
		break;
}

//====================================================================================================
//FOR SENDING EMAILS
define('USE_SMTP',false);
define('SMTP_HOST','mail.starfi.sh');
define('SMTP_AUTH',false);
define('SMTP_USER','mailing+starfi.sh');
define('SMTP_PASS','4mailing');
define('SMTP_PORT',26);


define('FROM_EMAIL','francis.barbero@starfi.sh');
define('FROM_NAME','development test email');

define('REPLY_TO_EMAIL','');
define('REPLY_TO_NAME','');

define('TO_EMAIL','');
define('TO_NAME','');

define('CC_EMAIL','');
define('CC_NAME','');

define('CC_EMAIL2','');
define('CC_NAME2','');




//====================================================================================================
//CORE CONNECTION
// used client side
define('HTTP_ACCESS_CORE','http://starfi.sh/StarfishCore_V3');
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