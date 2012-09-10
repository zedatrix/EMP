<?php  if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');
 /*************************************************
  * 	@name Global Procedural Functions For EMP Framework for php
  *	@package		EMP
 * 	@author		We Excel Team
 **************************************************/
if( ! function_exists('remove_invisible_characters')){
	function remove_invisible_characters($str, $url_encoded = TRUE){
		$non_displayables = array();
		// every control character except newline, carriage return , and horizontal tab
		
		if($url_encoded){
			$non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
		}
		
		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do{
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
	}
}
/**
	 * Filter URI for malicious characters
	 */
if ( ! function_exists('filter_uri')){
	function filter_uri($str, $perm_char=''){
		if($str != '' && $perm_char != ''){
			// preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
			// compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
			if( ! preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote($this->config->item('permitted_uri_chars'), '-'))."]+$|i", $str)) return 'The URI you submitted has disallowed characters.';
		}

		// Convert programatic characters to entities
		$bad	= array('$',		'(',		')',		'%28',		'%29');
		$good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');

		return str_replace($bad, $good, $str);
	}
}