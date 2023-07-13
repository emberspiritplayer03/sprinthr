<?php
class G_Employee_Attendance_Correction_Request_Helper {
		
	public static function isIdExist(G_Employee_Attendance_Correction_Request $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_ATTENDANCE_CORRECTION_REQUEST ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function findByDate($date,$order_by,$limit)
	{
		$sql = "
			SELECT a.*
			FROM ". G_EMPLOYEE_ATTENDANCE_CORRECTION_REQUEST ." a
			WHERE
				a.date_start >= ". Model::safeSql($date) ."
				AND a.is_approved=1
				
			".$order_by."
			".$limit."
		";
		return Model::runSql($sql,true);
	}
	
	public static function findBySearch($csid,$search,$order_by,$limit) {
		$sql = "
			SELECT 
			a.id,
			a.company_structure_id,
			a.employee_id,
			a.date_applied, 
			a.date_start,
			a.date_end,
			a.overtime_comments,
			a.is_approved,
			CONCAT(e.lastname, ' ', e.firstname) AS employee_name,e.hash, l.name as leave_type
			FROM ". G_EMPLOYEE_ATTENDANCE_CORRECTION_REQUEST ." a
			LEFT JOIN g_employee e ON e.id=a.employee_id 
			LEFT JOIN g_leave as l ON l.id=a.leave_id
			WHERE
				a.company_structure_id = ". Model::safeSql($csid) ."
			".$search."
			".$order_by."
			".$limit."
		";
		
		return Model::runSql($sql,true);
	}
	
	public static function findByCompanyStructureId($csid,$order_by,$limit)
	{
		$sql = "
			SELECT 
			a.id,
			a.company_structure_id,
			a.employee_id,
			a.date_applied, 
			a.date_start,
			a.date_end,
			a.overtime_comments,
			a.is_approved,
			CONCAT(e.lastname, ' ', e.firstname) AS employee_name,e.hash, l.name as leave_type
			FROM ". G_EMPLOYEE_ATTENDANCE_CORRECTION_REQUEST ." a, g_employee e, g_leave as l
			WHERE
				a.company_structure_id = ". Model::safeSql($csid) ."
				AND e.id=a.employee_id AND a.leave_id=l.id
			".$order_by."
			".$limit."
		";
		//echo $sql;
		return Model::runSql($sql,true);
	}
	
	public static function countTotalRecords($csid)
	{
		$sql = "
			SELECT count(*) as total_employee
			FROM ". G_EMPLOYEE_ATTENDANCE_CORRECTION_REQUEST ." a, g_employee e
			WHERE
				a.company_structure_id >= ". Model::safeSql($csid) ."
				AND e.id=a.employee_id
			".$order_by."
			".$limit."
		";
		return Model::runSql($sql,true);
	}

}
?>