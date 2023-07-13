<?php
class Notifications {
	protected $id;
	public $event_type;
	public $description;
	public $status;
	public $item;
	public $date_modified;
	public $date_created;
	
	public function __construct() {
		
	}

	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}

	public function setEventType($value) {
		$this->event_type = $value;
	}
	
	public function getEventType() {
		return $this->event_type;
	}

	public function setDescription($value) {
		$this->description = $value;
	}
	
	public function getDescription() {
		return $this->description;
	}

	public function setStatus($value) {
		$this->status = $value;
	}
	
	public function getStatus() {
		return $this->status;
	}

	public function setItem($value) {
		$this->item = $value;
	}
	
	public function getItem() {
		return $this->item;
	}
    
    public function setDateModified($value) {
		$this->date_modified = $value;
	}
	
	public function getDateModified() {
		return $this->date_modified;
	}

	public function setDateCreated($value) {
		$this->date_created = $value;
	}
	
	public function getDateCreated() {
		return $this->date_created;
	}

}
?>
