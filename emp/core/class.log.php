<?php  if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');

/***** Class to log errors *****
 * 		
 * @@author Eitan
 * 
 * @@Version 1.2.1
 * 
 *****                               *****/

class Log{	

	public $time_stamp = NULL;
	public $msg_log = NULL; 
	public $my_txt = NULL;
	private $my_file = NULL;
	public $line = '';
	private $type = NULL;
	public $_Config=array();

	function __construct($type = 'log'){
		$this->type=$type;
		if($this->type=='log'){
			$this->log_setup();
		}elseif($this->type=='error_log'){
			$this->error_log_setup();
		}
		$this->time_stamp=date("Ymd His");
	}

	function __deconstruct(){
		fclose($this->my_file);
	}

	function log_setup(){
		$this->msg_log=LOG_DIR."debug_log.txt";
		$this->my_file=fopen($this->msg_log,"a+");
	}

	function error_log_setup(){
		$this->msg_log=LOG_DIR."error_log.txt";
		$this->my_file=fopen($this->msg_log,"a+");
	}

	function log_msg($msg){
		$this->line = count(file($this->msg_log));
		$this->my_txt='['.$this->line.']'.' ('.$this->time_stamp.") [Message] ".$msg."\n";

		fwrite($this->my_file,$this->my_txt);
	}

}

// EOF ServerRoot/framework/core/php/classes {purpose: loging}