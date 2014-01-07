<?php if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');

/*********************************************************
*	Class for Controlling the EMP Framework
*
* 	@Name	class.emp.php
* 	@author Eitan
*
* 	@Version 1.2
*********************************************************/

 class Emp{
 		
 	public $_Config;
    public $Return;
    public $PM;

/*************************************************
* 	@name constructor
* @desc sets up the initial classes & scripts for the framework
**************************************************/
	function __construct(){}

/*************************************************
* 	@name construct
* @desc Custom construct for the class
**************************************************/
     function Construct(){}

/*************************************************
* 	@name index
* @desc default location if no function name was provided
**************************************************/
    function index(){}

/*************************************************
* 	@name load classes
* @desc loads the classes for the framework
**************************************************/
	 function load_classes(){
	 	if(is_dir(CORE_PATH)){
	 		if($dh=opendir(CORE_PATH)){
	 			while(($file=readdir($dh)) !== FALSE){
						try{
							if(substr($file,-4)==EXT){
								$class=explode(".", $file);
								if($class[0]!='class') continue;
								$class=ucfirst($class[1]);
								if($class != 'Emp'){
                                    $tmpClass= new $class();
									$this->$class = $tmpClass;
									$this->$class->_Config=array_merge($this->_Config);
								}
							}
							
						}catch(Exception $e){
							//do nothing
						}
	 			}
	 		}
	 	}
	}
	 
/*************************************************
* 	@name load config
* @desc loads the configuration files for the specific controller
**************************************************/
 	 function load_config($client_path=''){
		if($client_path='') return;
		global $conf;
		$this->Config=array();
		if(substr($client_path,-1)!=SEP)$client_path.=SEP;
		$dir=FRAMEWORK.$client_path."config";
			if(is_dir($dir)){
				if($dh=opendir($dir)){
					while(($file=readdir($dh)) !== FALSE){
						try{
							if(substr($file,-4)==EXT){
								require_once($dir.SEP.$file);
								if(is_array($conf)){
										if(count($conf)>0) $this->_Config=array_merge($conf);
								}
							}
							
						}catch(Exception $e){
							//do nothing
						}
					}
				}
			}
			if($this->Config['site_url'] ==''){
				$this->Config['site_url']=(isset($_SERVER['HTTPS']) &&  $_SERVER['HTTPS'] == 'on' )? 'https://'.$_SERVER['SERVER_NAME']."/":'http://'.$_SERVER['SERVER_NAME']."/";
			}
 	 }
	 
 }
