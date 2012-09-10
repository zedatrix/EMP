<?php  if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');
class Xml{
	/*
	 * To hold the actual XML Element
	 */
	protected $Xml=NULL;
	/*
	 * To hold XML Output
	 */
	 public $Output=NULL;
	/**
	 * Creates the Simple XML Object
	 * @param
	 * @xml xml file or xml string
	 * @file true if file false if string
	 */
	 
	 public $_Config=array();

	function __construct($xml=NULL, $file=TRUE){
		if($xml===NULL) return;
		if($file===TRUE){
			$this->Xml=simplexml_load_file($xml) or die("Error: Can not create object!");
		}else{
			try{
			$this->Xml=simplexml_load_string($xml) or $this->Xml=simplexml_load_string($xml, "SimpleXMLElement", 0, "ecomm", TRUE);;
			}catch(Exception $e){
				die($e);
			}
		}
		$this->Output=$this->Xml;
	}
	   /** 
    * returns array with values of given simpleXml object
	*  @param $oXML Simple XML Object
    *@return array
    */ 
    function xml2Array(){
    	$oXML=$this->Xml;
        $values = ((array) $oXML);
       foreach ($values as $index => $value) { 
            if (!is_string($value)) { 
                $values[$index] = $this->xml2Array($value);
            }else{
	            $values[$index] = $value; 
            } 
        }
		$this->Output=$values;
		return $this->Output;
    } 
}
