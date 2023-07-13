<?php

class G_Project_Site_Extends{

	public $id;
    public $projectname;
    public $projectlocation;
    public $start_date;
    public $end_date;
    public $projectDescription;
	public $device_id;

    function __construct($id = ''){
       $this->id = $id;
    }

    public function setId($value) {
		$this->id = $value;
	}

	public function getId() {
		return $this->id;
	}

	public function setprojectname($value) {
		$this->projectname = $value;
	}

	public function getprojectname() {
		return $this->projectname;
	}

	public function setlocation($value) {
		$this->projectlocation = $value;
	}

	public function getlocation() {
		return $this->projectlocation;
	}

	public function setStart_date($value) {
		$this->start_date = $value;
	}

	public function getStart_date() {
		return $this->start_date;
	}

	public function setEnd_date($value) {
		$this->end_date = $value;
	}

	public function getEnd_date() {
		return $this->end_date;
	}

	public function setProjectDescription($value) {
		$this->projectDescription = $value;
	}

	public function getProjectDescription() {
		return $this->projectDescription;
	}

	public function setDeviceId($value) {
		$this->device_id = $value;
	}

	public function getDeviceId() {
		return $this->device_id;
	}

	public function setProjectSite() {
		return G_Project_Site::insert_project_site($this);
	}

	public function updateProjectSite() {
		return G_Project_Site::updateProjectSite($this);
	}


}



?>