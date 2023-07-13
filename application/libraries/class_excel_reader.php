<?php
Loader::appMainLibrary('excel_reader/excel_reader2');

class Excel_Reader {
	protected $data;
	
	function __construct($file = '', $store_extended_info = false, $output_encoding = '') {
		$this->data = new Spreadsheet_Excel_Reader($file, $store_extended_info, $output_encoding);
	}
	
	public function countRow() {
		return $this->data->rowcount();	
	}
	
	public function getValue($row, $column, $sheet = 0) {
		return $this->data->val($row, $column, $sheet);	
	}
}
?>