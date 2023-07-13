<?php
/**
* Gleent Inc. SHR Audit Trail Class
*
* global class for shr audit trai.
*
* @version 1.0.0
* @author Gleent Inc.
* @date created October-06-2022  
*/


// Sample Usage:
/********************
	$audit = new Sprint_Audit();			
	$audit->setUser("hr@sprinthr.com");
	$audit->setAction('');
	$audit->setDetails("");
	$audit->triggerAudit('Success'); Success or Fail	
*/


class Sprint_Shr_Audit
{
	protected $employee_id;
	protected $user;
	protected $role;
	protected $module;
	protected $activity_action;
	protected $activity_type;
	protected $audited_action;
	protected $from;
	protected $to;
	protected $event_status;
	protected $position;
	protected $department;
	protected $audit_date;
	protected $audit_time;
	protected $ip_address;


	public function setShrEmployeeID($value) {
		$this->employee_id = $value;
	}

	public function setShrUser($value) {
		$this->user = $value;
	}

	public function setShrRole($value) {
		$this->role = $value;
	}

	public function setShrModule($value) {
		$this->module = $value;
	}
	
	public function setShrActivityAction($value) {
		$this->activity_action = $value;	
	}
	
	public function setShrActivityType($value) {
		$this->activity_type = $value;
	}

	public function setShrAuditedAction($value) {
		$this->audited_action = $value;
	}

	public function setShrFrom($value) {
		$this->from = $value;
	}

	public function setShrTo($value) {
		$this->to = $value;
	}

	//public function setShrEventStatus($value) {
	//	$this->event_status = $value;
	//}

	public function setShrPosition($value) {
		$this->position = $value;
	}

	public function setShrDepartment($value) {
		$this->department = $value;
	}

	/*public function setShrAuditDate($value) {
		$this->audit_date = $value;
	}

	public function setShrAuditTime($value) {
		$this->audit_time = $value;
	}

	public function setShrIpAddress($value) {
		$this->ip_address = $value;
	}*/
	
	public function triggerShrAudit($status) {
		date_default_timezone_set("Asia/Manila");
		$c_date = Tools::getCurrentDateTime('Y-m-d');
		$c_time = Tools::getCurrentDateTime('h:i:a');

		$at = new G_Shr_Audit_Trail();
		$user_info = $at->getShrUserIPAndCountry();

		//echo $this->module;
		//$at->setShrEmployeeID($this->employee_id);
		$at->setShrUser($this->user);
		$at->setShrRole($this->role);
		$at->setShrModule($this->module);
		$at->setShrActivityAction($this->activity_action);
		$at->setShrActivityType($this->activity_type);
		$at->setShrAuditedAction($this->audited_action);
		$at->setShrFrom($this->from);
		$at->setShrTo($this->to);		

		if($status == 1) {
			$at->setShrEventStatus(G_Shr_Audit_Trail::SUCCESS);
		}
		else{ 
			$at->setShrEventStatus(G_Shr_Audit_Trail::FAILED); 
		} 

		$at->setShrPosition($this->position);
		$at->setShrDepartment($this->department);
		$at->setShrAuditDate($c_date);
		$at->setShrAuditTime($c_time);
		$at->setShrIpAddress($user_info['ip']);	
		$at->save_shr_audit_trail();		
	}
				
}

?>