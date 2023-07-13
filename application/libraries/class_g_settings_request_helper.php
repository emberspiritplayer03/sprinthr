<?php
class G_Settings_Request_Helper {
	public static function isIdExist(G_Settings_Request $gsr) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_REQUEST ."
			WHERE id = ". Model::safeSql($gsr->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function isDescriptionExists($description, $type, $request_id = 0) {
		
		if($request_id > 0){
			$sql = "
				SELECT COUNT(*) as total
				FROM " . G_SETTINGS_REQUEST ." gsr
				WHERE (gsr.applied_to_description LIKE '%{$description}%' AND gsr.request_type =" . Model::safeSql($type) . " AND id <> " . Model::safeSql($request_id) . ")
				
			";

		}else{
			$sql = "
				SELECT COUNT(*) as total
				FROM " . G_SETTINGS_REQUEST ." gsr
				WHERE (gsr.applied_to_description LIKE '%{$description}%' AND gsr.request_type =" . Model::safeSql($type) . ")
				
			";
		}
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByType(G_Settings_Request $gsr) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_REQUEST ."
			WHERE type = ". Model::safeSql($gsr->getType()) ."
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_SETTINGS_REQUEST ."			
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	
	/*
		$employee = G_Employee_Finder::findById(30);
		$approvers = G_Settings_Request_Helper::getAllApprovers(Settings_Request::OT,$employee);
		return approvers id (array)
		
	*/
	public static function getAllApprovers($request_type,$employee) {
		$position 	 = G_Employee_Job_History_Finder::findCurrentJob($employee);
		$subdivision = G_Employee_Subdivision_History_Finder::findCurrentSubdivision($employee);
		
		$settings = G_Settings_Request_Finder::findAllActiveApproversByType($request_type);
		
		foreach($settings as $s):
			$departments = explode(',',unserialize($s->getDepartments()));
			foreach($departments as $d=>$value):
				if($value == $subdivision->getCompanyStructureId() || $value == -1) {
					$approvers_by_department = G_Settings_Request_Approver_Helper::getAllApproversBySettingsRequestId($s->getId());
				} else { 
					if(!$approvers_by_department)
					$approvers_by_department = array(); 
				}
			endforeach;
			
			$positions = explode(',',unserialize($s->getPositions()));
			foreach($positions as $p=>$value):
				//echo $value . ' ' . $position->getJobId() . '<Br/>';
				if($value == $position->getJobId() || $value == -1) {
					$approvers_by_position = G_Settings_Request_Approver_Helper::getAllApproversBySettingsRequestId($s->getId());
				} else {
					if(!$approvers_by_position)
					$approvers_by_position = array();
				}
			endforeach;
			
			$employees = explode(',',unserialize($s->getEmployees()));
			foreach($employees as $e=>$value):
			
				//echo $value . ' ' . $employee->getId() . '<Br/>';
				if($value == $employee->getId() || $value == -1) {
					$approvers_by_employees = G_Settings_Request_Approver_Helper::getAllApproversBySettingsRequestId($s->getId());
				} else {
					if(!$approvers_by_employees)
					$approvers_by_employees = array();
				}
			endforeach;
			
			$approvers			= array_merge($approvers_by_department,$approvers_by_position,$approvers_by_employees);
			if($approvers) {
				$unique_approvers 	= array_unique($approvers);
				//print_r($unique_approvers);
			} else {
				
			}
			
			//Tools::showArray($approvers);
			
			/*print_r($approvers_by_department);
			echo '<br/>';
			print_r($approvers_by_position);
			echo '<br/>';
			print_r($approvers_by_employees);*/
		endforeach;
		
		//Tools::showArray($unique_approvers);
		return $unique_approvers;
	}
}
?>