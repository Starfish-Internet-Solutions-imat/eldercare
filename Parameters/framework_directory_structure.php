<?
//====================================================================================================
//PERMANENT FIXTURES OF THE FRAMEWORK THAT CANNOT BE TOUCHED without having to change the framework around
// they should not be modified 

//The document root directory under which the current script is executing, as defined in the server's configuration file. 
define('STAR_SITE_ROOT',(($_SERVER['DOCUMENT_ROOT'])));
//THIS ISNT WORKING
define('DEBUG_LOG_FILE', STAR_SITE_ROOT.'/Data/'.DOMAIN.'/Backend/temp/debug.txt');

//USED IN CMS BUT NEEDS TO BE RETHOUGHT
define('BACKUPS_DIRECTORY','Data/backups');
define ('TIMEZONE','Asia/Manila');

//PHOTO LIBRARY DIRECTORY
define('PHOTO_LIBRARY_DIRECTORY','Data/Images');

//===================================================================================================
define('STAR_CODE_MAINLAYOUT','/Project/Code/'.DOMAIN.'/Main_Layout');
define('STAR_CODE_PAGES','/Project/Code/'.DOMAIN.'/Pages');
define('STAR_CODE_APPLICATIONS','/Project/Code/'.DOMAIN.'/Applications');

define('STAR_DESIGN_MAINLAYOUT','/Project/Design/'.DOMAIN.'/Main_Layout');
define('STAR_DESIGN_PAGES','/Project/Design/'.DOMAIN.'/Pages');
define('STAR_DESIGN_PANELS','/Project/Design/'.DOMAIN.'/Panels');
//===================================================================================================

//===================================================================================================
// HTTP_ACCESS_CORE is defined in development, staging and live parameters
define('HTTP_ACCESS_CORE_CODE_MODULES',HTTP_ACCESS_CORE.'/Core/Code/Modules');
define('HTTP_ACCESS_CORE_CODE_OBJECTS',HTTP_ACCESS_CORE.'/Core/Code/Objects');
define('HTTP_ACCESS_CORE_DESIGN_MODULES',HTTP_ACCESS_CORE.'/Core/Design/Modules');
define('HTTP_ACCESS_CORE_DESIGN_LIBRARIES',HTTP_ACCESS_CORE.'/Core/Design/Libraries');
//===================================================================================================
// FILE_ACCESS_CORE is defined in development, staging and live parameters
define('FILE_ACCESS_CORE_CODE',FILE_ACCESS_CORE.'/Core/Code');
define('FILE_ACCESS_CORE_DESIGN',FILE_ACCESS_CORE.'/Core/Design');
define('FILE_ACCESS_CORE_DATA',FILE_ACCESS_CORE.'/Data');

define('HEALTHCARE_PROVIDER_IMAGES', STAR_SITE_ROOT.'/Data/Healthcare_Providers/Images');
define('HEALTHCARE_PROVIDER_IMAGES_SITE', '/Data/Healthcare_Providers/Images');

?>