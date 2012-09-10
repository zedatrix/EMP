<?php  if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');
/****************** Class - Service Comms ***********************
 * This class is for communicating with processmakers webservices either from within triggers or externally
 * @Author Ethan
 * @Version !.0
 */
class Servicecom{
	public $obj; //Variable for object to send data into processmaker. processmaker sends data with webservices as an object like this.
	public $result; //Variable for holding the return result
	protected $sessionId; //Variable for holding the session id once we have logged into processmaker. it is with this session id that we can then continue to use other webservice functions inside processmaker without the need to log in again
	protected $sessionArray = array(); //Variable for housing the session id in a variable format. some webservices only require the session id, and it must be in an array.
	protected $client; //Variable for housing the soapcall client object instance
	protected $mysqlEngine;
	protected $sqlHelper;
	protected $UserName=NULL;
	protected $PassWord=NULL;
	protected $UserUID=NULL;
	protected $Site=NULL;
	protected $dbEngine=NULL;
	public $_Config=array();

	function __construct(){

	}
	
	function setup(){
		$this->Site = $this->_Config['site_url'];
		$this->obj = new obj();
		$db=$this->_Config['database'];
		$dbase=$db['pm_workflow'];
		$this->UserName = $this->_Config['webservice']['user'];
		$this->PassWord = $this->_Config['webservice']['pass'];
		$this->UserUID = $this->_Config['webservice']['uid'];
		$this->dbEngine=new Database();
        $this->dbEngine->_Config=array_merge($this->_Config);
		$this->dbEngine->connect($dbase);
	}
	
	function __deconstruct(){
		
	}
	function login(){
		$this->client = new SoapClient($this->Site.'sysworkflow/en/green/services/wsdl2');
		$params = array(array('userid'=>$this->UserName, 'password'=>$this->PassWord));
		$this->result = $this->client->__SoapCall('login', $params);
		
		if($this->result->status_code == 0){
		$this->sessionId = $this->result->message;
		$this->sessionArray = array(array('sessionId'=>$this->sessionId));
		}else{
			die('There was a problem with connecting to the webservice. Please contact your system administrator.');
		}
	}
	function send_vars($data){
		$this->login();
		$this->obj->name="UF_CR_hidden_BankDetails";
		$this->obj->value=$data->empty;
		$vars=array($this->obj);
		$params = array(array('sessionId' => $this->sessionId, 'caseId' => $data->application, 'variables' => $vars));
		$this->result = $this->client->__SoapCall('sendVariables', $params);
	}
	function deleteCase($AppUid){
        //if( ! $this->dbEngine) $this->setup();
        //die(print_r("DELETE FROM APPLICATION WHERE APP_UID='$AppUid'", TRUE));
        //R::setup("mysql:host=".$this->_Config['database']['host'].";dbname=".$this->_Config['database']['database_default'], $this->_Config['database']['username'], $this->_Config['database']['password']);
        R::selectDatabase(2);
        /*$t=R::getAll("SELECT * FROM APPLICATION WHERE APP_UID='$AppUid'");
        die(print_r($t, TRUE));*/
        R::exec("DELETE FROM APPLICATION WHERE APP_UID='$AppUid'");
        R::exec("DELETE FROM APP_CACHE_VIEW WHERE APP_UID='$AppUid'");
        R::exec("DELETE FROM APP_DELAY WHERE APP_UID='$AppUid'");
        R::exec("DELETE FROM APP_DELEGATION WHERE APP_UID='$AppUid'");
        R::exec("DELETE FROM APP_HISTORY WHERE APP_UID='$AppUid'");
        R::selectDatabase(1);
        R::exec("DELETE FROM tbl_app WHERE APPLICATION='$AppUid'");
        R::exec("DELETE FROM tbl_app_data WHERE APPLICATION='$AppUid'");
	}
	function deriviateCase($AppUid){
		//get the del index
		$this->dbEngine->sql("SELECT MAX(DEL_INDEX) FROM APP_DELEGATION WHERE APP_UID='$AppUid'");
		$Del=$this->dbEngine->execute();
		$delIndex=$Del[0][0];
		//update the table for the webservice user so we can route it to stale unassigned
		$this->dbEngine->sql("UPDATE APP_DELEGATION SET USR_UID='$this->UserUID' WHERE APP_UID='$AppUid' AND DEL_INDEX=$delIndex");
		$this->dbEngine->execute();
	
		//route the case
		$params = array(array('sessionId'=>$this->sessionArray[0]['sessionId'], 'caseId'=>$AppUid, 'delIndex'=>$delIndex));
		$result = $this->client->__SoapCall('routeCase', $params);
		if ($result->status_code != 0)	die("Error routing case: ".print_r($result, TRUE) ."\n");
	}
	function sendVars($AppUid, $Vars){
		
		$i=0;
		 foreach($Vars as $k => $v){
		 	$Obj='Obj'.$i;
		 	$$Obj = new Obj();
		 	$$Obj->name=$k;
			$$Obj->value=$v;
			$variables[]=$$Obj;
			$i++;
		 }
		 
		 $params = array(array('sessionId'=>$this->sessionArray[0]['sessionId'], 'caseId'=>$AppUid, 'variables'=>$variables));
		 $result = $this->client->__SoapCall('sendVariables', $params);
		 if ($result->status_code != 0) die("Error sending vars: ".print_r($result, TRUE)." \n");
	}
}

//EOF {File Location: )
