<?php
class G_Employee_Request_Approver_Helper {
	public static function isIdExist(G_Employee_Request_Approver $gera) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ."
			WHERE id = ". Model::safeSql($gera->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByPositionEmployeeId(G_Employee_Request_Approver $gera) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ."
			WHERE position_employee_id = ". Model::safeSql($gera->getPositionEmployeeId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByLevel(G_Employee_Request_Approver $gera) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ."
			WHERE level = ". Model::safeSql($gera->getLevel()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByStatus(G_Employee_Request_Approver $gera) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ."
			WHERE status = ". Model::safeSql($gera->getStatus()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByType(G_Employee_Request_Approver $gera) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ."
			WHERE type = ". Model::safeSql($gera->getType()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalPendingDisapprovedRecordsByRequestTypeRequestTypeId($request_type, $request_type_id) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ."
			WHERE 
			request_type 	= ". Model::safeSql($request_type) ." AND
			request_type_id = ". Model::safeSql($request_type_id) ." AND 
			(status = '" . G_Employee_Overtime_Request::PENDING . "' OR status = '" . G_Employee_Overtime_Request::DISAPPROVED . "')
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countApprovedOverride($request_type, $request_type_id) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ."
			WHERE 
			request_type 	= ". Model::safeSql($request_type) ." AND
			request_type_id = ". Model::safeSql($request_type_id) ." AND
			override_level	= 'Granted' AND status = '". G_Employee_Overtime_Request::APPROVED  ."'
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalApprovers($request_type, $request_type_id) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ."
			WHERE 
			request_type 	= ". Model::safeSql($request_type) ." AND
			request_type_id = ". Model::safeSql($request_type_id) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalApproversByStatus($request_type, $request_type_id, $status) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ."
			WHERE 
			request_type 	= ". Model::safeSql($request_type) ." AND
			request_type_id = ". Model::safeSql($request_type_id) ." AND
			status = '". $status  ."'
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function getApproversOverideStatus($request_type, $request_type_id) {
		$sql = "
			SELECT status
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ."
			WHERE 
			request_type 	= ". Model::safeSql($request_type) ." AND
			request_type_id = ". Model::safeSql($request_type_id) ." AND
			override_level = 'Granted'
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['status'];
	}
	
	public static function validate_approver_status($request_type, $request_type_id) {

		$total_request_approvers 	 = self::countTotalApprovers($request_type,$request_type_id);
		$total_approvers_pending 	 = self::countTotalApproversByStatus($request_type,$request_type_id,G_Employee_Overtime_Request::PENDING);
		$total_approvers_approved 	 = self::countTotalApproversByStatus($request_type,$request_type_id,G_Employee_Overtime_Request::APPROVED);
		$total_approvers_disapproved = self::countTotalApproversByStatus($request_type,$request_type_id,G_Employee_Overtime_Request::DISAPPROVED);
		$override_status			 = self::getApproversOverideStatus($request_type,$request_type_id);

		if($override_status == G_Employee_Overtime_Request::PENDING) {
			$request_status = G_Employee_Overtime_Request::PENDING;
		} else if($override_status == G_Employee_Overtime_Request::APPROVED) {
			$request_status = G_Employee_Overtime_Request::APPROVED;
		} else if($override_status == G_Employee_Overtime_Request::DISAPPROVED) {
			$request_status = G_Employee_Overtime_Request::DISAPPROVED;
		} else {
			if($total_request_approvers / $total_approvers_pending == 1) {
				$request_status = G_Employee_Overtime_Request::PENDING;	
			} else if($total_request_approvers / $total_approvers_approved == 1) {
				$request_status = G_Employee_Overtime_Request::APPROVED;	
			} else if($total_request_approvers / $total_approvers_disapproved == 1) {
				$request_status = G_Employee_Overtime_Request::DISAPPROVED;	
			} else {
				$request_status = G_Employee_Overtime_Request::PENDING;
			}
		}
	
		return $request_status;
	} 
	
