<?php  if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');
 /*************************************************
  * 	@name Constants Config File For EMP Framework for php
  *	@package		EMP
  * 	@file				Constants.php
 * 	@author		We Excel Team
 
 **************************************************/
 /*************************************************
 * 	EMP INCLUDE PATH
 **************************************************/
define('INCLUDE_PATH', ';'.FRAMEWORK);
 /*************************************************
 * 	CORE PATH
 **************************************************/
define('CORE_PATH', SYS_PATH.'core'.SEP);
 /*************************************************
 * 	VIEW PATH
 **************************************************/
 define('VIEW_PATH', APP_PATH.'views'.SEP);
 /*************************************************
 * 	PHP LIBRARY PATH
 **************************************************/
define('LIB_PATH_PHP', SYS_PATH.'lib'.SEP.'php'.SEP);
 /*************************************************
 * 	JAVASCRIPT LIBRARY PATH
 **************************************************/
define('LIB_PATH_JS', SYS_PATH.'lib'.SEP.'js'.SEP);
 /*************************************************
 * 	LOG DIRECTORY
 **************************************************/
 define('LOG_DIR', FRAMEWORK.'logs'.SEP);
/*************************************************
 * 	SARA DIRECTORY
 **************************************************/
define('SARA_DIR', DOCUMENT_ROOT.SEP.'Sara'.SEP);
$url=(isset($_SERVER['HTTPS']) &&  $_SERVER['HTTPS'] == 'on' )? 'https://'.$_SERVER['SERVER_NAME']."/":'http://'.$_SERVER['SERVER_NAME']."/";
/**
 * SITE URL
 */
define('URL', $url);