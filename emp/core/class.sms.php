<?php  if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');
class Sms {

/*********************************************************
* To hold gateway url
*********************************************************/
protected $url=NULL;

/*********************************************************
* To hold gateway username
*********************************************************/
private $username=NULL;

/*********************************************************
* To hold gateway password
*********************************************************/
private $password=NULL;

/*********************************************************
* To hold client ref
*********************************************************/
private $client_ref=NULL;

/*********************************************************
* To hold uid for batchid & batchref
*********************************************************/
protected $uid=NULL;

/*********************************************************
* Holds the value for priority, by default it is set to FALSE
*********************************************************/
protected $priority=TRUE;

/*********************************************************
* Holds Cell Phone Number
*********************************************************/
public $cell_num=NULL;

/*********************************************************
* Holds Text for the SMS
*********************************************************/
public $msg=NULL;

/*********************************************************
* Holds name of User Agent
*********************************************************/
protected $user_agent='EMP_DeathStar';

/*********************************************************
* Holds the post data
*********************************************************/
protected $post_data=NULL;

/*********************************************************
* Holds the Xml Class
*********************************************************/
protected $cXml=NULL;

/*********************************************************
* Holds the email address to be replied to by the receiver of the sms
*********************************************************/
protected $Email=NULL;

/*********************************************************
* Set initial static settings
* username password client ref & the url
* @@params:
* @url: url for gateway
* @usr_agent: name of browser, in this case by default set to the framework
*********************************************************/
public $_Config=array();
	
	function __construct($user_agent='EMP_DeathStar'){
		$this->user_agent=$user_agent;
	}
	
	public function setup($cell_num, $message, $uid=NULL, $priority=NULL){
		$this->url=$this->_Config['sms']['url'];
		$this->username=$this->_Config['sms']['user'];
		$this->password=$this->_Config['sms']['pass'];
		$this->client_ref=$this->_Config['sms']['client_ref'];
		$this->Email=$this->_Config['sms']['email'];
        $this->cell_num = $cell_num;
        $this->msg = $message;
        $this->uid = ($uid==NULL)?'emp'.date('YmdHis'):'emp'.$uid;
        $this->priority = ($priority==NULL)?TRUE:$priority;
	}

/*********************************************************
* Set the settings that pertain to each individual sms
* @@params:
* @cell_num: *required, cellular phone number to which you are sending the sms to
* @msg: *required, text to send to with the sms
* @uid: batchid & batchref are set to this value
* @priority: keep this set to NULL and it will keep it set to FALSE, unless you understand what you are doing
*********************************************************/
	private function set_details(){
		if($this->cell_num===NULL || $this->cell_num==='NULL') return "You have not included a Cellular Number.<br />Please make sure you have included a Cellular Number & try again.";
		if($this->msg===NULL || $this->msg==='NULL') return "You have not included any text for the sms.<br />Please make sure you have included text for the sms & try again.";

        $this->post_data="userid=".$this->username."&";
        $this->post_data.="password=".$this->password."&";
        $this->post_data.="clientref=".$this->client_ref."&";
        $this->post_data.="batchref=".$this->uid."&";
        $this->post_data.="batchid=".$this->uid."&";
        $this->post_data.="message=".$this->msg."&";
        $this->post_data.="cell=".$this->cell_num."&";
        $this->post_data.="priority=".$this->priority."&";
        $this->post_data.="repliesperuserid=true&";
        $this->post_data.="specversion=2.61&";
        $this->post_data.="rdr=true&";
        $this->post_data.="sendingemail=true&";
        $this->post_data.="sendingemailaddress1=".$this->Email."&";
        $this->post_data.="replyemail1=".$this->Email."&";
        $this->post_data.="deliveryemail=true&";
        $this->post_data.="deliveryemailaddress1=".$this->Email."&";
        $this->post_data.="failuretosendemailaddress=".$this->Email."&";
		return 1;
	}

/*********************************************************
* Sends the sms
* @@params:
* @cell_num: *required, cellular phone number to which you are sending the sms to
* @msg: *required, text to send to with the sms
* @uid: batchid & batchref are set to this value
* @priority: keep this set to NULL and it will keep it set to FALSE, unless you understand what you are doing
*********************************************************/
	 public function send_sms(){
	 	$details=$this->set_details();
		if( $details===1 ){
			$xml=$this->send();
			$response=$this->clean_output($xml);
			if( $response->result->status=='success' ){
				return 'Sent';
			}else{
				return print_r($response, true);
			}
		}else{
			return $details;
		}
	 }

/*********************************************************
* Cleans the xml output to array
* @param
* @xml the xml to be converted to array
*********************************************************/
	  function clean_output($xml){
	  		try{
	  			$this->Xml=new Xml($xml, FALSE);
			}catch(Exception $e){
				return "Problem converting xml to array. ".$e;
			}
			return $this->Xml->getXmlObj();
	  }
/*********************************************************
* Uses curl to send the sms through the gateway
*********************************************************/
	  function send(){
        try{
	  	$ch=curl_init();
		if( ! curl_setopt($ch, CURLOPT_URL, $this->url)) return "Problem with gateway url.";
		if( ! curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE) ) return "Problem with ssl settings for curl. (E 1)";
		if( ! curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2) ) return "Problem with ssl settings for curl. (E 2)";
		if( ! curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent)) return "Problem with User Agent";
		if( ! curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE)) return "Problem with retrieving results";
		if( ! curl_setopt($ch, CURLOPT_POST, TRUE) ) return "Problem with sending post data. (E 1)";
		if( ! curl_setopt($ch, CURLOPT_POSTFIELDS, $this->post_data) ) return "Problem with sending post data. (E 2)";
		$sms_output = curl_exec($ch);
		curl_close($ch);
        }catch (Exception $e){
            return "The following errors occurred". print_r($e, true);
        }
		return ( ! $sms_output)? 'Problem with executing the curl request.' : $sms_output ;
	  }
}
