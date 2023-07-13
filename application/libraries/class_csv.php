<?php
//$csv = new Csv($table_name,$file_name);
//$csv->execute();

class Csv { //sample class name

	public $table_name; //please input your table
	public $file_name;
	
	public function __construct($table_name='',$file_name='') {
		$this->file_name = ($file_name=='') ? date("Y-m-d") : $file_name;
		$this->table_name = ($table_name!='') ? $table_name : die("No Table given");
	}
	
	public function execute() {
		$filename = tempnam(sys_get_temp_dir(), "csv");
		
		$file = fopen($filename,"w");
		
		// Write column names
		$result = mysql_query("show columns from ".$this->table_name);
		for ($i = 0; $i < mysql_num_rows($result); $i++) {
			$colArray[$i] = mysql_fetch_assoc($result);
			$fieldArray[$i] = $colArray[$i]['Field'];
		}
		fputcsv($file,$fieldArray);
		
		// Write data rows
		$result = mysql_query("select * from " .$this->table_name);
		for ($i = 0; $i < mysql_num_rows($result); $i++) {
			$dataArray[$i] = mysql_fetch_assoc($result);
		}
		foreach ($dataArray as $line) {
			fputcsv($file,$line);
		}
		
		fclose($file);
		
		header("Content-Type: application/csv");
		header("Content-Disposition: attachment;Filename=".$this->file_name.".csv");
		
		// send file to browser
		readfile($filename);
		unlink($filename);
	}
	
}
?>