<?php  if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');

/*************************************************
* 	@name Constants Config File For EMP Framework for php
*	@package		EMP
* 	@file			Constants.php
* 	@author		    We Excel Team
**************************************************/

/*************************************************
* 	EMP INCLUDE PATH
**************************************************/
define('INCLUDE_PATH', ';'.FRAMEWORK);

/*************************************************
* 	CORE PATH
**************************************************/
define('CORE_PATH', SYS_PATH.'core'.DIRECTORY_SEPARATOR);

/*************************************************
* 	VIEW PATH
**************************************************/
define('VIEW_PATH', APP_PATH.'views'.DIRECTORY_SEPARATOR);

/*************************************************
* 	PHP LIBRARY PATH
**************************************************/
define('LIB_PATH_PHP', SYS_PATH.'lib'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR);

/*************************************************
* 	JAVASCRIPT LIBRARY PATH
**************************************************/
define('LIB_PATH_JS', SYS_PATH.'lib'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR);

/*************************************************
* 	LOG DIRECTORY
**************************************************/
define('LOG_DIR', FRAMEWORK.'logs'.DIRECTORY_SEPARATOR);

/*************************************************
* SITE URL
**************************************************/
define('URL', $url);

/*************************************************
* DOCUMENTS PATH
**************************************************/
define('DOC_PATH', '/opt/processmaker/shared/sites/workflow/');
