<?php 

class Frequency {

	protected $id;
	protected $frequency_type;



	public function setId($id) {
		$this->id = $id;
	}
	public function getId() {
		return $this->id;	
	}

	public function setFrequencyType($frequency_type) {
		$this->frequency_type = $frequency_type;
	}
	public function getFrequencyType() {
		return $this->frequency_type;	
	}



}


?>