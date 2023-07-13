<?php
class IO_Reader {	
	protected $content;
    protected $filename;
	protected $date_created;	

	const PREFIX_FILE_USER_INFO  = "user_info_";
	const PREFIX_FILE_PERMISSION = "user_actions_";
	const DEFAULT_FILE_EXT       = ".tmp";
	
	public function __construct() {
		
	}
	
	public function setContent($value) {
		$this->content = $value;
	}
	
	public function getContent() {
		return $this->content;
	}
	
	public function setFileName($value) {
		$this->filename = $value;
	}
	
	public function setDateCreated($value) {
		$this->date_created = $value;
	}
	
	public function writeToTextFile() {				
		if(isset($this->filename)){		
			$handle = fopen($this->filename, 'w') or die("error"); //implicitly creates file		
			fwrite($handle, $this->content);
			fclose($handle);
			$return = true;		
		}else{
			$return = false;
		}

		return $return;
	}
	
	public function appendTotTextFile() {		
		if(isset($this->filename)){						
			if(Tools::isFileExist($this->filename)==1) {
				$handle = fopen($this->filename, 'a') or die("error"); //implicitly creates file			
				fwrite($handle, $this->content);
				fclose($handle);
				$return = true;
			}else{			
				$this->writeToTextFile();
				$return = false;
			}
		}else{
			return false;
		}
		return $return;
	}
	
	public function readTextFile() {
		if(isset($this->filename)){				
			$data = array(); 				
			//if(Tools::isFileExist($this->filename)==1) {			
				$handle = fopen($this->filename, 'r');			
				fclose($handle);				
				
				$handle = fopen($this->filename, "r");
				if ($handle) {
					while (($line = fgets($handle)) !== false) {
						$data[] = explode(",",$line);
					}
				} else {
					return false;
				}
				fclose($handle);	
				
			/*}else{								
				return false;
			}	*/	
		}else{
			return false;
		}
		return $data;
	}
	
	public function deleteTextFile() {		
		if(isset($this->filename)){
			unlink($this->filename);
		}
	}
}

?>
