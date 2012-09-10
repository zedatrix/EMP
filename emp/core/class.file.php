<?php  if ( ! defined('FRAMEWORK')) exit('No direct script access allowed');

class File {

	/*
	 *  File Name
	 * @access: Public
	 * @var Str
	 */
	public $filename;
	/*
	 *  File Path
	 * @access: Public
	 * @var Str
	 */
	public $path;
	/*
	 *  File Full Name
	 * @access: Public
	 * @var Str
	 */
	public $full_filename;
	
	/*
	 *  File Name With Path
	 * @access: Public
	 * @var Str
	 */
	public $full_path;
	
	/*
	 *  File To Handle
	 * @access: Public
	 * @var Str/Res
	 */
	public $fileHandle;

	/*
	 *  File Extension
	 * @access: Public
	 * @var Str
	 */
	public $ext;
	
	/*
	 *  File Mime Type
	 * @access: Public
	 * @var Str
	 */
	public $mime;
	
	public $_Config=array();
	function __construct($_file=NULL){
		if($_file!==NULL){
			$this->fileHandle = $_file;
			$this->get_info();
			$this->get_mime();
		}

	}
	function set_handler($_file){
		$this->fileHandle = $_file;
		$this->get_info();
		$this->get_mime();
	}
	function get_info(){
		if( ! defined('MY_SEP')){
			if( PHP_OS == 'WINNT' && ! strpos ( $_SERVER['DOCUMENT_ROOT'], '/' )){
				define('MY_SEP',"\\");
			}else{
				define('MY_SEP', "/");
			}
		}
		$tmp_info=pathinfo($this->fileHandle);
		$this->path=$tmp_info['dirname'];
		$this->full_filename=$tmp_info['basename'];
		$this->ext=$tmp_info['extension'];
		$this->filename=$tmp_info['filename'];
		$this->full_path=$this->path.MY_SEP.$this->full_filename;
	}
	/**
	 * Sets file permissions
	 * @param str $mode permission to set to
	 * @return bool $set TRUE if successful otherwise FALSE
	 * @desc Sets file permissions
	 */
	function set_perm($path='', $mode=0777){
		if($path=='') $path=$this->full_path;
		return chmod($path, $mode);
	}
	/**
    * Returns the filesize in bytes
    * @return int $filesize The filesize in bytes
    * @desc Returns the filesize in bytes
    */
    function get_size(){
        return filesize($this->full_path);
    }
	/**
    * Returns the timestamp of the last change
    * @return timestamp $timestamp The time of the last change as timestamp
    * @desc Returns the timestamp of the last change
    */
    function get_time(){
        return fileatime($this->full_path);
    } 
	/**
    * Returns user id of the file
    * @return string $user_id The user id of the file
    * @desc Returns user id of the file
    */
    function get_owner_id(){
        return fileowner($this->full_path);
    } 
	/**
    * Returns group id of the file
    * @return string $group_id The group id of the file
    * @desc Returns group id of the file
    */
    function get_group_id(){
        return filegroup($this->full_path);
    }
	/**
    * Deletes a file
    * @return boolean $deleted Returns TRUE if file could be deleted, FALSE if not
    * @desc Deletes a file
    */
	function delete(){
        if(file_exists($this->full_path) && unlink($this->full_path)){
            return TRUE;
        }
        return FALSE;
    } 
	/**
	 * Creates a folder/directory
	 * @param $path path to create
	 * @param $mode mode to set to, default is 0777
	 * @param $R Allows the creation of nested directories specified in the pathname. Defaults to FALSE. 
	 * @return returns TRUE on success FALSE on failure
	 * @desc Creates a folder/directory
	 */
	function create_dir($path, $mode=0777, $R=FALSE){
		if( ! is_dir($path)) return mkdir($path, $mode, $R);
	}
	/**
	 * Deletes a folder/directory
	 * @param $path path to delete
	 * @return returns TRUE on success FALSE on failure
	 * @desc Deletes a folder/directory
	 */
	function delete_dir($path){
		return rmdir($path);
	}
	/**
    * Moves a file to the given destination
    * @param string $destination The new file destination
    * @return boolean $moved Returns TRUE if file could be moved, FALSE if not
    * @desc Moves a file to the given destination
    */
	function move($destination){
		if(strlen($destination)>0){
	        if(rename($this->full_path, $destination)){
	            return TRUE;
	        }else{
	        	die("Couldn't copy file to destination, please check permissions");
				return FALSE;
	        }
		}else{
			die("Destination must have at least one char");
			return FALSE;
		}
    } 
	/**
    * Copies a file to the given destination
    * @param string $destination The new file destination
    * @return boolean/str $copied Returns path copied to if file could be copied, FALSE if not
    * @desc Copies a file to the given destination
    */
    function copy($destination){
        if(strlen($destination)>0){
            if(copy($this->full_path,$destination)){
                return $destination;
            }else{
                die("Couldn't copy file to destination, please check permissions");
                return FALSE;
            }
        }else{
            die("Destination must have at least one char");
        }
    }
	function get_mime(){
		$_mimetypes = array(
         ".ez" => "application/andrew-inset",
         ".hqx" => "application/mac-binhex40",
         ".cpt" => "application/mac-compactpro",
         ".doc" => "application/msword",
         ".bin" => "application/octet-stream",
         ".dms" => "application/octet-stream",
         ".lha" => "application/octet-stream",
         ".lzh" => "application/octet-stream",
         ".exe" => "application/octet-stream",
         ".class" => "application/octet-stream",
         ".so" => "application/octet-stream",
         ".dll" => "application/octet-stream",
         ".oda" => "application/oda",
         ".pdf" => "application/pdf",
         ".ai" => "application/postscript",
         ".eps" => "application/postscript",
         ".ps" => "application/postscript",
         ".smi" => "application/smil",
         ".smil" => "application/smil",
         ".wbxml" => "application/vnd.wap.wbxml",
         ".wmlc" => "application/vnd.wap.wmlc",
         ".wmlsc" => "application/vnd.wap.wmlscriptc",
         ".bcpio" => "application/x-bcpio",
         ".vcd" => "application/x-cdlink",
         ".pgn" => "application/x-chess-pgn",
         ".cpio" => "application/x-cpio",
         ".csh" => "application/x-csh",
         ".dcr" => "application/x-director",
         ".dir" => "application/x-director",
         ".dxr" => "application/x-director",
         ".dvi" => "application/x-dvi",
         ".spl" => "application/x-futuresplash",
         ".gtar" => "application/x-gtar",
         ".hdf" => "application/x-hdf",
         ".js" => "application/x-javascript",
         ".skp" => "application/x-koan",
         ".skd" => "application/x-koan",
         ".skt" => "application/x-koan",
         ".skm" => "application/x-koan",
         ".latex" => "application/x-latex",
         ".nc" => "application/x-netcdf",
         ".cdf" => "application/x-netcdf",
         ".sh" => "application/x-sh",
         ".shar" => "application/x-shar",
         ".swf" => "application/x-shockwave-flash",
         ".sit" => "application/x-stuffit",
         ".sv4cpio" => "application/x-sv4cpio",
         ".sv4crc" => "application/x-sv4crc",
         ".tar" => "application/x-tar",
         ".tcl" => "application/x-tcl",
         ".tex" => "application/x-tex",
         ".texinfo" => "application/x-texinfo",
         ".texi" => "application/x-texinfo",
         ".t" => "application/x-troff",
         ".tr" => "application/x-troff",
         ".roff" => "application/x-troff",
         ".man" => "application/x-troff-man",
         ".me" => "application/x-troff-me",
         ".ms" => "application/x-troff-ms",
         ".ustar" => "application/x-ustar",
         ".src" => "application/x-wais-source",
         ".xhtml" => "application/xhtml+xml",
         ".xht" => "application/xhtml+xml",
         ".zip" => "application/zip",
         ".au" => "audio/basic",
         ".snd" => "audio/basic",
         ".mid" => "audio/midi",
         ".midi" => "audio/midi",
         ".kar" => "audio/midi",
         ".mpga" => "audio/mpeg",
         ".mp2" => "audio/mpeg",
         ".mp3" => "audio/mpeg",
         ".aif" => "audio/x-aiff",
         ".aiff" => "audio/x-aiff",
         ".aifc" => "audio/x-aiff",
         ".m3u" => "audio/x-mpegurl",
         ".ram" => "audio/x-pn-realaudio",
         ".rm" => "audio/x-pn-realaudio",
         ".rpm" => "audio/x-pn-realaudio-plugin",
         ".ra" => "audio/x-realaudio",
         ".wav" => "audio/x-wav",
         ".pdb" => "chemical/x-pdb",
         ".xyz" => "chemical/x-xyz",
         ".bmp" => "image/bmp",
         ".gif" => "image/gif",
         ".ief" => "image/ief",
         ".jpeg" => "image/jpeg",
         ".jpg" => "image/jpeg",
         ".jpe" => "image/jpeg",
         ".png" => "image/png",
         ".tiff" => "image/tiff",
         ".tif" => "image/tif",
         ".djvu" => "image/vnd.djvu",
         ".djv" => "image/vnd.djvu",
         ".wbmp" => "image/vnd.wap.wbmp",
         ".ras" => "image/x-cmu-raster",
         ".pnm" => "image/x-portable-anymap",
         ".pbm" => "image/x-portable-bitmap",
         ".pgm" => "image/x-portable-graymap",
         ".ppm" => "image/x-portable-pixmap",
         ".rgb" => "image/x-rgb",
         ".xbm" => "image/x-xbitmap",
         ".xpm" => "image/x-xpixmap",
         ".xwd" => "image/x-windowdump",
         ".igs" => "model/iges",
         ".iges" => "model/iges",
         ".msh" => "model/mesh",
         ".mesh" => "model/mesh",
         ".silo" => "model/mesh",
         ".wrl" => "model/vrml",
         ".vrml" => "model/vrml",
         ".css" => "text/css",
         ".html" => "text/html",
         ".htm" => "text/html",
         ".asc" => "text/plain",
         ".txt" => "text/plain",
         ".rtx" => "text/richtext",
         ".rtf" => "text/rtf",
         ".sgml" => "text/sgml",
         ".sgm" => "text/sgml",
         ".tsv" => "text/tab-seperated-values",
         ".wml" => "text/vnd.wap.wml",
         ".wmls" => "text/vnd.wap.wmlscript",
         ".etx" => "text/x-setext",
         ".xml" => "text/xml",
         ".xsl" => "text/xml",
         ".mpeg" => "video/mpeg",
         ".mpg" => "video/mpeg",
         ".mpe" => "video/mpeg",
         ".qt" => "video/quicktime",
         ".mov" => "video/quicktime",
         ".mxu" => "video/vnd.mpegurl",
         ".avi" => "video/x-msvideo",
         ".movie" => "video/x-sgi-movie",
         ".ice" => "x-conference-xcooltalk"
		);
		// return mime type for extension
		if(isset($_mimetypes[$this->ext])){
			$this->mime = $_mimetypes[$this->ext];
		// if the extension wasn't found return octet-stream         
		}else{
			$this->mime = 'application/octet-stream';
		}	
	}
}

//EOF {File Location: )