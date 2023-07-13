<?php


class G_Employee_Activity_Attendance
{

protected $id;
protected $employee_activity_id;
protected $employee_id;
protected $project_site_id;
protected $frequency_id;
protected $payslip_id;
protected $date;
protected $activity_in;
protected $activity_out;
protected $activity_raw_worked_hrs;
protected $activity_deductible_break_hrs;
protected $activity_total_worked_hrs;
protected $activity_total_amount_worked;


public function __construct() {}


public function setId($value){

       $this->id = $value;
}

public function getId(){

	return $this->id;
}

public function setActivityId($value){

       $this->employee_activity_id = $value;
}

public function getActivityId(){

	return $this->employee_activity_id;
}


public function setEmployeeId($value){

       $this->employee_id = $value;
}

public function getEmployeeId(){

	return $this->employee_id;
}

public function setProjectSiteId($value){

       $this->project_site_id = $value;
}

public function getProjectSiteId(){

	return $this->project_site_id;
}

public function setFrequencyId($value){

       $this->frequency_id = $value;
}

public function getFrequencyId(){

	return $this->frequency_id;
}


public function setPayslipId($value){

       $this->payslip_id = $value;
}

public function getPayslipId(){

	return $this->payslip_id;
}

public function setDate($value){

       $this->date = $value;
}

public function getDate(){

	return $this->date;
}


public function setActivityIn($value){

       $this->activity_in = $value;
}

public function getActivityIn(){

	return $this->activity_in;
}


public function setActivityOut($value){

       $this->activity_out = $value;
}

public function getActivityOut(){

	return $this->activity_out;
}

public function setActivityRawWorkedHrs($value){

       $this->activity_raw_worked_hrs = $value;
}

public function getActivityRawWorkedHrs(){

	return $this->activity_raw_worked_hrs;
}

public function setActivityDeductibleBreakHrs($value){

       $this->activity_deductible_break_hrs = $value;
}

public function getActivityDeductibleBreakHrs(){

	return $this->activity_deductible_break_hrs;
}


public function setActivityTotalWorkedHrs($value){

       $this->activity_total_worked_hrs = $value;
}

public function getActivityTotalWorkedHrs(){

	return $this->activity_total_worked_hrs;
}


public function setActivityTotalAmountWorked($value){

       $this->activity_total_amount_worked = $value;
}

public function getActivityTotalAmountWorked(){

	return $this->activity_total_amount_worked;
}



 public function save(){

 	return G_Employee_Activity_Attendance_Manager::save($this);
 }


}
?>