<?php
class G_Settings_Memo extends Settings_Memo {
	
	public $is_archive;
	
	// Constants
	//const IS_ACTIVATED 			= 'Yes';
	//const IS_DEACTIVATED 		= 'No';

	const TERMINATION = 1;	
	const YES = "Yes";
	const NO  = "No";
		
	public function __construct() {}
	
	public function setIsArchive($value) {
		$this->is_archive = $value;
	}
	
	public function getIsArchive() {
		return $this->is_archive;
	}
	
	public function archive() {
		G_Settings_Memo_Manager::archive($this);
	}	
	
	public function restore() {
		G_Settings_Memo_Manager::restore($this);
	}
	
	public function save() {		
		return G_Settings_Memo_Manager::save($this);
	}
	
	public function delete() {
		return G_Settings_Memo_Manager::delete($this);
	}
}
?>