<?php
class G_Employee_Undertime_Request_Helper {
	public static function isIdExist(G_Employee_Undertime_Request $gur) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_UNDERTIME_REQUEST ."
			WHERE id = ". Model::safeSql($gur->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByIsArchive($is_archive) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_UNDERTIME_REQUEST ."
			WHERE is_archive = ". Model::safeSql($is_archive) ."
		";
		echo $sql;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByIsApproved($is_approved) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_UNDERTIME_REQUEST ."
			WHERE is_approved = ". Model::safeSql($is_approved) ."
		";
		echo $sql;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function getAllByPeriodAndCompanyStructureId($from,$to,$company_structure_id) {
		$sql = "
			SELECT u.id, CONCAT(e.firstname ,', ',e.lastname) as emp_name,CONCAT(jbh.name) as job_name, u.date_of_undertime,u.time_out,u.reason,u.is_approved
			FROM " . G_EMPLOYEE_UNDERTIME_REQUEST ." u 
				LEFT JOIN " . EMPLOYEE . " e ON u.employee_id = e.id
				LEFT JOIN " . G_EMPLOYEE_JOB_HISTORY . " jbh ON e.id = jbh.employee_id
			WHERE u.is_archive = ". Model::safeSql(G_Employee_Undertime_Request::NO) ."
				AND u.company_structure_id = " . Model::safeSql($company_structure_id) . "
				AND u.date_of_undertime BETWEEN '" . $from . "' AND '" . $to . "' 
		";		
		return Model::runSql($sql,true);
	}
	
	public static function countTotalRecordsByPeriodCompanyStructureIdPendingAndIsNotArchive($from,$to,$company_structure_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_UNDERTIME_REQUEST ."
			WHERE is_archive = " . Model::safeSql(G_Employee_Undertime_Request::NO) ."
				AND is_approved = " . Model::safeSql(G_Employee_Undertime_Request::PENDING) . "
				AND company_structure_id = " . Model::safeSql($company_structure_id) . "
				AND date_of_undertime BETWEEN '" . $from . "' AND '" . $to . "' 
		";				
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByPeriodCompanyStructureIdApprovedAndIsNotArchive($from,$to,$company_structure_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_UNDERTIME_REQUEST ."
			WHERE is_archive = " . Model::safeSql(G_Employee_Undertime_Request::NO) ."
				AND is_approved = " . Model::safeSql(G_Employee_Undertime_Request::APPROVED) . "
				AND company_structure_id = " . Model::safeSql($company_structure_id) . "
				AND date_of_undertime BETWEEN '" . $from . "' AND '" . $to . "' 
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>