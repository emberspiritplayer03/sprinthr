<?php
class G_Convert_Leave extends Converted_Leave {

	public function __construct() {
		
	}

	public function convertedLeavesByYear( $year = 0 ) {
		$data = array();

		if( $year > 0 ){
			$data = G_Converted_Leave_Helper::allConvertedLeavesByYear($year);
		}

		return $data;
	}
	
	public function save() {		
		return G_Converted_Leave_Manager::save($this);
	}
	
	public function delete() {
		return G_Converted_Leave_Manager::delete($this);
	}
}
?>