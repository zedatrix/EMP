<?php
/****************************************************
 * 	            EMP Utility Framework               
 *
 * @Name emp.php
 *
 * @author Eitan
 *
 * @Version 3.0.0
 *
 * **************************************************/

/*************************************************
 * 	Declare PM Object for manipulating PM Fields inside the framework
 **************************************************/
global $PM;
/*************************************************
 * 	If the PM Object exists then we are running it from a trigger, otherwise we are running an ajax request
 **************************************************/
if( ! class_exists('PMScript')) empGo();function empGo(&$param=NULL){

    if(isset($param) && $param!=NULL){
        global $PM;
        $PM=$param;
    }
    /*
 **************************************************
 * APPLICATION ENVIRONMENT
 **************************************************
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */

    /**
    *---------------------------------------------------------------
    * TIME ZONE
    *---------------------------------------------------------------
    */
    date_default_timezone_set('Africa/Johannesburg');

    define('ENV', 'dev');
    /*
    *---------------------------------------------------------------
    * ERROR REPORTING
    *---------------------------------------------------------------
    *
    * Different environments will require different levels of error reporting.
    * By default development will show errors but testing and live will hide them.
    */

    if (defined('ENV'))
    {
        switch (ENV)
        {
            case 'dev':
                error_reporting(E_ALL);
                break;

            case 'testing':
            case 'production':
                error_reporting(0);
                break;

            default:
                exit('The application environment is not set correctly.');
        }
    }

    /*************************************************
     * 	FRAMEWORK FOLDER NAME
     **************************************************
     *
     * Set this constant to whatever you named your framework folder to
     */
    define('FRAMEWORK_NAME', 'emp');

    /*************************************************
     * 	APPLICATION FOLDER NAME
     **************************************************
     *
     * Set this constant to whatever you named your application folder to
     */
    define('APP_NAME', 'app');

    /*************************************************
     * 	CORE FOLDER NAME
     **************************************************
     *
     * Set this constant to whatever you named your core folder to
     */
    define('SYS_NAME',  'emp');

    /**
     * Set Include Path
     */

    /*************************************************
     * 	END OF USER CONFIGURATION
     *
     * 	BEYOND THIS POINT DO NOT EDIT
     **************************************************/

    /*************************************************
     * 	SYSTEM PATH SEPERATOR
     **************************************************/

    if ( PHP_OS == 'WINNT' && !strpos ( __FILE__, '/' ) ){
        define('SEP',"\\");
    }else{
        define('SEP', "/");
    }

    /*************************************************
     * 	MAIN SYSTEM CONSTANTS
     **************************************************/
//Path to the document root
    $docuroot = explode ( SEP , __FILE__);
    array_pop($docuroot);
    array_pop($docuroot);

    define('DOCUMENT_ROOT', join(SEP,$docuroot));

    // The name of THIS file
    define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// Path to the framework
    define('FRAMEWORK', str_replace(SELF, '', __FILE__));

//Path to the sys folder
    define('SYS_PATH', FRAMEWORK.SYS_NAME.SEP);

//Path to the app folder
    define('APP_PATH', FRAMEWORK.APP_NAME.SEP);

// The PHP file extension
// this global constant is deprecated.
    define('EXT', '.php');

    /*************************************************
     * 	BOOT UP THE FRAMEWORK
     **************************************************
     *
     * And away we go...
     *
     */

    return include(SYS_PATH."Boot.php");
}