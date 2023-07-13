<?php
/*
	Usage:
		$e = G_Employee_Finder::findById(1);
		$s = new G_Schedule_Specific;
		$s->setDateStart('2012-08-21');
		$s->setDateEnd('2012-08-23');
		$s->setTimeIn('08:00:00');
		$s->setTimeOut('20:00:00');
		$s->setEmployeeId($e->getId());
		$s->save();
*/
class G_Schedule_Specific {
	protected $id;
	protected $employee_id;
	protected $date_start;
	protected $date_end;
	protected $time_in;
	protected $time_out;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;	
	}	
	
	public function setDateStart($value) {
		$this->date_start = $value;	
	}
	
	public function getDateStart() {
		return $this->date_start;	
	}
	
	public function setDateEnd($value) {
		$this->date_end = $value;	
	}
	
	public function getDateEnd() {
		return $this->date_end;	
	}	
	
	public function setTimeIn($value) {
		$this->time_in = $value;	
	}
	
	public function getTimeIn() {
		return $this->time_in;	
	}
	
	public function setTimeOut($value) {
		$this->time_out = $value;	
	}
	
	public function getTimeOut() {
		return $this->time_out;	
	}
	
	public function delete() {
		return G_Schedule_Specific_Manager::delete($this);	
	}
	
	public function save() {
		return G_Schedule_Specific_Manager::save($this);	
	}
}
?>