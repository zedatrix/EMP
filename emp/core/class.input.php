<?php if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');

/*********************************************************
*       Class for Manipulating Data Input For
*       The EMP Framework
*
* 	@Name	class.input.php
* 	@author Eitan
*
* 	@Version 1.0
*
*********************************************************/

class Input{

    public $_Config;
    function __construct(){}

/*************************************************
* 	CLEANS DATA
**************************************************/

    function clean(&$input, $type, $sh_arg=FALSE){
        //if the input is invalid then exist with empty string
        if($input === NULL || $input === '') return;
        //What type of cleansing do we want to do?
        switch($type){
            //Clean data for html
            case 'html':
                //if its an array then
                if(is_array($input)){
                    //loop through the array
                    foreach($input as $k => $v){
                        //if the value is also an array then
                        if(is_array($v) || is_object($v)) {
                            $this->clean($v, $type);
                            continue;
                        }
                        //otherwise clean it with appropriate cleansers
                        $v=htmlentities($v);
                        $v=htmlspecialchars($v);
                    }
                    //otherwise if its an object then
                }elseif(is_object($input)){
                    //loop through the object
                    foreach($input as $k => $v){
                        //if its also an array/object then
                        if(is_array($v) || is_object($v)) {
                            $this->clean($v, $type);
                            continue;
                        }
                        //otherwise clean it with appropriate cleansers
                        $v=htmlentities($v);
                        $v=htmlspecialchars($v);
                    }
                    //otherwise if its a string then just cleans with appropriate cleansers
                }else{
                    $input=htmlentities($input);
                    $input=htmlspecialchars($input);
                }
            break;
            case 'mysql':
                //if its an array then
                if(is_array($input)){
                    //loop through the array
                    foreach($input as $k => $v){
                        //if the value is also an array then
                        if(is_array($v) || is_object($v)) {
                            $this->clean($v, $type);
                            continue;
                        }
                        //otherwise clean it with appropriate cleansers
                        $v=mysql_real_escape_string($v);
                    }
                    //otherwise if its an object then
                }elseif(is_object($input)){
                    //loop through the object
                    foreach($input as $k => $v){
                        //if its also an array/object then
                        if(is_array($v) || is_object($v)) {
                            $this->clean($v, $type);
                            continue;
                        }
                        //otherwise clean it with appropriate cleansers
                        $v=mysql_real_escape_string($v);

                    }
                    //otherwise if its a string then just cleans with appropriate cleansers
                }else{
                    $input=mysql_real_escape_string($input);
                }
            break;
            case 'sh':
                //if its an array then
                if(is_array($input)){
                    //loop through the array
                    foreach($input as $k => $v){
                        //if the value is also an array then
                        if(is_array($v) || is_object($v)) {
                            $this->clean($v, $type);
                            continue;
                        }
                        //otherwise clean it with appropriate cleansers
                        $v=escapeshellcmd($v);
                        if($sh_arg==TRUE)   $v=escapeshellarg($v);
                    }
                    //otherwise if its an object then
                }elseif(is_object($input)){
                    //loop through the object
                    foreach($input as $k => $v){
                        //if its also an array/object then
                        if(is_array($v) || is_object($v)) {
                            $this->clean($v, $type);
                            continue;
                        }
                        //otherwise clean it with appropriate cleansers
                        $v=escapeshellcmd($v);
                        if($sh_arg==TRUE)   $v=escapeshellarg($v);
                    }
                    //otherwise if its a string then just cleans with appropriate cleansers
                }else{
                    $input=escapeshellcmd($input);
                    if($sh_arg==TRUE)   $input=escapeshellarg($input);
                }
            break;
            case 'text':
                //if its an array then
                if(is_array($input)){
                    //loop through the array
                    foreach($input as $k => $v){
                        //if the value is also an array then
                        if(is_array($v) || is_object($v)) {
                            $this->clean($v, $type);
                            continue;
                        }
                        //otherwise clean it with appropriate cleansers
                        $v=stripslashes($v);
                        $v=strip_tags($v);
                    }
                    //otherwise if its an object then
                }elseif(is_object($input)){
                    //loop through the object
                    foreach($input as $k => $v){
                        //if its also an array/object then
                        if(is_array($v) || is_object($v)) {
                            $this->clean($v, $type);
                            continue;
                        }
                        //otherwise clean it with appropriate cleansers
                        $v=stripslashes($v);
                        $v=strip_tags($v);
                    }
                    //otherwise if its a string then just cleans with appropriate cleansers
                }else{
                    $input=stripslashes($input);
                    $input=strip_tags($input);
                }
            break;
            default:
                return 'No specified data type.';
            break;
        }
        return $input;
    }

/*************************************************
* 	JSON DECODER
**************************************************/
    function get_json(&$POST){
        //if there are backslashes then remove them
        $POST=str_replace("\\","",$POST);
        //if the passing argument is an object
        if(is_object($POST)){
            //loop through the object
            foreach($POST as $k => $v){
                //if the json_decode value doesnt return null then we know that its a json object and we decode it
                if(json_decode($v) != NULL)$POST->$k=json_decode($v);
            }
        //otherwise if its an array
        }elseif(is_array($POST)){
            //loop through the array
            foreach($POST as $k => $v){
                //if the json_decode value doesnt return null then we know that its a json object and we decode it
                if(json_decode($v) != NULL)$POST[$k]=json_decode($v, FALSE);
            }
        //otherwise if its just a single string (not sure if this is possible...)
        }else{
            //if the json_decode value doesnt return null then we know that its a json object and we decode it
            if(json_decode($POST) != NULL)$POST=json_decode($POST);
        }
        //return the json object
        return $POST;

    }

/*************************************************
* 	CONVERTS MULTI ARRAY TO SINGLE ARRAY
**************************************************/
    function single_array(&$array){
        //check to make sure the passed argument is an array
        if( ! is_array($array) && ! is_object($array)) return 'Value not array.';
        //if the array is only length 1, this means its the single dimension we want
        if(count($array)==1){
            //reset the index of the array to the begining
            reset($array);
            //get the key of the array in case it is an assoc array
            $key=key($array);
            //return the single array
            $array=$array[$key];
        //otherwise if the array is more than one element then
        }elseif(count($array)>1){
            //we loop through the array
            foreach($array as $arr){
                //make the array come back until its done
                $this->single_array($arr);
            }
        }
        //return the array
        return $array;
    }

/*************************************************
* CONVERTS ARRAYS TO OBJECTS
**************************************************/
    function array_to_obj(&$array){
        //check to make sure the passed argument is an array
        if( ! is_array($array) && ! is_object($array)) return 'Value not array.';
        //if the array is only length 1, this means its the single dimension we want
        if(count($array)==1){
            //reset the index of the array to the begining
            reset($array);
            $obj = new stdClass();
            for($i=0;$i<count($array);$i++){
                //get the key of the array
                $key=key($array);
                $obj->$key=$array[$key];
                next($array);
            }
        }elseif(count($array)>1){
            //we loop through the array
            foreach($array as $arr){
                //make the array come back until its done
                $this->array_to_obj($arr);
            }
        }
        return $array=$obj;
    }
}
