<?php  if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');
	
	/* -------------------------------------------------------------------------------------------------------- *
	 * 
	 * @@author Eitan
	 * 
	 * @@Version 1.0.1
	 * 
	 * Class to send emails
	 * 
	 * ----------------------------------------------------------------------------------------------------------*/

require_once(LIB_PATH_PHP.'phpmailer'.SEP.'class.phpmailer.php');
class Mail{
	
	private $caseID = NULL;
	private $site=NULL;
	private $mailer=NULL;
	public $_Config=array();
	public $str='';
		
		function __construct(){
			$this->mailer=new PHPMailer();
		}
		function send_mail($to, $subj, $body='', $filePath=NULL, $name=''){
				$this->mailer->IsSMTP();
				$this->mailer->SMTPAuth=TRUE;
				$this->mailer->Username=$this->_Config['emailSettings']['user'];
				$this->mailer->Password=$this->_Config['emailSettings']['pass'];
				$this->mailer->Host=$this->_Config['emailSettings']['host'];
				$this->mailer->Port=$this->_Config['emailSettings']['port'];
				$this->mailer->From=$this->_Config['emailSettings']['fromDefault'];
				$this->mailer->FromName=$this->_Config['emailSettings']['fromNameDefault'];
				$this->mailer->AddAddress($to);
				$this->mailer->Subject=$subj;
				$this->mailer->Body=$body;
				if($filePath !== NULL){
					$filePath=$this->_Config['email_attach_path'].$filePath;
					$this->mailer->AddAttachment($filePath, $name);
				}
				$this->mailer->WordWrap=50;
				if( ! $this->mailer->Send()){
					$this->str='Msg was not sent.';
					$this->str.='Mailer error: '.$this->mailer->ErrorInfo;
				}else{
					$this->str='Msg has been sent.';
				}
				die($this->str);
			
		}
	
	
}

//EOF