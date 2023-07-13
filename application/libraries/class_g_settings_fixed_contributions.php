<?php
class G_Settings_Fixed_Contributions {

	public $contribution;
	public $id;
	public $is_enabled;


	public function setId($value) {
		$this->id = $value;	
	}

	public function getId() {
		return $this->id;
	}


	public function setIsEnabled($value) {
		$this->is_enabled = $value;	
	}

	public function getIsEnabled() {
		return $this->is_enabled;
	}


	public function setContributionName($value) {
		$this->contribution = $value;	
	}

	public function getContributionName() {
		return $this->contribution;
	}

	public function update(){

		return G_Settings_Fixed_Contributions_Manager::update($this);
	}

}


?>