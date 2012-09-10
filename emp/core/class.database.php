<?php  if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');
class Database{
	
	public $_Config;
	
	function __construct($dbname=''){
		if($dbname != ''){
            if(count($this->_Config)>0) $this->connect($dbname);
        }
	}
/*************************************************
 * Method to connect to the database
 * Can take an optional param of db name to connect to a db other
 * than the default
**************************************************/
	function connect($dbname=''){
		try{
            include_once(LIB_PATH_PHP.'redbean'.SEP.'class.redbean.php');
			if($dbname===''){
				R::setup("mysql:host=".$this->_Config['database']['host'].";dbname=".$this->_Config['database']['database_default'], $this->_Config['database']['username'], $this->_Config['database']['password']);
			}elseif($dbname !==''){
				R::setup("mysql:host=".$this->_Config['database']['host'].";dbname=$dbname", $this->_Config['database']['username'], $this->_Config['database']['password']);
			}
            R::addDatabase(1, "mysql:host=".$this->_Config['database']['host'].";dbname=".$this->_Config['database']['database_default'], $this->_Config['database']['username'], $this->_Config['database']['password']);
            R::addDatabase(2, "mysql:host=".$this->_Config['database']['host'].";dbname=".$this->_Config['database']['pm_workflow'], $this->_Config['database']['username'], $this->_Config['database']['password']);
		}catch(PDOException $e){
			print_r("Error!: " . $e->getMessage() . "<br/>");
			die();
		}
	}
}
