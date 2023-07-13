<?php
class G_Email_Buffer {

	protected $id;
	protected $from;
	protected $email_address;
	protected $name;
	protected $subject;
	protected $message;
	protected $attachment;
	protected $is_sent;
	protected $is_archive;
	protected $error_message;
	protected $date_added;
	
	const YES = 'Yes';
	const NO  = 'No';
		
	public function __construct($id) {
		$this->id = $id;
	}

	public function setId($value) {
		$this->id = $value;
	}

	public function getId() {
		return $this->id;
	}
	
	public function setFrom($value) {
		$this->from = $value;
	}

	public function getFrom() {
		return $this->from;
	}
	
	public function setEmailAddress($value) {
		$this->email_address = $value;
	}

	public function getEmailAddress() {
		return $this->email_address;
	}
	
	public function setName($value) {
		$this->name = $value;
	}

	public function getName() {
		return $this->name;
	}
	
	public function setSubject($value) {
		$this->subject = $value;
	}

	public function getSubject() {
		return $this->subject;
	}
	
	public function setMessage($value) {
		$this->message = $value;
	}

	public function getMessage() {
		return $this->message;
	}
	
	public function setAttachment($value) {
		$this->attachment = $value;
	}

	public function getAttachment() {
		return $this->attachment;
	}
	
	public function setIsSent($value) {
		$this->is_sent = $value;
	}

	public function getIsSent() {
		return $this->is_sent;
	}
	
	public function setIsArchive($value) {
		$this->is_archive = $value;
	}

	public function getIsArchive() {
		return $this->is_archive;
	}
	
	public function setErrorMessage($value) {
		$this->error_message = $value;
	}

	public function getErrorMessage() {
		return $this->error_message;
	}
	
	public function setDateAdded($value) {
		$this->date_added = $value;
	}

	public function getDateAdded() {
		return $this->date_added;
	}
	
	public function save() {
		return G_Email_Buffer_Manager::save($this);
	}

	public function delete() {
		return G_Email_Buffer_Manager::delete($this);
	}
}

?>