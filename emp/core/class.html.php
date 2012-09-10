<?php  if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');

class Html {
	
	public $_Config=array();

	function __construct(){
	}
	
	
/**
 * Text Input Field
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
	function input_box($data = '', $value = '', $extra = ''){
		if(is_array($data)){
			if(strlen($data['id'])>0) $defaults = array('type'=>'text','id' => $data['id'], 'name'=> $data['id'], 'value'=>$data['value']);
		}else{
			$defaults = array('type' => 'text', 'id' => (( ! is_array($data)) ? $data : ''),'name' => (( ! is_array($data)) ? $data : ''), 'value' => $this->form_prep($value));
		}
		return "<input ".$this->parse_attribs($defaults).$extra." />";
	}
	
	/**
	 * alias for input_box
	 */
	function text_box($data = '', $value = '', $extra = ''){
		return $this->input_box($data, $value, $extra);
	}

    /**
     * Hidden Input Field
     *
     * Generates hidden fields.  You can pass a simple key/value string or an associative
     * array with multiple values.
     *
     * @access	public
     * @param	mixed
     * @param	string
     * @return	string
     */
    function hidden_box($data = '', $value = '', $extra = ''){
        if(is_array($data)){
            if(strlen($data['id'])>0) $defaults = array('type'=>'hidden','id' => $data['id'], 'name'=> $data['id'], 'value'=>$data['value']);
        }else{
            $defaults = array('type' => 'hidden', 'id' => (( ! is_array($data)) ? $data : ''),'name' => (( ! is_array($data)) ? $data : ''), 'value' => $this->form_prep($value));
        }
        return "<input ".$this->parse_attribs($defaults).$extra." />";
    }
	
	/**
	 * Textarea field
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @param	string
	 * @return	string
	 */
		function textarea_box($data = '', $value = '', $extra = ''){
		if(is_array($data)){
			if(strlen($data['id'])>0) $defaults = array('id' => $data['id'], 'name'=> $data['id'],'cols' => '30', 'rows' => '5');
		}else{
			$defaults = array('id' => (( ! is_array($data)) ? $data : ''),'name'=> (( ! is_array($data)) ? $data : ''), 'cols' => '30', 'rows' => '5');
		}
		if( ! is_array($data) OR ! isset($data['value']))	{
			$val = $value;
		}else	{
			$val = $data['value'];
			unset($data['value']); // textareas don't use the value attribute
		}
		$name = (is_array($data)) ? $data['id'] : $data;
		return "<textarea ".$this->parse_attribs($defaults).$extra.">".$this->form_prep($val, $name)."</textarea>";
	}
	/**
	 * Form Label Tag
	 *
	 * @access	public
	 * @param	string	The text to appear onscreen
	 * @param	string	The id the label applies to
	 * @param	string	Additional attributes
	 * @return	string
	 */
		function label($data = '', $value ='',$attributes = array()){
        $label = '<label';
        if( ! is_array($data)){
            if($data != '') $label .= " for=\"$data\"";
            if(is_array($attributes) AND count($attributes) > 0){
                foreach($attributes as $key => $val){
                    $label .= ' '.$key.'="'.$val.'"';
                }
            }
            $label .= ">$value</label>";
        }elseif(is_array($data)){
            $label .= (isset($data['id']) && $data['id']!='')?" for='".$data['id']."'":"";
            if(is_array($attributes) AND count($attributes) > 0){
                foreach($attributes as $key => $val){
                    $label .= ' '.$key.'="'.$val.'"';
                }
            }elseif(isset($data['attribs']) && count($data['attribs'])>0){
                foreach($data['attribs'] as $k => $v){
                    $label .= ' '.$k.'="'.$v.'"';
                }
            }
            $label .= (isset($data['value']) && $data['value']!='')?">".$data['value']."</label>":"";
        }
		return $label;
	}
	/**
	 * Submit Button
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @param	string
	 * @return	string
	 */
		function form_submit($type='submit', $id = '', $value = '', $extra = ''){
			$defaults = array('type' => $type, 'id' => $id, 'value' => $value);
			return "<input ".$this->parse_attribs($defaults).$extra." />";
		}
	/**
	 * Parse the form attributes
	 *
	 * Helper function used by some of the form helpers
	 *
	 * @access	private
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	function parse_attribs($defaults){
		$att = '';
		foreach ($defaults as $key => $val){
			$att .= $key . '="' . $val . '" ';
		}
		return $att;
	}
	
	/**
	 * Form Prep
	 *
	 * Formats text so that it can be safely placed in a form field in the event it has HTML tags.
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
		function form_prep($str = '', $field_name = ''){
		static $prepped_fields = array();
		// if the field name is an array we do this recursively
		if (is_array($str)){
			foreach ($str as $key => $val){
				$str[$key] = $this->form_prep($val);
			}
			return $str;
		}

		if ($str === '') return '';

		// we've already prepped a field with this name
		// @todo need to figure out a way to namespace this so
		// that we know the *exact* field and not just one with
		// the same name
		if (isset($prepped_fields[$field_name])){
			return $str;
		}
		$str = htmlspecialchars($str);
		// In case htmlspecialchars misses these.
		$str = str_replace(array("'", '"'), array("&#39;", "&quot;"), $str);
		if ($field_name != ''){
			$prepped_fields[$field_name] = $field_name;
		}
		return $str;
	}
	function __destruct(){
		
	}

}

//EOF {File Location: )