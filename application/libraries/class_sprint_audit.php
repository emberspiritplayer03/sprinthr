<?php
/**
* Gleent Inc. Audit Trail Class
*
* global class for audit trai.
*
* @version 1.0.0
* @author Gleent Inc.
* @date created May-15-2013    
*/


// Sample Usage:
/********************
	$audit = new Sprint_Audit();			
	$audit->setUser("hr@sprinthr.com");
	$audit->setAction('');
	$audit->setDetails("");
	$audit->triggerAudit('Success'); Success or Fail	
*/


class Sprint_Audit
{
	protected $user;
	protected $action;
	protected $details;	
	public function setUser($value) {
		$this->user = $value;
	}
	
	public function setAction($value) {
		$this->action = $value;	
	}
	
	public function setDetails($value) {
		$this->details = $value;
	}
	
	public function triggerAudit($status) {
		$c_date = Tools::getCurrentDateTime('Y-m-d h:i:s','Asia/Manila');
		$at = new G_Audit_Trail();
		$user_info = $at->getUserIPAndCountry();
		$at->setUser($this->user);
		$at->setAction($this->action); 
		if($status == 1) {
			$at->setEventStatus(G_Audit_Trail::SUCCESS);
		}else{ $at->setEventStatus(G_Audit_Trail::FAIL); } 
		$at->setDetails($this->details);
		$at->setAuditDate($c_date);
		$at->setIpAddress($user_info['ip']);	
		$at->save();		
	}
				
}

?>