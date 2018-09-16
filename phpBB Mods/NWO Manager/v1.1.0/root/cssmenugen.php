<?php


class OB_FileWriter
{
	private $_filename;
	private $_fp = null;
	private $_errorHandlersRegistered = false;

	public function __construct($filename)
	{
		$this->setFilename($filename);
	}

	public function __destruct()
	{
		//Make sure no data is lost
		if($this->_fp)
			$this->end();
	}

	public function setFilename($filename)
	{
		$this->_filename = $filename;
	}

	public function getFilename()
	{
		return $this->_filename;
	}

	public function setHaltOnError($value)
	{
		//If new state is same as old, don't do anything
		if($value === $this->_errorHandlersRegistered)
			return;


		if($value === true)
		{
			set_exception_handler(array($this,'exceptionHandler'));
			set_error_handler(array($this,'errorHandler'));
			$this->_errorHandlersRegistered = true;
		}
		else
		{
			restore_error_handler();
			restore_exception_handler();
			$this->_errorHandlersRegistered = false;
		}
	}

	public function start()
	{
		$this->_fp = @fopen($this->_filename,'w');
		if(!$this->_fp)
			throw new Exception('Cannot open file '.$this->_filename.' for writing!');

		ob_start(array($this,'outputHandler'),1024);
	}

	public function end()
	{
		$this->_stopBuffering();
		$this->setHaltOnError(false);
	}

	private function _stopBuffering()
	{
		@ob_end_flush();
		if($this->_fp)
			fclose($this->_fp);

		$this->_fp = null;
	}

	public function outputHandler($buffer)
	{
		fwrite($this->_fp,$buffer);
	}

	public function exceptionHandler($exception)
	{
		$this->_stopBuffering();
		echo '<b>Fatal error: uncaught', $exception;
	}

	public function errorHandler($errno, $errstr, $errfile, $errline)
	{
		$this->_stopBuffering();
		$errorNumber = E_USER_ERROR;
		switch($errno)
		{
		case E_ERROR:
			$errorNumber = E_USER_ERROR;
			break;
		case E_NOTICE:
			$errorNumber = E_USER_NOTICE;
			break;
		case E_WARNING:
			$errorNumber = E_USER_WARNING;
			break;
		}
		trigger_error("$errstr, File: $errfile line $errline",$errorNumber);
	}
}



$obfw = new OB_FileWriter('test.txt');
$obfw->start();

echo 'Hi everyone!';
echo 'I bet you can\'t see this in your browser';

$obfw->end();









function output_file($file, $name, $mime_type='')
{
 /*
 This function takes a path to a file to output ($file),
 the filename that the browser will see ($name) and
 the MIME type of the file ($mime_type, optional).

 If you want to do something on download abort/finish,
 register_shutdown_function('function_name');
 */

 ob_start()

 if(!is_readable($file)) die('File not found or inaccessible!');

 $size = filesize($file);
 $name = rawurldecode($name);

 /* Figure out the MIME type (if not specified) */
 $known_mime_types=array(
 	"pdf" => "application/pdf",
 	"txt" => "text/plain",
 	"html" => "text/html",
 	"htm" => "text/html",
	"exe" => "application/octet-stream",
	"zip" => "application/zip",
	"doc" => "application/msword",
	"xls" => "application/vnd.ms-excel",
	"ppt" => "application/vnd.ms-powerpoint",
	"gif" => "image/gif",
	"png" => "image/png",
	"jpeg"=> "image/jpg",
	"jpg" =>  "image/jpg",
	"php" => "text/plain"
 );

 if($mime_type==''){
	 $file_extension = strtolower(substr(strrchr($file,"."),1));
	 if(array_key_exists($file_extension, $known_mime_types)){
		$mime_type=$known_mime_types[$file_extension];
	 } else {
		$mime_type="application/force-download";
	 };
 };

 @ob_end_clean(); //turn off output buffering to decrease cpu usage

 // required for IE, otherwise Content-Disposition may be ignored
 if(ini_get('zlib.output_compression'))
  ini_set('zlib.output_compression', 'Off');

 header('Content-Type: ' . $mime_type);
 header('Content-Disposition: attachment; filename="'.$name.'"');
 header("Content-Transfer-Encoding: binary");
 header('Accept-Ranges: bytes');

 /* The three lines below basically make the
    download non-cacheable */

	header("Pragma: ");
	header("Cache-Control: ");
	header("Cache-Control: public");

 header("Cache-control: private");
 header('Pragma: private');
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

 // multipart-download and download resuming support
 if(isset($_SERVER['HTTP_RANGE']))
 {
	list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
	list($range) = explode(",",$range,2);
	list($range, $range_end) = explode("-", $range);
	$range=intval($range);
	if(!$range_end) {
		$range_end=$size-1;
	} else {
		$range_end=intval($range_end);
	}

	$new_length = $range_end-$range+1;
	header("HTTP/1.1 206 Partial Content");
	header("Content-Length: $new_length");
	header("Content-Range: bytes $range-$range_end/$size");
 } else {
	$new_length=$size;
	header("Content-Length: ".$size);
 }

 /* output the file itself */
 $chunksize = 1*(1024*1024); //you may want to change this
 $bytes_send = 0;
 if ($file = fopen($file, 'r'))
 {
	if(isset($_SERVER['HTTP_RANGE']))
	fseek($file, $range);

	while(!feof($file) &&
		(!connection_aborted()) &&
		($bytes_send<$new_length)
	      )
	{
		$buffer = fread($file, $chunksize);
		print($buffer); //echo($buffer); // is also possible
		flush();
		$bytes_send += strlen($buffer);
	}
 fclose($file);
 } else die('Error - can not open file.');

die();
}

/*********************************************
			Example of use
**********************************************/

/*
Make sure script execution doesn't time out.
Set maximum execution time in seconds (0 means no limit).
*/
set_time_limit(0);
$file_path='that_one_file.txt';
output_file($file_path, 'some file.txt', 'text/plain');


?>