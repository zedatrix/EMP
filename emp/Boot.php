<?php
if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');
/*************************************************
* 	@name Boot File For EMP Framework for php
*	@package		EMP
* 	@author		We Excel Team
**************************************************/

if( ! class_exists('Boot') ){

    class Boot{
 	
        public $Config=array();
        public $Dir='';
        public $Class='';
        public $Args=NULL;
        public $Method='index';
        /**
         * @name constructor
         * @desc the construct function is basically the bootstrap
         */
        function __construct(){
            $this->get_config();
            $this->load_core();
            $this->Args=$this->set_call();
            spl_autoload_register(array($this, '_autoload'));
        }
        /**
         * @name autoload
         * @desc this is the function that will serve as the magic autoload function
         */
        function _autoload($className){

            try{
                if(file_exists(CORE_PATH."class.".strtolower($className).EXT)) require_once(CORE_PATH."class.".strtolower($className).EXT);
                if(file_exists(LIB_PATH_PHP."class.".strtolower($className).EXT)) 	require_once(LIB_PATH_PHP."class.".strtolower($className).EXT);
            }catch(Exception $e){
                    if(defined('ENV')){
                        if(ENV=='dev') echo print_r($e, TRUE);
                    }
            }
        }

        /**
         * @name load core
         * Loads the system core files needed to boot up
         */
        function load_core(){
            require(CORE_PATH.'Functions.php');
        }
        /**
         * @name get configuration
         * @desc this function gets all the configurational data and sets it up in the $Config variable
         */
        function get_config(){
            global $conf;
            $dir=APP_PATH."config";
                if(is_dir($dir)){
                    if($dh=opendir($dir)){
                        while(($file=readdir($dh)) !== FALSE){
                            try{
                                if(substr($file,-4)==EXT){
                                    require_once($dir.SEP.$file);
                                    if(is_array($conf)){
                                            if(count($conf)>0) $this->Config=array_merge($conf);
                                    }
                                }

                            }catch(Exception $e){
                                //do nothing
                            }
                        }
                    }
                }
                if($this->Config['site_url'] ==''){
                    $this->Config['site_url']=(isset($_SERVER['HTTPS']) &&  $_SERVER['HTTPS'] == 'on' )? 'https://'.$_SERVER['SERVER_NAME']."/":'http://'.$_SERVER['SERVER_NAME']."/";
                }
                if(defined('INCLUDE_PATH')){
                    ini_set('include_path', ini_get('include_path').INCLUDE_PATH);
                }else{
                    ini_set('include_path', ini_get('include_path').";".FRAMEWORK);
                }
        }

        /**
         * Sets the request from user, and points to the right location ie takes the url and sends it to appropriate controller and function
         */
        function set_call(){
            $uri=$this->_get_url();
            if( ! $this->check_url_type($uri)){
                //todo: if the $url is == '' then we can automatically direct it to a default controller
                $segments=$this->_explode_segments($uri);
                return $this->_validate_request($segments);
            }else{
                include(FRAMEWORK.$uri);
            }
        }
        /**
         * Checks the type of url, to see if it is a js call or css call
         */
        function check_url_type($url){
            $a=explode("/", $url);
            if($a[2]=='lib'|| $a[2]=='styles') return TRUE;
        }
            /**
         * Get the URLString
         *
         */
         function _get_url(){
                // Is the request coming from the command line?
                if(php_sapi_name() == 'cli' or defined('STDIN'))return $this->_set_uri_string($this->_parse_cli_args());
                // Let's try the REQUEST_URI first, this will work in most situations
                if($uri = $this->_check_url()) return $this->_set_uri_string($uri);
                // Is there a PATH_INFO variable?
                // Note: some servers seem to have trouble with getenv() so we'll test it two ways
                $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
                if(trim($path, '/') != '' && $path != "/".SELF) return $this->_set_uri_string($path);
                // No PATH_INFO?... What about QUERY_STRING?
                $path =  (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
                if(trim($path, '/') != '')return $this->_set_uri_string($path);
                // As a last ditch effort lets try using the $_GET array
                if(is_array($_GET) && count($_GET) == 1 && trim(key($_GET), '/') != '') return $this->_set_uri_string(key($_GET));
                // We've exhausted all our options...
                return '';

        }

        /**
         * Set the URI String
         *
         * @access	public
         * @param 	string
         * @return	string
         */
        function _set_uri_string($str){
            // Filter out control characters
            $str = remove_invisible_characters($str, FALSE);

            // If the URI contains only a slash we'll kill it
            return ($str == '/') ? '' : $str;
        }

        /**
         * Parse cli arguments
         *
         * Take each command line argument and assume it is a URI segment.
         *
         */
        private function _parse_cli_args()
        {
            $args = array_slice($_SERVER['argv'], 1);

            return $args ? '/' . implode('/', $args) : '';
        }

        /**
         * Checks the URI
         *
         * Takes the url, strips the GET from it, puts the GET into a $_GET array and returns the url without the get
         */
        private function _check_url(){
            if( ! isset($_SERVER['REQUEST_URI']) OR ! isset($_SERVER['SCRIPT_NAME']))	return '';

            $uri = $_SERVER['REQUEST_URI'];
            $_SERVER['SCRIPT_NAME']='emp/emp.php';
              $script_name=$_SERVER['SCRIPT_NAME'];
              if(strpos($uri, $script_name) === 0){
                  $uri = substr($uri, strlen($script_name));
              }elseif(strpos($uri, dirname($script_name)) === 0){
                  $uri = substr($uri, strlen(dirname($script_name)));
              }
              // This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
              // URI is found, and also fixes the QUERY_STRING server var and $_GET array.
              if(strncmp($uri, '?/', 2) === 0) $uri = substr($uri, 2);
              $parts = preg_split('#\?#i', $uri, 2);
              $uri = $parts[0];
              if(isset($parts[1])){
                  $_SERVER['QUERY_STRING'] = $parts[1];
                  parse_str($_SERVER['QUERY_STRING'], $_GET);
              }else{
                  $_SERVER['QUERY_STRING'] = '';
                  //removed due to incompatibility with processmaker
                  //$_GET = array();
              }
              if($uri == '/' || empty($uri)) return '/';
              $uri = parse_url($uri, PHP_URL_PATH);

              // Do some final cleaning of the URI and return it
              return str_replace(array('//', '../'), '/', trim($uri, '/'));
        }

        /**
         *	@name explode segments
         *
         * Explode the URI Segments. The individual segments will
         * be stored in the $this->segments array.
         */
        function _explode_segments($uri){
            foreach(explode("/", preg_replace("|/*(.+?)/*$|", "\\1", $uri)) as $val){
                // Filter segments for security
                $val = trim(filter_uri($val));
                if($val != '') $segments[] = $val;
            }
            return $segments;
        }

        /**
         *	@name validate request
         *
         * Validates the supplied segments.  Attempts to determine the path to
         * the controller.
         */
        function _validate_request($segments){
            //die(print_r($segments, TRUE));
            if( ! isset($segments[0])) return $this->set_404();
            if(count($segments) == 0) 	return $segments;
            if($segments[0]=='emp'){
                array_shift($segments);
                if( ! isset($segments[0])) return $this->set_404();
                $this->set_directory(FRAMEWORK);
                $this->set_directory($segments[0]);
                array_shift($segments);
            }else{
                $this->set_directory(FRAMEWORK);
                $this->set_directory($segments[0]);
                array_shift($segments);
            }
            // Does the requested controller exist in the users folder?
            if( ! isset($segments[0])) return $this->set_404();
            if(file_exists($this->Dir.ucfirst(strtolower($segments[0])).EXT)){
                $this->set_class($segments[0]);
                array_shift($segments);
                if(isset($segments[0])){
                    $this->set_method($segments[0]);
                    array_shift($segments);
                    return ($segments==0 || $segments>0) ? $segments : '';
                }else{
                    return '';
                }
            }elseif(is_dir($this->Dir.$segments[0])){// Is the controller in a sub-folder?
                // Set the directory and remove it from the segment array
                $this->set_directory($segments[0]);
                array_shift($segments);

                if(count($segments) == 0 || count($segments) > 0){
                    // Does the requested controller exist in the sub-folder?
                    if(file_exists(FRAMEWORK.$this->Dir.strtolower($segments[0]).EXT)){
                            $this->set_class($segments[0]);
                            array_shift($segments);
                            $this->set_method(isset($segments[0]) ? $segments[0] : 'index');
                            array_shift($segments);
                            //if(count($segments)==0 || count($segments>0)) return $segments;
                            return ($segments==0 || $segments>0) ? $segments : '';
                    }else{
                        return $this->set_404();
                    }
                }
            }else{
                return $this->set_404();
            }
        }
        /**
         *  Set the directory name
         */
        function set_directory($dir){
            if($dir=='') return $this->Dir='';
            $this->Dir.= (substr($dir,-1)!=SEP)?str_replace(array('.'), '', $dir).SEP:str_replace(array('.'), '', $dir);
        }
            /**
         * Set the class name
         */
        function set_class($class){
            if($class=='') return $this->Class='';
            $this->Class = ucfirst(str_replace('/', '', $class));
        }
        /**
         * Set the class method
         */
        function set_method($method){
            if($method=='') return $this->Method='index';
            $this->Method = str_replace(array('/', '.'), '', $method);
        }
        function set_404(){
            $this->set_directory('');
            $this->set_directory(VIEW_PATH);
            $this->set_class('404');
            return '';
        }

     }

}
/*************************************************
*  Boot Up!
**************************************************/
$BootUp=new Boot();

/*************************************************
*  Load the requested controller
**************************************************/
include_once($BootUp->Dir.$BootUp->Class.EXT);

/*************************************************
*  Instantiate the requested controller
**************************************************/
$EMP=new $BootUp->Class();

/*************************************************
*  Assign config variables to the new class
**************************************************/
$EMP->_Config=array_merge($BootUp->Config);

/*************************************************
*  Create core classes
**************************************************/
$EMP->load_classes();

/*************************************************
*  Run the custom construct function
**************************************************/
$EMP->Construct();

/*************************************************
*  Call the requested method
**************************************************/
return call_user_func_array(array(&$EMP, $BootUp->Method), $BootUp->Args);
