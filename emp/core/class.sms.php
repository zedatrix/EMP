<?php  if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');
class Sms {
	/*
	 * To hold gateway url
	 */
	protected $url=NULL;
	/*
	 * To hold gateway username
	 */
	 private $username=NULL;
	 /*
	  * To hold gateway password
	  */
	  private $password=NULL;
	  /*
	   * To hold client ref
	   */
	   private $client_ref=NULL;
	   /*
	    * To hold uid for batchid & batchref
	    */
	    protected $uid=NULL;
	/*
	 * Holds the value for priority, by default it is set to FALSE
	 */
	 protected $priority=FALSE;
	 /*
	  * Holds Cell Phone Number
	  */
	  public $cell_num=NULL;
	  /* 
	   * Holds Text for the SMS
	   */
	   public $msg=NULL;
	   /*
	    * Holds name of User Agent
	    */
	    protected $user_agent='EMP_DeathStar';
		/*
		 * Holds the post data
		 */
		 protected $post_data=NULL;
		 /*
		  * Holds the Xml Class
		  */
		  protected $cXml=NULL;
		  /*
		   * Holds the email address to be replied to by the receiver of the sms
		   */
		   protected $Email=NULL;
	/*
	 * Set initial static settings
	 * username password client ref & the url
	 * @@params:
	 * @url: url for gateway
	 * @usr_agent: name of browser, in this case by default set to the framework
	 */	
	 public $_Config=array();
	
	function __construct($user_agent='EMP_DeathStar'){
		$this->user_agent=$user_agent;
	}
	
	function setup(){
		$this->url=$this->_Config['sms']['url'];
		$this->username=$this->_Config['sms']['user'];
		$this->password=$this->_Config['sms']['pass'];
		$this->client_ref=$this->_Config['sms']['client_ref'];
		$this->Email=$this->_Config['sms']['email'];
	}
	
	/*
	 * Set the settings that pertain to each individual sms
	 * @@params:
	 * @cell_num: *required, cellular phone number to which you are sending the sms to
	 * @msg: *required, text to send to with the sms
	 * @uid: batchid & batchref are set to this value
	 * @priority: keep this set to NULL and it will keep it set to FALSE, unless you understand what you are doing
	 */
	function set_details($cell_num=NULL, $msg=NULL, $uid=NULL, $priority=NULL){
		if($uid===NULL || $uid==='NULL'){
			$this->uid=date('Ymd His');
		}else{
			$this->uid=$uid;
		}
		if($priority!==NULL && $priority!=='NULL'){
			$this->priority=$priority;
		}
		if($cell_num===NULL || $cell_num==='NULL'){
			return "You have not included a Cellular Number.<br />Please make sure you have included a Cellular Number & try again.";
		}else{
			$this->cell_num=$cell_num;
		}
		if($msg===NULL || $msg==='NULL'){
			return "You have not included any text for the sms.<br />Please make sure you have included text for the sms & try again.";
		}else{
			$this->msg=$msg;
		}
		$this->post_data="userid=".$this->username."&";
		$this->post_data.="password=".$this->password."&";
		$this->post_data.="clientref=".$this->client_ref."&";
		$this->post_data.="batchref=".$this->uid."&";
		$this->post_data.="batchid=".$this->uid."&";
		$this->post_data.="message=".$this->msg."&";
		$this->post_data.="cell=".$this->cell_num."&";
		$this->post_data.="priority=".$this->priority."&";
		$this->post_data.="replyemail1=".$this->Email."&";
		return 1;
	}
	
	/*
	 * Sends the sms
	 * @@params:
	 * @cell_num: *required, cellular phone number to which you are sending the sms to
	 * @msg: *required, text to send to with the sms
	 * @uid: batchid & batchref are set to this value
	 * @priority: keep this set to NULL and it will keep it set to FALSE, unless you understand what you are doing
	 */
	 function send_sms($cell_num, $msg, $uid=NULL, $priority=NULL){
	 	
	 	$details=$this->set_details($cell_num, $msg, $uid, $priority);
		if($details===1){
			$xmlResult=$this->send();
			$result=$this->clean_output($xmlResult);
			if($result->result->status=='success'){
				return 'Sent';
			}else{
				return 'Failed';
			}
		}else{
			return $details;
		}
	 }
 
	 /**
	  * Cleans the xml output to array
	  * @param
	  * @xml the xml to be converted to array
	  */
	  function clean_output($xml){
	  		try{
	  			$this->cXml=new Xml($xml, FALSE);
			}catch(Exception $e){
				return "Problem converting xml to array. ".$e;
			}
			return $this->cXml->Output;
	  }
	 /*
	  * Uses curl to send the sms through the gateway
	  */
	  function send(){
	  	$ch=curl_init();
		try{
			curl_setopt($ch, CURLOPT_URL, $this->url);
		}catch(Exception $e){
			return "Problem with gateway url.".$e;
		}
		try{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		}catch(Exception $e){
			return "Problem with ssl settings for curl.".$e;
		}
		try{
			curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		}catch(Exception $e){
			return "Problem with User Agent".$e;
		}
		try{
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		}catch(Exception $e){
			return "Problem with retrieving results".$e;
		}
		try{
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->post_data);
		}catch(Exception $e){
			return "Problem with sending post data.".$e;
		}
		try{
			$sms_output = curl_exec($ch);
		}catch(Exception $e){
			return "Problem with executing the curl request.".$e;
		}
		try{
			curl_close($ch);
		}catch(Exception $e){
			return "Problem with closing the curl request.".$e;
		}
		return $sms_output;
	  }
}