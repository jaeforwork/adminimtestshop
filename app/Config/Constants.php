<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

define("TIME_YMDHIS", date('Y-m-d H:i:s'));
define("TIME_YMD", date('Y-m-d'));
define("TIME_HIS", date('H:i:s'));
define("TIME_TODAY", date('Y-m-d 00:00:00'));
/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code




define("ENC_KEY_128","PetGleTComApi128");
define("ENC_KEY_256","PetGleTComApi256");
define("IV","petglet.com.api");


define("HEADER_AT","access-token");//access token header name
define("HEADER_RT","refresh-token");//refresh token header name
define("TOKEN_EXPIRE",86400); //1일



define("RESULT_SUCCESS","Y");
define("RESULT_FAIL","N");
define("RESULT_EMPTY","E");
define("RESULT_LOGIN","L");
define("RESULT_TOKEN_TIMEOUT","T");

// define("PROFILE_IMAGE","/data/icon_no_image.png"); //프로필 기본 이미지

// if(isset($_SERVER["WINDIR"]) || isset($_SERVER["windir"])){
    
        
//     define("CDN_SERVER",array("CDN_SERVER_1"=>"http://api.petgleta.com"));        
//     define("DB_MASTER_DB",array("host"=>"localhost","dbname"=>"petgle_t", "id"=>"root","pwd"=>"1234"));
//     define("DB_SLAVE_DB",array("host"=>"localhost","dbname"=>"petgle_t", "id"=>"root","pwd"=>"1234"));
// }else{
    
//     define("CDN_SERVER",array("CDN_SERVER_1"=>"http://api.petgleta.com"));//이미지, 동영상 CDN 서버 
//     define("DB_MASTER_DB",array("host"=>"petgle_db_master","dbname"=>"", "id"=>"","pwd"=>""));
//     define("DB_SLAVE_DB",array("host"=>"petgle_db_slave","dbname"=>"", "id"=>"","pwd"=>""));
    
// }

// define("DB_MASTER","master");
// define("DB_SLAVE","slave");

