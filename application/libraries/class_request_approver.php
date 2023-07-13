<?php
class Request_Approver {
	protected $id;
	protected $title;	
	protected $approvers_name;
	protected $requestors_name;
	protected $date_created;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = (int) $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
    public function setTitle($value) {    	
		$this->title = $value;
	}
	
	public function getTitle() {
		return $this->title;
	}

	public function setApproversName($value) {
		$this->approvers_name = $value;
	}

	public function getApproversName() {
		return $this->approvers_name;
	}

	public function setRequestorsName($value) {
		$this->requestors_name = $value;
	}

	public function getRequestorsName() {
		return $this->requestors_name;
	}

	public function setDateCreated($value) {
		$date_format = date("Y-m-d H:i:s",strtotime($value));
		$this->date_created = $date_format;
	}

	public function getDateCreated() {
		return $this->date_created;
	}
}
?>