	/*
		$ora = G_Employee_Request_Approver_Finder::findById(Utilities::decrypt($_POST['h_approvers_id']));
		G_Employee_Request_Approver_Helper::validate_approver_level($ora);
	*/
	public static function validate_approver_level($ora) {
		if($ora->getStatus() == G_Employee_Overtime_Request::APPROVED) {	
			//Get the Next Approver if the Previous one approved the request
			$level = $ora->getLevel() + 1;
			$approver = G_Employee_Request_Approver_Finder::findByRequestTypeRequestTypeIdLevel($ora->getRequestType(),$ora->getRequestTypeId(),$level);
			if($approver) {
				
				if($approver->getOverrideLevel() != Employee_Request_Approver::GRANTED) {
					if($approver->getType() == Employee_Request_Approver::EMPLOYEE_ID) {
						Email_Templates::sendApproverRequestNotification($approver);
					} else {
						//Send to the Group (By Position)
						Email_Templates::sendApproverByPositionRequestNotification($approver);
					}
					
					$approver->setOverrideLevel(Employee_Request_Approver::CURRENT);
					$approver->update();
				}
	
			} 
			
			//$ora->setOverrideLevel("");
			//$ora->update();
		} else {
			
		}
	}
	
	public static function validateApproverLevel($era,$request_type) {
		if($era->getStatus() == Employee_Request_Approver::APPROVED) {	
			//Get the Next Approver if the Previous one approved the request
			$level = $era->getLevel() + 1;
			$approver = G_Employee_Request_Approver_Finder::findByRequestTypeRequestTypeIdLevel($era->getRequestType(),$era->getRequestTypeId(),$level);
			if($approver) {
				
				if($approver->getOverrideLevel() != Employee_Request_Approver::GRANTED) {
					if($approver->getType() == Employee_Request_Approver::EMPLOYEE_ID) {
						Email_Templates::sendApproverRequestNotification($approver,$request_type);
					} else {
						//Send to the Group (By Position)
						Email_Templates::sendApproverByPositionRequestNotification($approver,$request_type);
					}
					
					$approver->setOverrideLevel(Employee_Request_Approver::CURRENT);
					$approver->update();
				}
				$era->setOverrideLevel("");
				$era->update();
	
			} 
			
			
		} else {
			
		}
	}
	
	public static function validateApproverStatus($request_type, $request_type_id) {

		$total_request_approvers 	 = self::countTotalApprovers($request_type,$request_type_id);
		$total_approvers_pending 	 = self::countTotalApproversByStatus($request_type,$request_type_id,G_Employee_Overtime_Request::PENDING);
		$total_approvers_approved 	 = self::countTotalApproversByStatus($request_type,$request_type_id,G_Employee_Overtime_Request::APPROVED);
		$total_approvers_disapproved = self::countTotalApproversByStatus($request_type,$request_type_id,G_Employee_Overtime_Request::DISAPPROVED);
		$override_status			 = self::getApproversOverideStatus($request_type,$request_type_id);

		if($override_status == G_Employee_Overtime_Request::PENDING) {
			$request_status = G_Employee_Overtime_Request::PENDING;
		} else if($override_status == G_Employee_Overtime_Request::APPROVED) {
			$request_status = G_Employee_Overtime_Request::APPROVED;
		} else if($override_status == G_Employee_Overtime_Request::DISAPPROVED) {
			$request_status = G_Employee_Overtime_Request::DISAPPROVED;
		} else {
			if($total_request_approvers / $total_approvers_pending == 1) {
				$request_status = G_Employee_Overtime_Request::PENDING;	
			} else if($total_request_approvers / $total_approvers_approved == 1) {
				$request_status = G_Employee_Overtime_Request::APPROVED;	
			} else if($total_request_approvers / $total_approvers_disapproved == 1) {
				$request_status = G_Employee_Overtime_Request::DISAPPROVED;	
			} else {
				$request_status = G_Employee_Overtime_Request::PENDING;
			}
		}
	
		return $request_status;
	}
	
	public static function verifyRequestIfAlreadyApproved($id) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . G_EMPLOYEE_REQUEST_APPROVERS ."
			WHERE 
			id = " . Model::safeSql($id) . " AND 
			status = " . Model::safeSql(G_Employee_Overtime_Request::PENDING) . "
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		
		return ($row['total'] >= 1 ? false : true);
		
		
	}
	
}
?>